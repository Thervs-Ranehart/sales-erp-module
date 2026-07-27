<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CommunicationLog;
use App\Models\Employee;
use Illuminate\Http\Request;

class CustomerFollowUpsController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status');


        $baseQuery = CommunicationLog::with([
            'customer',
            'agent'
        ])
        ->whereNotNull('follow_up_date');


        if ($search !== '') {

            $baseQuery->where(function ($q) use ($search) {

                $q->where('subject', 'like', "%{$search}%")

                ->orWhereHas('customer', function ($cq) use ($search) {
                    $cq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%");
                })

                ->orWhereHas('agent', function ($aq) use ($search) {
                    $aq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%");
                });

            });
        }


        if ($status) {
            $baseQuery->where(
                'communication_status',
                $status
            );
        }


        $followUps = $baseQuery
            ->orderBy('follow_up_date')
            ->paginate(10)
            ->withQueryString();



        return view('crm.customer-followups', [

            'followUps' => $followUps,

            'customers' => Customer::orderBy('first_name')->get(),

            // Existing employees table used as agents
            'agents' => Employee::where(
                'employee_status',
                'Active'
            )
            ->orderBy('first_name')
            ->get(),


            'todayCount' => CommunicationLog::whereDate(
                'follow_up_date',
                now()->toDateString()
            )->count(),


            'pendingCount' => CommunicationLog::where(
                'communication_status',
                'Pending'
            )->count(),


            'overdueCount' => CommunicationLog::whereDate(
                'follow_up_date',
                '<',
                now()->toDateString()
            )
            ->where(
                'communication_status',
                '!=',
                'Completed'
            )
            ->count(),


            'completedCount' => CommunicationLog::where(
                'communication_status',
                'Completed'
            )->count(),


            'search'=>$search,

            'status'=>$status

        ]);
    }



    public function store(Request $request)
    {

        $data = $request->validate([

            'customer_id' => [
                'required',
                'exists:customers,customer_id'
            ],

            'employee_id' => [
                'required',
                'exists:employees,employee_id'
            ],

            'communication_channel'=>[
                'required'
            ],

            'subject'=>[
                'required',
                'string'
            ],

            'notes'=>[
                'nullable',
                'string'
            ],

            'follow_up_date'=>[
                'required',
                'date'
            ],

            'priority'=>[
                'required',
                'in:Low,Medium,High'
            ],

            'communication_status'=>[
                'required',
                'in:Pending,Completed'
            ]

        ]);



        CommunicationLog::create([

            'customer_id'=>$data['customer_id'],

            // Assigned Agent
            'employee_id'=>$data['employee_id'],

            'communication_date'=>now(),

            'communication_channel'=>$data['communication_channel'],

            'subject'=>$data['subject'],

            'notes'=>$data['notes'] ?? null,

            'follow_up_date'=>$data['follow_up_date'],

            'priority'=>$data['priority'],

            'communication_status'=>$data['communication_status']

        ]);



        return back()->with(
            'success',
            'Follow-up created successfully.'
        );
    }
    public function updateStatus(Request $request, CommunicationLog $log)
{
    $data = $request->validate([
        'communication_status' => [
            'required',
            'in:Pending,Completed'
        ]
    ]);

    $log->update([
        'communication_status' => $data['communication_status']
    ]);

    return back()->with(
        'success',
        'Follow-up status updated successfully.'
    );
}
}