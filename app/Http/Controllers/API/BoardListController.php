<?php

namespace App\Http\Controllers\API;

use App\BoardList;
use App\Board;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BoardListController extends Controller
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
            'board_id' => 'required|integer',
        ]);

        $board = Board::findOrFail($request->board_id);

        if (Auth::id() !== $board->user->id) {
            abort(403, 'Unauthorized for this action.');
        }

        $boardList = new BoardList;
        $boardList->name = $request->name;
        $boardList->order = $request->order;
        $boardList->board()->associate($request->board_id);
        $boardList->save();

        return response()->json($boardList->load('cards'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BoardList  $boardList
     * @return \Illuminate\Http\Response
     */
    public function show(BoardList $boardList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BoardList  $boardList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BoardList $boardList)
    {
        if (Auth::id() !== $boardList->board->user->id) {
            abort(403, 'Unauthorized for this action.');
        }

        $request->validate([
            'name' => 'required',
        ]);

        $boardList->name = $request->name;
        $boardList->save();

        return response()->json($boardList);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BoardList  $boardList
     * @return \Illuminate\Http\Response
     */
    public function destroy(BoardList $boardList)
    {
        if (Auth::id() !== $boardList->board->user->id) {
            abort(403, 'Unauthorized for this action.');
        }

        $boardList->delete();
        return response()->json(null, 204);
    }
}
