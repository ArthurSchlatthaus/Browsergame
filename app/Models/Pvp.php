<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pvp extends Model
{
    use HasFactory;
    protected $table = 'pvp';
    protected $fillable = ['attackerId','defenderId','attackerHp','defenderHp','attackerSp','defenderSp'];
}
