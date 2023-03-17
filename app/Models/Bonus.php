<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use HasFactory;

    protected $table = 'bonus';
    public $timestamps = false;

    public static function getWeaponBonus()
    {
        return Bonus::where('weapon', 1)->orderby('prob', 'DESC')->get()->toArray();
    }

    public static function getBodyBonus()
    {
        return Bonus::where('body', 1)->orderby('prob', 'DESC')->get()->toArray();
    }
}
