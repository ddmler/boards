<?php

namespace App\Http\Controllers\API;

use App\Card;
use App\BoardList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'order' => 'required|integer',
            'list_id' => 'required|integer',
        ]);

        $boardList = BoardList::findOrFail($request->list_id);

        if (Auth::id() !== $boardList->board->user->id) {
            abort(403, 'Unauthorized for this action.');
        }

        $card = new Card;
        $card->name = $request->name;
        $card->order = $request->order;
        $card->description = "";
        $card->boardList()->associate($request->list_id);
        $card->save();

        return response()->json($card, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        return response()->json($card);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {
        if (Auth::id() !== $card->boardList->board->user->id) {
            abort(403, 'Unauthorized for this action.');
        }

        $card->name = $request->name ?: $card->name;
        $card->description = $request->description ?: $card->description;
        $card->save();

        return response()->json($card);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        if (Auth::id() !== $card->boardList->board->user->id) {
            abort(403, 'Unauthorized for this action.');
        }

        $card->delete();
        return response()->json(null, 204);
    }
}
