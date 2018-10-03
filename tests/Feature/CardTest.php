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
    protected $user;
    protected $board;
    protected $boardList;
    protected $card;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->board = factory(Board::class)->create(['user_id' => $this->user->id]);
        $this->boardList = factory(BoardList::class)->create(['board_id' => $this->board->id]);
        $this->card = factory(Card::class)->create(['board_list_id' => $this->boardList->id]);
    }

    public function testLoginRequired()
    {
        $payload = ['name' => 'My Card', 'order' => 1, 'list_id' => $this->boardList->id];

        $this->json('POST', 'api/cards', $payload)
            ->assertStatus(401);
    }

    public function testUserCanCreateCards()
    {
        $payload = ['name' => 'My Card', 'order' => 1, 'list_id' => $this->boardList->id];

        $this->actingAs($this->user)
            ->json('POST', 'api/cards', $payload)
            ->assertStatus(201)
            ->assertJson(['name' => $payload['name']]);
    }

    public function testCreateValidation()
    {
        $this->actingAs($this->user)
            ->json('POST', 'api/cards')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name field is required."],
                    "order" => ["The order field is required."],
                    "list_id" => ["The list id field is required."],
                ]
            ]);
    }

    public function testUserCanUpdateCards()
    {
        $payload = ['name' => 'My Card'];

        $this->actingAs($this->user)
            ->json('PATCH', 'api/cards/' . $this->card->id, $payload)
            ->assertStatus(200)
            ->assertJson(['name' => $payload['name']])
            ->assertJsonMissing(['name' => $this->card->name]);
    }

    public function testUserCanDeleteCards()
    {
        $card2 = factory(Card::class)->create(['board_list_id' => $this->boardList->id]);

        $this->actingAs($this->user)
            ->json('DELETE', 'api/cards/' . $this->card->id)
            ->assertStatus(204);

        // Check that it is really gone with a GET request of the board of the user
        $this->actingAs($this->user)
            ->json('GET', 'api/boards/' . $this->board->id)
            ->assertDontSee($this->card->name)
            ->assertSee($card2->name);
    }

    public function testAuthorization()
    {
        $user2 = factory(User::class)->create();
        $payload = ['name' => 'My Card', 'order' => 1, 'list_id' => $this->boardList->id];

        $this->actingAs($user2)
            ->json('POST', 'api/cards', $payload)
            ->assertStatus(403);

        $this->actingAs($user2)
            ->json('PATCH', 'api/cards/' . $this->card->id, $payload)
            ->assertStatus(403);

        $this->actingAs($user2)
            ->json('DELETE', 'api/cards/' . $this->card->id)
            ->assertStatus(403);
    }
}
