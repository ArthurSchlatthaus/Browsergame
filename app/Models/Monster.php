<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monster extends Model
{
    use HasFactory;

    public $timestamps = false;
    public array $monsterDrop = array(
        1 => '10:1,1000:1,2000:1,3000:1,11200:1,11400:1,27001:10',
        2 => '11:1,1001:1,2001:1,3001:1,11201:1,11401:1,27001:15',
        3 => '12:1,1002:1,2002:1,3002:1,11202:1,11402:1,27001:20',
        4 => '20:1,1003:1,2003:1,3003:1,11210:1,11410:1,27001:25',
        5 => '21:1,1004:1,2004:1,3004:1,11211:1,11411:1,27001:30',
        6 => '25:1,1005:1,2005:1,3005:1,11212:1,11412:1,27001:35');

    public function getMonsterName($monsterId)
    {
        return __('custom.monster_' . $monsterId);
    }

    public function getDropItem()
    {
        if ($this->monsterDrop[$this->id]) {
            return $this->monsterDrop[$this->id];
        }
    }
}
