<?php

namespace App\Http\Controllers;

use App\Events\sendError;
use App\Events\sendInfo;
use App\Events\sendNotify;
use App\Events\sendSuccess;
use App\Models\Equipment;
use App\Models\Fight;
use App\Models\Group;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Messages;
use App\Models\Monster;
use App\Models\Player;
use App\Models\Pvp;
use App\Models\Skill;
use App\Models\User;
use App\Notifications\notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class FightController extends Controller
{
    public static function sendAttack(Request $request)
    {
        $user = auth()->user();
        $fight = $user->player->getActiveFight();
        $player = $user->player;
        if (isset($fight)) {
            $group = Group::find($fight->groupId);
        } else {
            return ['player' => $player->getReturnValues(), 'fight' => $fight, 'monsters' => null];
        }
        if (isset($player) and isset($group)) {
            if (!FightController::checkRequest($player)) {
                $player->requestcounter++;
                $player->save();
                //$time = 2 + (2 * ($player->requestcounter / 10));
                time_sleep_until(Carbon::now()->addSeconds(2 + $player->requestcounter)->timestamp);
                event(new sendError($user, 'Too fast'));
                return ['player' => $player->getReturnValues(), 'fight' => $fight, 'monsters' => null];
                //return Redirect::back()->withErrors(['msg' => 'Too fast']);
            }
            $playerHits = '';
            $monsterHits = '';
            if (isset($fight)) {
                $fight->rounds += 1;
                $hitNumPlayer = FightController::getHitNumPlayer($player, 5);
                $monster1 = Monster::find($group->monster1Id);
                $monster2 = Monster::find($group->monster2Id);
                $monster3 = Monster::find($group->monster3Id);
                $roundHits = max(5, $hitNumPlayer);
                $hitNumMonsterMax = 2;
                for ($x = 1; $x <= $roundHits; $x++) { // combo
                    if ($x <= $hitNumPlayer) {
                        $playerHit = FightController::getAttackPlayer($player, $fight, null);
                        $playerHits .= $playerHit . ',';
                        $fight->dmgAvg = ($fight->dmgAvg + $playerHit) / 2;
                        $fight->monster1Hp -= $playerHit;
                        if ($fight->monster1Hp <= 0) {
                            $fight->monster1Hp = 0;
                        }
                        $fight->monster2Hp -= $playerHit;
                        if ($fight->monster2Hp <= 0) {
                            $fight->monster2Hp = 0;
                        }
                        $fight->monster3Hp -= $playerHit;
                        if ($fight->monster3Hp <= 0) {
                            $fight->monster3Hp = 0;
                        }
                        if ($fight->monster1Hp <= 0 and $fight->monster2Hp <= 0 and $fight->monster3Hp <= 0) {
                            $fight->playerMonsterDmgArray = $playerHits . '//' . $monsterHits;
                            $player->save();
                            $fight->save();
                            $player = FightController::playerWon($user, $player, $playerHits, $monsterHits);
                            $monsters = ['monster1' => $monster1, 'monster2' => $monster2, 'monster3' => $monster3, 'monsterAnimation' => $player->getMonsterAnimation()];
                            return ['player' => $player->getReturnValues(), 'fight' => $fight, 'monsters' => $monsters, 'win' => true];
                        }
                    }
                    if ($x <= $hitNumMonsterMax) {
                        $playerDef = FightController::getDefensePlayer($player);
                        $monster1Hit = null;
                        $monster2Hit = null;
                        $monster3Hit = null;
                        if ($fight->monster1Hp > 0) {
                            $monster1Hit = FightController::getAttackMonster($monster1);
                        }
                        if ($fight->monster2Hp > 0) {
                            $monster2Hit = FightController::getAttackMonster($monster2);
                        }
                        if ($fight->monster3Hp > 0) {
                            $monster3Hit = FightController::getAttackMonster($monster3);
                        }
                        if ($monster1Hit != null) {
                            $monsterHits .= max(0, $monster1Hit - $playerDef) . ',';
                        }
                        if ($monster2Hit != null) {
                            $monsterHits .= max(0, $monster2Hit - $playerDef) . ',';
                        }
                        if ($monster3Hit != null) {
                            $monsterHits .= max(0, $monster3Hit - $playerDef) . ',';
                        }
                        if ($playerDef < ($monster1Hit ?? 0) and max(0, $monster1Hit - $playerDef) > 0) {
                            $player->hp -= max(0, $monster1Hit - $playerDef);
                        }
                        if ($playerDef < ($monster2Hit ?? 0) and max(0, $monster2Hit - $playerDef) > 0) {
                            $player->hp -= max(0, $monster2Hit - $playerDef);
                        }
                        if ($playerDef < ($monster3Hit ?? 0) and max(0, $monster3Hit - $playerDef) > 0) {
                            $player->hp -= max(0, $monster3Hit - $playerDef);
                        }
                        if ($player->hp > $player->maxHp) {
                            $player->hp = $player->maxHp;
                        }
                        if ($player->hp <= 0) {
                            $fight->playerMonsterDmgArray = $playerHits . '//' . $monsterHits;
                            $player->save();
                            $fight->save();
                            $player = FightController::playerDead($user, $player, $playerHits, $monsterHits);
                            $monsters = ['monster1' => $monster1, 'monster2' => $monster2, 'monster3' => $monster3, 'monsterAnimation' => $player->getMonsterAnimation()];
                            return ['player' => $player->getReturnValues(), 'fight' => $fight, 'monsters' => $monsters, 'win' => false];

                        }
                    }
                }
                $fight->playerMonsterDmgArray = $playerHits . '//' . $monsterHits;
                $player->save();
                $fight->save();
                time_sleep_until(Carbon::now()->addSeconds(3)->timestamp);
                $monsters = ['monster1' => $monster1, 'monster2' => $monster2, 'monster3' => $monster3, 'monsterAnimation' => $player->getMonsterAnimation()];
                return ['player' => $player->getReturnValues(), 'fight' => $fight, 'monsters' => $monsters];//redirect()->route('missions');
            }
        }
        return ['player' => $player->getReturnValues(), 'fight' => $fight, 'monsters' => null];
    }

    public static function getHitNumPlayer(Player $player, $hitNumPlayer)
    {
        if (Controller::getRace($player) === __('custom.warrior')) {
            if (isset($player->equipment)) {
                if (isset($player->equipment->weapon)) {
                    $inventoryItem = Inventory::find($player->equipment->weapon);
                    if (isset($inventoryItem)) {
                        $item = Item::where('vnum', $inventoryItem->vnum)->first();
                        if (isset($item)) {
                            if ($item->getSubtypeName() === "twohand") {
                                $hitNumPlayer--;
                            }
                        }
                    }
                }
            }
        }
        if (Controller::getRace($player) === __('custom.ninja')) {
            if (isset($player->equipment)) {
                if (isset($player->equipment->weapon)) {
                    $inventoryItem = Inventory::find($player->equipment->weapon);
                    if (isset($inventoryItem)) {
                        $item = Item::where('vnum', $inventoryItem->vnum)->first();
                        if (isset($item)) {
                            if ($item->getSubtypeName() === "bow") {
                                $hitNumPlayer = 1;
                            }
                            if ($item->getSubtypeName() === "dagger") {
                                $hitNumPlayer++;
                            }
                        }
                    }
                }
            }
            if (($player->dex / 2) >= rand(0, 100)) {
                $hitNumPlayer++;
            }
            $hitNumPlayer++;
        }
        return $hitNumPlayer;
    }

    public static function sendSkill(Request $request)
    {
        if (isset($request->skill_id)) {
            $user = auth()->user();
            if (isset($user)) {
                $player = $user->player;
                if (isset($player)) {
                    if (!FightController::checkRequest($player)) {
                        time_sleep_until(Carbon::now()->addSeconds(2)->timestamp);
                        event(new sendError($user, 'Too fast'));
                        return ['player' => $player->getReturnValues(), 'fight' => null, 'monsters' => null];
                    }
                    $fight = $user->player->getActiveFight();
                    if (isset($fight)) {
                        $fight->rounds += 1;
                        $group = Group::find($fight->groupId);
                        if (isset($group)) {
                            $monster1 = Monster::find($group->monster1Id);
                            $monster2 = Monster::find($group->monster2Id);
                            $monster3 = Monster::find($group->monster3Id);
                            $skill = Skill::find($request->skill_id);
                            if (isset($skill)) {
                                if ($player->skill0id == intval($request->skill_id)) {
                                    if ($player->sp < $skill->spcost * $player->skill0level) {
                                        event(new sendError($user, 'Not enough sp'));
                                        $monsters = ['monster1' => $monster1, 'monster2' => $monster2, 'monster3' => $monster3, 'monsterAnimation' => $player->getMonsterAnimation()];
                                        return ['player' => $player->getReturnValues(), 'fight' => $fight, 'monsters' => $monsters];
                                    }
                                    $player->sp -= $skill->spcost * $player->skill0level;
                                } else if ($player->skill1id === intval($request->skill_id)) {
                                    if ($player->sp < $skill->spcost * $player->skill1level) {
                                        event(new sendError($user, 'Not enough sp'));
                                        $monsters = ['monster1' => $monster1, 'monster2' => $monster2, 'monster3' => $monster3, 'monsterAnimation' => $player->getMonsterAnimation()];
                                        return ['player' => $player->getReturnValues(), 'fight' => $fight, 'monsters' => $monsters];
                                    }
                                    $player->sp -= $skill->spcost * $player->skill1level;
                                } else {
                                    event(new sendError($user, "You can't use this skill !"));
                                    $monsters = ['monster1' => $monster1, 'monster2' => $monster2, 'monster3' => $monster3, 'monsterAnimation' => $player->getMonsterAnimation()];
                                    return ['player' => $player->getReturnValues(), 'fight' => $fight, 'monsters' => $monsters];
                                }
                                $player->save(); // save the sp consume
                                if (Controller::getSkillType($skill) === 'buff') {
                                    $fight->buffId = $skill->id;
                                    $fight->buffDuration = $skill->duration;
                                    $fight->save();
                                    //\Session::flash('notify', 'Buff active for ' . $fight->buffDuration . ' hits');
                                    event(new sendNotify($user, 'Buff active for ' . $fight->buffDuration . ' hits'));
                                    $monsters = ['monster1' => $monster1, 'monster2' => $monster2, 'monster3' => $monster3, 'monsterAnimation' => $player->getMonsterAnimation()];
                                    return ['player' => $player->getReturnValues(), 'fight' => $fight, 'monsters' => $monsters];
                                }

                                $playerHit = 0;
                                if (Controller::getSkillType($skill) === 'hit') {
                                    $playerHit = FightController::getSkillPlayer($player, $request->skill_id);
                                }
                                $fight->monster1Hp -= $playerHit;
                                if ($fight->monster1Hp <= 0) {
                                    $fight->monster1Hp = 0;
                                }
                                $fight->monster2Hp -= $playerHit;
                                if ($fight->monster2Hp <= 0) {
                                    $fight->monster2Hp = 0;
                                }
                                $fight->monster3Hp -= $playerHit;
                                if ($fight->monster3Hp <= 0) {
                                    $fight->monster3Hp = 0;
                                }
                                $fight->dmgAvg = ($fight->dmgAvg + $playerHit) / 2;
                                $monsterHits = "";
                                for ($i = 1; $i < 3; $i++) {
                                    $monster1Hit = null;
                                    $monster2Hit = null;
                                    $monster3Hit = null;
                                    if ($fight->monster1Hp > 0) {
                                        $monster1Hit = FightController::getAttackMonster($monster1);
                                    }
                                    if ($fight->monster2Hp > 0) {
                                        $monster2Hit = FightController::getAttackMonster($monster2);
                                    }
                                    if ($fight->monster3Hp > 0) {
                                        $monster3Hit = FightController::getAttackMonster($monster3);
                                    }
                                    $playerDef = FightController::getDefensePlayer($player);
                                    if ($monster1Hit != null) {
                                        $monsterHits .= max(0, $monster1Hit - $playerDef) . ',';
                                    }
                                    if ($monster2Hit != null) {
                                        $monsterHits .= max(0, $monster2Hit - $playerDef) . ',';
                                    }
                                    if ($monster3Hit != null) {
                                        $monsterHits .= max(0, $monster3Hit - $playerDef) . ',';
                                    }
                                    if ($fight->monster1Hp <= 0 and $fight->monster2Hp <= 0 and $fight->monster3Hp <= 0) {
                                        $fight->playerMonsterDmgArray = $playerHit . '//' . $monsterHits;
                                        $player->save();
                                        $fight->save();
                                        $player = FightController::playerWon($user, $player, $playerHit, $monsterHits);
                                        $monsters = ['monster1' => $monster1, 'monster2' => $monster2, 'monster3' => $monster3, 'monsterAnimation' => $player->getMonsterAnimation()];
                                        return ['player' => $player->getReturnValues(), 'fight' => $fight, 'monsters' => $monsters, 'win' => true];

                                    }
                                    if ($playerDef < ($monster1Hit ?? 0) and max(0, $monster1Hit - $playerDef) > 0) {
                                        $player->hp -= max(0, $monster1Hit - $playerDef);
                                    }
                                    if ($playerDef < ($monster2Hit ?? 0) and max(0, $monster2Hit - $playerDef) > 0) {
                                        $player->hp -= max(0, $monster2Hit - $playerDef);
                                    }
                                    if ($playerDef < ($monster3Hit ?? 0) and max(0, $monster3Hit - $playerDef) > 0) {
                                        $player->hp -= max(0, $monster3Hit - $playerDef);
                                    }
                                    if ($player->hp > $player->maxHp) {
                                        $player->hp = $player->maxHp;
                                    }
                                    if ($player->hp <= 0) {
                                        $fight->playerMonsterDmgArray = $playerHit . '//' . $monsterHits;
                                        $player->save();
                                        $fight->save();
                                        $player = FightController::playerDead($user, $player, $playerHit, $monsterHits);
                                        $monsters = ['monster1' => $monster1, 'monster2' => $monster2, 'monster3' => $monster3, 'monsterAnimation' => $player->getMonsterAnimation()];
                                        return ['player' => $player->getReturnValues(), 'fight' => $fight, 'monsters' => $monsters, 'win' => false];

                                    }
                                }
                                $fight->playerMonsterDmgArray = $playerHit . '//' . $monsterHits;
                                if (Controller::getSkillType($skill) === 'hit') {
                                    if ($fight->buffDuration > 0) {
                                        $fight->buffDuration--;
                                    }
                                }
                                $player->save();
                                $fight->save();
                                time_sleep_until(Carbon::now()->addSeconds(2)->timestamp);
                                $monsters = ['monster1' => $monster1, 'monster2' => $monster2, 'monster3' => $monster3, 'monsterAnimation' => $player->getMonsterAnimation()];
                                return ['player' => $player->getReturnValues(), 'fight' => $fight, 'monsters' => $monsters];//return redirect()->back();
                            }
                        } else {
                            return ['player' => $player->getReturnValues(), 'fight' => $fight, 'monsters' => null];
                        }
                    }
                }
            }
        }
        return ['player' => null, 'fight' => null, 'monsters' => null];
    }

    public static function cancelPvp()
    {
        $user = auth()->user();
        if (isset($user)) {
            if (isset($user->player)) {
                $pvp = $user->player->getActivePvp(true);
                if (isset($pvp)) {
                    $pvp->isActive = 0;
                    $pvp->save();
                }
                $pvpNew = Controller::getPvpReturnValues();
                return ['player' => $user->player->getReturnValues(), "pvp" => $pvpNew];
            }
        }
    }

    public static function cancelFight()
    {
        $user = auth()->user();
        if (isset($user)) {
            if (isset($user->player)) {
                $fight = $user->player->getActiveFight();
                if (isset($fight)) {
                    $fight->canceled = 1;
                    $fight->isActive = 0;
                    $fight->gotReward = 1;
                    $fight->save();
                    event(new sendInfo($user, 'Fight canceled'));
                    return ['player' => $user->player->getReturnValues(), 'fight' => $fight];
                } else {
                    $lastFight = $user->player->getLastFight();
                    if (isset($lastFight)) {
                        $lastFight->gotReward = 1;
                        $lastFight->save();
                        event(new sendInfo($user, 'Fight closed'));
                        return ['player' => $user->player->getReturnValues(), 'fight' => $lastFight];
                    }

                }
                event(new senderror($user, 'no fight'));
                return ['player' => $user->player->getReturnValues(), 'fight' => null];
            }
            event(new senderror($user, 'no player'));
            return ['player' => null, 'fight' => null];
        }
        return ['player' => null, 'fight' => null];
    }


    public static function startFight(Request $request)
    {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
            if (isset($player)) {
                $group = Group::find($request->groupId);
                $fight = Fight::firstOrCreate(['playerId' => $player->id, 'canceled' => 0, 'isActive' => 1]);
                if (isset($fight) and isset($group)) {
                    $monster1 = Monster::find($group->monster1Id);
                    $monster2 = Monster::find($group->monster2Id);
                    $monster3 = Monster::find($group->monster3Id);
                    if ($fight->isActive == 0 and isset($monster1)) {
                        $fight->isActive = 1;
                        $fight->start_at = now();
                        $fight->monster1Id = $monster1->id;
                        $fight->monster2Id = $monster2->id;
                        $fight->monster3Id = $monster3->id;
                        $fight->playerId = $player->id;
                        $fight->monster1Hp = $monster1->hp;
                        $fight->monster2Hp = $monster2->hp;
                        $fight->monster3Hp = $monster3->hp;
                        $fight->isAutoFight = 0;//$request->autoFight != null ? $request->autoFight : 0;
                        $fight->groupId = $group->id;
                        $fight->save();
                        time_sleep_until(Carbon::now()->addSeconds()->timestamp);

                        $monsters = ['monster1' => \App\Models\Monster::find($fight->monster1Id), 'monster2' => \App\Models\Monster::find($fight->monster2Id), 'monster3' => \App\Models\Monster::find($fight->monster3Id), 'monsterAnimation' => $player->getMonsterAnimation()];
                        $monsters['monster1']->name = \App\Models\Monster::find($fight->monster1Id)->getMonsterName($fight->monster1Id);
                        $monsters['monster2']->name = \App\Models\Monster::find($fight->monster2Id)->getMonsterName($fight->monster2Id);
                        $monsters['monster3']->name = \App\Models\Monster::find($fight->monster3Id)->getMonsterName($fight->monster3Id);
                        return ['player' => $player->getReturnValues(), 'fight' => $fight, "monsters" => $monsters];
                    }
                    event(new sendInfo($user, 'Allready active Fight'));
                    return ['player' => $player->getReturnValues(), 'fight' => $fight];
                }
                event(new sendInfo($user, 'No Fight'));
                return ['player' => $player->getReturnValues(), 'fight' => null];
            }
            event(new sendInfo($user, 'No Player'));
            return ['player' => null, 'fight' => null];
        }
        event(new sendInfo($user, 'No User'));
        return ['player' => null, 'fight' => null];
    }

    public static function checkRequest(Player $player)
    {
        $timeInDB = $player->requesttimestamp;
        if ($timeInDB === null) {
            $player->requesttimestamp = Carbon::now()->timestamp;
            return true;
        }
        $diff = Carbon::now()->timestamp - $timeInDB;
        if ($diff > 1) {
            $player->requesttimestamp = Carbon::now()->timestamp;
            return true;
        }
        return false;
    }

    public static function getAttackPlayer(Player $player, $fight, $pvp, $isAttacker = null)
    {
        if (isset($player)) {
            $weaponDmg = 0;
            $equipment = Equipment::where('playerId', $player->id)->first();
            if (isset($equipment) and $equipment->weapon > 0) {
                $inventoryItem = Inventory::find($player->equipment->weapon);
                if (isset($inventoryItem)) {
                    $item = Item::where('vnum', $inventoryItem->vnum)->first();
                    if (Controller::getType($item) === 'weapon') {
                        $weaponDmg = rand($item->value0, $item->value1);
                    }
                }
            }
            $aw = $player->aw;
            $str = $player->str;
            //$fight = Fight::where(['playerId' => $player->id, 'canceled' => 0, 'isActive' => 1])->first();
            $buffBonus = 0;
            if (isset($fight) && $fight != null) {
                if ($fight->buffDuration > 0 and $fight->buffId != 3) {//buff is not SK #FixMe
                    $fight->buffDuration--;
                    $skill = Skill::find($fight->buffId);
                    if (isset($skill) and Controller::getSkillType($skill) === 'buff') {
                        if ($skill->id == $player->skill0id) {
                            $buffBonus = $skill->value0 * $player->skill0level;
                        } else if ($skill->id == $player->skill1id) {
                            $buffBonus = $skill->value0 * $player->skill1level;
                        }
                    }
                    if ($fight->buffDuration == 0) {
                        $fight->buffId = 0;
                    }
                    $fight->save();
                }
            }
            if (isset($pvp) && $pvp != null) {
                if ($isAttacker === 1) {
                    if ($pvp->attackerBuffDuration > 0 and $pvp->attackerBuffId != 3) {//buff is not SK #FixMe
                        $pvp->attackerBuffDuration--;
                        $skill = Skill::find($pvp->buffId);
                        if (isset($skill) and Controller::getSkillType($skill) === 'buff') {
                            if ($skill->id == $player->skill0id) {
                                $buffBonus = $skill->value0 * $player->skill0level;
                            } else if ($skill->id == $player->skill1id) {
                                $buffBonus = $skill->value0 * $player->skill1level;
                            }
                        }
                        if ($pvp->attackerBuffDuration == 0) {
                            $pvp->attackerBuffId = 0;
                        }
                        $pvp->save();
                    }
                } else if ($isAttacker === 0) {
                    if ($pvp->defenderBuffDuration > 0 and $pvp->defenderBuffId != 3) {//buff is not SK #FixMe
                        $pvp->defenderBuffDuration--;
                        $skill = Skill::find($pvp->buffId);
                        if (isset($skill) and Controller::getSkillType($skill) === 'buff') {
                            if ($skill->id == $player->skill0id) {
                                $buffBonus = $skill->value0 * $player->skill0level;
                            } else if ($skill->id == $player->skill1id) {
                                $buffBonus = $skill->value0 * $player->skill1level;
                            }
                        }
                        if ($pvp->defenderBuffDuration == 0) {
                            $pvp->defenderBuffId = 0;
                        }
                        $pvp->save();
                    }
                }
            }
            return (($aw + ($aw * (rand(0, 50) / 100))) * ($str * 0.15)) + $weaponDmg + $buffBonus;
        } else {
            return 0;
        }
    }

    public static function getSkillPlayer(Player $player, $skillId)
    {
        if (isset($player) and $skillId > 0) {
            $weaponDmg = 0;
            $equipment = Equipment::where('playerId', $player->id)->first();
            if (isset($equipment) and $equipment->weapon > 0) {
                $inventoryItem = Inventory::find($player->equipment->weapon);
                if (isset($inventoryItem)) {
                    $item = Item::where('vnum', $inventoryItem->vnum)->first();
                    if (Controller::getType($item) === 'weapon') {
                        $weaponDmg = rand($item->value0, $item->value1);
                    }
                }
            }
            $aw = $player->aw;
            $str = $player->str;
            $skill = Skill::find($skillId);
            $skillDmg = 0;
            $fight = Fight::where(['playerId' => $player->id, 'canceled' => 0, 'isActive' => 1])->first();
            $buffBonus = 0;
            if (isset($skill) and isset($fight)) {
                if ($fight->buffDuration > 0) {
                    $fight->buffDuration--;
                    if ($fight->buffDuration == 0) {
                        $fight->buffId = 0;
                    }
                    $buffSkill = Skill::find($fight->buffId);
                    if (isset($buffSkill) and Controller::getSkillType($buffSkill) === 'buff'
                        and $fight->buffId != 3) { //SK ist kein DMG Buff #FixMe
                        if ($player->skill0id == $fight->buffId) {
                            $buffBonus = $buffSkill->value0 * $player->skill0level;
                        } else if ($player->skill1id == $fight->buffId) {
                            $buffBonus = $buffSkill->value0 * $player->skill1level;
                        }
                    }
                    $fight->save();
                }
                if ($skillId == $player->skill0id) {
                    $skillDmg = $skill->value0 * $player->skill0level;//hit
                } else if ($skillId == $player->skill1id) {
                    $skillDmg = $skill->value0 * $player->skill1level;//hit
                }
            }
            return (($aw + ($aw + $skillDmg)) * ($str * 0.15)) + ($weaponDmg / 2) + $buffBonus;
        } else {
            return 0;
        }
    }

    public static function getPlayerExp($monsterExp, $monsterLevel, $playerLevel)
    {
        $expTable = [
            15 => 130,
            14 => 128,
            13 => 126,
            12 => 124,
            11 => 122,
            10 => 120,
            9 => 118,
            8 => 116,
            7 => 114,
            6 => 112,
            5 => 110,
            4 => 108,
            3 => 106,
            2 => 104,
            1 => 102,
            0 => 100,
            -1 => 100,
            -2 => 98,
            -3 => 96,
            -4 => 94,
            -5 => 92,
            -6 => 90,
            -7 => 85,
            -8 => 80,
            -9 => 70,
            -10 => 50,
            -11 => 30,
            -12 => 20,
            -13 => 10,
            -14 => 5,
            -15 => 1

        ];
        $diff = $monsterLevel - $playerLevel;
        if ($diff > 15) {
            $percentValue = $expTable[15];
        } elseif ($diff < -15) {
            $percentValue = $expTable[-15];
        } else {
            $percentValue = $expTable[$diff];
        }
        return $monsterExp * ($percentValue / 100);
    }

    public static function getDefensePlayer(Player $player)
    {
        if (isset($player)) {
            $defense = $player->def;
            $equipment = Equipment::where('playerId', $player->id)->first();
            if (isset($equipment) and $equipment->body > 0) {
                $inventoryItem = Inventory::find($equipment->body);
                if (isset($inventoryItem)) {
                    $item = Item::where('vnum', $inventoryItem->vnum)->first();
                    if (Controller::getType($item) === 'body') {
                        $defense += $item->value0;
                    }
                }
            }
            $fight = Fight::where(['playerId' => $player->id, 'canceled' => 0, 'isActive' => 1])->first();
            $defBonus = 0;
            if (isset($fight)) {
                $buffSkillId = $fight->buffId;
                $buffSkill = Skill::find($buffSkillId);
                if ($buffSkillId === 3 and $fight->buffDuration > 0) {//sk has Def Bonus #FixMe
                    $fight->buffDuration--;
                    if ($fight->buffDuration == 0) {
                        $fight->buffId = 0;
                    }
                    if ($buffSkillId == $player->skill0id) {
                        $defBonus = 10 * $buffSkill->value0 * $player->skill0level;//hit
                    } else if ($buffSkillId === $player->skill1id) {
                        $defBonus = 10 * $buffSkill->value0 * $player->skill1level;//hit
                    }
                }
                $fight->save();
            }
            return $defense + $defBonus;
        } else {
            return 0;
        }
    }

    public static function getAttackMonster(Monster $monster)
    {
        if (isset($monster)) {
            $aw = $monster->aw;
            $str = $monster->str;
            return (($aw + ($aw * (rand(0, 30) / 100))) * $str) * 2.5;
        } else {
            return 0;
        }
    }


    public static function playerWon(User $user, Player $player, $playerHits, $monsterHits)
    {
        $fight = Fight::where(['playerId' => $player->id, 'canceled' => 0, 'isActive' => 1])->first();
        $monster1 = Monster::find($fight->monster1Id);
        $monster2 = Monster::find($fight->monster2Id);
        $monster3 = Monster::find($fight->monster3Id);
        $monster1Drops = (explode(',', $monster1->getDropItem()));
        $monster2Drops = (explode(',', $monster2->getDropItem()));
        $monster3Drops = (explode(',', $monster3->getDropItem()));
        $drops = [];
        if (count($monster1Drops) > 0 and 10 >= rand(0, 100)) {
            $monster1Drop = explode(':', $monster1Drops[array_rand($monster1Drops)]);
            $vnum = $monster1Drop[0];
            $count = $monster1Drop[1];
            $player->giveItem($vnum, $count, true);
            $drops[] = $monster1Drop;
        }
        if (count($monster2Drops) > 0 and 10 >= rand(0, 100)) {
            $monster2Drop = explode(':', $monster2Drops[array_rand($monster2Drops)]);
            $vnum = $monster2Drop[0];
            $count = $monster2Drop[1];
            $player->giveItem($vnum, $count, true);
            $drops[] = $monster2Drop;
        }
        if (count($monster3Drops) > 0 and 10 >= rand(0, 100)) {
            $monster3Drop = explode(':', $monster3Drops[array_rand($monster3Drops)]);
            $vnum = $monster3Drop[0];
            $count = $monster3Drop[1];
            $player->giveItem($vnum, $count, true);
            $drops[] = $monster3Drop;
        }
        if (count($drops) > 0) {
            $text = 'You got: ' . "<br>\n";
            foreach ($drops as $drop) {
                $text .= $drop[1] . ' ' . Controller::getItemName($drop[0]) . "<br>\n";
            }
            //\Session::flash('info', $text);
            event(new sendInfo($user, $text));
        } else {
            //\Session::flash('info', 'No Items dropped');
            event(new sendInfo($user, 'No Items dropped'));
        }
        $fight->monster1Hp = 0;
        $fight->monster2Hp = 0;
        $fight->monster3Hp = 0;
        $monsterLevelAvg = ($monster1->level + $monster2->level + $monster3->level) / 3;
        $monsterExpAvg = ($monster1->exp + $monster2->exp + $monster3->exp) / 3;
        if ($player->level > $monsterLevelAvg + 15) {
            //\Session::flash('notify', 'Your level is to high to get exp and yang');
            event(new sendNotify($user, 'Your level is to high to get exp and yang'));
        } else {
            $exp = FightController::getPlayerExp($monsterExpAvg, $monsterLevelAvg, $player->level);
            $gold = $exp * 2;
            $player->exp += $exp;
            $player->gold += $gold;
            //\Session::flash('notify', 'You got ' . round($exp) . ' exp and ' . round($gold) . ' Yang ');
            event(new sendNotify($user, 'You got ' . round($exp) . ' exp and ' . round($gold) . ' Yang '));
        }
        $player->save();
        $levelTmp = $player->level;
        $player = PlayerController::checkLevel($player);
        $levelUpMsg = "";
        if ($levelTmp < $player->level) {
            $levelUpMsg = 'Level up!!! You are now Level ' . $player->level;
        }
        $fight->isActive = 0;
        $fight->playerIsWinner = 1;
        $fight->playerMonsterDmgArray = $playerHits . '//' . $monsterHits;
        $fight->gotReward = 0;
        $player->requestcounter = 0;
        $player->save();
        $fight->save();
        //\Session::flash('success', 'You are WINNER !!!');
        event(new sendSuccess($user, "You are WINNER !!! \n " . $levelUpMsg));
        time_sleep_until(Carbon::now()->addSeconds(2)->timestamp);
        return $player;
    }

    public static function playerDead(User $user, Player $player, $playerHits, $monsterHits)
    {
        $fight = Fight::where(['playerId' => $player->id, 'canceled' => 0, 'isActive' => 1])->first();
        $player->hp = 50;
        $player->requestcounter = 0;
        $fight->isActive = 0;
        $fight->playerIsWinner = 0;
        $fight->playerMonsterDmgArray = $playerHits . '//' . $monsterHits;
        $fight->gotReward = 0;
        //\Session::flash('notify', 'Your HP set to 50');
        event(new sendNotify($user, 'Your HP set to 50'));
        if ($player->gold < 50) {
            $player->gold += 50;
            $player->save();
            $fight->save();
            //\Session::flash('info', 'You have lost =( Here are 50 Yang to buy potion');
            event(new sendInfo($user, 'You have lost =( Here are 50 Yang to buy potion'));
        } else {
            $player->save();
            $fight->save();
            //\Session::flash('info', 'You have lost =(');
            event(new sendInfo($user, 'You have lost =('));
        }
        time_sleep_until(Carbon::now()->addSeconds(2)->timestamp);
        return $player;
    }

    public function startPvp(Request $request)
    {
        $user = auth()->user();
        if (isset($user)) {
            $attacker = Player::find($user->playerId);
            $pvpNew = null;
            if (isset($attacker)) {
                $activePvp = $attacker->getActivePvp(true);
                if (isset($activePvp)) {
                    event(new sendError($user, 'You are already in active Pvp-Fight'));
                    return ['player' => $user->player->getReturnValues()];
                }
                if (!str_contains(url()->current(), '127.0.0.1') && !str_contains(url()->current(), 'dev.ludus2.de')) {
                    $lastPvp = $attacker->getLastPvp();
                    $waitTimePvp = 15;
                    if (isset($lastPvp)) {
                        $to = \Carbon\Carbon::parse(now());
                        $from = \Carbon\Carbon::parse($lastPvp->ended_at);
                        $diff = $to->diffInMinutes($from);
                        if ($diff < $waitTimePvp) {
                            event(new sendError($user, "You have to wait " . ($waitTimePvp - $diff) . " minutes"));
                            return ['player' => $user->player->getReturnValues()];
                        }
                    }
                }
                if (strcasecmp($attacker->name, $request->defenderName) == 0) {
                    event(new sendError($user, "You can't fight against yourself"));
                    return ['player' => $user->player->getReturnValues()];
                }
                $defender = Player::where('name', $request->defenderName)->first();
                if (!isset($defender)) {
                    return ['player' => $user->player->getReturnValues()];
                }
                if (!str_contains(url()->current(), '127.0.0.1') && !str_contains(url()->current(), 'dev.ludus2.de')) {
                    if ($attacker->level < 5 || $defender->level < 5 || $attacker->level + 5 <= $defender->level || $attacker->level - 5 >= $defender->level) {
                        return ['player' => $user->player->getReturnValues()];
                    }
                }

                $pvp = Pvp::firstOrCreate([
                    'attackerId' => $attacker->id,
                    'defenderId' => $defender->id,
                    'attackerHp' => $attacker->maxHp,
                    'defenderHp' => $defender->maxHp,
                    'attackerSp' => $attacker->maxSp,
                    'defenderSp' => $defender->maxSp,
                    'winner' => null,
                    'looser' => null
                ]);
                $pvp->isActive = 1;
                $pvp->save();

                $pvpNew = Controller::getPvpReturnValues();
            }
        }

        return ['player' => $user->player->getReturnValues(), "pvp" => $pvpNew];
    }

    public function sendPvpAttack()
    {
        $user = auth()->user();
        if (isset($user)) {
            $attacker = Player::find($user->playerId);
            if (!isset($attacker)) {
                return 'error';
            }
            $pvp = $attacker->getActivePvp(true);
            if (!isset($pvp)) {
                return 'error';
            }
            $defender = \App\Models\Player::find($pvp->defenderId);
            if (!isset($defender)) {
                return 'error';
            }
            if (!FightController::checkRequest($attacker)) {
                $attacker->requestcounter++;
                $attacker->save();
                time_sleep_until(Carbon::now()->addSeconds(2 + $attacker->requestcounter)->timestamp);
                event(new sendError($user, 'Too fast'));
                return 'error';
            }
            $pvp->rounds++;
            $hitNumAttacker = FightController::getHitNumPlayer($attacker, 5);
            $hitNumDefender = FightController::getHitNumPlayer($defender, 5);
            $roundHits = max($hitNumDefender, $hitNumAttacker);
            $attackerDmgArray = [];
            $defenderDmgArray = [];
            for ($x = 1; $x <= $roundHits; $x++) {
                if ($x <= $hitNumAttacker) {
                    $attackerHit = number_format(FightController::getAttackPlayer($attacker, null, $pvp, 1));
                    $attackerDmgArray[] = number_format($attackerHit);
                    $pvp->attackerDmg = implode("/ ", $attackerDmgArray);
                    $pvp->defenderHp -= $attackerHit;
                    if ($pvp->defenderHp <= 0) {
                        $pvp->defenderHp = 0;
                        $pvp->winner = $attacker->id;
                        $pvp->looser = $defender->id;
                        $pvp->ended_at = now();
                        $pvp->save();
                        FightController::sendMessage($defender, "Du wurdest von $attacker->name angegriffen! Du hast leider verloren. \n\nYou have been attacked by $attacker->name! You have lost unfortunately.");
                        time_sleep_until(Carbon::now()->addSeconds(3)->timestamp);
                        return ['pvp' => Controller::getPvpReturnValues(), 'end' => true];
                    }
                }
                if ($x <= $hitNumDefender) {
                    $defenderHit = number_format(FightController::getAttackPlayer($defender, null, $pvp, 1));
                    $defenderDmgArray[] = number_format($defenderHit);
                    $pvp->defenderDmg = implode("/ ", $defenderDmgArray);
                    $pvp->attackerHp -= $defenderHit;
                    if ($pvp->attackerHp <= 0) {
                        $pvp->attackerHp = 0;
                        $pvp->winner = $defender->id;
                        $pvp->looser = $attacker->id;
                        $pvp->ended_at = now();
                        $pvp->save();
                        FightController::sendMessage($defender, "Du wurdest von $attacker->name angegriffen! Du hast gewonnen! \n\nYou have been attacked by $attacker->name! You have won!");
                        time_sleep_until(Carbon::now()->addSeconds(3)->timestamp);
                        return ['pvp' => Controller::getPvpReturnValues(), 'end' => true];
                    }
                }
            }
            $pvp->save();
            time_sleep_until(Carbon::now()->addSeconds(3)->timestamp);
            return ['pvp' => Controller::getPvpReturnValues(), 'end' => false];
        }
        return false;
    }

    private function sendMessage(Player $player, $text)
    {
        $adminUser = User::where('email', 'admin@ludus2.de')->first();
        $adminPlayer = Player::find($adminUser->playerId);
        $message = new Messages();
        $message->message = $text;
        $message->senderId = $adminPlayer->id;
        $message->senderName = 'PvP-Info';
        $message->receiverId = $player->id;
        $message->receiverName = $player->name;
        $message->wasRead = 0;
        $message->created_at = \Illuminate\Support\Carbon::now();
        $message->save();
    }
}
