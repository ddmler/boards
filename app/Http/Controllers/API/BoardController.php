<?php

namespace App\Http\Controllers\API;

use App\Board;
use App\Card;
use App\BoardList;
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
        $request->validate([
            'name' => 'required',
        ]);

        $board = new Board;
        $board->name = $request->name;
        $board->user()->associate(Auth::id());
        $board->save();

        return response()->json($board, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show(Board $board)
    {
        if (Auth::id() !== $board->user->id) {
            abort(403, 'Unauthorized for this action.');
        }

        $board->load(['boardLists' => function ($query) {
            $query->with(['cards' => function ($query) {
                $query->orderBy('order');
            }])->orderBy('order');
        }]);

        return response()->json($board);
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
        if (Auth::id() !== $board->user->id) {
            abort(403, 'Unauthorized for this action.');
        }

        $request->validate([
            'name' => 'required',
        ]);

        $board->name = $request->name;
        $board->save();

        return response()->json($board);
    }

    public function updateOrder(Request $request)
    {
        $board = Board::findOrFail($request->board["id"]);

        if (Auth::id() !== $board->user->id) {
            abort(403, 'Unauthorized for this action.');
        }

        foreach ($request->board["board_lists"] as $boardList) {
            foreach ($boardList["cards"] as $card) {
                $cardModel = Card::find($card["id"]);
                $cardModel->board_list_id = $boardList["id"];
                $cardModel->order = $card["order"];
                $cardModel->save();
            }
        }

        return response()->json("OK");
    }

    public function updateListOrder(Request $request)
    {
        $board = Board::findOrFail($request->board["id"]);

        if (Auth::id() !== $board->user->id) {
            abort(403, 'Unauthorized for this action.');
        }

        foreach ($request->board["board_lists"] as $boardList) {
            $listModel = BoardList::find($boardList["id"]);
            $listModel->order = $boardList["order"];
            $listModel->save();
        }

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
        if (Auth::id() !== $board->user->id) {
            abort(403, 'Unauthorized for this action.');
        }

        $board->delete();
        return response()->json(null, 204);
    }
}
