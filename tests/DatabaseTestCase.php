<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

abstract class DatabaseTestCase extends TestCase
{
    use RefreshDatabase;

    public function setUp() {
        parent::setUp();
        Artisan::call('db:seed');
    }
}
