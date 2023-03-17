<div>
    <div style="position: relative;padding-bottom: 200px">
        <img alt="equipment" src="{{ URL::to('/') }}/images/equipment.jpg" style="position: absolute;top: 0;left: 0">

        <div id="weaponContainer" style="display: none;position: absolute;top: 5px;left: 10px">
            <button class="tooltipItem">
                <img id="weaponImg" class="itemSlot"
                     onclick="unEquipItem(window.equipmentEQ.weapon)"
                     alt="weapon_slot"
                     draggable="false"
                     src="/images/items/0.png"
                >
                <div id="weaponTitle" style="pointer-events:none;">
                    <p>
                        <span id="weaponTitleName"></span>
                    </p>
                    <p>
                        <span id="weaponTitleLevel"></span>
                    </p>
                    <a>
                        <span id="weaponTitleDamage"></span>
                    </a>
                    <br>
                    <p>
                        <span id="weaponTitleBonus1" style="display:none;"></span>
                        <span id="weaponTitleBonus2" style="display:none;"></span>
                        <span id="weaponTitleBonus3" style="display:none;"></span>
                        <span id="weaponTitleBonus4" style="display:none;"></span>
                        <span id="weaponTitleBonus5" style="display:none;"></span>
                    </p>
                    <p>
                        <span id="weaponTitleRace" style="display: none"></span>
                    </p>
                </div>
            </button>
        </div>

        <div id="bodyContainer" style="display: none;position: absolute;top: 38px;left: 48px">
            <button class="tooltipItem">
                <img id="bodyImg" class="itemSlot" onclick="unEquipItem(window.equipmentEQ.body)"
                     alt="body_slot"
                     draggable="false"
                     src="/images/items/0.png"
                >
                <div id="bodyTitle" style="pointer-events:none;">
                    <p>
                        <span id="bodyTitleName" style="display: none"></span>
                    </p>
                    <p>
                        <span id="bodyTitleLevel" style="display: none"></span>
                    </p>
                    <a>
                        <span id="bodyTitleDefense" style="display: none"></span>
                    </a>
                    <br>
                    <p>
                        <span id="bodyTitleBonus1" style="display: none"></span>
                        <span id="bodyTitleBonus2" style="display: none"></span>
                        <span id="bodyTitleBonus3" style="display: none"></span>
                        <span id="bodyTitleBonus4" style="display: none"></span>
                        <span id="bodyTitleBonus5" style="display: none"></span>
                    </p>
                    <p>
                        <span id="bodyTitleRace" style="display: none"></span>
                    </p>
                </div>
            </button>
        </div>

    </div>
</div>
