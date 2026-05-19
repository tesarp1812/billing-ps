<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->getJson('/');

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'PS Backend API is running.',
            ]);
    }
}
