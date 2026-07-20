<?php

namespace Tests\Feature;

use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class ServiceContractModalViewTest extends TestCase
{
    public function test_service_contract_modal_view_does_not_hardcode_placeholder_fields(): void
    {
        $paginator = new LengthAwarePaginator([], 0, 10, 1);

        $html = view('support.service-contracts', [
            'serviceContracts' => $paginator,
            'search' => null,
            'status' => null,
            'customer' => null,
            'activeCoverageCount' => 0,
            'expiringSoonCount' => 0,
            'expiredCount' => 0,
            'coverageVerificationRatePct' => 0,
        ])->render();

        $this->assertStringNotContainsString("document.getElementById('serviceContractOwner').textContent = '—'", $html);
        $this->assertStringNotContainsString("document.getElementById('serviceContractSla').textContent = '—'", $html);
        $this->assertStringNotContainsString("document.getElementById('serviceContractDispatchFrequency').textContent = '—'", $html);
    }
}
