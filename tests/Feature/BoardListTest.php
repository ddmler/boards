<?php

namespace Tests\Feature;

use Tests\DatabaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;
use App\Board;
use App\BoardList;

class BoardListTest extends DatabaseTestCase
{
    public function testLoginRequired()
    {
        $user = factory(User::class)->create();
        $board = factory(Board::class)->create(['user_id' => $user->id]);
        $payload = ['name' => 'My List', 'board_id' => $board->id];

        $this->json('POST', 'api/boardLists', $payload)
            ->assertStatus(401);
    }

    public function testUserCanCreateLists()
    {
        $user = factory(User::class)->create();
        $board = factory(Board::class)->create(['user_id' => $user->id]);
        $payload = ['name' => 'My List', 'board_id' => $board->id];

        $this->actingAs($user)
            ->json('POST', 'api/boardLists', $payload)
            ->assertStatus(201)
            ->assertJson(['name' => $payload['name']]);
    }

    public function testUserCanUpdateLists()
    {
        $user = factory(User::class)->create();
        $board = factory(Board::class)->create(['user_id' => $user->id]);
        $boardList = factory(BoardList::class)->create(['board_id' => $board->id]);
        $payload = ['name' => 'My List'];

        $this->actingAs($user)
            ->json('PATCH', 'api/boardLists/' . $boardList->id, $payload)
            ->assertStatus(200)
            ->assertJson(['name' => $payload['name']])
            ->assertJsonMissing(['name' => $boardList->name]);
    }

    public function testUserCanDeleteLists()
    {
        $user = factory(User::class)->create();
        $board = factory(Board::class)->create(['user_id' => $user->id]);
        $boardList = factory(BoardList::class)->create(['board_id' => $board->id]);

        $this->actingAs($user)
            ->json('DELETE', 'api/boardLists/' . $boardList->id)
            ->assertStatus(204);

        // Check that it is really gone with a GET request of the board of the user
        $this->actingAs($user)
            ->json('GET', 'api/boards/' . $board->id)
            ->assertJsonCount(0, 'board_lists');
    }
}
