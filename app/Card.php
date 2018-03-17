<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public function boardList()
    {
        return $this->belongsTo("App\BoardList");
    }
}
