<?php

namespace App\Http\Controllers;

use App\Models\ResolutionTracking;
use App\Models\SalesOrder;
use App\Models\SatisfactionMonitoring;
use App\Models\ServiceContract;
use App\Models\ServiceRequest;
use App\Models\SupportTicket;
use App\Models\WarrantyClaim;
use App\Models\WarrantyRecord;
use App\Services\AfterSalesAutomationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SupportOperationsController extends Controller
{
    public function __construct(private AfterSalesAutomationService $automation) {}

    public function storeTicket(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'order_id' => ['required', 'exists:sales_orders,order_id'],
            'product_id' => ['required', 'exists:products,product_id'],
            'service_contract_id' => ['nullable', 'exists:service_contracts,contract_id'],
            'ticket_type' => ['required', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'in:High,Medium,Low'],
            'department' => ['required', 'string', 'max:100'],
        ]);
        $order = SalesOrder::query()->with('items')->findOrFail($data['order_id']);
        if (! $order->items->contains('product_id', (int) $data['product_id'])) {
            throw ValidationException::withMessages(['product_id' => 'The product must belong to the selected order.']);
        }

        DB::transaction(function () use ($data, $order): void {
            $ticket = SupportTicket::query()->create([
                ...$data,
                'customer_id' => $order->customer_id,
                'status' => 'Open',
                'created_at' => now(),
                ...$this->automation->deadlines($data['priority']),
            ]);
            $ticket->caseEvents()->create($this->event('Created', 'Support ticket created.'));
        });

        return back()->with('success', 'Support ticket created successfully.');
    }

    public function updateTicket(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $data = $request->validate([
            'ticket_type' => ['required', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'in:High,Medium,Low'],
            'department' => ['required', 'string', 'max:100'],
        ]);
        DB::transaction(function () use ($ticket, $data): void {
            $deadlines = $ticket->priority === $data['priority'] ? [] : $this->automation->deadlines($data['priority']);
            $ticket->update([...$data, ...$deadlines]);
            $ticket->caseEvents()->create($this->event('Updated', 'Ticket details were updated.'));
        });

        return back()->with('success', 'Support ticket updated successfully.');
    }

    public function archiveTicket(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $data = $request->validate(['archive_reason' => ['required', 'string', 'max:1000']]);
        $ticket->update(['archived_at' => now(), 'archive_reason' => $data['archive_reason']]);
        $ticket->caseEvents()->create($this->event('Archived', $data['archive_reason']));

        return back()->with('success', 'Support ticket archived without deleting its history.');
    }

    public function restoreTicket(SupportTicket $ticket): RedirectResponse
    {
        $ticket->update(['archived_at' => null, 'archive_reason' => null]);
        $ticket->caseEvents()->create($this->event('Restored', 'Ticket restored to the active queue.'));

        return back()->with('success', 'Support ticket restored.');
    }

    public function storeWarranty(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'order_id' => ['required', 'exists:sales_orders,order_id'],
            'product_id' => ['required', 'exists:products,product_id'],
            'warranty_start' => ['required', 'date'],
            'warranty_end' => ['required', 'date', 'after_or_equal:warranty_start'],
            'warranty_status' => ['required', 'in:Active,On Hold,Expired'],
        ]);
        $order = SalesOrder::query()->with('items')->findOrFail($data['order_id']);
        if (! $order->items->contains('product_id', (int) $data['product_id'])) {
            throw ValidationException::withMessages(['product_id' => 'The product must belong to the selected order.']);
        }
        $record = WarrantyRecord::query()->create([
            ...$data,
            'warranty_number' => 'WR-'.now()->format('YmdHis').'-'.random_int(100, 999),
        ]);

        return back()->with('success', "Warranty {$record->warranty_number} created.");
    }

    public function updateWarranty(Request $request, WarrantyRecord $warranty): RedirectResponse
    {
        $warranty->update($request->validate([
            'warranty_start' => ['required', 'date'],
            'warranty_end' => ['required', 'date', 'after_or_equal:warranty_start'],
            'warranty_status' => ['required', 'in:Active,On Hold,Expired'],
        ]));

        return back()->with('success', 'Warranty record updated.');
    }

    public function archiveWarranty(Request $request, WarrantyRecord $warranty): RedirectResponse
    {
        $data = $request->validate(['archive_reason' => ['required', 'string', 'max:1000']]);
        $warranty->update(['archived_at' => now(), 'archive_reason' => $data['archive_reason']]);

        return back()->with('success', 'Warranty archived without removing its claim history.');
    }

    public function storeClaim(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'warranty_id' => ['required', 'exists:warranty_records,warranty_id'],
            'ticket_id' => ['required', 'exists:support_tickets,ticket_id'],
            'claim_reason' => ['required', 'string'],
        ]);
        $warranty = WarrantyRecord::query()->findOrFail($data['warranty_id']);
        $ticket = SupportTicket::query()->findOrFail($data['ticket_id']);
        if (WarrantyClaim::query()
            ->where('warranty_id', $data['warranty_id'])
            ->where('ticket_id', $data['ticket_id'])
            ->whereNotIn('claim_status', ['Rejected', 'Cancelled'])
            ->exists()) {
            throw ValidationException::withMessages(['warranty_id' => 'An active claim already exists for this warranty and ticket.']);
        }
        $eligibility = $this->automation->warrantyEligibility($warranty, $ticket);
        WarrantyClaim::query()->create([
            ...$data,
            'claim_status' => $eligibility['eligible'] ? 'Pending' : 'Rejected',
            'eligibility_status' => $eligibility['eligible'] ? 'Eligible' : 'Ineligible',
            'eligibility_notes' => $eligibility['reason'],
            'decision_reason' => $eligibility['eligible'] ? null : $eligibility['reason'],
            'claim_date' => now(),
        ]);

        return back()->with('success', 'Warranty claim submitted and eligibility checked automatically.');
    }

    public function cancelClaim(Request $request, WarrantyClaim $claim): RedirectResponse
    {
        $data = $request->validate(['decision_reason' => ['required', 'string', 'max:1000']]);
        $claim->update(['claim_status' => 'Cancelled', 'cancelled_at' => now(), 'decision_reason' => $data['decision_reason']]);

        return back()->with('success', 'Warranty claim cancelled.');
    }

    public function storeContract(Request $request): RedirectResponse
    {
        $data = $this->contractData($request);
        ServiceContract::query()->create([
            ...$data,
            'contract_number' => 'SC-'.now()->format('YmdHis').'-'.random_int(100, 999),
            'services_used' => 0,
        ]);

        return back()->with('success', 'Service contract created.');
    }

    public function updateContract(Request $request, ServiceContract $contract): RedirectResponse
    {
        $contract->update($this->contractData($request));

        return back()->with('success', 'Service contract updated.');
    }

    public function archiveContract(Request $request, ServiceContract $contract): RedirectResponse
    {
        $data = $request->validate(['archive_reason' => ['required', 'string', 'max:1000']]);
        $contract->update(['archived_at' => now(), 'archive_reason' => $data['archive_reason'], 'contract_status' => 'Terminated']);

        return back()->with('success', 'Service contract archived.');
    }

    public function storeServiceRequest(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ticket_id' => ['required', 'exists:support_tickets,ticket_id'],
            'request_type' => ['required', 'string', 'max:255'],
            'technician_id' => ['nullable', 'exists:employees,employee_id'],
            'schedule_notes' => ['nullable', 'string'],
        ]);
        ServiceRequest::query()->create([
            ...$data,
            'request_number' => 'SR-'.now()->format('YmdHis').'-'.random_int(100, 999),
            'requested_at' => now(),
            'service_status' => 'Pending',
        ]);

        return back()->with('success', 'Service request created.');
    }

    public function updateServiceRequest(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $data = $request->validate([
            'request_type' => ['required', 'string', 'max:255'],
            'technician_id' => ['nullable', 'exists:employees,employee_id'],
            'service_status' => ['required', 'in:Pending,Scheduled,In Progress,Completed,Cancelled,Failed,Rejected'],
            'service_result' => ['nullable', 'string'],
            'schedule_notes' => ['nullable', 'string'],
        ]);
        if ($data['service_status'] === 'Completed' && empty($data['service_result'])) {
            throw ValidationException::withMessages(['service_result' => 'A service result is required before completion.']);
        }
        $wasCompleted = $serviceRequest->service_status === 'Completed';
        $contract = $serviceRequest->supportTicket?->serviceContract;
        if (! $wasCompleted && $data['service_status'] === 'Completed' && $contract?->service_limit !== null && $contract->services_used >= $contract->service_limit) {
            throw ValidationException::withMessages(['service_status' => 'This service contract has no remaining entitlement.']);
        }
        $serviceRequest->update([
            ...$data,
            'completion_date' => $data['service_status'] === 'Completed' ? now() : $serviceRequest->completion_date,
        ]);
        if (! $wasCompleted && $data['service_status'] === 'Completed' && $contract) {
            $contract->increment('services_used');
        }

        return back()->with('success', 'Service request updated.');
    }

    public function cancelServiceRequest(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $data = $request->validate(['service_result' => ['required', 'string', 'max:1000']]);
        $serviceRequest->update(['service_status' => 'Cancelled', 'cancelled_at' => now(), 'service_result' => $data['service_result']]);

        return back()->with('success', 'Service request cancelled.');
    }

    public function storeResolution(Request $request): RedirectResponse
    {
        $data = $this->resolutionData($request);
        ResolutionTracking::query()->create([...$data, 'resolution_status' => 'Draft']);

        return back()->with('success', 'Resolution record created.');
    }

    public function updateResolution(Request $request, ResolutionTracking $resolution): RedirectResponse
    {
        $resolution->update($this->resolutionData($request));

        return back()->with('success', 'Resolution record updated.');
    }

    public function approveResolution(Request $request, ResolutionTracking $resolution): RedirectResponse
    {
        $employeeId = (int) $request->session()->get('employee_id');
        DB::transaction(function () use ($resolution, $employeeId): void {
            $resolution->update([
                'resolution_status' => 'Approved',
                'qc_status' => 'passed',
                'approved_by' => $employeeId,
                'approved_at' => now(),
                'resolved_at' => $resolution->resolved_at ?? now(),
            ]);
            $resolution->supportTicket->update(['status' => 'Resolved', 'resolved_at' => now()]);
            $resolution->supportTicket->caseEvents()->create($this->event('Resolved', 'Resolution approved and ticket resolved.'));
            $this->automation->requestSatisfactionFeedback($resolution->supportTicket);
        });

        return back()->with('success', 'Resolution approved and satisfaction feedback requested.');
    }

    public function submitFeedback(Request $request, SatisfactionMonitoring $feedback): RedirectResponse
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comments' => ['nullable', 'string', 'max:2000'],
        ]);
        $levels = [1 => 'Very Dissatisfied', 2 => 'Dissatisfied', 3 => 'Neutral', 4 => 'Satisfied', 5 => 'Very Satisfied'];
        $feedback->update([...$data, 'satisfaction_level' => $levels[$data['rating']], 'submitted_at' => now()]);

        return back()->with('success', 'Customer satisfaction feedback submitted.');
    }

    /** @return array<string, mixed> */
    private function contractData(Request $request): array
    {
        return $request->validate([
            'customer_id' => ['required', 'exists:customers,customer_id'],
            'product_id' => ['required', 'exists:products,product_id'],
            'service_type' => ['required', 'string', 'max:255'],
            'service_start' => ['required', 'date'],
            'service_end' => ['required', 'date', 'after_or_equal:service_start'],
            'contract_status' => ['required', 'in:Active,Suspended,Expired,Terminated'],
            'service_limit' => ['nullable', 'integer', 'min:1'],
        ]);
    }

    /** @return array<string, mixed> */
    private function resolutionData(Request $request): array
    {
        return $request->validate([
            'ticket_id' => ['required', 'exists:support_tickets,ticket_id'],
            'resolved_by' => ['required', 'exists:employees,employee_id'],
            'resolution_summary' => ['required', 'string'],
            'root_cause' => ['required', 'string'],
            'corrective_action' => ['required', 'string'],
            'resolution_time_hours' => ['required', 'numeric', 'min:0'],
            'qc_status' => ['required', 'in:pending,passed,failed'],
        ]);
    }

    /** @return array{employee_id: int|null, event_type: string, description: string, created_at: Carbon} */
    private function event(string $type, string $description): array
    {
        return [
            'employee_id' => session('employee_id') ? (int) session('employee_id') : null,
            'event_type' => $type,
            'description' => $description,
            'created_at' => now(),
        ];
    }
}
