<?php

namespace Tests\Feature;

use Tests\DatabaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;
use App\Board;
use App\BoardList;
use App\Card;

class CardTest extends DatabaseTestCase
{
    public function testLoginRequired()
    {
        $user = factory(User::class)->create();
        $board = factory(Board::class)->create(['user_id' => $user->id]);
        $boardList = factory(BoardList::class)->create(['board_id' => $board->id]);
        $payload = ['name' => 'My Card', 'order' => 1, 'list_id' => $boardList->id];

        $this->json('POST', 'api/cards', $payload)
            ->assertStatus(401);
    }

    public function testUserCanCreateCards()
    {
        $user = factory(User::class)->create();
        $board = factory(Board::class)->create(['user_id' => $user->id]);
        $boardList = factory(BoardList::class)->create(['board_id' => $board->id]);
        $payload = ['name' => 'My Card', 'order' => 1, 'list_id' => $boardList->id];

        $this->actingAs($user)
            ->json('POST', 'api/cards', $payload)
            ->assertStatus(201)
            ->assertJson(['name' => $payload['name']]);
    }

    public function testUserCanUpdateCards()
    {
        $user = factory(User::class)->create();
        $board = factory(Board::class)->create(['user_id' => $user->id]);
        $boardList = factory(BoardList::class)->create(['board_id' => $board->id]);
        $card = factory(Card::class)->create(['board_list_id' => $boardList->id]);
        $payload = ['name' => 'My Card'];

        $this->actingAs($user)
            ->json('PATCH', 'api/cards/' . $card->id, $payload)
            ->assertStatus(200)
            ->assertJson(['name' => $payload['name']])
            ->assertJsonMissing(['name' => $card->name]);
    }

    public function testUserCanDeleteCards()
    {
        $user = factory(User::class)->create();
        $board = factory(Board::class)->create(['user_id' => $user->id]);
        $boardList = factory(BoardList::class)->create(['board_id' => $board->id]);
        $card = factory(Card::class)->create(['board_list_id' => $boardList->id]);
        $card2 = factory(Card::class)->create(['board_list_id' => $boardList->id]);

        $this->actingAs($user)
            ->json('DELETE', 'api/cards/' . $card->id)
            ->assertStatus(204);

        // Check that it is really gone with a GET request of the board of the user
        $this->actingAs($user)
            ->json('GET', 'api/boards/' . $board->id)
            ->assertDontSee($card->name)
            ->assertSee($card2->name);
    }

}
