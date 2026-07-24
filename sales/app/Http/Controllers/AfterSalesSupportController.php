<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Models\ResolutionTracking;
use App\Models\SalesOrder;
use App\Models\SatisfactionMonitoring;
use App\Models\ServiceContract;
use App\Models\ServiceRequest;
use App\Models\SupportTicket;
use App\Models\WarrantyClaim;
use App\Models\WarrantyRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AfterSalesSupportController extends Controller
{
    public function ticketsIndex(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:all,Open,Pending,In Progress,Resolved,Closed,Escalated'],
            'priority' => ['nullable', 'in:all,High,Medium,Low'],
            'customer' => ['nullable', 'string', 'max:255'],
            'ticket_id' => ['nullable', 'integer', 'exists:support_tickets,ticket_id'],
            'assigned_employee' => ['nullable', 'integer', 'exists:employees,employee_id'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $search = trim((string) ($filters['search'] ?? ''));
        $status = $filters['status'] ?? null;
        $priority = $filters['priority'] ?? null;
        $customer = $filters['customer'] ?? null;
        $ticketId = $filters['ticket_id'] ?? null;
        $assignedEmployee = $filters['assigned_employee'] ?? null;
        $fromDate = $filters['from_date'] ?? null;
        $toDate = $filters['to_date'] ?? null;

        $perPage = (int) ($filters['per_page'] ?? 10);

        $customers = Customer::query()
            ->select(['customer_id', 'first_name', 'last_name'])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->map(function ($customerModel) {
                $name = trim(($customerModel->first_name ?? '').' '.($customerModel->last_name ?? ''));

                return (object) [
                    'customer_id' => $customerModel->customer_id,
                    'name' => $name !== '' ? $name : 'Unnamed Customer',
                ];
            });

        $query = SupportTicket::query()
            ->with(['customer', 'product', 'order', 'latestAssignment.employee', 'attachments']);

        if ($ticketId) {
            $query->where('ticket_id', $ticketId);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_id', 'like', "%{$search}%")
                    ->orWhere('ticket_type', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('priority', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('customer', function (Builder $q2) use ($search) {
                        $this->applyCustomerSearch($q2, $search);
                        $q2->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('product', function ($q3) use ($search) {
                        $q3->where('product_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('order', function ($orderQuery) use ($search) {
                        $orderQuery->where('order_number', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($status) && strtolower($status) !== 'all') {
            $query->where('status', $status);
        }

        if (! empty($priority) && strtolower($priority) !== 'all') {
            $query->where('priority', $priority);
        }

        if (is_numeric($customer) && (int) $customer > 0) {
            $query->whereHas('customer', function ($q) use ($customer) {
                $q->where('customer_id', (int) $customer);
            });
        } elseif (! empty($customer) && strtolower($customer) !== 'all') {
            $query->whereHas('customer', function (Builder $customerQuery) use ($customer) {
                $this->applyCustomerSearch($customerQuery, $customer);
            });
        }

        if ($assignedEmployee) {
            $query->whereHas('latestAssignment', function (Builder $assignmentQuery) use ($assignedEmployee) {
                $assignmentQuery->where('employee_id', $assignedEmployee);
            });
        }

        if (! empty($fromDate)) {
            $query->whereDate('created_at', '>=', $fromDate);
        }

        if (! empty($toDate)) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $tickets = $query
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('support.tickets', [
            'tickets' => $tickets,
            'search' => $search,
            'status' => $status,
            'priority' => $priority,
            'customer' => $customer,
            'ticketId' => $ticketId,
            'assignedEmployee' => $assignedEmployee,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'customers' => $customers,
            'employees' => Employee::query()->orderBy('first_name')->orderBy('last_name')->get(['employee_id', 'first_name', 'last_name']),
            'salesOrders' => SalesOrder::query()->with(['customer', 'items.product'])->orderByDesc('order_date')->get(),
            'serviceContracts' => ServiceContract::query()->whereNull('archived_at')->orderBy('contract_number')->get(),
        ]);
    }

    public function warrantyRecordsIndex(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:all,Active,Expiring Soon,Expired,On Hold'],
            'customer' => ['nullable', 'integer', 'exists:customers,customer_id'],
            'product' => ['nullable', 'integer', 'exists:products,product_id'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);
        $search = trim((string) ($filters['search'] ?? ''));
        $status = $filters['status'] ?? null;
        $customer = $filters['customer'] ?? null;
        $product = $filters['product'] ?? null;

        $perPage = (int) ($filters['per_page'] ?? 10);

        $query = WarrantyRecord::query()
            ->with(['product', 'order', 'customer']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('warranty_id', 'like', "%{$search}%")
                    ->orWhere('warranty_number', 'like', "%{$search}%")
                    ->orWhere('warranty_status', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q2) use ($search) {
                        $q2->where('product_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('order', function ($q3) use ($search) {
                        $q3->where('order_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('customer', function (Builder $customerQuery) use ($search): void {
                        $this->applyCustomerSearch($customerQuery, $search);
                    });
            });
        }

        if ($status !== null && $status !== '' && strtolower($status) !== 'all') {
            $query->where('warranty_status', $status);
        }

        if ($customer) {
            $query->whereHas('customer', fn (Builder $customerQuery) => $customerQuery->where('customers.customer_id', $customer));
        }

        if ($product) {
            $query->where('product_id', $product);
        }

        $warrantyRecords = $query
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('support.warranty-records', [
            'warrantyRecords' => $warrantyRecords,
            'search' => $search,
            'status' => $status,
            'customer' => $customer,
            'product' => $product,
            'products' => Product::query()->orderBy('product_name')->get(['product_id', 'product_name']),
            'customers' => Customer::query()->orderBy('first_name')->orderBy('last_name')->get(['customer_id', 'first_name', 'last_name']),
            'salesOrders' => SalesOrder::query()->with(['customer', 'items.product'])->orderByDesc('order_date')->get(),
        ]);
    }

    public function warrantyClaimsIndex(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:all,Pending,Approved,Rejected,Completed'],
            'customer' => ['nullable', 'integer', 'exists:customers,customer_id'],
            'warranty_id' => ['nullable', 'integer', 'exists:warranty_records,warranty_id'],
            'ticket_id' => ['nullable', 'integer', 'exists:support_tickets,ticket_id'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);
        $search = $filters['search'] ?? null;
        $status = $filters['status'] ?? null;
        $customer = $filters['customer'] ?? null;
        $warrantyId = $filters['warranty_id'] ?? null;
        $ticketId = $filters['ticket_id'] ?? null;
        $fromDate = $filters['from_date'] ?? null;
        $toDate = $filters['to_date'] ?? null;

        $perPage = (int) ($filters['per_page'] ?? 10);

        $query = WarrantyClaim::query()
            ->with([
                'warrantyRecord.product',
                'warrantyRecord.customer',
                'supportTicket.customer',
                'supportTicket.product',
                'supportTicket.latestAssignment.employee',
            ]);

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('claim_id', 'like', "%{$search}%")
                    ->orWhere('claim_reason', 'like', "%{$search}%")
                    ->orWhere('claim_status', 'like', "%{$search}%")
                    ->orWhereHas('warrantyRecord', function ($q2) use ($search) {
                        $q2->where('warranty_number', 'like', "%{$search}%")
                            ->orWhereHas('product', function ($q3) use ($search) {
                                $q3->where('product_name', 'like', "%{$search}%");
                            });
                    })
                    ->orWhereHas('supportTicket', function ($q4) use ($search) {
                        $q4->where('ticket_id', 'like', "%{$search}%")
                            ->orWhere('subject', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%")
                            ->orWhereHas('customer', function (Builder $q5) use ($search) {
                                $this->applyCustomerSearch($q5, $search);
                                $q5->orWhere('email', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if (! empty($status) && strtolower($status) !== 'all') {
            $query->where('claim_status', $status);
        }

        if ($customer !== null) {
            $query->whereHas('warrantyRecord.customer', function ($q) use ($customer) {
                $q->where('customers.customer_id', $customer);
            });
        }

        if ($warrantyId !== null) {
            $query->where('warranty_id', $warrantyId);
        }

        if ($ticketId !== null) {
            $query->where('ticket_id', $ticketId);
        }

        if ($fromDate !== null) {
            $query->whereDate('claim_date', '>=', $fromDate);
        }

        if ($toDate !== null) {
            $query->whereDate('claim_date', '<=', $toDate);
        }

        $claimCounts = (clone $query)
            ->selectRaw('LOWER(claim_status) as status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $warrantyClaims = $query
            ->orderByDesc('claim_date')
            ->paginate($perPage)
            ->withQueryString();

        return view('support.warranty-claims', [
            'warrantyClaims' => $warrantyClaims,
            'search' => $search,
            'status' => $status,
            'customer' => $customer,
            'warrantyId' => $warrantyId,
            'ticketId' => $ticketId,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'pendingClaims' => (int) ($claimCounts['pending'] ?? 0),
            'approvedClaims' => (int) ($claimCounts['approved'] ?? 0),
            'rejectedClaims' => (int) ($claimCounts['rejected'] ?? 0),
            'completedClaims' => (int) ($claimCounts['completed'] ?? 0),
            'customers' => Customer::query()->orderBy('first_name')->orderBy('last_name')->get(['customer_id', 'first_name', 'last_name']),
            'warranties' => WarrantyRecord::query()
                ->whereNull('archived_at')
                ->orderBy('warranty_number')
                ->get(['warranty_id', 'warranty_number']),
            'tickets' => SupportTicket::query()
                ->whereNull('archived_at')
                ->orderBy('ticket_id')
                ->get(['ticket_id', 'subject']),
        ]);
    }

    public function serviceContractShow(Request $request, $contract)
    {
        $serviceContract = ServiceContract::query()
            ->with(['customer', 'product'])
            ->findOrFail($contract);

        return response()->json([
            'contract' => [
                'contract_id' => $serviceContract->contract_id,
                'contract_number' => $serviceContract->contract_number,
                'service_type' => $serviceContract->service_type ?: null,
                'service_start' => $serviceContract->service_start ? optional($serviceContract->service_start)->format('Y-m-d') : null,
                'service_end' => $serviceContract->service_end ? optional($serviceContract->service_end)->format('Y-m-d') : null,
                'contract_status' => $serviceContract->currentStatus(),
                'created_at' => $serviceContract->created_at?->format('Y-m-d H:i'),
                'customer' => [
                    'name' => optional($serviceContract->customer)->full_name ?: null,
                ],
                'product' => [
                    'product_name' => optional($serviceContract->product)->product_name ?: null,
                ],

            ],
        ]);
    }

    public function serviceContractsIndex(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:all,Active,Expiring Soon,Expired,Terminated'],
            'customer' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'open_contract' => ['nullable', 'integer', 'exists:service_contracts,contract_id'],
        ]);
        $search = $filters['search'] ?? null;
        $status = $filters['status'] ?? null;
        $customer = $filters['customer'] ?? null;
        $perPage = (int) ($filters['per_page'] ?? 10);
        $openContractId = $filters['open_contract'] ?? null;
        $openContract = $openContractId ? ServiceContract::query()->find($openContractId) : null;

        $activeContractCount = (int) ServiceContract::query()->active()->count();
        $expiringSoonCount = (int) ServiceContract::query()->expiringSoon()->count();
        $expiredCount = (int) ServiceContract::query()->expired()->count();
        $totalContracts = (int) ServiceContract::query()->count();
        $activeContractRatePct = $totalContracts > 0
            ? round(($activeContractCount / $totalContracts) * 100, 0)
            : 0;

        $query = ServiceContract::query()
            ->with(['customer', 'product']);

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                    ->orWhere('service_type', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q2) use ($search) {
                        $q2->where('product_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('customer', function (Builder $q3) use ($search) {
                        $this->applyCustomerSearch($q3, $search);
                        $q3->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($status) && strtolower($status) !== 'all') {
            match ($status) {
                'Active' => $query->active(),
                'Expiring Soon' => $query->expiringSoon(),
                'Expired' => $query->expired(),
                'Terminated' => $query->whereRaw('LOWER(contract_status) = ?', ['terminated']),
            };
        }

        if (is_numeric($customer) && (int) $customer > 0) {
            $query->whereHas('customer', function ($q) use ($customer) {
                $q->where('customer_id', (int) $customer);
            });
        } elseif (! empty($customer) && strtolower($customer) !== 'all') {
            $query->whereHas('customer', function (Builder $customerQuery) use ($customer): void {
                $this->applyCustomerSearch($customerQuery, $customer);
            });
        }

        $serviceContracts = $query
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('support.service-contracts', [
            'serviceContracts' => $serviceContracts,
            'search' => $search,
            'status' => $status,
            'customer' => $customer,
            'activeContractCount' => $activeContractCount,
            'expiringSoonCount' => $expiringSoonCount,
            'expiredCount' => $expiredCount,
            'activeContractRatePct' => $activeContractRatePct,
            'customers' => Customer::query()->orderBy('first_name')->orderBy('last_name')->get(['customer_id', 'first_name', 'last_name']),
            'openContractId' => $openContractId,
            'openContract' => $openContract,
            'products' => Product::query()->orderBy('product_name')->get(['product_id', 'product_name']),
        ]);
    }

    public function serviceRequestsIndex(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:all,Pending,Scheduled,In Progress,Completed,Cancelled,Failed,Rejected'],
            'technician' => ['nullable', 'integer', 'exists:employees,employee_id'],
            'date' => ['nullable', 'date'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);
        $search = $filters['search'] ?? null;
        $status = $filters['status'] ?? null;
        $technician = $filters['technician'] ?? null;
        $date = $filters['date'] ?? null;
        $perPage = (int) ($filters['per_page'] ?? 10);

        $query = ServiceRequest::query()
            ->with([
                'supportTicket.customer',
                'supportTicket.product',
                'supportTicket.serviceContract',
                'technician',
            ]);

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                    ->orWhere('request_id', 'like', "%{$search}%")
                    ->orWhere('request_type', 'like', "%{$search}%")
                    ->orWhere('service_status', 'like', "%{$search}%")
                    ->orWhere('schedule_notes', 'like', "%{$search}%")
                    ->orWhereHas('supportTicket', function ($q2) use ($search) {
                        $q2->where('ticket_id', 'like', "%{$search}%")
                            ->orWhere('subject', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhereHas('product', function ($productQuery) use ($search) {
                                $productQuery->where('product_name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('serviceContract', function ($contractQuery) use ($search) {
                                $contractQuery->where('contract_number', 'like', "%{$search}%");
                            })
                            ->orWhereHas('customer', function (Builder $q3) use ($search) {
                                $this->applyCustomerSearch($q3, $search);
                                $q3->orWhere('email', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if (! empty($status) && strtolower($status) !== 'all') {
            $query->where('service_status', $status);
        }

        if ($technician) {
            $query->where(function (Builder $technicianQuery) use ($technician): void {
                $technicianQuery->where('technician_id', $technician)
                    ->orWhere(function (Builder $legacyQuery) use ($technician): void {
                        $legacyQuery->whereNull('technician_id')
                            ->whereHas('supportTicket.latestAssignment', function (Builder $assignmentQuery) use ($technician): void {
                                $assignmentQuery->where('employee_id', $technician);
                            });
                    });
            });
        }

        if (! empty($date)) {
            $query->whereDate('scheduled_date', $date);
        }

        $serviceRequests = $query
            ->orderByDesc('scheduled_date')
            ->paginate($perPage)
            ->withQueryString();

        // Summary card statistics
        $pendingServiceRequestsCount = (int) ServiceRequest::query()->whereRaw('lower(service_status) = ?', ['pending'])->count();
        $scheduledServiceRequestsCount = (int) ServiceRequest::query()->whereRaw('lower(service_status) = ?', ['scheduled'])->count();
        $inProgressServiceRequestsCount = (int) ServiceRequest::query()->whereRaw('lower(service_status) = ?', ['in progress'])->count();
        $completedServiceRequestsCount = (int) ServiceRequest::query()->whereRaw('lower(service_status) = ?', ['completed'])->count();

        return view('support.service-requests', [
            'serviceRequests' => $serviceRequests,
            'search' => $search,
            'status' => $status,
            'technician' => $technician,
            'date' => $date,
            'technicians' => Employee::query()->orderBy('department')->orderBy('first_name')->orderBy('last_name')->get(['employee_id', 'first_name', 'last_name', 'department']),
            'pendingServiceRequestsCount' => $pendingServiceRequestsCount,
            'scheduledServiceRequestsCount' => $scheduledServiceRequestsCount,
            'inProgressServiceRequestsCount' => $inProgressServiceRequestsCount,
            'completedServiceRequestsCount' => $completedServiceRequestsCount,
            'tickets' => SupportTicket::query()->whereNull('archived_at')->orderByDesc('created_at')->get(['ticket_id', 'subject']),
        ]);
    }

    public function resolutionTrackingIndex(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'ticket_status' => ['nullable', 'in:all,Open,Pending,In Progress,Resolved,Closed,Escalated,Reopened'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);
        $search = $filters['search'] ?? null;
        $ticketStatus = $filters['ticket_status'] ?? null;
        $perPage = (int) ($filters['per_page'] ?? 10);

        $query = ResolutionTracking::query()
            ->with(['supportTicket.customer', 'supportTicket.latestAssignment.employee', 'employee']);

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('resolution_summary', 'like', "%{$search}%")
                    ->orWhere('root_cause', 'like', "%{$search}%")
                    ->orWhere('corrective_action', 'like', "%{$search}%")
                    ->orWhereHas('employee', function (Builder $employeeQuery) use ($search): void {
                        $employeeQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supportTicket', function ($q2) use ($search) {
                        $q2->where('ticket_id', 'like', "%{$search}%")
                            ->orWhere('ticket_type', 'like', "%{$search}%")
                            ->orWhere('subject', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%")
                            ->orWhereHas('customer', function (Builder $customerQuery) use ($search): void {
                                $this->applyCustomerSearch($customerQuery, $search);
                            });
                    });
            });
        }

        if (! empty($ticketStatus) && strtolower($ticketStatus) !== 'all') {
            $query->whereHas('supportTicket', function (Builder $ticketQuery) use ($ticketStatus): void {
                $ticketQuery->where('status', $ticketStatus);
            });
        }

        $resolutionTrackings = $query
            ->orderByDesc('resolved_at')
            ->paginate($perPage)
            ->withQueryString();

        $metricsQuery = ResolutionTracking::query();
        $totalResolutionCount = (int) $metricsQuery->count();
        $resolvedTicketCount = (int) ResolutionTracking::query()->whereNotNull('resolved_at')->distinct('ticket_id')->count('ticket_id');
        $averageResolutionTime = (float) (ResolutionTracking::query()->whereNotNull('resolution_time_hours')->avg('resolution_time_hours') ?? 0);
        $qcPassedCount = (int) ResolutionTracking::query()
            ->where(function (Builder $qcQuery): void {
                $qcQuery->whereRaw('LOWER(qc_status) = ?', ['passed'])
                    ->orWhere(function (Builder $legacyQuery): void {
                        $legacyQuery->whereNull('qc_status')->whereRaw('LOWER(COALESCE(corrective_action, \'\')) LIKE ?', ['%pass%']);
                    });
            })->count();
        $qcFailedCount = (int) ResolutionTracking::query()
            ->where(function (Builder $qcQuery): void {
                $qcQuery->whereRaw('LOWER(qc_status) = ?', ['failed'])
                    ->orWhere(function (Builder $legacyQuery): void {
                        $legacyQuery->whereNull('qc_status')->whereRaw('LOWER(COALESCE(corrective_action, \'\')) LIKE ?', ['%fail%']);
                    });
            })->count();
        $pendingQcCount = $totalResolutionCount - $qcPassedCount - $qcFailedCount;

        return view('support.resolution-tracking', [
            'resolutionTrackings' => $resolutionTrackings,
            'search' => $search,
            'ticketStatus' => $ticketStatus,
            'totalResolutionCount' => $totalResolutionCount,
            'resolvedTicketCount' => $resolvedTicketCount,
            'averageResolutionTime' => round($averageResolutionTime, 2),
            'qcPassedCount' => $qcPassedCount,
            'qcFailedCount' => $qcFailedCount,
            'pendingQcCount' => $pendingQcCount,
            'tickets' => SupportTicket::query()->whereNull('archived_at')->orderByDesc('created_at')->get(['ticket_id', 'subject']),
            'employees' => Employee::query()->orderBy('first_name')->orderBy('last_name')->get(['employee_id', 'first_name', 'last_name']),
        ]);
    }

    public function serviceRequestShow(Request $request, $requestId)
    {
        $serviceRequest = ServiceRequest::query()
            ->with([
                'technician',
                'supportTicket.customer',
                'supportTicket.product',
                'supportTicket.serviceContract',
            ])
            ->findOrFail($requestId);

        $ticket = $serviceRequest->supportTicket;

        return response()->json([
            'request' => [
                'request_id' => $serviceRequest->request_id,
                'request_number' => $serviceRequest->request_number ?: 'SR-'.$serviceRequest->request_id,
                'request_type' => $serviceRequest->request_type,
                'requested_at' => optional($serviceRequest->requested_at)->format('Y-m-d H:i'),
                'scheduled_date' => optional($serviceRequest->scheduled_date)->format('Y-m-d'),
                'scheduled_time' => optional($serviceRequest->scheduled_date)->format('H:i'),
                'scheduled_at' => optional($serviceRequest->scheduled_date)->format('Y-m-d H:i'),
                'scheduled_end' => optional($serviceRequest->scheduled_end)->format('Y-m-d H:i'),
                'completion_date' => optional($serviceRequest->completion_date)->format('Y-m-d H:i'),
                'service_status' => $serviceRequest->service_status,
                'schedule_notes' => $serviceRequest->schedule_notes,
                'technicians' => Employee::query()->orderBy('department')->orderBy('first_name')->orderBy('last_name')->get(['employee_id', 'first_name', 'last_name', 'department'])
                    ->map(fn (Employee $employee): array => ['employee_id' => $employee->employee_id, 'name' => $employee->full_name, 'department' => $employee->department])
                    ->values(),
                'technician_id' => $serviceRequest->technician_id,
                'technician' => $serviceRequest->technician ? [
                    'name' => $serviceRequest->technician->full_name,
                    'department' => $serviceRequest->technician->department,
                ] : null,
                'priority' => $ticket?->priority,
            ],
            'ticket' => [
                'ticket_id' => $ticket?->ticket_id,
                'name' => optional($ticket?->customer)->full_name,
                'subject' => $ticket?->subject,
                'description' => $ticket?->description,
                'product' => $ticket?->product?->product_name,
                'service_contract' => $ticket?->serviceContract ? [
                    'contract_id' => $ticket->serviceContract->contract_id,
                    'contract_number' => $ticket->serviceContract->contract_number,
                    'status' => $ticket->serviceContract->currentStatus(),
                    'coverage' => $ticket->serviceContract->isCovered() ? 'Covered' : 'Not Covered',
                ] : null,
            ],
            'contract' => $ticket?->serviceContract ? [
                'contract_id' => $ticket->serviceContract->contract_id,
                'contract_number' => $ticket->serviceContract->contract_number,
                'view_url' => route('support.service-contracts', ['open_contract' => $ticket->serviceContract->contract_id]),
                'details_url' => route('support.service-contracts', ['open_contract' => $ticket->serviceContract->contract_id]),
            ] : null,
        ]);
    }

    public function scheduleServiceRequest(Request $request, $requestId)
    {
        $validator = Validator::make($request->all(), [
            'technician_id' => ['required', 'integer', 'exists:employees,employee_id'],
            'scheduled_date' => ['required', 'date'],
            'scheduled_time' => ['required', 'date_format:H:i'],
            'scheduled_end' => ['nullable', 'date_format:H:i', 'after:scheduled_time'],
            'priority' => ['required', 'in:High,Medium,Low'],
            'schedule_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        $serviceRequest = ServiceRequest::query()->with('supportTicket')->findOrFail($requestId);

        DB::transaction(function () use ($request, $serviceRequest): void {
            $serviceRequest->update([
                'technician_id' => (int) $request->input('technician_id'),
                'scheduled_date' => $request->input('scheduled_date').' '.$request->input('scheduled_time'),
                'scheduled_end' => $request->filled('scheduled_end')
                    ? $request->input('scheduled_date').' '.$request->input('scheduled_end')
                    : null,
                'schedule_notes' => $request->input('schedule_notes'),
                'service_status' => 'Scheduled',
            ]);

            $ticket = $serviceRequest->supportTicket;
            if ($ticket) {
                $ticket->update(['priority' => $request->input('priority')]);
            }
        });

        $serviceRequest->refresh()->load(['technician', 'supportTicket']);

        if (! $request->expectsJson()) {
            return redirect()->route('support.service-requests')->with('success', 'Service request scheduled successfully.');
        }

        return response()->json([
            'message' => 'Service request scheduled successfully.',
            'request_id' => $serviceRequest->request_id,
            'scheduled_date' => $serviceRequest->fresh()->scheduled_date?->format('Y-m-d H:i'),
            'scheduled_end' => $serviceRequest->scheduled_end?->format('Y-m-d H:i'),
            'technician' => $serviceRequest->technician?->full_name,
            'priority' => $serviceRequest->supportTicket?->priority,
            'status' => $serviceRequest->service_status,
        ]);
    }

    public function resolutionShow(Request $request, $resolutionId)
    {
        $resolution = ResolutionTracking::query()
            ->with([
                'supportTicket.customer',
                'supportTicket.product',
                'supportTicket.latestAssignment.employee',
                'employee',
            ])
            ->findOrFail($resolutionId);

        $ticket = $resolution->supportTicket;
        $assignedEmployee = $resolution->employee ?? $ticket?->latestAssignment?->employee;

        $resolvedDate = $resolution->resolved_at ? $resolution->resolved_at->format('Y-m-d H:i') : null;
        $resolutionTimeHours = $resolution->resolution_time_hours;
        $resolutionTimeText = $resolutionTimeHours !== null
            ? rtrim(rtrim(number_format((float) $resolutionTimeHours, 2, '.', ''), '0'), '.').'h'
            : null;

        $qcStatus = ucfirst($resolution->resolveQcStatus());
        $resolvedOutcome = $resolution->outcome();

        return response()->json([
            'resolution' => [
                'resolution_id' => $resolution->resolution_id,
                'ticket_id' => $resolution->ticket_id,
                'resolved_by' => $resolution->resolved_by,
                'resolution_summary' => $resolution->resolution_summary,
                'root_cause' => $resolution->root_cause,
                'corrective_action' => $resolution->corrective_action,
                'resolution_time_hours' => $resolutionTimeHours !== null ? (float) $resolutionTimeHours : null,
                'resolved_at' => $resolution->resolved_at ? $resolution->resolved_at->format('Y-m-d H:i:s') : null,
                'resolved_date' => $resolvedDate,
                'outcome' => $resolvedOutcome,
                'qc_status' => $qcStatus,
            ],
            'ticket' => [
                'ticket_id' => $ticket?->ticket_id,
                'ticket_number' => $ticket?->ticket_id ? ('TK-'.$ticket->ticket_id) : null,
                'subject' => $ticket?->subject,
                'status' => $ticket?->status,
                'customer' => [
                    'name' => optional($ticket?->customer)->full_name,
                    'email' => optional($ticket?->customer)->email,
                ],
                'product' => [
                    'product_name' => optional($ticket?->product)->product_name,
                    'sku' => optional($ticket?->product)->sku,
                ],
            ],
            'assignedEmployee' => [
                'employee_id' => $assignedEmployee?->employee_id,
                'name' => $assignedEmployee?->full_name,
                'department' => $assignedEmployee?->department,
                'role' => $assignedEmployee?->role,
            ],
            'modal' => [
                'resolutionRootCauseText' => $resolution->root_cause ?? '—',
                'resolutionOutcomeBadge' => $resolvedOutcome ?? '—',
                'resolutionCorrectiveActionText' => $resolution->corrective_action ?? '—',
                'resolutionResolvedByText' => $assignedEmployee?->getFullNameAttribute() ?? '—',
                'resolutionTimeHoursText' => $resolutionTimeText ?? '—',
                'resolutionResolvedDateText' => $resolvedDate ?? '—',
                'resolutionTicketNumberText' => $ticket?->ticket_id ? ('TK-'.$ticket->ticket_id) : '—',
                'resolutionWorkflowStatusText' => $qcStatus ?? '—',
            ],
        ]);
    }

    public function customerSatisfactionIndex(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'rating' => ['nullable', 'in:all,1,2,3,4,5'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);
        $search = $filters['search'] ?? null;
        $rating = $filters['rating'] ?? null;
        $perPage = (int) ($filters['per_page'] ?? 10);

        $query = SatisfactionMonitoring::query()
            ->with(['supportTicket.customer']);

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('comments', 'like', "%{$search}%")
                    ->orWhereHas('supportTicket', function ($q2) use ($search) {
                        $q2->where('ticket_type', 'like', "%{$search}%")
                            ->orWhere('ticket_id', 'like', "%{$search}%")
                            ->orWhere('subject', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%")
                            ->orWhereHas('customer', function (Builder $customerQuery) use ($search) {
                                $this->applyCustomerSearch($customerQuery, $search);
                                $customerQuery->orWhere('email', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($rating !== null && strtolower($rating) !== 'all') {
            $query->where('rating', (int) $rating);
        }

        $metricsQuery = clone $query;
        $satisfactions = $query
            ->orderByDesc('submitted_at')
            ->paginate($perPage)
            ->withQueryString();

        $stats = (clone $metricsQuery)->selectRaw('COUNT(*) as responses, AVG(rating) as average_rating, SUM(CASE WHEN rating IN (4, 5) THEN 1 ELSE 0 END) as satisfied, SUM(CASE WHEN rating IN (1, 2) THEN 1 ELSE 0 END) as dissatisfied')->first();
        $responsesCount = (int) ($stats->responses ?? 0);
        $averageRating = round((float) ($stats->average_rating ?? 0), 1);
        $satisfiedCount = (int) ($stats->satisfied ?? 0);
        $dissatisfiedCount = (int) ($stats->dissatisfied ?? 0);
        $satisfactionPct = $responsesCount > 0 ? round(($satisfiedCount / $responsesCount) * 100, 0) : 0;
        $ratingDistribution = (clone $metricsQuery)->selectRaw('rating, COUNT(*) as aggregate')->whereNotNull('rating')->groupBy('rating')->pluck('aggregate', 'rating');

        return view('support.customer-satisfaction', [
            'satisfactions' => $satisfactions,
            'search' => $search,
            'rating' => $rating,
            'averageRating' => $averageRating,
            'satisfactionPct' => (int) $satisfactionPct,
            'dissatisfiedCount' => $dissatisfiedCount,
            'responsesCount' => $responsesCount,
            'ratingDistribution' => $ratingDistribution,
        ]);
    }

    private function applyCustomerSearch(Builder $query, string $search): void
    {
        $nameParts = preg_split('/\s+/', trim($search)) ?: [];

        $query->where(function (Builder $nameQuery) use ($search, $nameParts): void {
            $nameQuery->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%");

            if (count($nameParts) >= 2) {
                $nameQuery->orWhere(function (Builder $fullNameQuery) use ($nameParts): void {
                    $fullNameQuery->where('first_name', 'like', '%'.$nameParts[0].'%')
                        ->where('last_name', 'like', '%'.$nameParts[count($nameParts) - 1].'%');
                });
            }
        });
    }
}
