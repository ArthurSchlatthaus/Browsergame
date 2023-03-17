<?php

use App\Events\sendNotify;
use App\Http\Controllers\Controller;
use App\Models\Changelog;
use App\Models\Monster;
use App\Models\Player;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware('locale')->group(function () {

    Route::get('/', function () {
        return view('welcome', ['showProfile' => true]);
    })->name('welcome');

    Route::get('missions', function () {
        return view('welcome', ['showMissions' => true]);
    })->name('missions');

    Route::get('loading', function () {
        return view('welcome', ['showLoadingScreen' => true]);
    })->name('loading');


    /*ADMIN*/
    Route::post('saveChangelog', function (Request $request) {
        Changelog::insertChangelog($request->changelogText);
        return redirect()->back();
    })->name('saveChangelog');

    Route::post('editChangelog', function (Request $request) {
        $changelog = Changelog::find($request->changelog_id);
        if (isset($changelog)) {
            $changelog->text = $request->changelog_text;
            $changelog->save();
        }
        return redirect()->back();
    })->name('editChangelog');
    /*ADMIN*/

    Route::post('sendAttack', 'App\Http\Controllers\FightController@sendAttack')->name('sendAttack');
    Route::post('sendSkill', 'App\Http\Controllers\FightController@sendSkill')->name('sendSkill');
    Route::post('sendRead', 'App\Http\Controllers\MessageController@sendRead')->name('sendRead');
    Route::post('sendMessage', 'App\Http\Controllers\MessageController@sendMessage')->name('sendMessage');
    Route::post('reloadMessage', 'App\Http\Controllers\MessageController@reloadMessage')->name('reloadMessage');
    Route::post('sendDelete', 'App\Http\Controllers\MessageController@sendDelete')->name('sendDelete');
    Route::post('buyItem', 'App\Http\Controllers\Controller@buyItem')->name('buyItem');
    Route::post('equipItem', 'App\Http\Controllers\PlayerController@equipItem')->name('equipItem');
    Route::post('unEquipItem', 'App\Http\Controllers\PlayerController@unEquipItem')->name('unEquipItem');
    Route::post('sendHeal', 'App\Http\Controllers\PlayerController@sendHeal')->name('sendHeal');
    Route::post('sendFight', 'App\Http\Controllers\FightController@startFight')->name('sendFight');
    Route::post('cancelFight', 'App\Http\Controllers\FightController@cancelFight')->name('cancelFight');
    Route::post('cancelPvp', 'App\Http\Controllers\FightController@cancelPvp')->name('cancelPvp');
    Route::post('setStatus', 'App\Http\Controllers\PlayerController@setStatus')->name('setStatus');
    Route::post('setClass', 'App\Http\Controllers\PlayerController@setClass')->name('setClass');
    Route::post('setSkill', 'App\Http\Controllers\PlayerController@setSkill')->name('setSkill');
    Route::post('deletePlayer', 'App\Http\Controllers\PlayerController@deletePlayer')->name('deletePlayer');
    Route::post('registerPlayer', 'App\Http\Controllers\PlayerController@registerPlayer')->name('registerPlayer');
    Route::post('upgradeItem', 'App\Http\Controllers\PlayerController@upgradeItem')->name('upgradeItem');
    Route::post('sellItem', 'App\Http\Controllers\PlayerController@sellItem')->name('sellItem');
    Route::post('resetClass', 'App\Http\Controllers\PlayerController@resetClass')->name('resetClass');

    Route::get('player/{name}', 'App\Http\Controllers\PlayerController@getPlayerProfile');

    Route::post('ranking', 'App\Http\Controllers\PlayerController@getRankingData');

    Route::post('startPvp', 'App\Http\Controllers\FightController@startPvp')->name('startPvp');
    Route::post('sendPvpAttack', 'App\Http\Controllers\FightController@sendPvpAttack')->name('sendPvpAttack');

    Route::post('setHair', function (Request $request) {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
            if (isset($player)) {
                if ($request->hair > 0 && $request->hair < 5) {
                    $player->hair = $request->hair;
                    $player->save();
                    return ['player' => $player->getReturnValues()];
                }
            }
        }
        return ['player' => null];
    });

    Route::post('getData', function () {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
            if (isset($player)) {
                $fight = $player->getActiveFight() != null ? $player->getActiveFight() : $player->getInactiveFight();
                $monsters = null;
                if ($fight != null) {
                    $monster1 = Monster::find($fight->monster1Id);
                    $monster2 = Monster::find($fight->monster2Id);
                    $monster3 = Monster::find($fight->monster3Id);
                    $monsters = ['monster1' => $monster1, 'monster2' => $monster2, 'monster3' => $monster3, 'monsterAnimation' => $player->getMonsterAnimation()];
                    $monsters['monster1']->name = $monster1->getMonsterName($fight->monster1Id);
                    $monsters['monster2']->name = $monster2->getMonsterName($fight->monster2Id);
                    $monsters['monster3']->name = $monster3->getMonsterName($fight->monster3Id);
                }
                $pvp = Controller::getPvpReturnValues();
                $messages['sendMessages'] = $player->sendMessages;
                $messages['receivedMessages'] = $player->receivedMessages;
                return ['player' => $player->getReturnValues(), 'fight' => $fight, "monsters" => $monsters, "pvp" => $pvp, "messages" => $messages];
            }
        }
        return ['player' => null, 'fight' => null, "monsters" => null, "pvp" => null];
    });

    Route::get('getPvpAttack', function () {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
            if (isset($player)) {
                $pvp = auth()->user()->player->getActivePvp(true) ?? auth()->user()->player->getLastPvp();
                $defender = \App\Models\Player::find($pvp->defenderId);
                $defenderAnimation = \App\Http\Controllers\PlayerController::getPvpAttack($defender,false);
                $attacker = \App\Models\Player::find($pvp->attackerId);
                $attackerAnimation = \App\Http\Controllers\PlayerController::getPvpAttack($attacker,true);
                return ["defender" => $defenderAnimation, "attacker" => $attackerAnimation];
            }
        }
        return 'none';
    });

    Route::post('setDance', function (Request $request) {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
            if (isset($player)) {
                $player->winningDance = $request->dance;
                $player->save();
            }
        }
        return redirect()->route('welcome');
    });

    Route::post('loginPlayer', function () {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
            if (isset($player)) {
                $player->isLoggedIn = 1;
                $player->save();
            }
        }
        return redirect()->route('welcome');
    })->name('loginPlayer');

    Route::post('closeFight', function () {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
            if (isset($player)) {
                $lastFight = $player->getLastFight();
                if (isset($lastFight)) {
                    $lastFight->gotReward = 1;
                    $lastFight->save();
                    return ['player' => $player->GetReturnValues(), 'fight' => $lastFight];
                }
            }
        }
        return ['player' => null, 'fight' => null];
    })->name('closeFight');


    Route::post('login', 'App\Http\Controllers\AuthController@authenticate')->name('login');
    Route::post('register', 'App\Http\Controllers\AuthController@store')->name('register');

    Route::get('/lang/{locale}', function ($locale) {
        if (!in_array($locale, ['en', 'de', 'ro', 'tr'])) {
            abort(400);
        }
        App::setLocale($locale);
        Session::put('locale', $locale);
        return redirect()->back();
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', 'App\Http\Controllers\AuthController@logout')->name('logout');

    });


    Route::any('/{any}', function () {
        return redirect()->route('welcome');
    })->where('any', '.*');
});


