<?php

namespace Tests;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Secara default jalankan DatabaseSeeder untuk setiap test yang
     * menggunakan RefreshDatabase ataupun DatabaseTransactions.
     */
    protected bool $seed = true;

    protected string $seeder = DatabaseSeeder::class;
}
