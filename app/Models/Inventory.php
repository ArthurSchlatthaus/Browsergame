<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    public function item()
    {
        return $this->hasOne(Item::class, 'vnum', 'vnum');
    }

    public function getAllBonus()
    {
        $ret = [];
        if ($this->attrType0 > 0) {
            $ret[] = $this->attrType0 . ':' . $this->attrValue0;
        }
        if ($this->attrType1 > 0) {
            $ret[] = $this->attrType1 . ':' . $this->attrValue1;
        }
        if ($this->attrType2 > 0) {
            $ret[] = $this->attrType2 . ':' . $this->attrValue2;
        }
        if ($this->attrType3 > 0) {
            $ret[] = $this->attrType3 . ':' . $this->attrValue3;
        }
        if ($this->attrType4 > 0) {
            $ret[] = $this->attrType4 . ':' . $this->attrValue4;
        }
        return $ret;
    }
}
