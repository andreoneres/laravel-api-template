<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterApplicationCreated(function () {
            $this->artisan("key:generate", ["--env" => "testing"]);
            $this->artisan("migrate:fresh", ["--env" => "testing"]);
            $this->artisan("migrate", ["--env" => "testing"]);
            $this->artisan("db:seed", ["--env" => "testing"]);
        });
    }
}
