<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoardList extends Model
{
    protected $table = "board_lists";

    public function board()
    {
        return $this->belongsTo("App\Board");
    }

    public function cards()
    {
        return $this->hasMany("App\Card");
    }
}
