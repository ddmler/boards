<?php

namespace App\Http\Controllers\API;

use App\BoardList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $boardList = new BoardList;
        $boardList->name = $request->name;
        $boardList->board()->associate($request->board);
        $boardList->save();

        return response()->json("OK");
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
        $boardList->name = $request->name;
        $boardList->save();

        return response()->json("OK");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BoardList  $boardList
     * @return \Illuminate\Http\Response
     */
    public function destroy(BoardList $boardList)
    {
        $boardList->delete();
        return response()->json("OK");
    }
}
