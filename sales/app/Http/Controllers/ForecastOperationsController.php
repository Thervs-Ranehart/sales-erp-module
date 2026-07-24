<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ForecastRecommendation;
use App\Models\ForecastWorkflowEvent;
use App\Models\PlanningAction;
use App\Models\SalesForecast;
use App\Models\SalesRegion;
use App\Services\SalesAnalyticsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ForecastOperationsController extends Controller
{
    public function __construct(private SalesAnalyticsService $analytics) {}

    public function storeRegion(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'region_code' => ['required', 'string', 'max:30', 'unique:sales_regions,region_code'],
            'region_name' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
        ]);
        SalesRegion::query()->create([...$data, 'status' => 'Active']);

        return back()->with('success', 'Sales region created.');
    }

    public function assignRegion(Request $request, Customer $customer): RedirectResponse
    {
        $data = $request->validate(['region_id' => ['nullable', 'exists:sales_regions,region_id']]);
        $customer->update($data);

        return back()->with('success', 'Customer sales region updated.');
    }

    public function updateRecommendation(Request $request, ForecastRecommendation $recommendation): RedirectResponse
    {
        $data = $request->validate([
            'implementation_status' => ['required', 'in:New,Under Review,Approved,Rejected,In Progress,Completed'],
            'assigned_to' => ['nullable', 'exists:employees,employee_id'],
            'assigned_department' => ['nullable', 'string', 'max:100'],
            'due_date' => ['nullable', 'date'],
            'decision_notes' => ['nullable', 'required_if:implementation_status,Rejected', 'string', 'max:2000'],
            'outcome' => ['nullable', 'required_if:implementation_status,Completed', 'string', 'max:2000'],
        ]);
        $allowed = [
            'New' => ['Under Review', 'Approved', 'Rejected'],
            'Under Review' => ['Approved', 'Rejected'],
            'Approved' => ['In Progress', 'Completed'],
            'In Progress' => ['Completed'],
            'Rejected' => [],
            'Completed' => [],
        ];
        $current = $recommendation->implementation_status ?? 'New';
        if ($data['implementation_status'] !== $current && ! in_array($data['implementation_status'], $allowed[$current] ?? [], true)) {
            throw ValidationException::withMessages(['implementation_status' => "Cannot move a recommendation from {$current} to {$data['implementation_status']}."]);
        }

        DB::transaction(function () use ($recommendation, $data, $request, $current): void {
            $status = $data['implementation_status'];
            $recommendation->update([
                ...$data,
                'reviewed_by' => in_array($status, ['Approved', 'Rejected'], true) ? $request->session()->get('employee_id') : $recommendation->reviewed_by,
                'reviewed_at' => in_array($status, ['Approved', 'Rejected'], true) ? now() : $recommendation->reviewed_at,
                'completed_at' => $status === 'Completed' ? now() : null,
            ]);

            if ($status === 'Approved' && in_array($recommendation->recommendation_type, ['Inventory Planning', 'Marketing'], true)) {
                PlanningAction::query()->firstOrCreate(
                    ['recommendation_id' => $recommendation->recommendation_id],
                    [
                        'action_type' => $recommendation->recommendation_type,
                        'title' => $recommendation->title,
                        'assigned_to' => $data['assigned_to'] ?? null,
                        'assigned_department' => $data['assigned_department'] ?? null,
                        'due_date' => $data['due_date'] ?? null,
                        'status' => 'Open',
                    ]
                );
            }

            ForecastWorkflowEvent::query()->create([
                'subject_type' => ForecastRecommendation::class,
                'subject_id' => $recommendation->recommendation_id,
                'employee_id' => $request->session()->get('employee_id'),
                'event_type' => 'Status Changed',
                'description' => "Recommendation moved from {$current} to {$status}.",
                'created_at' => now(),
            ]);
        });

        return back()->with('success', 'Recommendation workflow updated.');
    }

    public function evaluateForecast(Request $request, SalesForecast $forecast): RedirectResponse
    {
        $data = $request->validate(['actual_revenue' => ['required', 'numeric', 'min:0']]);
        $predicted = (float) $forecast->predicted_revenue;
        $actual = (float) $data['actual_revenue'];
        $error = abs($predicted - $actual);
        $forecast->update([
            'actual_revenue' => $actual,
            'mae' => $error,
            'mape' => $actual > 0 ? ($error / $actual) * 100 : null,
            'rmse' => $error,
            'forecast_status' => 'Evaluated',
        ]);

        return back()->with('success', 'Forecast actuals and accuracy metrics updated.');
    }

    public function export(Request $request, string $type): StreamedResponse|Response
    {
        abort_unless(in_array($type, ['dashboard', 'reports', 'performance', 'forecast', 'recommendations'], true), 404);
        $format = $request->validate(['format' => ['nullable', 'in:csv,print']])['format'] ?? 'csv';
        [$headers, $rows] = $this->exportRows($type, $request);
        if (Schema::hasTable('forecast_export_logs')) {
            DB::table('forecast_export_logs')->insert([
                'employee_id' => $request->session()->get('employee_id'),
                'export_type' => $type,
                'format' => $format,
                'filters' => json_encode($request->except('format'), JSON_THROW_ON_ERROR),
                'exported_at' => now(),
            ]);
        }

        if ($format === 'print') {
            return response()->view('forecasting.export-print', compact('type', 'headers', 'rows'));
        }

        return response()->streamDownload(function () use ($headers, $rows): void {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, $headers);
            foreach ($rows as $row) {
                fputcsv($stream, $row);
            }
            fclose($stream);
        }, "{$type}-".now()->format('Ymd-His').'.csv', ['Content-Type' => 'text/csv']);
    }

    /** @return array{0: array<int, string>, 1: array<int, array<int, string|int|float|null>>} */
    private function exportRows(string $type, Request $request): array
    {
        if ($type === 'recommendations') {
            $rows = ForecastRecommendation::query()->with('assignee')->orderByDesc('created_at')->get();

            return [
                ['Recommendation', 'Category', 'Priority', 'Status', 'Assignee', 'Department', 'Due Date', 'Evidence', 'Outcome'],
                $rows->map(fn (ForecastRecommendation $item): array => [
                    $item->title, $item->recommendation_type, $item->priority, $item->implementation_status,
                    $item->assignee?->full_name, $item->assigned_department, $item->due_date?->toDateString(),
                    $item->evidence, $item->outcome,
                ])->all(),
            ];
        }

        if ($type === 'forecast') {
            $rows = SalesForecast::query()->orderByDesc('generated_at')->get();

            return [
                ['Version', 'Period Start', 'Period End', 'Method', 'Predicted Revenue', 'Lower 95%', 'Upper 95%', 'Actual', 'MAE', 'MAPE', 'RMSE', 'Sample Size', 'Status'],
                $rows->map(fn (SalesForecast $item): array => [
                    $item->version, $item->forecast_period_start?->toDateString(), $item->forecast_period_end?->toDateString(),
                    $item->forecast_method, $item->predicted_revenue, $item->prediction_lower, $item->prediction_upper,
                    $item->actual_revenue, $item->mae, $item->mape, $item->rmse, $item->sample_size, $item->forecast_status,
                ])->all(),
            ];
        }

        $snapshot = $this->analytics->snapshot((int) $request->query('year', now()->year), $request->query());

        return match ($type) {
            'performance' => [
                ['Period', 'Target Revenue', 'Actual Revenue', 'Achievement %'],
                collect($snapshot['labels'])->map(fn (string $label, int $index): array => [
                    $label, $snapshot['monthlyTargets'][$index] ?? 0, $snapshot['monthlyRevenue'][$index] ?? 0,
                    ($snapshot['monthlyTargets'][$index] ?? 0) > 0 ? (($snapshot['monthlyRevenue'][$index] ?? 0) / $snapshot['monthlyTargets'][$index]) * 100 : 0,
                ])->all(),
            ],
            default => [
                ['Period', 'Revenue', 'Orders'],
                collect($snapshot['labels'])->map(fn (string $label, int $index): array => [$label, $snapshot['monthlyRevenue'][$index] ?? 0, $snapshot['monthlyOrders'][$index] ?? 0])->all(),
            ],
        };
    }
}
