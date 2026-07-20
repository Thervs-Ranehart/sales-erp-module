<?php

namespace Tests\Feature;

use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class CustomerSatisfactionViewTest extends TestCase
{
    public function test_customer_satisfaction_view_uses_real_database_metrics_without_placeholder_text(): void
    {
        $paginator = new LengthAwarePaginator([], 0, 10, 1);

        $html = view('support.customer-satisfaction', [
            'satisfactions' => $paginator,
            'search' => null,
            'rating' => null,
            'averageRating' => 4.2,
            'satisfactionPct' => 70,
            'dissatisfiedCount' => 1,
            'responsesCount' => 10,
            'ratingDistribution' => [
                '5' => 4,
                '4' => 3,
                '3' => 2,
                '2' => 1,
                '1' => 0,
            ],
        ])->render();

        $this->assertStringContainsString('Satisfied Responses', $html);
        $this->assertStringContainsString('Ratings 4–5', $html);
        $this->assertStringNotContainsString('Top Sentiment', $html);
    }
}
