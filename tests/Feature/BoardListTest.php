<?php

namespace Tests\Feature;

use Tests\DatabaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;
use App\Board;
use App\BoardList;

class BoardListTest extends DatabaseTestCase
{
    protected $user;
    protected $board;
    protected $boardList;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->board = factory(Board::class)->create(['user_id' => $this->user->id]);
        $this->boardList = factory(BoardList::class)->create(['board_id' => $this->board->id]);
    }

    public function testLoginRequired()
    {
        $payload = ['name' => 'My List', 'order' => 1, 'board_id' => $this->board->id];

        $this->json('POST', 'api/boardLists', $payload)
            ->assertStatus(401);
    }

    public function testUserCanCreateLists()
    {
        $payload = ['name' => 'My List', 'order' => 1, 'board_id' => $this->board->id];

        $this->actingAs($this->user)
            ->json('POST', 'api/boardLists', $payload)
            ->assertStatus(201)
            ->assertJson(['name' => $payload['name']]);
    }

    public function testCreateValidation()
    {
        $this->actingAs($this->user)
            ->json('POST', 'api/boardLists')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name field is required."],
                    "order" => ["The order field is required."],
                    "board_id" => ["The board id field is required."],
                ]
            ]);
    }

    public function testUserCanUpdateLists()
    {
        $payload = ['name' => 'My List'];

        $this->actingAs($this->user)
            ->json('PATCH', 'api/boardLists/' . $this->boardList->id, $payload)
            ->assertStatus(200)
            ->assertJson(['name' => $payload['name']])
            ->assertJsonMissing(['name' => $this->boardList->name]);
    }

    public function testUpdateValidation()
    {
        $this->actingAs($this->user)
            ->json('PATCH', 'api/boardLists/' . $this->boardList->id)
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name field is required."]
                ]
            ]);
    }

    public function testUserCanDeleteLists()
    {
        $this->actingAs($this->user)
            ->json('DELETE', 'api/boardLists/' . $this->boardList->id)
            ->assertStatus(204);

        // Check that it is really gone with a GET request of the board of the user
        $this->actingAs($this->user)
            ->json('GET', 'api/boards/' . $this->board->id)
            ->assertJsonCount(0, 'board_lists');
    }

    public function testAuthorization()
    {
        $user2 = factory(User::class)->create();
        $payload = ['name' => 'My List', 'order' => 1, 'board_id' => $this->board->id];

        $this->actingAs($user2)
            ->json('POST', 'api/boardLists', $payload)
            ->assertStatus(403);

        $this->actingAs($user2)
            ->json('PATCH', 'api/boardLists/' . $this->boardList->id, $payload)
            ->assertStatus(403);

        $this->actingAs($user2)
            ->json('DELETE', 'api/boardLists/' . $this->boardList->id)
            ->assertStatus(403);
    }
}
