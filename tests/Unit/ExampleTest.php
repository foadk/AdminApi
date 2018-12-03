<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $routeCollection = Route::getRoutes();

        foreach ($routeCollection as $value) {
            fwrite(STDERR, print_r($value->getName(), true));
            fwrite(STDERR, print_r('   ', true));
        }
        // $routes = Route::getRoutes();
        // fwrite(STDERR, print_r($routes, true));
        $this->assertTrue(true);
    }
}
