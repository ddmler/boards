<?php

namespace Tests\Feature;

use Tests\DatabaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;
use App\Board;
use App\BoardList;

class BoardTest extends DatabaseTestCase
{
    public function testLoginRequired()
    {
        $this->json('GET', 'api/boards')
            ->assertStatus(401);
    }

    public function testUserCanSeeBoards()
    {
        $user = factory(User::class)->create();
        $board = factory(Board::class)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->json('GET', 'api/boards')
            ->assertStatus(200)
            ->assertJson([
                ['name' => $board->name]
            ])
            ->assertJsonCount(1);
    }

    public function testUserCanViewHisBoard()
    {
        $user = factory(User::class)->create();
        $board = factory(Board::class)->create(['user_id' => $user->id]);
        $boardLists = factory(BoardList::class, 2)->create(['board_id' => $board->id]);


        $this->actingAs($user)
            ->json('GET', 'api/boards/' . $board->id)
            ->assertStatus(200)
            ->assertJsonCount(2, 'board_lists');
    }

    public function testUserCanCreateBoards()
    {
        $user = factory(User::class)->create();
        $payload = ['name' => 'My Board'];

        $this->actingAs($user)
            ->json('POST', 'api/boards', $payload)
            ->assertStatus(201)
            ->assertJson(['name' => $payload['name']]);
    }

    public function testUserCanUpdateBoard()
    {
        $user = factory(User::class)->create();
        $board = factory(Board::class)->create(['user_id' => $user->id]);
        $payload = ['name' => 'My Board'];

        $this->actingAs($user)
            ->json('PATCH', 'api/boards/' . $board->id, $payload)
            ->assertStatus(200)
            ->assertJson(['name' => $payload['name']])
            ->assertJsonMissing(['name' => $board->name]);
    }

    public function testUserCanDeleteBoards()
    {
        $user = factory(User::class)->create();
        $board = factory(Board::class)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->json('DELETE', 'api/boards/' . $board->id)
            ->assertStatus(204);

        // Check that it is really gone with a GET request of all boards of the user
        $this->actingAs($user)
            ->json('GET', 'api/boards')
            ->assertJsonCount(0);
    }
}
