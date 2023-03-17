<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FightController;
use App\Http\Controllers\PlayerController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function PHPUnit\Framework\isEmpty;

class Player extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function getDamage()
    {
        $fight = $this->getActiveFight() != null ? $this->getActiveFight() : $this->getInactiveFight();
        if (!isEmpty($this->fightlog)) {
            return $this->fightlog->avgPlayerDamage;
        } else {
            if (isset($fight)) {
                return $fight->dmgAvg;
            } else {
                return 0;
            }
        }
    }

    public function isNewMessage()
    {
        $unReadMsg = Messages::where('receiverId', $this->id)->where('wasRead', 0)->first();
        if (isset($unReadMsg)) {
            return true;
        } else {
            return false;
        }
    }

    public function getActivePvp(bool $isAttacker)
    {
        //clean up bugged pvp
        Pvp::where('isActive', 1)->where('winner', '>=', 0)->where('looser', '>=', 0)->update(["isActive" => 0]);

        if ($isAttacker) {
            return Pvp::where('attackerId', $this->id)->where('isActive', 1)->where('winner', null)->where('looser', null)->orderBy('id', 'DESC')->first();
        } else {
            return Pvp::where('defenderId', $this->id)->where('isActive', 1)->where('winner', null)->where('looser', null)->orderBy('id', 'DESC')->first();
        }
    }

    public function getLastPvp()
    {
        return Pvp::where('attackerId', $this->id)->orderBy('id', 'DESC')->first();
    }

    public function getWonPvp()
    {
        return Pvp::where('winner', $this->id)->count();
    }

    public function getLoosePvp()
    {
        return Pvp::where('looser', $this->id)->count();
    }

    public function getActiveFight()
    {
        return Fight::where('playerId', $this->id)->where('isActive', 1)->where('canceled', 0)->where('isAutofight', 0)->orderBy('id', 'DESC')->first();
    }

    public function getLastFight()
    {
        return Fight::where('playerId', $this->id)->orderBy('id', 'DESC')->first();
    }

    public function getWonFights()
    {
        return Fight::where('playerId', $this->id)->where('playerIsWinner', 1)->count();
    }

    public function getLooseFights()
    {
        return Fight::where('playerId', $this->id)->where('playerIsWinner', 0)->count();
    }

    public function gotReward()
    {
        $fight = Fight::where('playerId', $this->id)->orderBy('id', 'DESC')->first();
        return isset($fight) ? $fight->gotReward : -1;
    }

    public function getInactiveFight()
    {
        if ($this->getActiveFight() == null) {
            return Fight::where('playerId', $this->id)->where('isActive', 0)->where('canceled', 0)->where('isAutofight', 0)->orderBy('id', 'DESC')->first();
        }
        return null;
    }

    public function equipment()
    {
        return $this->hasOne(Equipment::class, 'playerId', 'id');
    }

    public function fight()
    {
        return $this->hasMany(Fight::class, 'playerId', 'id');
    }

    public function fightlog()
    {
        return $this->hasMany(FightLog::class, 'playerId', 'id');
    }

    public function missions()
    {
        return $this->hasMany(Mission::class, 'playerId', 'id');
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'playerId', 'id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Messages::class, 'receiverId', 'id');
    }

    public function sendMessages()
    {
        return $this->hasMany(Messages::class, 'senderId', 'id');
    }

    public static function getTodayOnlineCount()
    {
        $cnt = 0;
        $players = Player::all();
        foreach ($players as $player) {
            $timestamp = $player->lastLoginTime;
            if (date('Ymd') == date('Ymd', $timestamp)) {
                $cnt++;
            }
        }
        return $cnt;
    }

    public function getModelPath()
    {
        if (\App\Http\Controllers\Controller::getRace($this) == __('custom.warrior')) {
            return "/warrior/";
        } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.ninja')) {
            return "/ninja/";
        } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.sura')) {
            return "/sura/";
        } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.shaman')) {
            return "/shaman/";
        }
        return "none";
    }

    public function getSkillValues($skillId)
    {
        if ($skillId == "") {
            return false;
        }
        $skill = Skill::find($skillId);
        if (isset($skill)) {
            if ($skill->type === 0) {//buff
                if ($this->skill0id === $skill->id) {
                    return [
                        __('custom.damage') . ': ' . $this->skill0level * 10 /*Skill value0*/,
                        __('custom.duration') . ': ' . /*$player->skill0level */ 4 /*Skill duration*/,
                        __('custom.sp_cost') . ': ' . $this->skill0level * 100 /*Skill duration*/
                    ];
                } else {
                    return [
                        __('custom.damage') . ': ' . $this->skill1level * 10 /*Skill value0*/,
                        __('custom.duration') . ': ' . /*$player->skill0level */ 4 /*Skill duration*/,
                        __('custom.sp_cost') . ': ' . $this->skill1level * 100 /*Skill duration*/
                    ];
                }
            } else if ($skill->type === 1) {//hit
                if ($this->skill0id === $skill->id) {
                    return [
                        __('custom.damage') . ': ' . $this->skill0level * 25 /*Skill value0*/,
                        __('custom.duration') . ': ' . $this->skill0level * 0 /*Skill duration*/,
                        __('custom.sp_cost') . ': ' . $this->skill0level * 250 /*Skill duration*/
                    ];
                } else {
                    return [
                        __('custom.damage') . ': ' . $this->skill1level * 25 /*Skill value0*/,
                        __('custom.duration') . ': ' . $this->skill1level * 0 /*Skill duration*/,
                        __('custom.sp_cost') . ': ' . $this->skill1level * 250 /*Skill duration*/
                    ];
                }
            }
        }
        return false;
    }

    public function getSkillId($skillNumber)
    {
        $warrior_1 = [
            1 => 1,//aura
            2 => 2,//sw
        ];
        $warrior_2 = [
            1 => 3,//sk
            2 => 4//stampfer
        ];
        $ninja_1 = [
            1 => 11,//hinterhalt
            2 => 12,//tarnung
        ];
        $ninja_2 = [
            1 => 13,//feuerpfeil
            2 => 14//giftpfeil
        ];
        if (\App\Http\Controllers\Controller::getRace($this) === __('custom.warrior')) {
            if ($this->class === 1) {
                return $warrior_1[$skillNumber];
            } elseif ($this->class === 2) {
                return $warrior_2[$skillNumber];
            }
        } else if (\App\Http\Controllers\Controller::getRace($this) === __('custom.ninja')) {
            if ($this->class === 1) {
                return $ninja_1[$skillNumber];
            } elseif ($this->class === 2) {
                return $ninja_2[$skillNumber];
            }
        }
        return null;
    }

    public function getModel()
    {
        $equipment = $this->equipment;
        $body = 0;
        if (isset($equipment)) {
            $inventoryItem = Inventory::find($equipment->body);
            if (isset($inventoryItem)) {
                $body = $inventoryItem->vnum;
            }
        }
        return substr($body, 0, -1) . "0.fbx";
    }

    public function getMonster($num)
    {
        $fight = $this->getLastFight();
        if (isset($fight)) {
            if ($num == 1) {
                return $fight->monster1Id;
            } else if ($num == 2) {
                return $fight->monster2Id;
            } else if ($num == 3) {
                return $fight->monster3Id;
            }
        }
        return 0;
    }

    public function getMonsterHp($num)
    {
        $fight = $this->getLastFight();
        if (isset($fight)) {
            if ($num == 1) {
                return $fight->monster1Hp;
            } else if ($num == 2) {
                return $fight->monster2Hp;
            } else if ($num == 3) {
                return $fight->monster3Hp;
            }
        }
        return 0;
    }

    public function giveItem($vnum, $count, $hasBonus)
    {
        if (!PlayerController::giveItem($this, Item::where('vnum', $vnum)->first(), $count, $hasBonus)) {
            return redirect()->back()->withErrors(['msg' => 'Not enough space in inventory']);
        }
    }

    public function getMonsterAnimation()
    {
        $fight = $this->getLastFight();
        if (isset($fight)) {
            $arr = [];
            if ($fight->monster1Hp == 0) {
                $rand = rand(0, 100);
                if ($rand >= 50) {
                    $arr[] = 'dead_1';
                } else {
                    $arr[] = 'dead_2';
                }
            } else if ($fight->monster1Hp > 0) {
                $rand = rand(0, 100);
                if ($rand < 33) {
                    $arr[] = 'wait_1';
                } elseif ($rand < 66) {
                    $arr[] = 'wait_2';
                } else {
                    $arr[] = 'wait_3';
                }
            }
            if ($fight->monster2Hp == 0) {
                $rand = rand(0, 100);
                if ($rand >= 50) {
                    $arr[] = 'dead_1';
                } else {
                    $arr[] = 'dead_2';
                }
            } else if ($fight->monster2Hp > 0) {
                $rand = rand(0, 100);
                if ($rand < 33) {
                    $arr[] = 'wait_1';
                } elseif ($rand < 66) {
                    $arr[] = 'wait_2';
                } else {
                    $arr[] = 'wait_3';
                }
            }
            if ($fight->monster3Hp == 0) {
                $rand = rand(0, 100);
                if ($rand >= 50) {
                    $arr[] = 'dead_1';
                } else {
                    $arr[] = 'dead_2';
                }
            } else if ($fight->monster3Hp > 0) {
                $rand = rand(0, 100);
                if ($rand < 33) {
                    $arr[] = 'wait_1';
                } elseif ($rand < 66) {
                    $arr[] = 'wait_2';
                } else {
                    $arr[] = 'wait_3';
                }
            }
            return $arr;
        }
        return "wait_1";
    }

    public function getWeaponPath()
    {
        $equipment = $this->equipment;
        if (isset($equipment) and isset($equipment->weapon)) {
            $inventoryItem = Inventory::find($this->equipment->weapon);
            if (isset($inventoryItem)) {
                $item = Item::where('vnum', $inventoryItem->vnum)->first();
                if (isset($item)) {
                    return "/weapon/{$item->getSubtypeName()}/";
                }
            }
        }
        return "/weapon/none/";
    }

    public function getWeapon()
    {
        $equipment = $this->equipment;
        $weapon = 0;
        if (isset($equipment)) {
            $inventoryItem = Inventory::find($this->equipment->weapon);
            if (isset($inventoryItem)) {
                $weapon = $inventoryItem->vnum;
            }
        }
        return substr($weapon, 0, -1) . "0.fbx";
    }

    public function getSkillName($skillId)
    {
        if ($skillId == "") {
            return false;
        }
        $warrior = [
            1 => __('custom.aura'),
            2 => __('custom.schwertwirbel'),
            3 => __('custom.starkerkörper'),
            4 => __('custom.stampfer')
        ];
        $ninja = [
            11 => __('custom.hinterhalt'),
            12 => __('custom.tarnung'),
            13 => __('custom.feuerpfeil'),
            14 => __('custom.giftpfeil')
        ];
        if (\App\Http\Controllers\Controller::getRace($this) === __('custom.warrior')) {
            return $warrior[$skillId];
        } else if (\App\Http\Controllers\Controller::getRace($this) === __('custom.ninja')) {
            return $ninja[$skillId];
        } else {
            return false;
        }
    }

    public function getClassName($classId)
    {
        $warrior = [
            1 => __('custom.body'),
            2 => __('custom.mental'),
        ];
        $ninja = [
            1 => __('custom.nah'),
            2 => __('custom.fern'),
        ];
        if (\App\Http\Controllers\Controller::getRace($this) === __('custom.warrior')) {
            return $warrior[$classId];
        } else if (\App\Http\Controllers\Controller::getRace($this) === __('custom.ninja')) {
            return $ninja[$classId];
        } else {
            return false;
        }
    }

    public function getReturnValues()
    {
        $redPotionVnum = 0;
        $bluePotionVnum = 0;
        $inventoryItems = $this->inventory;
        foreach ($this->inventory as $inventoryItem) {
            if ($inventoryItem->vnum === 27001 && $inventoryItem->count > 0) {
                $redPotionVnum = 27001;
            }
            if ($inventoryItem->vnum === 27004 && $inventoryItem->count > 0) {
                $bluePotionVnum = 27004;
            }
            $item = \App\Models\Item::where('vnum', $inventoryItem->vnum)->first();
            if (isset($item)) {
                if (\App\Http\Controllers\Controller::getType($item) === 'weapon') {
                    $title = \App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum, $inventoryItem);
                } elseif (\App\Http\Controllers\Controller::getType($item) === 'body') {
                    $title = \App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum, $inventoryItem);
                } else {
                    $title = \App\Http\Controllers\Controller::getType($item) == 'potion' ? __('items.item_id_' . $inventoryItem->vnum) : __('items.item_id_' . substr_replace($inventoryItem->vnum, '', -1)) . substr($inventoryItem->vnum, -1);
                }
            } else {
                $title = \App\Http\Controllers\Controller::getType($item) == 'potion' ? __('items.item_id_' . $inventoryItem->vnum) : __('items.item_id_' . substr_replace($inventoryItem->vnum, '', -1)) . substr($inventoryItem->vnum, -1);
            }
            $inventoryItem->title = $title;
        }
        $equipment = $this->equipment;
        $equipment->weaponItem = null;
        $equipment->weaponTitle = null;
        $equipment->bodyItem = null;
        $equipment->bodyTitle = null;
        if ($equipment->weapon > 0) {
            $weapon = \App\Models\Inventory::find($equipment->weapon);
            $weaponItem = \App\Models\Item::where('vnum', $weapon->vnum)->first();
            $equipment->weaponItem = $weapon;
            $equipment->weaponTitle = \App\Http\Controllers\PlayerController::weapon_tooltip($weapon->vnum, $weapon);
        }
        if ($equipment->body > 0) {
            $body = \App\Models\Inventory::find($equipment->body);
            $equipment->bodyItem = $body;
            $equipment->bodyTitle = \App\Http\Controllers\PlayerController::body_tooltip($body->vnum, $body);
        }
        $weaponType = null;
        if (isset($weaponItem)) {
            $weaponType = $weaponItem->getSubtypeName();
        }

        $getRace = "";
        if (\App\Http\Controllers\Controller::getRace($this) == __('custom.warrior')) {
            $getRace = "warrior";
        } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.ninja')) {
            $getRace = "ninja";
        } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.sura')) {
            $getRace = "sura";
        } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.shaman')) {
            $getRace = "shaman";
        }
        return [
            "name" => $this->name,
            "level" => $this->level,
            "exp" => $this->exp,
            "race" => $this->race,
            "hp" => $this->hp,
            "maxHp" => $this->maxHp,
            "sp" => $this->sp,
            "maxSp" => $this->maxSp,
            "modelPath" => $this->getModelPath(),
            "model" => $this->getModel(),
            "animationPath" => $this->getAnimationPath(),
            "animation" => $this->getAnimation(),
            "weaponPath" => $this->getWeaponPath(),
            "weapon" => $this->getWeapon(),
            "hair" => $this->getHair(),
            "damage" => $this->getDamage(),
            "defense" => FightController::getDefensePlayer($this),
            "vit" => $this->vit,
            "int" => $this->int,
            "str" => $this->str,
            "dex" => $this->dex,
            "freestatus" => $this->freeStatusPoints,
            "freeSkillPoints" => $this->freeSkillPoints,
            "redPotionVnum" => $redPotionVnum,
            "bluePotionVnum" => $bluePotionVnum,
            "hasUnreadMsg" => $this->isNewMessage(),
            "skill1" => \App\Models\Skill::find($this->getSkillId(1)),
            "skill2" => \App\Models\Skill::find($this->getSkillId(2)),
            "class" => $this->class,
            "className1" => $this->getClassName(1),
            "className2" => $this->getClassName(2),
            "skill0level" => $this->skill0level,
            "skill1level" => $this->skill1level,
            "canUseSkill1" => \App\Http\Controllers\PlayerController::canUseSkill(1),
            "canUseSkill2" => \App\Http\Controllers\PlayerController::canUseSkill(2),
            "skillPath" => $this->getSkillPath(),
            "skill1Animation" => $this->getSkill($this->getSkillId(1)),
            "skill2Animation" => $this->getSkill($this->getSkillId(2)),
            "inventory" => $inventoryItems,
            "equipment" => $equipment,
            "gold" => $this->gold,
            "isLoggedIn" => $this->isLoggedIn,
            "hitAnimation" => $this->getHitAnimation(),
            "getRace" => $getRace,
            "animationPathWinner" => $this->getAnimationPathWinner(),
            "winningDance" => $this->getWinningDance(),
            "weaponType" => $weaponType,
        ];
    }

    public function getSkill($skillId)
    {
        if ($skillId == "") {
            return false;
        }
        $warrior = [
            1 => 'geomgyeong',//aura
            2 => 'palbang',//sw
            3 => 'cheongeun',//sk
            4 => 'daejin'//stampfer
        ];
        $ninja = [
            11 => 'amseup',//Hinterhalt
            12 => 'eunhyeong',//Tarnung
            13 => 'shoot_Once',//Feuerpfeil//hwajo
            14 => 'shoot_Once'//Giftpfeil//gigung
        ];
        if (\App\Http\Controllers\Controller::getRace($this) === __('custom.warrior')) {
            return 'A_' . ucfirst($warrior[$skillId]) . '.fbx';
        } else if (\App\Http\Controllers\Controller::getRace($this) === __('custom.ninja')) {
            return 'A_' . ucfirst($ninja[$skillId]) . '.fbx';
        } else {
            return false;
        }
    }

    public function getSkillPath(): string
    {
        if (\App\Http\Controllers\Controller::getRace($this) == __('custom.warrior')) {
            return "/warrior/animation/skill/";
        } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.ninja')) {
            return "/ninja/animation/skill/";
        } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.sura')) {
            return "/sura/animation/skill/";
        } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.shaman')) {
            return "/shaman/animation/skill/";
        }
        return "none";
    }

    public function getHitAnimation(): string
    {
        $equipment = $this->equipment;
        if (isset($equipment)) {
            $inventoryItem = Inventory::find($this->equipment->weapon);
            if (isset($inventoryItem)) {
                $item = Item::where('vnum', $inventoryItem->vnum)->first();
                if (isset($item)) {
                    if ($item->getSubtypeName() === 'bow') {
                        return "A_Shoot.fbx";
                    }
                }
            }
        }
        $rand = rand(0, 100);
        if ($rand < 25) {
            return 'A_Combo01.fbx';
        } else if ($rand < 50) {
            return 'A_Combo02.fbx';
        } else if ($rand < 75) {
            return 'A_Combo03.fbx';
        } else {
            return 'A_Combo04.fbx';
        }

    }

    public function getHair()
    {
        return 'SK_Hair_1_' . $this->hair . '.fbx';
    }

    public function getWinningDance()
    {
        return $this->winningDance . '.fbx';
    }

    public function getAnimationPathWinner(): string
    {
        if (\App\Http\Controllers\Controller::getRace($this) == __('custom.warrior')) {
            return "/warrior/animation/";
        } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.ninja')) {
            return "/ninja/animation/";
        } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.sura')) {
            return "/sura/animation/";
        } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.shaman')) {
            return "/shaman/animation/";
        }
        return "none";
    }

    public function getAnimationPath(): string
    {
        $equipment = $this->equipment;
        $weapon = 0;
        if (isset($equipment)) {
            $inventoryItem = Inventory::find($this->equipment->weapon);
            if (isset($inventoryItem)) {
                $weapon = $inventoryItem->vnum;
            }
        }
        if (\App\Http\Controllers\Controller::getRace($this) == __('custom.warrior')) {
            $animationPath = "/warrior/animation/none/";
        } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.ninja')) {
            $animationPath = "/ninja/animation/none/";
        } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.sura')) {
            $animationPath = "/sura/animation/none/";
        } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.shaman')) {
            $animationPath = "/shaman/animation/none/";
        }
        if ($weapon != 0) {
            $inventoryItem = Inventory::find($this->equipment->weapon);
            if (isset($inventoryItem)) {
                $item = Item::where('vnum', $inventoryItem->vnum)->first();
                if (isset($item)) {
                    if (\App\Http\Controllers\Controller::getRace($this) == __('custom.warrior')) {
                        $animationPath = "/warrior/animation/{$item->getSubtypeName()}/";
                    } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.ninja')) {
                        $animationPath = "/ninja/animation/{$item->getSubtypeName()}/";
                    } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.sura')) {
                        $animationPath = "/sura/animation/{$item->getSubtypeName()}/";
                    } elseif (\App\Http\Controllers\Controller::getRace($this) == __('custom.shaman')) {
                        $animationPath = "/shaman/animation/{$item->getSubtypeName()}/";
                    }
                }
            }
        }

        return $animationPath;
    }

    public function canUseSkillWithWeapon($skillId)
    {
        if ($skillId == "") {
            return false;
        }
        if (isset($this->equipment) and isset($this->equipment->weapon)) {
            if (\App\Http\Controllers\Controller::getRace($this) == __('custom.warrior')) {
                return true;
            } else if (\App\Http\Controllers\Controller::getRace($this) == __('custom.ninja')) {
                $inventoryItem = Inventory::find($this->equipment->weapon);
                if (isset($inventoryItem)) {
                    $item = Item::where('vnum', $inventoryItem->vnum)->first();
                    if (isset($item) and $item->getSubtypeName() === "bow") {
                        if ($skillId >= 13 and $skillId <= 14) {
                            return true;
                        }
                    } else if (isset($item) and ($item->getSubtypeName() === "dagger" or $item->getSubtypeName() === "sword")) {
                        if ($skillId >= 11 and $skillId <= 12) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    public function canWearItem(Item $item): bool
    {
        if (isset($item)) {
            if ($item->level > $this->level) {
                return false;
            }
            if (\App\Http\Controllers\Controller::getRace($this) === __('custom.warrior')) {
                if ($item->type === 0
                    and ($item->subtype === 0 or $item->subtype === 1)) {
                    return true;
                }
                if ($item->type === 1 and $item->subtype === 0) {
                    return true;
                }
            } else if (\App\Http\Controllers\Controller::getRace($this) === __('custom.ninja')) {
                if ($item->type === 0
                    and ($item->subtype === 0 or $item->subtype === 2 or $item->subtype === 3)) {
                    return true;
                }
                if ($item->type === 1 and $item->subtype === 1) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getAnimation(): string
    {
        $rand = rand(0, 100);
        if ($rand >= 50) {
            return "A_Wait.fbx";
        } else {
            return "A_Wait1.fbx";
        }

    }
}
