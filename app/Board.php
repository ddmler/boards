<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $fillable = [
        'name'
    ];

    public function user()
    {
        return $this->belongsTo("App\User");
    }

    public function boardLists()
    {
        return $this->hasMany("App\BoardList");
    }
}
