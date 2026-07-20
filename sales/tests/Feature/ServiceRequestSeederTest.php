<?php

namespace Tests\Feature;

use App\Models\ServiceRequest;
use Database\Seeders\ServiceRequestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceRequestSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_request_seeder_creates_five_idempotent_database_backed_records(): void
    {
        $this->seed(ServiceRequestSeeder::class);
        $this->seed(ServiceRequestSeeder::class);

        $this->assertSame(5, ServiceRequest::query()->count());
        $this->assertSame(5, ServiceRequest::query()->distinct('request_type')->count('request_type'));

        $response = $this->get('/support/service-requests?search=Preventive%20maintenance');

        $response->assertOk()
            ->assertSee('SR-2');
    }
}
