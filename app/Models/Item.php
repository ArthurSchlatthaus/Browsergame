<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function getSubtypeName()
    {
        $subtype = [
            0 => 'onehand',
            1 => 'twohand',
            2 => 'dagger',
            3 => 'bow',
            4 => 'fan',
            5 => 'bell'
        ];
        return $subtype[$this->subtype];
    }
}
