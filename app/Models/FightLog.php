<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FightLog extends Model
{
    use HasFactory;

    protected $table = 'fightlog';

    /*public function Player()
    {
        return $this->belongsTo(Player::class);
    }*/
}
