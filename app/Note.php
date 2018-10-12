<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['title', 'body', 'color'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
