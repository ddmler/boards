<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

abstract class DatabaseTestCase extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        // Deactivated - right now we create all objects ourselves
        // Artisan::call('db:seed');
    }
}
