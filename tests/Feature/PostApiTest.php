<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_get_posts()
{
    $response = $this->getJson('/api/posts');

    $response->assertStatus(200);

$response = $this->postJson('/api/posts',[
    'title'=>'Laravel',
    'body'=>'Test'
]);

$response->assertStatus(201);
}}
