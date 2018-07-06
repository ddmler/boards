<?php

namespace Tests\Feature;

use Tests\DatabaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;
use App\Board;
use App\BoardList;

class BoardTest extends DatabaseTestCase
{
    protected $user;
    protected $board;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->board = factory(Board::class)->create(['user_id' => $this->user->id]);
    }

    public function testLoginRequired()
    {
        $this->json('GET', 'api/boards')
            ->assertStatus(401);
    }

    public function testUserCanSeeBoards()
    {
        $this->actingAs($this->user)
            ->json('GET', 'api/boards')
            ->assertStatus(200)
            ->assertJson([
                ['name' => $this->board->name]
            ])
            ->assertJsonCount(1);
    }

    public function testUserCanViewHisBoard()
    {
        $boardLists = factory(BoardList::class, 2)->create(['board_id' => $this->board->id]);

        $this->actingAs($this->user)
            ->json('GET', 'api/boards/' . $this->board->id)
            ->assertStatus(200)
            ->assertJsonCount(2, 'board_lists');
    }

    public function testUserCanCreateBoards()
    {
        $payload = ['name' => 'My Board'];

        $this->actingAs($this->user)
            ->json('POST', 'api/boards', $payload)
            ->assertStatus(201)
            ->assertJson(['name' => $payload['name']]);
    }

    public function testCreateBoardValidation()
    {
        $this->actingAs($this->user)
            ->json('POST', 'api/boards')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name field is required."]
                ]
            ]);
    }

    public function testUserCanUpdateBoard()
    {
        $payload = ['name' => 'My Board'];

        $this->actingAs($this->user)
            ->json('PATCH', 'api/boards/' . $this->board->id, $payload)
            ->assertStatus(200)
            ->assertJson(['name' => $payload['name']])
            ->assertJsonMissing(['name' => $this->board->name]);
    }

    public function testUpdateValidation()
    {
        $this->actingAs($this->user)
            ->json('PATCH', 'api/boards/' . $this->board->id)
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name field is required."]
                ]
            ]);
    }

    public function testUserCanDeleteBoards()
    {
        $this->actingAs($this->user)
            ->json('DELETE', 'api/boards/' . $this->board->id)
            ->assertStatus(204);

        // Check that it is really gone with a GET request of all boards of the user
        $this->actingAs($this->user)
            ->json('GET', 'api/boards')
            ->assertJsonCount(0);
    }

    public function testAuthorization()
    {
        $user2 = factory(User::class)->create();

        $this->actingAs($user2)
            ->json('GET', 'api/boards/' . $this->board->id)
            ->assertStatus(403);

        $this->actingAs($user2)
            ->json('DELETE', 'api/boards/' . $this->board->id)
            ->assertStatus(403);

        $this->actingAs($user2)
            ->json('PATCH', 'api/boards/' . $this->board->id)
            ->assertStatus(403);
        
        $this->actingAs($user2)
            ->json('PATCH', 'api/board/updateOrder', ['board' => $this->board])
            ->assertStatus(403);

        $this->actingAs($user2)
            ->json('PATCH', 'api/board/updateListOrder', ['board' => $this->board])
            ->assertStatus(403);
    }
}
