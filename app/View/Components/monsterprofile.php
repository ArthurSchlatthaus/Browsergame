<?php

namespace App\View\Components;

use App\Models\Monster;
use Illuminate\View\Component;
use PhpParser\Node\Expr\Array_;

class monsterprofile extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $monster;
    public $monsterHp;

    public function __construct($monster, $monsterHp)
    {
        $this->monster = $monster;
        $this->monsterHp = $monsterHp;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.monsterprofile');
    }
}
