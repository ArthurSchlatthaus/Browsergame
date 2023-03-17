<?php

namespace App\Http\Controllers;

use App\Events\sendError;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Player;
use App\Models\Shop;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function getInventoryMaxCount(): int
    {
        return 40;
    }

    public static function getMonsterbyId($monsterId)
    {
        return \App\Models\Monster::find($monsterId);
    }

    public static function getPvpReturnValues()
    {
        $pvpNew = auth()->user()->player->getLastPvp(true);
        if (isset($pvpNew)) {
            $defender = Player::find($pvpNew->defenderId);
            $pvpNew->defender = true;
            $pvpNew->defenderId = $defender->id;
            $pvpNew->defenderAnimation = $defender->getAnimation();
            $pvpNew->defenderAnimationPath = $defender->getAnimationPath();
            $pvpNew->defenderHair = $defender->getHair();
            $pvpNew->defenderSkillPath = $defender->getSkillPath();
            $pvpNew->defenderRace = Controller::getRace($defender);
            $pvpNew->defenderName = $defender->name;
            $pvpNew->defenderMaxHp = $defender->maxHp;
            $pvpNew->defenderMaxSp = $defender->maxSp;

            $pvpNew->defenderModel = $defender->getModel();
            $pvpNew->defenderModelPath = $defender->getModelPath();
            $pvpNew->defenderWeapon = $defender->getWeapon();
            $pvpNew->defenderWeaponPath = $defender->getWeaponPath();
            $pvpNew->defenderAnimationPathWinner = $defender->getAnimationPathWinner();
            $pvpNew->defenderWinningDance = $defender->getWinningDance();
            $attacker = Player::find($pvpNew->attackerId);
            $pvpNew->attacker = true;
            $pvpNew->attackerId = $attacker->id;
            $pvpNew->attackerAnimation = $attacker->getAnimation();
            $pvpNew->attackerAnimationPath = $attacker->getAnimationPath();
            $pvpNew->attackerHair = $attacker->getHair();
            $pvpNew->attackerSkillPath = $attacker->getSkillPath();
            $pvpNew->attackerRace = Controller::getRace($attacker);
            $pvpNew->attackerName = $attacker->name;
            $pvpNew->attackerMaxHp = $attacker->maxHp;
            $pvpNew->attackerMaxSp = $attacker->maxSp;

            $pvpNew->attackerModel = $attacker->getModel();
            $pvpNew->attackerModelPath = $attacker->getModelPath();
            $pvpNew->attackerWeapon = $attacker->getWeapon();
            $pvpNew->attackerWeaponPath = $attacker->getWeaponPath();
            $pvpNew->attackerAnimationPathWinner = $attacker->getAnimationPathWinner();
            $pvpNew->attackerWinningDance = $attacker->getWinningDance();
        }
        return $pvpNew;
    }

    public function buyItem(Request $request)
    {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
        } else {
            event(new sendError($user, 'no user'));
            return ['player' => null];
        }
        if (isset($player)) {
            $shopIndex = ($request->shopIndex);
            if ($shopIndex > 0 and $shopIndex < 100) {
                $shopItem = Shop::find($shopIndex);
            }
            if (isset($shopItem)) {
                if ($player->gold < $shopItem->price || $player->gold <= 0) {
                    event(new sendError($user, 'No Yang'));
                    return ['player' => $player->getReturnValues()];
                }
                if (!PlayerController::giveItem($player, Item::where('vnum', $shopItem->itemId)->first(), $shopItem->count)) {
                    event(new sendError($user, 'Not enough space in inventory'));
                    return ['player' => $player->getReturnValues()];
                }
                $player->gold -= $shopItem->price;
                $player->save();
            }
            return ['player' => $player->getReturnValues()];
        }
        event(new sendError($user, 'no player'));
        return ['player' => null];
    }

    public static function getItemName($vnum)
    {
        $item = Item::where('vnum', $vnum)->first();
        if (isset($item)) {
            return \App\Http\Controllers\Controller::getType($item) == 'potion' ? __('items.item_id_' . $item->vnum) : __('items.item_id_' . substr_replace($item->vnum, '', -1)) . substr($item->vnum, -1);
        } else {
            return false;
        }
    }

    public static function getSkillName(Skill $skill)
    {
        if (isset($skill)) {
            return __('skills.skill_id_' . $skill->id);
        } else {
            return false;
        }
    }

    public static function getType(Item $item)
    {
        $array = [
            0 => 'weapon',
            1 => 'body',
            2 => 'potion'
        ];
        return $array[$item->type];
    }

    public static function getRace(Player $player)
    {
        $array = [
            1 => __('custom.warrior'),
            2 => __('custom.ninja'),
            3 => __('custom.sura'),
            4 => __('custom.shaman')
        ];
        return $array[$player->race];
    }

    public static function getSkillType(Skill $skill)
    {
        $array = [
            0 => 'buff',
            1 => 'hit'
        ];
        return $array[$skill->type];
    }
}
