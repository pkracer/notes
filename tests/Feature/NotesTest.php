<?php

namespace Tests\Feature;

use App\Note;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function unauthenticated_users_cannot_take_notes()
    {
        $this->json('POST', '/api/notes')
            ->assertStatus(401);
    }

    /** @test */
    function adding_a_note_requires_presence_of_a_title()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('POST', '/api/notes')
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'title' => ['The title field must be present.']
                ]
            ]);
    }

    /** @test */
    function adding_a_note_requires_presence_of_a_body()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('POST', '/api/notes')
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'body' => ['The body field must be present.']
                ]
            ]);
    }

    /** @test */
    function adding_a_note_takes_a_valid_color()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('POST', '/api/notes', ['color' => 'invalid'])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'color' => ['The selected color is invalid.']
                ]
            ]);
    }

    /** @test */
    function authenticated_users_can_add_a_note()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('POST', '/api/notes', [
                'title' => 'Test',
                'body' => 'Note body',
                'color' => 'green'
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('notes', [
            'user_id' => $user->id,
            'title' => 'Test',
            'body' => 'Note body',
            'color' => 'green'
        ]);
    }

    /** @test */
    function authenticated_users_can_add_a_note_with_a_blank_title()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('POST', '/api/notes', [
                'title' => '',
                'body' => 'Note body',
                'color' => 'green'
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('notes', [
            'user_id' => $user->id,
            'title' => '',
            'body' => 'Note body',
            'color' => 'green'
        ]);
    }

    /** @test */
    function authenticated_users_can_add_a_note_with_a_blank_body()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('POST', '/api/notes', [
                'title' => 'Title',
                'body' => '',
                'color' => 'green'
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('notes', [
            'user_id' => $user->id,
            'title' => 'Title',
            'body' => '',
            'color' => 'green'
        ]);
    }

    /** @test */
    function unauthenticated_users_cannot_edit_notes()
    {
        $note = factory(Note::class)->create();

        $this->json('PATCH', "/api/notes/{$note->id}")
            ->assertStatus(401);
    }

    /** @test */
    function unauthorized_users_cannot_edit_notes()
    {
        $note = factory(Note::class)->create();

        $this->actingAs(factory(User::class)->create(), 'api')
            ->json('PATCH', "/api/notes/{$note->id}")
            ->assertForbidden();
    }

    /** @test */
    function users_cannot_edit_non_existent_notes()
    {
        $this->actingAs(factory(User::class)->create(), 'api')
            ->json('PATCH', "/api/notes/1239018230")
            ->assertNotFound();
    }

    /** @test */
    function editing_a_note_requires_presence_of_a_title()
    {
        $note = factory(Note::class)->create();

        $this->actingAs($note->user, 'api')
            ->json('PATCH', "/api/notes/{$note->id}")
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'title' => ['The title field must be present.']
                ]
            ]);
    }

    /** @test */
    function editing_a_note_requires_presence_of_a_body()
    {
        $note = factory(Note::class)->create();

        $this->actingAs($note->user, 'api')
            ->json('PATCH', "/api/notes/{$note->id}")
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'body' => ['The body field must be present.']
                ]
            ]);
    }

    /** @test */
    function editing_a_note_takes_a_valid_color()
    {
        $note = factory(Note::class)->create();

        $this->actingAs($note->user, 'api')
            ->json('PATCH', "/api/notes/{$note->id}", ['color' => 'invalid'])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'color' => ['The selected color is invalid.']
                ]
            ]);
    }

    /** @test */
    function authenticated_users_can_edit_their_notes()
    {
        $note = factory(Note::class)->create();

        $this->actingAs($note->user, 'api')
            ->json('PATCH', "/api/notes/{$note->id}", [
                'title' => 'Updated title',
                'body' => 'Updated body',
                'color' => 'red'
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'user_id' => $note->user_id,
            'title' => 'Updated title',
            'body' => 'Updated body',
            'color' => 'red'
        ]);
    }

    /** @test */
    function authenticated_users_edit_a_note_to_have_a_blank_title()
    {
        $note = factory(Note::class)->create();

        $this->actingAs($note->user, 'api')
            ->json('PATCH', "/api/notes/{$note->id}", [
                'title' => '',
                'body' => 'Note body',
                'color' => 'green'
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => '',
            'body' => 'Note body',
            'color' => 'green'
        ]);
    }

    /** @test */
    function authenticated_users_edit_a_note_to_have_a_blank_body()
    {
        $note = factory(Note::class)->create();

        $this->actingAs($note->user, 'api')
            ->json('PATCH', "/api/notes/{$note->id}", [
                'title' => 'Title',
                'body' => '',
                'color' => 'green'
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'Title',
            'body' => '',
            'color' => 'green'
        ]);
    }

    /** @test */
    function unauthenticated_users_cannot_delete_notes()
    {
        $note = factory(Note::class)->create();

        $this->json('DELETE', "/api/notes/{$note->id}")
            ->assertStatus(401);
    }

    /** @test */
    function unauthorized_users_cannot_delete_notes()
    {
        $note = factory(Note::class)->create();

        $this->actingAs(factory(User::class)->create(), 'api')
            ->json('DELETE', "/api/notes/{$note->id}")
            ->assertForbidden();
    }

    /** @test */
    function users_cannot_delete_non_existent_notes()
    {
        $this->actingAs(factory(User::class)->create(), 'api')
            ->json('DELETE', "/api/notes/1239018230")
            ->assertNotFound();
    }

    /** @test */
    function authenticated_users_can_delete_their_notes()
    {
        $note = factory(Note::class)->create();

        $this->actingAs($note->user, 'api')
            ->json('DELETE', "/api/notes/{$note->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('notes', [
            'id' => $note->id
        ]);
    }
}
