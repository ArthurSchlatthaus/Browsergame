<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;

class logoutPlayer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logoutPlayer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'logoutPlayer';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $players = Player::where('isLoggedIn', 1)->whereRaw('from_unixtime(lastLoginTime) < (NOW()- INTERVAL 4 HOUR)')->get();
        foreach ($players as $player) {
            $player->isLoggedIn = 0;
            $player->save();
        }
        return true;
    }
}
