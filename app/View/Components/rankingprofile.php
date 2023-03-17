<?php

namespace App\View\Components;

use Illuminate\View\Component;

class rankingprofile extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $rankingPlayer;

    public function __construct($rankingPlayer)
    {
        $this->rankingPlayer = $rankingPlayer;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.rankingprofile');
    }
}
