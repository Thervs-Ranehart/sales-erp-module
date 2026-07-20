<?php

namespace Database\Seeders;

use App\Models\SatisfactionMonitoring;
use App\Models\SupportTicket;
use Illuminate\Database\Seeder;

class SatisfactionMonitoringSeeder extends Seeder
{
    public function run(): void
    {
        $tickets = SupportTicket::query()->orderBy('ticket_id')->get();
        if ($tickets->isEmpty()) {
            return;
        }

        $samples = [
            [5, 'Very Satisfied', 'Issue was resolved quickly and clearly.'], [4, 'Satisfied', 'Support was helpful, but the response took longer than expected.'], [3, 'Neutral', 'The service was satisfactory.'], [2, 'Dissatisfied', 'The technician arrived late.'], [1, 'Very Dissatisfied', 'The issue was not completely resolved.'], [2, 'Dissatisfied', 'I had to follow up several times.'], [1, 'Very Dissatisfied', 'The replacement process was confusing.'], [5, 'Very Satisfied', 'The support team explained the warranty process well.'], [3, 'Neutral', 'My concern remains unresolved.'], [4, 'Satisfied', 'The response was fast and professional.'],
        ];

        foreach ($samples as $index => [$rating, $level, $comment]) {
            $ticket = $tickets[$index % $tickets->count()];
            SatisfactionMonitoring::query()->updateOrCreate(
                ['ticket_id' => $ticket->ticket_id, 'comments' => $comment],
                ['rating' => $rating, 'satisfaction_level' => $level, 'submitted_at' => now()->subDays($index)],
            );
        }
    }
}
