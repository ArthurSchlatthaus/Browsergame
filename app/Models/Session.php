<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Session extends Model
{
    public $table = 'sessions';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('User');
    }

    public static function onlineUser()
    {
        $lastActivity = strtotime(Carbon::now()->subMinutes(30));
        return Session::where('last_activity', '>=', $lastActivity);
    }

    public function scopeUpdateCurrent(Builder $query)
    {
        $user = User::check();
        return $query->where('id', Session::getId())->update([
            'user_id' => $user ? $user->id : null
        ]);
    }
}
