<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function unauthenticated_users_cannot_navigate_to_the_home_page()
    {
        $this->get('/')
            ->assertRedirect(route('login'));
    }

    /** @test */
    function unauthenticated_users_can_navigate_to_the_home_page()
    {
        $this->actingAs(factory(User::class)->create())
            ->get('/')
            ->assertOk()
            ->assertViewHas('notes');
    }
}
