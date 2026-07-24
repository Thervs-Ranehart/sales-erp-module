<?php

use App\Services\AfterSalesAutomationService;
use App\Services\RetentionAutomationService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('crm:retention-automation', function (RetentionAutomationService $service) {
    $this->info($service->run().' retention follow-up(s) created.');
})->purpose('Create deduplicated CRM retention follow-ups and notifications');

Schedule::command('crm:retention-automation')->daily()->withoutOverlapping();

Artisan::command('support:escalate-sla', function (AfterSalesAutomationService $service) {
    $this->info($service->escalateBreaches().' overdue support ticket(s) escalated.');
})->purpose('Escalate overdue after-sales cases and notify the support team');

Schedule::command('support:escalate-sla')->everyFifteenMinutes()->withoutOverlapping();
