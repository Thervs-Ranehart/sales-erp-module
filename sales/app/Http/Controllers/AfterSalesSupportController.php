<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AfterSalesSupportController extends Controller
{
    public function ticketsIndex()
    {
        return view('support.tickets');
    }

    public function warrantyRecordsIndex()
    {
        return view('support.warranty-records');
    }

    public function warrantyClaimsIndex()
    {
        return view('support.warranty-claims');
    }

    public function serviceContractsIndex()
    {
        return view('support.service-contracts');
    }

    public function serviceRequestsIndex()
    {
        return view('support.service-requests');
    }

    public function resolutionTrackingIndex()
    {
        return view('support.resolution-tracking');
    }

    public function customerSatisfactionIndex()
    {
        return view('support.customer-satisfaction');
    }
}

