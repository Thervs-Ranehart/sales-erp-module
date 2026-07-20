<?php

namespace Tests\Unit;

use App\Models\ResolutionTracking;
use Tests\TestCase;

class ResolutionTrackingStatusTest extends TestCase
{
    public function test_explicit_qc_status_takes_priority_over_corrective_action_text(): void
    {
        $resolution = new ResolutionTracking([
            'corrective_action' => 'pending review',
            'qc_status' => 'passed',
        ]);

        $this->assertSame('passed', $resolution->resolveQcStatus());
    }

    public function test_falls_back_to_corrective_action_text_when_qc_status_is_missing(): void
    {
        $resolution = new ResolutionTracking([
            'corrective_action' => 'pending review needed',
        ]);

        $this->assertSame('pending', $resolution->resolveQcStatus());
    }
}
