<div id="fightDmgContainerLayout" style="display: none;">
    <div class="d-flex flex-column">
        <div id="fightDmgContainer" class="d-flex flex-row justify-content-center"
             style="width: 100%;position:absolute;top:0;left: 50%; transform: translateX(-50%)">
            <div class="d-flex flex-column ">
                @for($i=0;$i<6;$i++)
                    <div>
                        @for($j=0;$j<5;$j++)
                            <img id="monsterDmg{{$i.'_'.$j}}"
                                 src=""
                                 height="32"
                                 width="16"
                                 style="visibility: hidden">
                        @endfor
                    </div>
                @endfor
            </div>
            <div class="d-flex flex-column ">
                @for($i=0;$i<8;$i++)
                    <div>
                        @for($j=0;$j<9;$j++)
                            <img id="playerDmg{{$i.'_'.$j}}"
                                 src=""
                                 height="32"
                                 width="16"
                                 style="visibility: hidden">
                        @endfor
                    </div>
                @endfor
            </div>
            <div class="d-flex" id="monsterInfo"
                 style="position: absolute;top:80%;left: 60%; transform: translateX(-60%)">
                <div style="position: relative;color: lightgray;padding-right: 10px" id="monster1Info">
                    <img src="/images/fight/monsterInfo_sm.png" width="205" height="40">
                    <div style="height: 36px; position:absolute;top: 0;left: 5px">
                        <a style="font-size: 10px"
                           id="monster1Label">-</a>
                        <div class="progress" style="width: 190px;height: 12px;margin-left: 3px">
                            <div style="width: 100%;"
                                 id="healthBarMonster1"
                                 class="progress-bar bg-danger progress-bar-striped progress-bar-animated"
                                 role="progressbar"
                                 aria-label="healthBarMonster1"
                                 aria-valuenow="100"
                                 aria-valuemin="0"
                                 aria-valuemax="100">100%
                            </div>
                        </div>
                        <input id="monster1Hp" type="hidden" value="1000">

                    </div>

                </div>
                <div style="position: relative;color: lightgray;padding-right: 10px" id="monster2Info">
                    <img src="/images/fight/monsterInfo_sm.png" width="205" height="40">
                    <div style="height: 36px; position:absolute;top: 0;left: 5px">
                        <a style="font-size: 10px" id="monster2Label">-</a>
                        <div class="progress" style="width: 190px;height: 12px;margin-left: 3px">
                            <div style="width: 100%;"
                                 id="healthBarMonster2"
                                 class="progress-bar bg-danger progress-bar-striped progress-bar-animated"
                                 role="progressbar"
                                 aria-label="healthBarMonster2"
                                 aria-valuenow="100"
                                 aria-valuemin="0"
                                 aria-valuemax="100">100%
                            </div>
                        </div>
                        <input id="monster2Hp" type="hidden" value="1000">
                    </div>

                </div>
                <div style="position: relative;color: lightgray" id="monster3Info">
                    <img src="/images/fight/monsterInfo_sm.png" width="205" height="40">
                    <div style="height: 36px; position:absolute;top: 0;left: 5px">
                        <a style="font-size: 10px" id="monster3Label">-</a>
                        <div class="progress" style="width: 190px;height: 12px;margin-left: 3px">
                            <div style="width: 100%;"
                                 id="healthBarMonster3"
                                 class="progress-bar bg-danger progress-bar-striped progress-bar-animated"
                                 role="progressbar"
                                 aria-label="healthBarMonster3"
                                 aria-valuenow="100"
                                 aria-valuemin="0"
                                 aria-valuemax="100">100%
                            </div>
                        </div>
                        <input id="monster3Hp" type="hidden" value="1000">
                    </div>
                </div>
            </div>
        </div>
        <div id="fightAnimationContainer" style="position: absolute;top:40%;left: 40%; transform: translate(-40%)">
            <x-fightanimation></x-fightanimation>
        </div>
        <div style="position:absolute;bottom: 30px">
            <h6 id="roundCount" style="backdrop-filter: blur(10px);"></h6>
            <h6 id="buffCount" style="backdrop-filter: blur(10px);"></h6>
        </div>
    </div>
</div>
<script>

</script>
<style>
    .container {
        display: flex;
    }

    @media screen and (max-width: 990px) {
        .container {
            flex-wrap: wrap;
        }

        #fightDmgContainer {
            justify-content: start;
            align-items: start;
        }

        #fightContainer {
            margin-left: -100px;
        }
    }
</style>
