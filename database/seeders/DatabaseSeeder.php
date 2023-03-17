<?php

namespace Database\Seeders;

use App\Models\Bonus;
use App\Models\Group;
use App\Models\Item;
use App\Models\MissionType;
use App\Models\Monster;
use App\Models\Player;
use App\Models\Shop;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('monsters')->truncate();
        DB::table('shops')->truncate();
        DB::table('mission_types')->truncate();
        DB::table('items')->truncate();
        DB::table('skills')->truncate();
        DB::table('groups')->truncate();
        DB::table('bonus')->truncate();
        Schema::enableForeignKeyConstraints();
        if (User::where('email', 'a@a.a')->count() < 1) {
            User::factory()->create([
                'email' => 'a@a.a',
                'password' => Hash::make(1234),
                'api_token' => Str::random(60),
            ]);
        }
        if (User::where('email', 'admin@ludus2.de')->count() < 1) {
            User::factory()->create([
                'email' => 'admin@ludus2.de',
                'password' => Hash::make('yjLLGi6k'),
                'api_token' => Str::random(60),
                'role' => 'admin',
            ]);
        }
        $adminUser = User::where('email', 'admin@ludus2.de')->first();
        if ($adminUser->playerId < 1) {
            Player::factory()->create([
                'name' => 'Ludus2 Team',
                'race' => '1',
            ]);
            $player = Player::where('name', 'Ludus2 Team')->first();
            if (isset($player)) {
                $adminUser->playerId = $player->id;
                $adminUser->save();
            }
        }
        $this->monsters();
        $this->missiontypes();
        $this->items();
        $this->shops();
        $this->skills();
        $this->groups();
        $this->bonus();
    }

    public function bonus()
    {
        Bonus::factory()->create([
            'apply' => 'MAX_HP',
            'prob' => 35,
            'min' => 100,
            'max' => 1000,
            'weapon' => 0,
            'body' => 1,
        ]);
        Bonus::factory()->create([
            'apply' => 'MAX_SP',
            'prob' => 35,
            'min' => 100,
            'max' => 1000,
            'weapon' => 0,
            'body' => 1,
        ]);
        Bonus::factory()->create([
            'apply' => 'VIT',
            'prob' => 25,
            'min' => 1,
            'max' => 10,
            'weapon' => 1,
            'body' => 1,
        ]);
        Bonus::factory()->create([
            'apply' => 'INT',
            'prob' => 25,
            'min' => 1,
            'max' => 10,
            'weapon' => 1,
            'body' => 1,
        ]);
        Bonus::factory()->create([
            'apply' => 'STR',
            'prob' => 25,
            'min' => 1,
            'max' => 10,
            'weapon' => 1,
            'body' => 1,
        ]);
        Bonus::factory()->create([
            'apply' => 'DEX',
            'prob' => 25,
            'min' => 1,
            'max' => 10,
            'weapon' => 1,
            'body' => 1,
        ]);
        Bonus::factory()->create([
            'apply' => 'HP_REGEN',
            'prob' => 25,
            'min' => 1,
            'max' => 10,
            'weapon' => 0,
            'body' => 1,
        ]);
        Bonus::factory()->create([
            'apply' => 'SP_REGEN',
            'prob' => 25,
            'min' => 1,
            'max' => 10,
            'weapon' => 0,
            'body' => 1,
        ]);
        Bonus::factory()->create([
            'apply' => 'ATT_BONUS',
            'prob' => 15,
            'min' => 1,
            'max' => 10,
            'weapon' => 1,
            'body' => 0,
        ]);
        Bonus::factory()->create([
            'apply' => 'DEF_BONUS',
            'prob' => 15,
            'min' => 1,
            'max' => 10,
            'weapon' => 0,
            'body' => 1,
        ]);
    }

    public function groups()
    {
        Group::factory()->create([
            'id' => 1,
            'monster1Id' => 1,
            'monster2Id' => 1,
            'monster3Id' => 1,
        ]);
        Group::factory()->create([
            'id' => 2,
            'monster1Id' => 1,
            'monster2Id' => 2,
            'monster3Id' => 1,
        ]);
        Group::factory()->create([
            'id' => 3,
            'monster1Id' => 2,
            'monster2Id' => 2,
            'monster3Id' => 2,
        ]);
        Group::factory()->create([
            'id' => 4,
            'monster1Id' => 2,
            'monster2Id' => 3,
            'monster3Id' => 2,
        ]);
        Group::factory()->create([
            'id' => 5,
            'monster1Id' => 3,
            'monster2Id' => 3,
            'monster3Id' => 3,
        ]);
        Group::factory()->create([
            'id' => 6,
            'monster1Id' => 3,
            'monster2Id' => 4,
            'monster3Id' => 3,
        ]);
        Group::factory()->create([
            'id' => 7,
            'monster1Id' => 4,
            'monster2Id' => 4,
            'monster3Id' => 4,
        ]);
        Group::factory()->create([
            'id' => 8,
            'monster1Id' => 4,
            'monster2Id' => 5,
            'monster3Id' => 4,
        ]);
        Group::factory()->create([
            'id' => 9,
            'monster1Id' => 5,
            'monster2Id' => 5,
            'monster3Id' => 5,
        ]);
        Group::factory()->create([
            'id' => 10,
            'monster1Id' => 5,
            'monster2Id' => 6,
            'monster3Id' => 5,
        ]);
    }

    public function skills()
    {
        Skill::factory()->create([
            'id' => 1,
            'name' => 'Aura des Schwertes',
            'type' => 0, //buff
            'value0' => 10,
            'value1' => 0,
            'spcost' => 100,
            'duration' => 4,
        ]);
        Skill::factory()->create([
            'id' => 2,
            'name' => 'Schwertwirbel',
            'type' => 1, //hit
            'value0' => 75,
            'value1' => 0,
            'spcost' => 250,
            'duration' => 0,
        ]);
        Skill::factory()->create([
            'id' => 3,
            'name' => 'Starker Körper',
            'type' => 0, //buff
            'value0' => 10,
            'value1' => 0,
            'spcost' => 100,
            'duration' => 10,
        ]);
        Skill::factory()->create([
            'id' => 4,
            'name' => 'Stampfer',
            'type' => 1, //hit
            'value0' => 75,
            'value1' => 0,
            'spcost' => 250,
            'duration' => 0,
        ]);
        Skill::factory()->create([
            'id' => 11,
            'name' => 'Hinterhalt',
            'type' => 1, //hit
            'value0' => 75,
            'value1' => 0,
            'spcost' => 250,
            'duration' => 0,
        ]);
        Skill::factory()->create([
            'id' => 12,
            'name' => 'Tarnung',
            'type' => 0, //buff
            'value0' => 10,
            'value1' => 0,
            'spcost' => 100,
            'duration' => 4,
        ]);
        Skill::factory()->create([
            'id' => 13,
            'name' => 'Feuerpfeil',
            'type' => 1, //hit
            'value0' => 75,
            'value1' => 0,
            'spcost' => 250,
            'duration' => 0,
        ]);
        Skill::factory()->create([
            'id' => 14,
            'name' => 'Giftpfeil',
            'type' => 1, //hit
            'value0' => 75,
            'value1' => 0,
            'spcost' => 250,
            'duration' => 0,
        ]);
    }

    public function shops()
    {
        Shop::factory()->create([
            'itemId' => 27001,
            'count' => 10,
            'price' => 50,
            'pos' => 0,
            'npcId' => 1,
        ]);
        Shop::factory()->create([
            'itemId' => 27001,
            'count' => 100,
            'price' => 500,
            'pos' => 1,
            'npcId' => 1,
        ]);
        Shop::factory()->create([
            'itemId' => 27004,
            'count' => 10,
            'price' => 100,
            'pos' => 2,
            'npcId' => 1,
        ]);
        Shop::factory()->create([
            'itemId' => 27004,
            'count' => 100,
            'price' => 1000,
            'pos' => 3,
            'npcId' => 1,
        ]);

        //waffenhändler
        Shop::factory()->create([
            'itemId' => 10,
            'count' => 1,
            'price' => 1000,
            'pos' => 0,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 20,
            'count' => 1,
            'price' => 2500,
            'pos' => 1,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 30,
            'count' => 1,
            'price' => 5000,
            'pos' => 2,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 40,
            'count' => 1,
            'price' => 7500,
            'pos' => 3,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 50,
            'count' => 1,
            'price' => 10000,
            'pos' => 4,
            'npcId' => 2,
        ]);

        Shop::factory()->create([
            'itemId' => 1000,
            'count' => 1,
            'price' => 1000,
            'pos' => 10,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 1010,
            'count' => 1,
            'price' => 2500,
            'pos' => 11,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 1020,
            'count' => 1,
            'price' => 5000,
            'pos' => 12,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 1030,
            'count' => 1,
            'price' => 7500,
            'pos' => 13,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 1040,
            'count' => 1,
            'price' => 10000,
            'pos' => 14,
            'npcId' => 2,
        ]);

        Shop::factory()->create([
            'itemId' => 2000,
            'count' => 1,
            'price' => 1000,
            'pos' => 15,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 2010,
            'count' => 1,
            'price' => 2500,
            'pos' => 16,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 2020,
            'count' => 1,
            'price' => 5000,
            'pos' => 17,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 2030,
            'count' => 1,
            'price' => 7500,
            'pos' => 18,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 2040,
            'count' => 1,
            'price' => 10000,
            'pos' => 19,
            'npcId' => 2,
        ]);

        Shop::factory()->create([
            'itemId' => 3000,
            'count' => 1,
            'price' => 1000,
            'pos' => 25,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 3010,
            'count' => 1,
            'price' => 2500,
            'pos' => 26,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 3020,
            'count' => 1,
            'price' => 5000,
            'pos' => 27,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 3030,
            'count' => 1,
            'price' => 7500,
            'pos' => 28,
            'npcId' => 2,
        ]);
        Shop::factory()->create([
            'itemId' => 3040,
            'count' => 1,
            'price' => 10000,
            'pos' => 29,
            'npcId' => 2,
        ]);

        //rüstungshändler
        Shop::factory()->create([
            'itemId' => 11200,
            'count' => 1,
            'price' => 1000,
            'pos' => 0,
            'npcId' => 3,
        ]);
        Shop::factory()->create([
            'itemId' => 11210,
            'count' => 1,
            'price' => 5000,
            'pos' => 1,
            'npcId' => 3,
        ]);
        Shop::factory()->create([
            'itemId' => 11400,
            'count' => 1,
            'price' => 1000,
            'pos' => 2,
            'npcId' => 3,
        ]);
        Shop::factory()->create([
            'itemId' => 11410,
            'count' => 1,
            'price' => 5000,
            'pos' => 3,
            'npcId' => 3,
        ]);

    }

    public function items()
    {
        Item::factory()->create([
            'vnum' => 27001,
            'name' => 'Red Potion',
            'level' => 1,
            'type' => 2,
            'subtype' => 0,
            'size' => 1,
            'value0' => 100,
        ]);
        Item::factory()->create([
            'vnum' => 27004,
            'name' => 'Blue Potion',
            'level' => 1,
            'type' => 2,
            'subtype' => 1,
            'size' => 1,
            'value0' => 100,
        ]);
        for ($i = 0; $i < 10; $i++) {
            //onehand
            Item::factory()->create([
                'vnum' => '1' . $i,
                'name' => 'Sword+' . $i,
                'level' => 1,
                'type' => 0,
                'subtype' => 0,
                'size' => 2,
                'value0' => 13 + ($i * 2),
                'value1' => 15 + ($i * 2),
            ]);
            Item::factory()->create([
                'vnum' => '2' . $i,
                'name' => 'Long Sword+' . $i,
                'level' => 5,
                'type' => 0,
                'subtype' => 0,
                'size' => 2,
                'value0' => 15 + ($i * 2),
                'value1' => 19 + ($i * 2),
            ]);
            Item::factory()->create([
                'vnum' => '3' . $i,
                'name' => 'Crescent Sword+' . $i,
                'level' => 10,
                'type' => 0,
                'subtype' => 0,
                'size' => 2,
                'value0' => 20 + ($i * 2),
                'value1' => 24 + ($i * 2),
            ]);
            Item::factory()->create([
                'vnum' => '4' . $i,
                'name' => 'Bamboo Sword+' . $i,
                'level' => 15,
                'type' => 0,
                'subtype' => 0,
                'size' => 2,
                'value0' => 25 + ($i * 2),
                'value1' => 29 + ($i * 2),
            ]);
            Item::factory()->create([
                'vnum' => '5' . $i,
                'name' => 'Broad Sword+' . $i,
                'level' => 20,
                'type' => 0,
                'subtype' => 0,
                'size' => 2,
                'value0' => 30 + ($i * 2),
                'value1' => 34 + ($i * 2),
            ]);
            //twohand
            Item::factory()->create([
                'vnum' => '300' . $i,
                'name' => 'Glaive+' . $i,
                'level' => 1,
                'type' => 0,
                'subtype' => 1,
                'size' => 3,
                'value0' => 20 + ($i * 2),
                'value1' => 30 + ($i * 2),
            ]);
            Item::factory()->create([
                'vnum' => '301' . $i,
                'name' => 'Spear+' . $i,
                'level' => 5,
                'type' => 0,
                'subtype' => 1,
                'size' => 3,
                'value0' => 25 + ($i * 2),
                'value1' => 35 + ($i * 2),
            ]);
            Item::factory()->create([
                'vnum' => '302' . $i,
                'name' => 'Guillotine Blade+' . $i,
                'level' => 10,
                'type' => 0,
                'subtype' => 1,
                'size' => 3,
                'value0' => 30 + ($i * 2),
                'value1' => 40 + ($i * 2),
            ]);
            Item::factory()->create([
                'vnum' => '303' . $i,
                'name' => 'Spider Spear+' . $i,
                'level' => 15,
                'type' => 0,
                'subtype' => 1,
                'size' => 3,
                'value0' => 35 + ($i * 2),
                'value1' => 45 + ($i * 2),
            ]);
            Item::factory()->create([
                'vnum' => '304' . $i,
                'name' => 'Gisarme+' . $i,
                'level' => 20,
                'type' => 0,
                'subtype' => 1,
                'size' => 3,
                'value0' => 40 + ($i * 2),
                'value1' => 50 + ($i * 2),
            ]);
            //Dagger
            Item::factory()->create([
                'vnum' => '100' . $i,
                'name' => 'Dagger+' . $i,
                'level' => 1,
                'type' => 0,
                'subtype' => 2,
                'size' => 1,
                'value0' => 3 + ($i * 2),
                'value1' => 5 + ($i * 2),
            ]);
            Item::factory()->create([
                'vnum' => '101' . $i,
                'name' => 'Amija+' . $i,
                'level' => 5,
                'type' => 0,
                'subtype' => 2,
                'size' => 1,
                'value0' => 8 + ($i * 2),
                'value1' => 10 + ($i * 2),
            ]);
            Item::factory()->create([
                'vnum' => '102' . $i,
                'name' => 'Cobra Dagger+' . $i,
                'level' => 10,
                'type' => 0,
                'subtype' => 2,
                'size' => 1,
                'value0' => 13 + ($i * 2),
                'value1' => 15 + ($i * 2),
            ]);
            Item::factory()->create([
                'vnum' => '103' . $i,
                'name' => 'Nine Blades+' . $i,
                'level' => 15,
                'type' => 0,
                'subtype' => 2,
                'size' => 1,
                'value0' => 18 + ($i * 2),
                'value1' => 20 + ($i * 2),
            ]);
            Item::factory()->create([
                'vnum' => '104' . $i,
                'name' => 'Scissor Dagger+' . $i,
                'level' => 20,
                'type' => 0,
                'subtype' => 2,
                'size' => 1,
                'value0' => 23 + ($i * 2),
                'value1' => 25 + ($i * 2),
            ]);
            //Bow
            Item::factory()->create([
                'vnum' => '200' . $i,
                'name' => 'Bow+' . $i,
                'level' => 1,
                'type' => 0,
                'subtype' => 3,
                'size' => 2,
                'value0' => 73 + ($i * 2.5),
                'value1' => 82 + ($i * 2.5),
            ]);
            Item::factory()->create([
                'vnum' => '201' . $i,
                'name' => 'Long Bow+' . $i,
                'level' => 5,
                'type' => 0,
                'subtype' => 3,
                'size' => 2,
                'value0' => 78 + ($i * 2.5),
                'value1' => 87 + ($i * 2.5),
            ]);
            Item::factory()->create([
                'vnum' => '202' . $i,
                'name' => 'Composite Bow+' . $i,
                'level' => 10,
                'type' => 0,
                'subtype' => 3,
                'size' => 2,
                'value0' => 83 + ($i * 2.5),
                'value1' => 92 + ($i * 2.5),
            ]);
            Item::factory()->create([
                'vnum' => '203' . $i,
                'name' => 'Battle Bow+' . $i,
                'level' => 15,
                'type' => 0,
                'subtype' => 3,
                'size' => 2,
                'value0' => 88 + ($i * 2.5),
                'value1' => 97 + ($i * 2.5),
            ]);
            Item::factory()->create([
                'vnum' => '204' . $i,
                'name' => 'Horseback Long Bow+' . $i,
                'level' => 20,
                'type' => 0,
                'subtype' => 3,
                'size' => 2,
                'value0' => 93 + ($i * 2.5),
                'value1' => 102 + ($i * 2.5),
            ]);
            //Armor
            Item::factory()->create([
                'vnum' => '1120' . $i,
                'name' => 'Monk Plate Armour+' . $i,
                'level' => 1,
                'type' => 1,
                'subtype' => 0,
                'size' => 2,
                'value0' => 12 + ($i * 2),
            ]);
            Item::factory()->create([
                'vnum' => '1121' . $i,
                'name' => 'Iron Plate Armour+' . $i,
                'level' => 9,
                'type' => 1,
                'subtype' => 0,
                'size' => 2,
                'value0' => 21 + ($i * 2),
            ]);
            Item::factory()->create([
                'vnum' => '1140' . $i,
                'name' => 'Azure Suit+' . $i,
                'level' => 1,
                'type' => 1,
                'subtype' => 1,
                'size' => 2,
                'value0' => 12 + ($i * 2),
            ]);
            Item::factory()->create([
                'vnum' => '1141' . $i,
                'name' => 'Ivory Suit+' . $i,
                'level' => 9,
                'type' => 1,
                'subtype' => 1,
                'size' => 2,
                'value0' => 21 + ($i * 2),
            ]);
        }

    }

    public function missiontypes()
    {
        MissionType::factory()->create([
            'time' => 5,
            'monster1' => 101,
            'monster2' => 101,
            'monster3' => 101,
            'monster4' => 101,
            'monster5' => 101,
            'monster6' => 101,
            'monster7' => 101,
            'monster8' => 101,
            'monster9' => 101,
            'monster10' => 101,
            'gold' => 1000,
            'exp' => 250,
        ]);
        MissionType::factory()->create([
            'time' => 10,
            'monster1' => 101,
            'monster2' => 101,
            'monster3' => 101,
            'monster4' => 101,
            'monster5' => 102,
            'monster6' => 102,
            'monster7' => 102,
            'monster8' => 102,
            'monster9' => 102,
            'monster10' => 103,
            'gold' => 2500,
            'exp' => 350,
        ]);
    }

    public function monsters()
    {
        Monster::factory()->create([
            'id' => 1,
            'name' => 'Wildhund',
            'exp' => 50,
            'aw' => 22,
            'gold' => 50,
        ]);
        Monster::factory()->create([
            'id' => 2,
            'name' => 'Wolf',
            'level' => 3,
            'exp' => 60,
            'hp' => 1200,
            'aw' => 35,
            'gold' => 60,
        ]);
        Monster::factory()->create([
            'id' => 3,
            'name' => 'Blauwolf',
            'level' => 4,
            'exp' => 70,
            'hp' => 1400,
            'aw' => 40,
            'gold' => 70,
        ]);
        Monster::factory()->create([
            'id' => 4,
            'name' => 'Keiler',
            'level' => 6,
            'exp' => 80,
            'hp' => 1500,
            'aw' => 45,
            'gold' => 80,
        ]);
        Monster::factory()->create([
            'id' => 5,
            'name' => 'Bär',
            'level' => 7,
            'exp' => 100,
            'hp' => 2000,
            'aw' => 50,
            'gold' => 100,
        ]);
        Monster::factory()->create([
            'id' => 6,
            'name' => 'Lykos',
            'level' => 9,
            'exp' => 250,
            'hp' => 5000,
            'aw' => 75,
            'rank' => 2,
            'gold' => 250,
        ]);
    }
}
