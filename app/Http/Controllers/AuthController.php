<?php

namespace App\Http\Controllers;

use App\Events\SendError;
use App\Models\Equipment;
use App\Models\MissionType;
use App\Models\Monster;
use App\Models\Player;
use App\Models\Session;
use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = User::find(Auth::user()->id);
            if (isset($user)) {
                $user->lastLoginTime = Carbon::now()->timestamp;
                if ($user->playerId > 0) {
                    $player = Player::find($user->playerId);
                    if (isset($player)) {
                        $player->lastLoginTime = Carbon::now()->timestamp;
                        //$player->isLoggedIn = 1;
                        $player->save();
                    }
                }
                $user->save();
            }
            return redirect()->route('welcome');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if (isset($user)) {
            $user->lastLogoutTime = Carbon::now()->timestamp;
            if ($user->playerId > 0) {
                $player = Player::find($user->playerId);
                if (isset($player)) {
                    $player->lastLogoutTime = Carbon::now()->timestamp;
                    $player->playtime += abs(date($player->lastLogoutTime) - date($player->lastLoginTime));
                    $player->isLoggedIn = 0;
                    $player->save();
                }
            }
            $user->save();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function store()
    {
        try {
            $this->validate(request(), [
                'email' => 'required|confirmed|email|unique:users|min:4|max:20',
                'password' => 'required|confirmed'
            ]);
        } catch (\Exception $exeption) {
            return redirect()->back()->withErrors(['msg' => $exeption->getMessage()]);
        }

        $user = new User;
        $user->password = Hash::make(request()->password);
        $user->email = request()->email;
        $user->api_token = Str::random(60);
        $user->lastLoginTime = Carbon::now()->timestamp;
        $user->save();
        auth()->login($user);
        return redirect()->route('welcome');
    }

    public function storePlayer()
    {
        $user = User::find(Auth::id());
        try {
            $this->validate(request(), [
                'name' => 'required|unique:players,name|min:5|max:16',
            ]);
        } catch (\Exception $exeption) {
            return back()->withErrors(['You can`t use this name']);
        }
        $player = new Player;
        $player->name = request()->name;
        $player->race = request()->race;
        if (intval($player->race) === 1) {//Warrior
            $player->str = 6;
            $player->vit = 4;
            $player->int = 3;
            $player->dex = 3;
        } elseif (intval($player->race) === 2) {//Ninja
            $player->str = 4;
            $player->vit = 3;
            $player->int = 3;
            $player->dex = 6;
        } elseif (intval($player->race) === 3) {//Sura
            $player->str = 6;
            $player->vit = 3;
            $player->int = 5;
            $player->dex = 3;
        } elseif (intval($player->race) === 4) {//Shaman
            $player->str = 3;
            $player->vit = 4;
            $player->int = 6;
            $player->dex = 3;
        }
        $player->lastLoginTime = Carbon::now()->timestamp;
        $player->lastLogoutTime = Carbon::now()->subHour(1)->timestamp;
        try {
            $player->save();
        } catch (\Exception $exeption) {
            return back()->withErrors(['Player could not be created']);
        }
        $user->playerId = $player->id;
        $user->save();
        $this->startEQ($player);
        $equipment = Equipment::where('playerId', $user->playerId)->first();
        //$inventoryitems = \App\Http\Controllers\Controller::sendInventory($user);
        return [
            'player' => Player::find($user->playerId),
            //'inventory' => $inventoryitems,
            'equipment' => $equipment,
            'missiontypes' => MissionType::all(),
            'shop' => Shop::all(),
            'monsters' => Monster::all(),
        ];
    }
}
