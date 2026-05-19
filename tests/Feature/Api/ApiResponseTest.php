<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class ApiResponseTest extends TestCase
{
    public function test_health_endpoint_returns_standard_success_response(): void
    {
        $response = $this->getJson('/api/health');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'status',
                    'timestamp',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'API is healthy.',
                'data' => [
                    'status' => 'ok',
                ],
            ]);
    }

    public function test_protected_api_endpoint_returns_standard_unauthenticated_response(): void
    {
        $response = $this->getJson('/api/stations');

        $response
            ->assertUnauthorized()
            ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated.',
                'errors' => null,
            ]);
    }

    public function test_unknown_api_endpoint_returns_standard_not_found_response(): void
    {
        $response = $this->getJson('/api/unknown-endpoint');

        $response
            ->assertNotFound()
            ->assertJson([
                'success' => false,
                'message' => 'Resource tidak ditemukan.',
                'errors' => null,
            ]);
    }

    public function test_wrong_method_returns_standard_method_not_allowed_response(): void
    {
        $response = $this->postJson('/api/stations');

        $response
            ->assertStatus(405)
            ->assertJson([
                'success' => false,
                'message' => 'HTTP method tidak diizinkan untuk endpoint ini.',
                'errors' => null,
            ]);
    }
}
