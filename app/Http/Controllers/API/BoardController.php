<?php

namespace App\Http\Controllers\API;

use App\Board;
use App\Card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $boards = Board::with('boardLists.cards')->where('user_id', Auth::id())->get();
        return response()->json($boards);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $board = new Board;
        $board->name = $request->name;
        $board->user()->associate(Auth::id());
        $board->save();

        return response()->json($board);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show(Board $board)
    {
        return response()->json($board->load('boardLists.cards'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Board $board)
    {
        $board->name = $request->name;
        $board->save();

        return response()->json("OK");
    }

    public function updateOrder(Request $request)
    {

        $board = Board::findOrFail($request->board["id"]);

        foreach ($request->board["board_lists"] as $boardList) {
            foreach($boardList["cards"] as $card) {
                $cardModel = Card::find($card["id"]);
                $cardModel->board_list_id = $boardList["id"];
                $cardModel->order = $card["order"];
                $cardModel->save();
            }
        }

        // $board->boardLists()->sync(array_column($request->board["board_lists"], 'id'));

        // $board->save();


        return response()->json("OK");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy(Board $board)
    {
        $board->delete();
        return response()->json("OK");
    }
}
