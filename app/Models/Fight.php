<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fight extends Model
{
    use HasFactory;
    protected $table = 'fight';
    protected $fillable = ['playerId'];
    public function Player()
    {
        return $this->belongsTo(Player::class);
    }
}
