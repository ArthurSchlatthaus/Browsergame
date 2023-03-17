<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Changelog extends Model
{
    use HasFactory;
    public static function insertChangelog($text){
        if(isset($text)){
            $changelog = new Changelog();
            $changelog->text = $text;
            $changelog->created_at = Carbon::now();
            $changelog->save();
        }
    }
}
