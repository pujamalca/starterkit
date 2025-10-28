<?php

namespace Tests\Feature\Console;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DoctorCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_command_runs_successfully(): void
    {
        Storage::fake(config('filesystems.default'));

        $this->artisan('starterkit:doctor')
            ->expectsOutputToContain('Doctor Website - Health Check & Analytics')
            ->expectsOutputToContain('Database')
            ->expectsOutputToContain('Pemeriksaan selesai.')
            ->assertExitCode(0);
    }
}

