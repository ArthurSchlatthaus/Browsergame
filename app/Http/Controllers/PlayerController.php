<?php

namespace App\Http\Controllers;

use App\Events\sendError;
use App\Events\sendInfo;
use App\Events\sendNotify;
use App\Events\sendSuccess;
use App\Models\Bonus;
use App\Models\Equipment;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Messages;
use App\Models\Player;
use App\Models\Skill;
use App\Models\User;
use App\View\Components\content;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Session;
use Nette\Utils\Image;
use phpDocumentor\Reflection\Types\Boolean;

class PlayerController
{
    public static function getPvpAttack(Player $player, $isAttacker)
    {
        $pvp = $player->getActivePvp($isAttacker);
        if (!isset($pvp)) {
            return ['animation' => 'none', 'path' => $player->getAnimationPath()];
        }
        if (!isset($player->equipment)) {
            $pvp->defenderSkill = 0;
            $pvp->save();
            if (str_contains($player->getWeaponPath(), 'bow')) {
                return ['animation' => 'A_Shoot_Once.fbx', 'path' => $player->getAnimationPath()];
            } else {
                return ['animation' => 'none', 'path' => $player->getAnimationPath()];
            }
        } else {
            $inventoryItem = Inventory::find($player->equipment->weapon);
            if (!isset($inventoryItem)) {
                $pvp->defenderSkill = 0;
                $pvp->save();
                if (str_contains($player->getWeaponPath(), 'bow')) {
                    return ['animation' => 'A_Shoot_Once.fbx', 'path' => $player->getAnimationPath()];
                } else {
                    return ['animation' => 'none', 'path' => $player->getAnimationPath()];
                }
            }
        }
        if (str_contains($player->getWeaponPath(), 'bow')) {
            return ['animation' => 'A_Shoot_Once.fbx', 'path' => $player->getAnimationPath()];
        } else {
            return ['animation' => 'none', 'path' => $player->getAnimationPath()];
        }
        if ($player->level < 5 || $player->class === 0) {
            $pvp->defenderSkill = 0;
            $pvp->save();
            if (str_contains($player->getWeaponPath(), 'bow')) {
                return 'A_Shoot_Once.fbx';
            } else {
                return 'none';
            }
        }
        $skill0avail = false;
        if ($player->skill0id > 0 && $player->skill0level > 0) {
            $skill0 = Skill::find($player->skill0id);
            if (isset($skill0)) {
                if ($player->sp >= $skill0->spcost) {
                    if ($skill0->type === 1 || ($skill0->type === 0 && $pvp->defenderBuffDuration === 0)) {
                        $skill0avail = true;
                    }
                }
            }
        }
        $skill1avail = false;
        if ($player->skill1id > 0 && $player->skill1level > 0) {
            $skill1 = Skill::find($player->skill1id);
            if (isset($skill1)) {
                if ($player->sp >= $skill1->spcost) {
                    if ($skill1->type === 1 || ($skill1->type === 0 && $pvp->defenderBuffDuration === 0)) {
                        $skill1avail = true;
                    }
                }
            }
        }
        if ($skill0avail && $skill1avail) {
            $rand = rand(0, 100);
            if ($rand > 50) {
                $pvp->defenderSkill = $player->skill0id;
                $pvp->save();
                return $player->getSkill($player->skill0id);
            } else {
                $pvp->defenderSkill = $player->skill1id;
                $pvp->save();
                return $player->getSkill($player->skill1id);
            }
        } elseif ($skill0avail) {
            $pvp->defenderSkill = $player->skill0id;
            $pvp->save();
            return $player->getSkill($player->skill0id);
        } elseif ($skill1avail) {
            $pvp->defenderSkill = $player->skill1id;
            $pvp->save();
            return $player->getSkill($player->skill1id);
        } else {
            $pvp->defenderSkill = 0;
            $pvp->save();
            if (str_contains($player->getWeaponPath(), 'bow')) {
                return 'A_Shoot_Once.fbx';
            } else {
                return 'none';
            }
        }
    }

    public static function giveItem(Player $player, Item $item, $count, $hasNewBonus = false, $oldBonusList = null)
    {
        if ($count < 1) {
            return false;
        }
        $inventoryItems = Inventory::where('playerId', $player->id)->get();
        $allPos = [];
        foreach ($inventoryItems as $inventoryItem) {
            if (isset($inventoryItem)) {
                if ($inventoryItem->vnum == $item->vnum and Controller::getType($item) == 'potion') {
                    $inventoryItem->count += $count;
                    $inventoryItem->size = $item->size;
                    $inventoryItem->type = $item->type;
                    $inventoryItem->save();
                    return true;
                }
                $tmpItem = Item::where('vnum', $inventoryItem->vnum)->first();
                if (isset($tmpItem)) {
                    if ($tmpItem->size == 1) {
                        $allPos[] = $inventoryItem->pos;
                    } elseif ($tmpItem->size == 2) {
                        $allPos[] = $inventoryItem->pos;
                        $allPos[] = $inventoryItem->pos + 5;
                    } elseif ($tmpItem->size == 3) {
                        $allPos[] = $inventoryItem->pos;
                        $allPos[] = $inventoryItem->pos + 5;
                        $allPos[] = $inventoryItem->pos + 10;
                    }
                }
            }
        }
        for ($i = 0; $i < Controller::getInventoryMaxCount(); $i++) {
            if (in_array($i, $allPos)) {
                continue;
            }
            $freePos = $i;
            if ($item->size >= 2 and $freePos + 5 >= Controller::getInventoryMaxCount() or $item->size == 3 and $freePos + 10 >= Controller::getInventoryMaxCount()) {
                return false;
            }
            if ($item->size == 1
                or ($item->size == 2 and !array_search($freePos + 5, $allPos))
                or ($item->size == 3 and !array_search($freePos + 5, $allPos) and !array_search($freePos + 10, $allPos))) {
                $inventory = new Inventory();
                $inventory->playerId = $player->id;
                $inventory->vnum = $item->vnum;
                $inventory->count = $count;
                $inventory->pos = $freePos;
                $inventory->size = $item->size;
                $inventory->type = $item->type;
                if ($oldBonusList != null) {
                    $inventory->attrType0 = $oldBonusList[0]['type0'];
                    $inventory->attrValue0 = $oldBonusList[0]['value0'];
                    $inventory->attrType1 = $oldBonusList[1]['type1'];
                    $inventory->attrValue1 = $oldBonusList[1]['value1'];
                    $inventory->attrType2 = $oldBonusList[2]['type2'];
                    $inventory->attrValue2 = $oldBonusList[2]['value2'];
                } elseif ($hasNewBonus) {
                    if (Controller::getType($item) == 'weapon') {
                        $bonuses = Bonus::getWeaponBonus();
                    } elseif (Controller::getType($item) == 'body') {
                        $bonuses = Bonus::getBodyBonus();
                    }
                    foreach ($bonuses as $bonus) {
                        $weights[] = $bonus['prob'];
                    }
                    $bonus = PlayerController::getRandomBonus($bonuses, $weights);
                    $oldBonus1 = 0;
                    $oldBonus2 = 0;
                    if (isset($bonus)) {
                        $inventory->attrType0 = $bonus['id'];
                        $inventory->attrValue0 = random_int($bonus['min'], $bonus['max']);
                        $oldBonus1 = $bonus['id'];
                    }
                    while ($bonus['id'] == $oldBonus1) {
                        $bonus = PlayerController::getRandomBonus($bonuses, $weights);
                    }
                    if (isset($bonus)) {
                        $inventory->attrType1 = $bonus['id'];
                        $inventory->attrValue1 = random_int($bonus['min'], $bonus['max']);
                        $oldBonus2 = $bonus['id'];
                    }
                    while ($bonus['id'] == $oldBonus1 or $bonus['id'] == $oldBonus2) {
                        $bonus = PlayerController::getRandomBonus($bonuses, $weights);
                    }
                    if (isset($bonus)) {
                        $inventory->attrType2 = $bonus['id'];
                        $inventory->attrValue2 = random_int($bonus['min'], $bonus['max']);
                    }
                }
                $inventory->save();
                return true;
            }
        }
        return false;
    }


    public static function getRandomBonus($bonuses, $weights)
    {
        $count = count($bonuses);
        $i = 0;
        $n = 0;
        $num = mt_rand(0, array_sum($weights));
        while ($i < $count) {
            $n += $weights[$i];
            if ($n >= $num) {
                break;
            }
            $i++;
        }
        return $bonuses[$i];
    }

    public static function upgradeItem(Request $request)
    {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
        } else {
            event(new sendError($user, 'No user'));
            return ['player' => null];
        }
        if (isset($player) and isset($request->item_id)) {
            $oldItem = Inventory::where('playerId', $player->id)->where('id', $request->item_id)->first();
            if (!isset($oldItem)) {
                event(new sendError($user, 'Too fast'));
                return ['player' => $player->getReturnValues()];
            }
            if (str_ends_with(strval($oldItem->vnum), '9')) {
                event(new sendError($user, 'This item has already max refine level'));
                return ['player' => $player->getReturnValues()];
            }
            $cost = (intval(substr($oldItem->vnum, -1)) + 1) * 1000;
            if ($player->gold < $cost) {
                event(new sendError($user, 'Not enough yang'));
                return ['player' => $player->getReturnValues()];
            }
            $oldBonusList = [];
            $oldBonusList[] = ['type0' => $oldItem->attrType0, 'value0' => $oldItem->attrValue0];
            $oldBonusList[] = ['type1' => $oldItem->attrType1, 'value1' => $oldItem->attrValue1];
            $oldBonusList[] = ['type2' => $oldItem->attrType2, 'value2' => $oldItem->attrValue2];
            if (!PlayerController::giveItem($player, Item::where('vnum', $oldItem->vnum + 1)->first(), $oldItem->count, false, $oldBonusList)) {
                event(new sendError($user, 'Not enough space in inventory'));
                return ['player' => $player->getReturnValues()];
            }
            $oldItem->delete();
            $player->gold -= $cost;
            $player->save();
            //\Session::flash('success', 'Refine success');
            event(new sendSuccess($user, 'Refine success'));
            return ['player' => $player->getReturnValues()];
        }
        event(new sendError($user, 'no player'));
        return ['player' => $player->getReturnValues()];
    }

    public function sellItem(Request $request)
    {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
        } else {
            event(new sendError($user, 'no user'));
            return ['player' => null];
        }
        if (isset($player) and isset($request->item_id)) {
            $oldItem = Inventory::where('playerId', $player->id)->where('id', $request->item_id)->first();
            if (!isset($oldItem)) {
                event(new sendError($user, 'Too fast'));
                return ['player' => $player->getReturnValues()];
            }
            $oldItem->delete();
            $player->gold += (intval(substr($oldItem->vnum, -1)) + 1) * 100;
            $player->save();
            //\Session::flash('success', 'Success');
            //event(new sendSuccess($user, 'Success'));
            //\Session::flash('notify', 'You got ' . ((intval(substr($oldItem->vnum, -1)) + 1) * 100) . ' Yang');
            event(new sendNotify($user, 'You got ' . ((intval(substr($oldItem->vnum, -1)) + 1) * 100) . ' Yang'));
            return ['player' => $player->getReturnValues()];
        }
        event(new sendError($user, 'no player'));
        return ['player' => null];
    }

    public function setSkill(Request $request)
    {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
        } else {
            event(new sendError($user, 'no user'));
            return ["player" => null, "skillId" => null, "skillLvl" => null, "free" => null];
            //return redirect()->back()->withErrors(['msg' => 'no user']);
        }
        if (isset($player)) {
            if ($player->freeSkillPoints <= 0) {
                event(new sendError($user, 'no free skill points'));
                return ["player" => $player->getReturnValues(), "skillId" => null, "skillLvl" => null, "free" => null];
            }
            $skill = $request->skill;
            if ($skill === "1" and $player->skill0level < 18) {
                if ($player->skill0id == 0) {
                    $player->skill0id = $player->getSkillId(1);
                }
                $player->skill0level++;
            } else if ($skill === "2" and $player->skill1level < 18) {
                if ($player->skill1id === 0) {
                    $player->skill1id = $player->getSkillId(2);
                }
                $player->skill1level++;
            } else if ($skill === "3" and $player->skill2level < 18) {
                if ($player->skill2id === 0) {
                    $player->skill2id = $player->getSkillId(3);
                }
                $player->skill2level++;
            } else if ($skill === "4" and $player->skill3level < 18) {
                if ($player->skill3id === 0) {
                    $player->skill3id = $player->getSkillId(4);
                }
                $player->skill3level++;
            } else {
                return ["player" => $player->getReturnValues(), "skillId" => null, "skillLvl" => null, "free" => null];
            }
            $player->freeSkillPoints--;
            $player->save();
            if ($skill === "1") {
                if ($player->skill0level < 17) {
                    return ["player" => $player->getReturnValues(), "skillId" => $skill, "skillLvl" => $player->skill0level, "free" => $player->freeSkillPoints];
                }
            } elseif ($skill === "2") {
                if ($player->skill1level < 17) {
                    return ["player" => $player->getReturnValues(), "skillId" => $skill, "skillLvl" => $player->skill1level, "free" => $player->freeSkillPoints];
                }
            }
            return ["player" => $player->getReturnValues(), "skillId" => null, "skillLvl" => null, "free" => null];
        }
        event(new sendError($user, 'no player'));
        return ["player" => $player->getReturnValues(), "skillId" => null, "skillLvl" => null, "free" => null];
        //return redirect()->back()->withErrors(['msg' => 'no player']);
    }

    public function setClass(Request $request)
    {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
        } else {
            event(new sendError($user, 'no user'));
            return ["player" => null, "skillId" => null, "skillLvl" => null, "free" => null];
        }
        if (isset($player)) {
            if ($player->level < 5) {
                return redirect()->back();
            }
            $classId = $request->classId;
            if ($classId === "1") {
                $player->class = 1;
            } else if ($classId === "2") {
                $player->class = 2;
            } /*else if ($class === 3) {
                $player->class = 3;
            } else if ($class === 4) {
                $player->class = 4;
            }*/
            $player->save();
            return ["player" => $player->getReturnValues()];
        }
        event(new sendError($user, 'no player'));
        return ["player" => $player->getReturnValues()];
    }

    public static function setStatus(Request $request)
    {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
            if ($request->statusId > 0 and $request->statusId < 5) {
                if ($player->freeStatusPoints <= 0) {
                    return ["player" => $player->getReturnValues()];
                }
                if ($request->statusId === "1") {
                    $player->vit++;
                    $player->maxHp += 40;
                    $player->hp += 40;
                    if ($player->hp > $player->maxHp) {
                        $player->hp = $player->maxHp;
                    }
                } else if ($request->statusId === "2") {
                    $player->int++;
                    $player->maxSp += 20;
                    $player->sp += 20;
                    if ($player->sp > $player->maxSp) {
                        $player->sp = $player->maxSp;
                    }
                } else if ($request->statusId === "3") {
                    $player->str++;
                } else if ($request->statusId === "4") {
                    $player->dex++;
                }
                $player->freeStatusPoints--;
                $player->save();
            }
            return ["player" => $player->getReturnValues()];
        }
        return ["player" => null];
    }

    public function autoHealPlayer()
    {
        $players = Player::whereRaw('hp < maxHp')->get();
        foreach ($players as $player) {
            if ($player->hp < $player->maxHp) {
                $player->hp += $player->hpRegeneration;
                $player->save();
            }
        }
        $players = Player::whereRaw('hp > maxHp')->get();
        foreach ($players as $player) {
            $player->hp = $player->maxHp;
            $player->save();
        }

        $players = Player::whereRaw('sp < maxSp')->get();
        foreach ($players as $player) {
            if ($player->sp < $player->maxSp) {
                $player->sp += $player->spRegeneration;
                $player->save();
            }
        }
        $players = Player::whereRaw('sp > maxSp')->get();
        foreach ($players as $player) {
            $player->sp = $player->maxSp;
            $player->save();
        }
    }

    public static function deletePlayer(Request $request)
    {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
        } else {
            return redirect()->back()->withErrors(['msg' => 'No user']);
        }
        if (isset($player) and $player->id == $request->player_id) {
            $player->delete();
        }
        return redirect()->back();
    }

    public static function canUseSkill($skillNum)
    {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
            if (isset($player)) {
                if ($skillNum == 1) {
                    $skill_1 = \App\Models\Skill::find($player->getSkillId(1));
                    $fight = $player->getActiveFight() != null ? $player->getActiveFight() : $player->getInactiveFight();
                    if (isset($fight) && isset($skill_1) and $player->canUseSkillWithWeapon($player->getSkillId(1))
                        and ($fight->monster1Hp > 0 or $fight->monster2Hp > 0 or $fight->monster3Hp > 0)) {
                        if ($skill_1->id == $player->skill0id and $player->skill0level > 0) {
                            if (($skill_1->type === 0 and $fight->buffDuration === 0 and $fight->buffId != $skill_1->id and $player->sp >= ($skill_1->spcost * $player->skill0level))
                                or ($skill_1->type === 1 and $player->sp >= ($skill_1->spcost * $player->skill0level))) {
                                return 'yes';
                            }
                        } elseif ($skill_1->id == $player->skill1id and $player->skill1level > 0) {
                            if (($skill_1->type === 0 and $fight->buffDuration === 0 and $fight->buffId != $skill_1->id and $player->sp >= ($skill_1->spcost * $player->skill1level))
                                or ($skill_1->type === 1 and $player->sp >= ($skill_1->spcost * $player->skill1level))) {
                                return 'yes';
                            }
                        } else {
                            return 'no';
                        }
                    }
                } elseif ($skillNum == 2) {
                    $skill_2 = \App\Models\Skill::find($player->getSkillId(2));
                    $fight = $player->getActiveFight() != null ? $player->getActiveFight() : $player->getInactiveFight();
                    if (isset($fight) && isset($skill_2) and $player->canUseSkillWithWeapon($player->getSkillId(1))
                        and ($fight->monster1Hp > 0 or $fight->monster2Hp > 0 or $fight->monster3Hp > 0)) {
                        if ($skill_2->id == $player->skill0id and $player->skill0level > 0) {
                            if (($skill_2->type === 0 and $fight->buffDuration === 0 and $fight->buffId != $skill_2->id and $player->sp >= ($skill_2->spcost * $player->skill0level))
                                or ($skill_2->type === 1 and $player->sp >= ($skill_2->spcost * $player->skill0level))) {
                                return 'yes';
                            }
                        } elseif ($skill_2->id == $player->skill1id and $player->skill1level > 0) {
                            if (($skill_2->type === 0 and $fight->buffDuration === 0 and $fight->buffId != $skill_2->id and $player->sp >= ($skill_2->spcost * $player->skill1level))
                                or ($skill_2->type === 1 and $player->sp >= ($skill_2->spcost * $player->skill1level))) {
                                return 'yes';
                            }
                        } else {
                            return 'no';
                        }
                    }
                }
            }
        }
        return 'no';
    }

    public static function weapon_tooltip($vnum, Inventory $inventoryItem = null)
    {
        $item = Item::where('vnum', $vnum)->first();
        if (isset($item)) {
            $name = __('items.item_id_' . substr_replace($vnum, '', -1)) . substr($vnum, -1);
            $nameColor = '#ffc700';
            $races = [];
            if ($item->type === 0 and ($item->subtype === 0 or $item->subtype === 1)) {
                $races[] = __('custom.warrior');
            }
            if ($item->type === 0 and ($item->subtype === 0 or $item->subtype === 2 or $item->subtype === 3)) {
                $races[] = __('custom.ninja');
            }
            $races = implode(', ', $races);
            if (!str_contains($races, Controller::getRace(auth()->user()->player)) && !str_contains($races, ucfirst(Controller::getRace(auth()->user()->player)))) {
                $nameColor = '#f20707';
            }
            $bonus1 = null;
            $bonus2 = null;
            $bonus3 = null;
            $bonus4 = null;
            $bonus5 = null;
            if (isset($inventoryItem)) {
                if ($inventoryItem->attrType0 > 0) {
                    $bonus1 .= __('custom.bonus_' . $inventoryItem->attrType0) . ' ' . $inventoryItem->attrValue0;
                }
                if ($inventoryItem->attrType1 > 0) {
                    $bonus2 .= __('custom.bonus_' . $inventoryItem->attrType1) . ' ' . $inventoryItem->attrValue1;
                }
                if ($inventoryItem->attrType2 > 0) {
                    $bonus3 .= __('custom.bonus_' . $inventoryItem->attrType2) . ' ' . $inventoryItem->attrValue2;
                }
                if ($inventoryItem->attrType3 > 0) {
                    $bonus4 .= __('custom.bonus_' . $inventoryItem->attrType3) . ' ' . $inventoryItem->attrValue3;
                }
                if ($inventoryItem->attrType4 > 0) {
                    $bonus5 .= __('custom.bonus_' . $inventoryItem->attrType4) . ' ' . $inventoryItem->attrValue4;
                }
            }
            return [
                'name' => [$name, $nameColor],
                'level' => [__('custom.level') . ' ' . $item->level, auth()->user()->player->level < $item->level ? '#f20707' : '#c1c1c1'],
                'damage' => [__('custom.damage') . ' ' . $item->value0 . '-' . $item->value1, '#89b88d'],
                'bonus1' => [$bonus1, '#b0dfb4'],
                'bonus2' => [$bonus2, '#b0dfb4'],
                'bonus3' => [$bonus3, '#b0dfb4'],
                'bonus4' => [$bonus4, '#b0dfb4'],
                'bonus5' => [$bonus5, '#b0dfb4'],
                'races' => [$races, '#c1c1c1']
            ];
        } else {
            return '-';
        }
    }

    public static function sendHeal(Request $request)
    {
        $user = auth()->user();
        $potionId = $request->potion_id;
        if (isset($user)) {
            $player = Player::find($user->playerId);
            if (isset($player)) {
                if (!FightController::checkRequest($player)) {
                    $player->save();
                    time_sleep_until(Carbon::now()->addSeconds(2)->timestamp);
                    event(new sendError($user, "You have to wait"));
                    return ["player" => $player->getReturnValues()];
                }
                $fight = $player->getActiveFight() != null ? $player->getActiveFight() : $player->getInactiveFight();
                $inventoryItem = Inventory::where('vnum', $potionId)->where('playerId', $player->id)->first();
                if ($potionId > 0 and isset($inventoryItem)) {
                    $potion = Item::where('vnum', $potionId)->first();
                    if ($inventoryItem->count <= 0) {
                        $inventoryItem->delete();
                        return ["player" => $player->getReturnValues(), "fight" => $fight];
                    }
                    if (Controller::getType($potion) === 'potion' and $potion->subtype === 0) {
                        while ($player->hp < ($player->maxHp * 0.9)) {
                            $player->hp += 100;
                            if ($player->hp > $player->maxHp) {
                                $player->hp = $player->maxHp;
                            }
                            if ($inventoryItem->count <= 0) {
                                $inventoryItem->delete();
                                return ["player" => $player->getReturnValues(), "fight" => $fight];
                            }
                            $inventoryItem->count -= 1;
                            $inventoryItem->save();
                            $player->save();
                        }
                    } else if (Controller::getType($potion) == 'potion' and $potion->subtype === 1) {
                        while ($player->sp < ($player->maxSp * 0.9)) {
                            $player->sp += 100;
                            if ($player->sp > $player->maxSp) {
                                $player->sp = $player->maxSp;
                            }
                            if ($inventoryItem->count <= 0) {
                                $inventoryItem->delete();
                                return ["player" => $player->getReturnValues(), "fight" => $fight];
                            }
                            $inventoryItem->count -= 1;
                            $inventoryItem->save();
                            $player->save();
                        }
                    }
                } else {
                    event(new sendError($user, "You dont have this Item"));
                    return ["player" => $player->getReturnValues(), "fight" => $fight];
                }
                time_sleep_until(Carbon::now()->addSeconds(1)->timestamp);
            }
        }
        return ['player' => $player->getReturnValues(), 'usedItem' => [$inventoryItem->id, $inventoryItem->count], "fight" => $fight];
    }

    public static function body_tooltip($vnum, Inventory $inventoryItem = null)
    {
        $item = Item::where('vnum', $vnum)->first();
        if (isset($item)) {
            $name = __('items.item_id_' . substr_replace($vnum, '', -1)) . substr($vnum, -1);
            $races = [];
            $nameColor = '#ffc700';
            if ($item->type === 1 and $item->subtype === 0) {
                $races[] = __('custom.warrior');
            }
            if ($item->type === 1 and $item->subtype === 1) {
                $races[] = __('custom.ninja');
            }
            $races = implode(', ', $races);
            if (!str_contains($races, Controller::getRace(auth()->user()->player)) && !str_contains($races, ucfirst(Controller::getRace(auth()->user()->player)))) {
                $nameColor = '#f20707';
            }
            $bonus1 = null;
            $bonus2 = null;
            $bonus3 = null;
            $bonus4 = null;
            $bonus5 = null;
            if (isset($inventoryItem)) {
                if ($inventoryItem->attrType0 > 0) {
                    $bonus1 .= __('custom.bonus_' . $inventoryItem->attrType0) . ' ' . $inventoryItem->attrValue0;
                }
                if ($inventoryItem->attrType1 > 0) {
                    $bonus2 .= __('custom.bonus_' . $inventoryItem->attrType1) . ' ' . $inventoryItem->attrValue1;
                }
                if ($inventoryItem->attrType2 > 0) {
                    $bonus3 .= __('custom.bonus_' . $inventoryItem->attrType2) . ' ' . $inventoryItem->attrValue2;
                }
                if ($inventoryItem->attrType3 > 0) {
                    $bonus4 .= __('custom.bonus_' . $inventoryItem->attrType3) . ' ' . $inventoryItem->attrValue3;
                }
                if ($inventoryItem->attrType4 > 0) {
                    $bonus5 .= __('custom.bonus_' . $inventoryItem->attrType4) . ' ' . $inventoryItem->attrValue4;
                }
            }
            return [
                'name' => [$name, $nameColor],
                'level' => [__('custom.level') . ' ' . $item->level, auth()->user()->player->level < $item->level ? '#f20707' : '#c1c1c1'],
                'defense' => [__('custom.defense') . ' ' . $item->value0, '#89b88d'],
                'bonus1' => [$bonus1, '#b0dfb4'],
                'bonus2' => [$bonus2, '#b0dfb4'],
                'bonus3' => [$bonus3, '#b0dfb4'],
                'bonus4' => [$bonus4, '#b0dfb4'],
                'bonus5' => [$bonus5, '#b0dfb4'],
                'races' => [$races, '#c1c1c1']
            ];
        } else {
            return '-';
        }
    }

    public static function checkLevel(Player $player)
    {
        if ($player->exp >= ($player->level * 200)) {
            $player->level++;
            $player->freeStatusPoints++;
            $player->freeSkillPoints++;
            $player->exp = 0;
            $player->maxHp += $player->maxHp * 0.1;
            $player->hp = $player->maxHp;
            $player->maxSp += $player->maxSp * 0.1;
            $player->sp = $player->maxSp;
            $player->save();
        }
        return $player;
    }

    public function equipItem(Request $request)
    {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
        } else {
            event(new sendInfo($user, 'No User'));
            return ["player" => null];
        }
        if (isset($player) and isset($request->inventoryId)) {
            $inventoryId = $request->inventoryId;
            $inventory = Inventory::find($inventoryId);
            if (isset($inventory)) {
                $item = Item::where('vnum', $inventory->vnum)->first();
            } else {
                event(new sendInfo($user, 'No Item'));
                return ["player" => $player->getReturnValues()];
            }
            if (isset($item)) {
                $equipment = Equipment::where('playerId', $player->id)->first();
                $type = Controller::getType($item);
                if (!isset($equipment)) {
                    $equipment = new Equipment();
                    $equipment->playerId = $player->id;
                }
                if ($type != 'potion') {
                    if ($player->getActiveFight() != null) {
                        event(new sendError($user, "You can't change Equipment while fight is active"));
                        return ["player" => $player->getReturnValues(), "change" => 0];
                    }
                    if ($player->getActivePvp(true) != null) {
                        event(new sendError($user, "You can't change Equipment while PvP is active"));
                        return ["player" => $player->getReturnValues(), "change" => 0];
                    }
                }
                if ($type === 'weapon') {
                    if (!isset($equipment->weapon) or $equipment->weapon == 0) {
                        if ($player->canWearItem($item)) {
                            $equipment->weapon = $inventory->id;
                            $inventory->isEquipped = 1;
                            $inventory->pos = -1;
                        } else {
                            event(new sendError($user, "You can't wear this"));
                            return ["player" => $player->getReturnValues()];
                        }

                    } else if ($equipment->weapon != $inventory->id) {//swap
                        if ($player->canWearItem($item)) {
                            $oldEQ = Inventory::find($equipment->weapon);
                            if (isset($oldEQ)) {
                                $oldEQ->isEquipped = 0;
                                $oldEQitem = Item::where('vnum', $oldEQ->vnum)->first();
                                $freePos = PlayerController::getFreeInvPos($player, $oldEQitem->size);
                                if (is_nan($freePos)) {
                                    event(new sendError($user, "Not enough space in inventory"));
                                    return ["player" => $player->getReturnValues()];
                                }
                                $oldEQ->pos = $freePos;
                                $oldEQ->save();
                                $player = PlayerController::removePlayerBonus($player, $oldEQ);
                            }
                            $equipment->weapon = $inventory->id;
                            $inventory->isEquipped = 1;
                            $inventory->pos = -1;
                        } else {
                            event(new sendError($user, "You can't wear this"));
                            return ["player" => $player->getReturnValues()];
                        }
                    } else {
                        event(new sendError($user, "You can't do that"));
                        return ["player" => $player->getReturnValues()];
                    }
                } elseif ($type === 'body') {
                    if (!isset($equipment->body) or $equipment->body === 0) {
                        if ($player->canWearItem($item)) {
                            $equipment->body = $inventory->id;
                            $inventory->isEquipped = 1;
                            $inventory->pos = -1;
                        } else {
                            event(new sendError($user, "You can't wear this"));
                            return ["player" => $player->getReturnValues()];
                        }
                    } else if ($equipment->body != $inventory->id) {//Swap
                        if ($player->canWearItem($item)) {
                            $oldEQ = Inventory::find($equipment->body);
                            if (isset($oldEQ)) {
                                $oldEQ->isEquipped = 0;
                                $oldEQitem = Item::where('vnum', $oldEQ->vnum)->first();
                                $freePos = PlayerController::getFreeInvPos($player, $oldEQitem->size);
                                if (is_nan($freePos)) {
                                    event(new sendError($user, "Not enough space in inventory"));
                                    return ["player" => $player->getReturnValues()];
                                }
                                $oldEQ->pos = $freePos;
                                $oldEQ->save();
                                $player = PlayerController::removePlayerBonus($player, $oldEQ);
                            }
                            //!is_null($equipment->body) ? $equipment->body : (!is_null($item->vnum) ? $item->vnum : 0);
                            $equipment->body = $inventory->id;
                            $inventory->isEquipped = 1;
                            $inventory->pos = -1;
                        } else {
                            event(new sendError($user, "You can't wear this"));
                            return ["player" => $player->getReturnValues()];
                        }
                    } else {
                        event(new sendError($user, "You can't do that"));
                        return ["player" => $player->getReturnValues()];
                    }
                } elseif ($type === 'potion') {
                    $request->potion_id = $item->vnum;
                    PlayerController::usePotion($request);
                    $inventoryTmp = Inventory::find($inventoryId);
                    return ['player' => $player->getReturnValues(), 'usedItem' => [$inventoryId, $inventoryTmp->count]];
                }
                $inventory->save();
                $equipment->save();
                $player->save();
                $player = PlayerController::setPlayerBonus($player, $inventory);
                return ["player" => $player->getReturnValues(), "change" => 1];
            }
        }
        event(new sendError($user, "No Player"));
        return ["player" => null];
    }

    public static function setPlayerBonus(Player $player, Inventory $inventoryItem)
    {
        $equipment = Equipment::where('playerId', $player->id)->first();
        if (isset($equipment)) {
            $allBonus = [];
            if (isset($equipment->weapon)) {
                $weapon = Inventory::find($equipment->weapon);
                if (isset($weapon) and $weapon->id == $inventoryItem->id) {
                    $allBonus[] = $weapon->getAllBonus();
                }
            }
            if (isset($equipment->body)) {
                $body = Inventory::find($equipment->body);
                if (isset($body) and $body->id == $inventoryItem->id) {
                    $allBonus[] = $body->getAllBonus();
                }
            }
            foreach ($allBonus as $bonuses) {
                foreach ($bonuses as $bonus) {
                    $type = explode(':', $bonus)[0];
                    $value = explode(':', $bonus)[1];
                    if ($type == 1) {
                        $player->hp += intval($value);
                        $player->maxHp += intval($value);
                    } elseif ($type == 2) {
                        $player->sp += intval($value);
                        $player->maxSp += intval($value);
                    } elseif ($type == 3) {
                        $player->vit += intval($value);
                        $player->maxHp += intval($value) * 40;
                        $player->hp += intval($value) * 40;
                        if ($player->hp > $player->maxHp) {
                            $player->hp = $player->maxHp;
                        }
                    } elseif ($type == 4) {
                        $player->int += intval($value);
                        $player->sp += intval($value) * 20;
                        $player->maxSp += intval($value) * 20;
                    } elseif ($type == 5) {
                        $player->str += intval($value);
                    } elseif ($type == 6) {
                        $player->dex += intval($value);
                    } elseif ($type == 7) {
                        $player->hpRegeneration += intval($value);
                    } elseif ($type == 8) {
                        $player->spRegeneration += intval($value);
                    } elseif ($type == 9) {
                        $player->aw += intval($value);
                    } elseif ($type == 10) {
                        $player->def += intval($value);
                    }
                    $player->save();
                }
            }
        }
        return $player;
    }

    public static function removePlayerBonus(Player $player, Inventory $inventoryItem)
    {
        $equipment = Equipment::where('playerId', $player->id)->first();
        if (isset($equipment)) {
            $allBonus = [];
            if (isset($equipment->weapon)) {
                $weapon = Inventory::find($equipment->weapon);
                if (isset($weapon) and $weapon->id == $inventoryItem->id) {
                    $allBonus[] = $weapon->getAllBonus();
                }
            }
            if (isset($equipment->body)) {
                $body = Inventory::find($equipment->body);
                if (isset($body) and $body->id == $inventoryItem->id) {
                    $allBonus[] = $body->getAllBonus();
                }
            }
            foreach ($allBonus as $bonuses) {
                foreach ($bonuses as $bonus) {
                    $type = explode(':', $bonus)[0];
                    $value = explode(':', $bonus)[1];
                    if ($type == 1) {
                        $player->hp -= intval($value);
                        if ($player->hp < 0) {
                            $player->hp = 0;
                        }
                        $player->maxHp -= intval($value);
                        if ($player->maxHp < 0) {
                            $player->maxHp = 0;
                        }
                    } elseif ($type == 2) {
                        $player->sp -= intval($value);
                        if ($player->sp < 0) {
                            $player->sp = 0;
                        }
                        $player->maxSp -= intval($value);
                        if ($player->maxSp < 0) {
                            $player->maxSp = 0;
                        }
                    } elseif ($type == 3) {
                        $player->vit -= intval($value);
                        $player->maxHp -= intval($value) * 40;
                        $player->hp -= intval($value) * 40;
                        if ($player->hp > $player->maxHp) {
                            $player->hp = $player->maxHp;
                        }
                    } elseif ($type == 4) {
                        $player->int -= intval($value);
                        $player->sp -= intval($value) * 20;
                        $player->maxSp -= intval($value) * 20;
                    } elseif ($type == 5) {
                        $player->str -= intval($value);
                    } elseif ($type == 6) {
                        $player->dex -= intval($value);
                    } elseif ($type == 7) {
                        $player->hpRegeneration -= intval($value);
                    } elseif ($type == 8) {
                        $player->spRegeneration -= intval($value);
                    } elseif ($type == 9) {
                        $player->aw -= intval($value);
                    } elseif ($type == 10) {
                        $player->def -= intval($value);
                    }
                    $player->save();
                }
            }
        }
        return $player;
    }

    public static function usePotion(Request $request)
    {
        $user = auth()->user();
        $potionId = $request->potion_id;
        if (isset($user)) {
            $player = Player::find($user->playerId);
            if (isset($player)) {
                if (!FightController::checkRequest($player)) {
                    $player->save();
                    time_sleep_until(Carbon::now()->addSeconds(2)->timestamp);
                    event(new sendError($user, "You have to wait"));
                    return ["player" => $player->getReturnValues()];
                }
                $inventoryItem = Inventory::where('vnum', $potionId)->where('playerId', $player->id)->first();
                if ($potionId > 0 and isset($inventoryItem)) {
                    $potion = Item::where('vnum', $potionId)->first();
                    if ($inventoryItem->count <= 0) {
                        $inventoryItem->delete();
                        return ["player" => $player->getReturnValues()];
                    }
                    if (Controller::getType($potion) === 'potion' and $potion->subtype === 0) {
                        if ($player->hp < $player->maxHp) {
                            $player->hp += 100;
                            if ($player->hp > $player->maxHp) {
                                $player->hp = $player->maxHp;
                            }
                            if ($inventoryItem->count <= 0) {
                                $inventoryItem->delete();
                                return ["player" => $player->getReturnValues()];
                            }
                            $inventoryItem->count -= 1;
                            $inventoryItem->save();
                            $player->save();
                        }
                    } else if (Controller::getType($potion) == 'potion' and $potion->subtype === 1) {
                        if ($player->sp < $player->maxSp) {
                            $player->sp += 100;
                            if ($player->sp > $player->maxSp) {
                                $player->sp = $player->maxSp;
                            }
                            if ($inventoryItem->count <= 0) {
                                $inventoryItem->delete();
                                return ["player" => $player->getReturnValues()];
                            }
                            $inventoryItem->count -= 1;
                            $inventoryItem->save();
                            $player->save();
                        }
                    }
                } else {
                    event(new sendError($user, "You dont have this Item"));
                    return ["player" => $player->getReturnValues()];
                }
            }
        }
        return ['player' => $player->getReturnValues(), 'usedItem' => [$inventoryItem->id, $inventoryItem->count]];
    }

    public function unEquipItem(Request $request)
    {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
        } else {
            return ["player" => null];
        }
        if (isset($player) and isset($request->inventoryId)) {
            if ($player->getActiveFight() != null) {
                event(new sendError($user, "You can't change Equipment while fight is active"));
                return ["player" => $player->getReturnValues(), "change" => 0];
            }
            if ($player->getActivePvp(true) != null) {
                event(new sendError($user, "You can't change Equipment while PvP is active"));
                return ["player" => $player->getReturnValues(), "change" => 0];
            }
            $equipment = Equipment::where('playerId', $player->id)->first();
            $inventory = Inventory::find($request->inventoryId);
            if (isset($inventory) and isset($equipment)) {
                $item = Item::where('vnum', $inventory->vnum)->first();
                $type = Controller::getType($item);
                if ($type === 'weapon') {
                    if ($equipment->weapon == $inventory->id) {
                        $equipment->weapon = 0;
                        $inventory->isEquipped = 0;
                        $freePos = PlayerController::getFreeInvPos($player, $item->size);
                        if (is_nan($freePos)) {
                            event(new sendError($user, "Not enough space in inventory"));
                            return ["player" => $player->getReturnValues()];
                        }
                        $inventory->pos = $freePos;
                        PlayerController::removePlayerBonus($player, $inventory);
                        $inventory->save();
                        $equipment->save();
                        $player->save();
                    } else {
                        event(new sendError($user, "Not equipped"));
                        return ["player" => $player->getReturnValues()];
                    }
                } elseif ($type === 'body') {
                    if ($equipment->body == $inventory->id) {
                        $equipment->body = 0;
                        $inventory->isEquipped = 0;
                        $freePos = PlayerController::getFreeInvPos($player, $item->size);
                        if (is_nan($freePos)) {
                            event(new sendError($user, "Not enough space in inventory"));
                            return ["player" => $player->getReturnValues()];
                        }
                        $inventory->pos = $freePos;
                        PlayerController::removePlayerBonus($player, $inventory);
                        $inventory->save();
                        $equipment->save();
                        $player->save();
                    } else {
                        event(new sendError($user, "Not equipped"));
                        return ["player" => $player->getReturnValues()];
                    }
                }
                return ["player" => $player->getReturnValues(), "change" => 1];
            }
            event(new sendError($user, "No Equipment/Item"));
            return ["player" => $player->getReturnValues()];
        }
        event(new sendError($user, "No Player"));
        return ["player" => null];
    }

    public static function getFreeInvPos($player, $size)
    {
        $inventoryItems = Inventory::where('playerId', $player->id)->get();
        $allPos = [];
        foreach ($inventoryItems as $inventoryItem) {
            if (isset($inventoryItem)) {
                $tmpItem = Item::where('vnum', $inventoryItem->vnum)->first();
                if (isset($tmpItem) and $inventoryItem->pos != -1) {
                    if ($tmpItem->size == 1) {
                        $allPos[] = $inventoryItem->pos;
                    } elseif ($tmpItem->size == 2) {
                        $allPos[] = $inventoryItem->pos;
                        $allPos[] = $inventoryItem->pos + 5;
                    } elseif ($tmpItem->size == 3) {
                        $allPos[] = $inventoryItem->pos;
                        $allPos[] = $inventoryItem->pos + 5;
                        $allPos[] = $inventoryItem->pos + 10;
                    }
                }
            }
        }
        for ($i = 0; $i < Controller::getInventoryMaxCount(); $i++) {
            if (in_array($i, $allPos)) {
                continue;
            }
            $freePos = $i;
            if ($size >= 2 and $freePos + 5 >= Controller::getInventoryMaxCount() or $size == 3 and $freePos + 10 >= Controller::getInventoryMaxCount()) {
                return NAN;
            }
            if ($size == 1
                or ($size == 2 and !array_search($freePos + 5, $allPos))
                or ($size == 3 and !array_search($freePos + 5, $allPos) and !array_search($freePos + 10, $allPos))) {
                return $freePos;
            }
        }
        return NAN;
    }

    public function registerPlayer(Request $request)
    {
        $user = auth()->user();
        try {
            $request->validate([
                'name' => 'required|unique:players,name|min:5',
            ]);
        } catch (\Exception $exeption) {
            return back()->withErrors(['msg' => 'You can`t use this name']);
        }
        if ($request->race > 2) {
            return back()->withErrors(['msg' => 'This race is currently not available =(']);
        }
        $player = new Player;
        $player->name = $request->name;
        $player->race = $request->race;
        $player->hair = intval($request->hair);
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
            return back()->withErrors(['msg' => 'Player could not be created']);
        }
        $user->playerId = $player->id;
        $user->save();
        $this->startEQ($player);

        $equipment = new Equipment();
        $equipment->playerId = $player->id;
        $equipment->save();

        PlayerController::sendWelcomeMessage($user, $player);
        return redirect()->route('welcome');
    }

    public function sendWelcomeMessage($user, $player)
    {
        $adminUser = User::where('email', 'admin@ludus2.de')->first();
        $adminPlayer = Player::find($adminUser->playerId);
        $text = "Hallo und Willkommen bei Ludus2!
Dies ist ein Metin2 Fan Projekt.
Das ist aktuell eine Demo zum entwickeln und zeigen, was so möglich wäre.
Vielen Dank für dein Interesse.
Wir haben auch einen Discord-Server:
https://discord.gg/Hqe9aRQhX6

EN:
Hello and welcome to Ludus2!
This is a Metin2 fan project.
This is currently a demo to develop and show what would be possible.
Thanks for your interest.
We also have a Discord server:
https://discord.gg/Hqe9aRQhX6";

        $message = new Messages();
        $message->message = $text;
        $message->senderId = $adminPlayer->id;
        $message->senderName = $adminPlayer->name;
        $message->receiverId = $player->id;
        $message->receiverName = $player->name;
        $message->wasRead = 0;
        $message->created_at = \Illuminate\Support\Carbon::now();
        $message->save();
    }

    public function resetClass(Request $request)
    {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
            if (isset($player)) {
                if ($player->getActiveFight() != null) {
                    event(new sendError($user, "You can't reset class while fight is active"));
                    return ["player" => $player->getReturnValues()];
                }
                if ($player->getActivePvp(true) != null) {
                    event(new sendError($user, "You can't reset class while PvP is active"));
                    return ["player" => $player->getReturnValues()];
                }
                $player->class = 0;

                $player->skill0id = 0;
                $player->freeSkillPoints = $player->freeSkillPoints + $player->skill0level;
                $player->skill0level = 0;

                $player->skill1id = 0;
                $player->freeSkillPoints = $player->freeSkillPoints + $player->skill1level;
                $player->skill1level = 0;
                $player->save();
                return ["player" => $player->getReturnValues()];
            }
        }
        event(new sendInfo($user, 'No User'));
        return ["player" => null];
    }

    public function startEQ($player)
    {
        if (!PlayerController::giveItem($player, Item::where('vnum', 27001)->first(), 100)) {
            return redirect()->back()->withErrors(['msg' => 'Not enough space in inventory']);
        }
        if (!PlayerController::giveItem($player, Item::where('vnum', 27004)->first(), 50)) {
            return redirect()->back()->withErrors(['msg' => 'Not enough space in inventory']);
        }

        $race = Controller::getRace($player);
        if ($race === __('custom.warrior')) {
            if (!PlayerController::giveItem($player, Item::where('vnum', 10)->first(), 1)) {
                return redirect()->back()->withErrors(['msg' => 'Not enough space in inventory']);
            }
            if (!PlayerController::giveItem($player, Item::where('vnum', 11200)->first(), 1)) {
                return redirect()->back()->withErrors(['msg' => 'Not enough space in inventory']);
            }
            if (!PlayerController::giveItem($player, Item::where('vnum', 3000)->first(), 1)) {
                return redirect()->back()->withErrors(['msg' => 'Not enough space in inventory']);
            }
        } elseif ($race === __('custom.ninja')) {
            if (!PlayerController::giveItem($player, Item::where('vnum', 10)->first(), 1)) {
                return redirect()->back()->withErrors(['msg' => 'Not enough space in inventory']);
            }
            if (!PlayerController::giveItem($player, Item::where('vnum', 11400)->first(), 1)) {
                return redirect()->back()->withErrors(['msg' => 'Not enough space in inventory']);
            }
            if (!PlayerController::giveItem($player, Item::where('vnum', 1000)->first(), 1)) {
                return redirect()->back()->withErrors(['msg' => 'Not enough space in inventory']);
            }
            if (!PlayerController::giveItem($player, Item::where('vnum', 2000)->first(), 1)) {
                return redirect()->back()->withErrors(['msg' => 'Not enough space in inventory']);
            }
        }
    }

    public function showPlayerProfile($name)
    {
        if (isset($name)) {
            $player = Player::where('name', $name)->first();
            return view('welcome', ['showPlayerProfile' => true, 'rankingPlayer' => $player]);
        }
    }

    public function getPlayerProfile($name)
    {
        if (isset($name)) {
            $rankingPlayer = Player::where('name', $name)->first();
            if (isset($rankingPlayer)) {
                $modelPath = $rankingPlayer->getModelPath();
                $model = $rankingPlayer->getModel();
                $weaponPath = $rankingPlayer->getWeaponPath();
                $weapon = $rankingPlayer->getWeapon();
                $animationPath = $rankingPlayer->getAnimationPath();
                $animation = $rankingPlayer->getAnimation();
                $lastFight = $rankingPlayer->getLastFight();
                $equipment = $rankingPlayer->equipment !== null ? $rankingPlayer->equipment : NULL;
                $battlesWon = $rankingPlayer->getWonFights();
                $battlesLost = $rankingPlayer->getLooseFights();
                $battlesWonPvp = $rankingPlayer->getWonPvp();
                $battlesLostPvp = $rankingPlayer->getLoosePvp();
                return ['rankingPlayer' => $rankingPlayer,
                    'modelPath' => $modelPath,
                    'model' => $model,
                    'weaponPath' => $weaponPath,
                    'weapon' => $weapon,
                    'animationPath' => $animationPath,
                    'animation' => $animation,
                    'lastFight' => $lastFight,
                    'equipment' => $equipment,
                    'battlesWon' => $battlesWon,
                    'battlesLost' => $battlesLost,
                    'battlesWonPvp' => $battlesWonPvp,
                    'battlesLostPvp' => $battlesLostPvp,
                ];
            } else {
                return null;
            }
        }
    }

    public function getRankingData(Request $request)
    {
        $currentPage = $request->page_num;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        return Player::where('name', 'NOT LIKE', 'Ludus2 Team')->orderBy('level', 'DESC')->orderBy('exp', 'DESC')->simplePaginate(10);
    }
}
