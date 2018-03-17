<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    public function user()
    {
        return $this->belongsTo("App\User");
    }

    public function boardLists()
    {
        return $this->hasMany("App\BoardList");
    }
}
