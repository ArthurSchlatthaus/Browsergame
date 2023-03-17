<nav class="navbar statusbar fixed-bottom">
    <div id="navbarContent" style="display: none">
        <img style="position: absolute;top: 0;left: 0;" src="/images/statusbar/status.png">
        <div class="progress" style="width: 94px;position: absolute;top: 5px;left: 6px;height:8px;">
            <div style="width: 100%;" id="healthBar"
                 class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar"
                 aria-label="heathBar" aria-valuenow="1000"
                 aria-valuemin="0"
                 aria-valuemax="1000">100%
            </div>
        </div>
        <div class="progress" style="width: 94px;position: absolute;top: 17px;left: 6px;height:8px;">
            <div style="width: 100%;" id="manaBar"
                 class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar"
                 aria-label="manaBar" aria-valuenow="1000"
                 aria-valuemin="0"
                 aria-valuemax="1000">100%
            </div>
        </div>

        <img style="position: absolute;top: 1px;left: 105px;" src="/images/statusbar/exp.png">
        <img id="expBubble1"
             style="position: absolute;top: 8px;left: 110px; clip-path: inset(20px 0 0 0);"
             src="/images/statusbar/expbubble.png">
        <img id="expBubble2" style="position: absolute;top: 8px;left: 135px; clip-path: inset(20px 0 0 0);"
             src="/images/statusbar/expbubble.png">
        <img id="expBubble3" style="position: absolute;top: 8px;left: 160px; clip-path: inset(20px 0 0 0);"
             src="/images/statusbar/expbubble.png">
        <img id="expBubble4" style="position: absolute;top: 8px;left: 185px; clip-path: inset(20px 0 0 0);"
             src="/images/statusbar/expbubble.png">
        <div id="fightMenuDesktop" style="display:block;">
            <div id="activeFight" style="display: none">
                <div
                    style="position: absolute;left: 0;right: 0;margin-left: auto;margin-right: auto;width: 34px;height:34px;top: 3px">
                    <img src="/images/statusbar/att_normal.png">
                    <button id="attBtn_active"
                            onclick="if(!this.disabled){sendAttack()}"
                            style="display:none;position:absolute;left: 0;top:-2px;width:32px;height:32px;border-radius: 8px;background-color: transparent"></button>
                    <button id="attBtn_inActive" class="disabled"
                            style="display:none;position:absolute;left: 0;top:-2px;width:32px;height:32px;border-radius: 8px;background-color: #000;opacity: .5; pointer-events: none"></button>
                </div>
                <div id="skills">
                    <div id="skill1"
                         style="display: none;position: absolute;left: 66px;right: 0;margin-left: auto;margin-right: auto;width: 34px;height:34px;top: 3px">
                        <img id="skill1Img" src="images/skills/0_1.png">
                        <button type="submit"
                                id="skillBtn1_active"
                                style="position:absolute;left: 0;top:-2px;width:32px;height:32px;border-radius: 8px;background-color: transparent"></button>
                        <button type="submit"
                                id="skillBtn1_inActive"
                                style="position:absolute;left: 0;top:-2px;width:32px;height:32px;border-radius: 8px;background-color: #000;opacity: .5; pointer-events: none"></button>
                    </div>
                    <div id="skill2"
                         style="display: none;position: absolute;left: 132px;right: 0;margin-left: auto;margin-right: auto;width: 34px;height:34px;top: 3px">
                        <img id="skill2Img" src="images/skills/0_1.png">
                        <button type="submit"
                                id="skillBtn2_active"
                                style="position:absolute;left: 0;top:-2px;width:32px;height:32px;border-radius: 8px;background-color: transparent"></button>
                        <button type="submit"
                                id="skillBtn2_inActive"
                                style="position:absolute;left: 0;top:-2px;width:32px;height:32px;border-radius: 8px;background-color: #000;opacity: .5; pointer-events: none"></button>
                    </div>

                </div>
                <div id="healLife"
                     style="position: absolute;left: 198px;right: 0;margin-left: auto;margin-right: auto;width: 34px;height:34px;top: 3px">
                    <img src="/images/items/27001.png">
                    <button id="redPotion_active" onclick="sendHeal(redPotionVnum)"
                            style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>
                    <button id="redPotion_inActive"
                            class="disabled"
                            style="position:absolute;left: 11px;top:0;width:10px;height:32px;border-radius: 8px;background-color: #000;opacity: .5; pointer-events: none"></button>
                </div>
                <div id="healMana"
                     style="position: absolute;left: 264px;right: 0;margin-left: auto;margin-right: auto;width: 34px;height:34px;top: 3px">
                    <img src="/images/items/27004.png">
                    <button id="bluePotion_active" onclick="sendHeal(bluePotionVnum)"
                            style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>
                    <button id="bluePotion_inActive"
                            class="disabled"
                            style="position:absolute;left: 11px;top:0;width:10px;height:32px;border-radius: 8px;background-color: #000;opacity: .5; pointer-events: none"></button>
                </div>

                <div id="cancelFight"
                     style="position: absolute;left: 330px;right: 0;margin-left: auto;margin-right: auto;width: 34px;height:34px;top: 3px">
                    <img src="/images/close.png" width="30" height="30">
                    <button type="submit"
                            onclick="cancelFight()"
                            style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>
                </div>
            </div>
            <div id="openFight" style="display: none">
                <div
                    style="position: absolute;left: 0;right: 0;margin-left: auto;margin-right: auto;width: 34px;height:34px;top: 3px">
                    <img src="/images/statusbar/att_normal.png">
                    <button onclick="openFightWrapper()"
                            style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>
                </div>
                <div id="pvpContainer"
                     style="position: absolute;left: 70px;right: 0;margin-left: auto;margin-right: auto;width: 34px;height:34px;top: 3px">
                    <img src="/images/statusbar/pvp_normal.png">
                    <button type="submit"
                            title="PVP"
                            onclick="openWindowStatus('pvpModal')"
                            style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>
                </div>
            </div>
            <div id="closeFight"
                 style="display: none;position: absolute;left: 70px;right: 0;margin-left: auto;margin-right: auto;width: 34px;height:34px;top: 3px">
                <img src="/images/close.png" width="32" height="32">
                <button type="submit"
                        style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>
            </div>
            <div id="openMissionModal" style="display: none">
                <div
                    style="position: absolute;left: 0;right: 0;margin-left: auto;margin-right: auto;width: 34px;height:34px;top: 3px">
                    <img src="/images/statusbar/att_normal.png">
                    <button type="submit"
                            title="Missions"
                            onclick="openWindowStatus('missionsModal')"
                            style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>
                </div>
                <div id="pvpContainer"
                     style="position: absolute;left: 70px;right: 0;margin-left: auto;margin-right: auto;width: 34px;height:34px;top: 3px">
                    <img src="/images/statusbar/pvp_normal.png">
                    <button type="submit"
                            title="PVP"
                            onclick="openWindowStatus('pvpModal')"
                            style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>
                </div>
            </div>
        </div>
        <div id="fightMenuMobile" style="display: none; position: absolute; left: 55%;top:0" class="dropdown dropup">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButtonFightMobile"
                    data-bs-toggle="dropdown" aria-expanded="false"
                    style="background-color: transparent; border: none;">
                <img width="40px" src="/images/statusbar/att_normal.png">
            </button>
            <ul class="dropdown-menu" style="background-color: transparent; border: none"
                aria-labelledby="dropdownMenuButtonFightMobile">
                <div id="activeFight_Mobile" style="display: none">
                    <a class="dropdown-item"
                       style="position: relative;width: 34px;height:34px;top: 3px">
                        <img src="/images/statusbar/att_normal.png" style="position:absolute;left: 0;top:0;">
                        <button id="attBtn_mobile_active"
                                onclick="if(!this.disabled){sendAttack()}"
                                style="display:none;position:absolute;left: 0;top:-2px;width:32px;height:32px;border-radius: 8px;background-color: transparent"></button>
                        <button id="attBtn_mobile_inActive" class="disabled"
                                style="display:none;position:absolute;left: 0;top:-2px;width:32px;height:32px;border-radius: 8px;background-color: #000;opacity: .5; pointer-events: none"></button>
                    </a>
                    <a class="dropdown-item" id="skills">
                        <div id="skill1"
                             style="display: none;position: relative;width: 34px;height:34px;">
                            <img id="skill1Img" src="images/skills/0_1.png" style="position:absolute;left: 0;top:0;">
                            <button type="submit"
                                    id="skillBtn1_mobile_active"
                                    style="position:absolute;left: 0;top:-2px;width:32px;height:32px;border-radius: 8px;background-color: transparent"></button>
                            <button type="submit"
                                    id="skillBtn1_mobile_inActive"
                                    style="position:absolute;left: 0;top:-2px;width:32px;height:32px;border-radius: 8px;background-color: #000;opacity: .5; pointer-events: none"></button>
                        </div>
                        <div id="skill2"
                             style="display: none;position: relative;width: 34px;height:34px;">
                            <img id="skill2Img" src="images/skills/0_1.png" style="position:absolute;left: 0;top:0;">
                            <button type="submit"
                                    id="skillBtn2_mobile_active"
                                    style="position:absolute;left: 0;top:-2px;width:32px;height:32px;border-radius: 8px;background-color: transparent"></button>
                            <button type="submit"
                                    id="skillBtn2_mobile_inActive"
                                    style="position:absolute;left: 0;top:-2px;width:32px;height:32px;border-radius: 8px;background-color: #000;opacity: .5; pointer-events: none"></button>
                        </div>
                    </a>


                    <a class="dropdown-item" id="healLife"
                       style="position: relative;width: 34px;height:34px;">
                        <img src="/images/items/27001.png" style="position:absolute;left: 0;top:0;">
                        <button id="redPotion_active_mobile" onclick="sendHeal(redPotionVnum)"
                                style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>
                        <button id="redPotion_inActive_mobile"
                                class="disabled"
                                style="position:absolute;left: 11px;top:0;width:10px;height:32px;border-radius: 8px;background-color: #000;opacity: .5; pointer-events: none"></button>
                    </a>
                    <a class="dropdown-item" id="healMana"
                       style="position: relative;width: 34px;height:34px;">
                        <img src="/images/items/27004.png" style="position:absolute;left: 0;top:0;">
                        <button id="bluePotion_active_mobile" onclick="sendHeal(bluePotionVnum)"
                                style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>
                        <button id="bluePotion_inActive_mobile"
                                class="disabled"
                                style="position:absolute;left: 11px;top:0;width:10px;height:32px;border-radius: 8px;background-color: #000;opacity: .5; pointer-events: none"></button>
                    </a>
                    <a class="dropdown-item" id="cancelFight"
                       style="position: relative;width: 34px;height:34px;">
                        <img src="/images/close.png" width="30" height="30"
                             style="position:absolute;left: 0;top:0;">
                        <button type="submit"
                                onclick="cancelFight()"
                                style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>
                    </a>
                </div>
                <div id="openFight_Mobile" style="display: none">
                    <a class="dropdown-item" style="position: relative;width: 34px;height:34px;">
                        <img src="/images/statusbar/att_normal.png" style="position:absolute;left: 0;top:0;">
                        <button onclick="openFightWrapper()"
                                style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>

                    </a>
                    <a class="dropdown-item" style="position: relative;width: 34px;height:34px;">
                        <img src="/images/statusbar/pvp_normal.png" style="position:absolute;left: 0;top:0;">
                        <button type="submit"
                                title="PVP"
                                onclick="openWindowStatus('pvpModal')"
                                style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>

                    </a>
                </div>
                <div id="closeFight_Mobile">
                    <a class="dropdown-item"
                       style="display: none;position: relative;width: 34px;height:34px;top: 3px">
                        <img src="/images/close.png" width="32" height="32">
                        <button type="submit"
                                style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>
                    </a>
                </div>
                <div id="openMissionModal_Mobile" style="display: none">
                    <a class="dropdown-item" style="position: relative;width: 34px;height:34px;">
                        <img src="/images/statusbar/att_normal.png" style="position:absolute;left: 0;top:0;">
                        <button type="submit"
                                title="Missions"
                                onclick="openWindowStatus('missionsModal')"
                                style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>
                    </a>
                    <a class="dropdown-item" style="position: relative;width: 34px;height:34px;">
                        <img src="/images/statusbar/pvp_normal.png" style="position:absolute;left: 0;top:0;">
                        <button type="submit"
                                title="PVP"
                                onclick="openWindowStatus('pvpModal')"
                                style="position:absolute;left: 0;top:0;width:34px;height:34px;background-color: transparent"></button>
                    </a>
                </div>
            </ul>
        </div>
        <div id="mainMenuMobile" style="display: none; position: absolute; right: 25px;top:0" class="dropdown dropup">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButtonMainMobile"
                    data-bs-toggle="dropdown" aria-expanded="false"
                    style="background-color: transparent; border: none;">
                <img width="40px" src="/images/statusbar/toggle.png">
            </button>
            <ul class="dropdown-menu" style="background-color: transparent; border: none"
                aria-labelledby="dropdownMenuButtonMainMobile">

                <a class="dropdown-item">
                    <img src="/images/statusbar/char_normal.png"
                         onclick="loadElement('profileContainer')">
                </a>

                <a class="dropdown-item">
                    <img src="/images/statusbar/inv_normal.png"
                         onclick="loadElement('inventoryContainer')">
                </a>

                <div class="dropdown-item" style="position:relative;">
                    <a id="hasUnreadMsg_mobile" style="display: none;position: absolute;left: 25px"
                       onclick="openWindowStatus('messagesModal')">❗</a>
                    <img src="/images/statusbar/social_normal.png"
                         onclick="openWindowStatus('messagesModal')">
                </div>

                <a class="dropdown-item">
                    <img src="/images/statusbar/settings_normal.png"
                         onclick="openWindowStatus('settingsModal')">
                </a>

                <a class="dropdown-item">
                    <img src="/images/statusbar/ranking_normal.png"
                         onclick="openWindowStatus('rankingModal')">
                </a>

                <a class="dropdown-item" onclick="closeFightWrapper()" style="cursor: pointer;">
                    <img src="/images/statusbar/base_normal.png">
                </a>

            </ul>
        </div>
        <div id="mainMenuDesktop" style="display:block;">
            <div style="position: absolute;top: 3px;right: 164px;">
                <img src="/images/statusbar/char_normal.png">
                <button onclick="loadElement('profileContainer')"
                        style="position:absolute;left: 0;width:34px;height:34px;background-color: transparent"></button>
            </div>
            <div style="position: absolute;top: 3px;right: 132px;">
                <img src="/images/statusbar/inv_normal.png">
                <button onclick="loadElement('inventoryContainer')"
                        style="position:absolute;left: 0;width:34px;height:34px;background-color: transparent"></button>
            </div>
            <div style="position: absolute;top: 3px;right: 100px;">
                <img src="/images/statusbar/social_normal.png">
                <a id="hasUnreadMsg"
                   style="display: none;position: absolute; bottom: 0; left:50%;transform: translateX(-50%);">❗</a>
                <button style="position:absolute;left: 0;width:34px;height:34px;background-color: transparent"
                        onclick="openWindowStatus('messagesModal')"></button>
            </div>
            <div style="position: absolute;top: 3px;right: 68px;">
                <img src="/images/statusbar/settings_normal.png">
                <button style="position:absolute;left: 0;width:34px;height:34px;background-color: transparent"
                        onclick="openWindowStatus('settingsModal')"></button>
            </div>

            <div style="position: absolute;top: 3px;right: 36px;">
                <img src="/images/statusbar/ranking_normal.png" style="height: 32px;width: 32px">
                <button
                    style="position:absolute;left: 0;width:34px;height:34px;background-color: transparent"
                    onclick="openWindowStatus('rankingModal')"></button>
            </div>
            <a style="position: absolute;top: 3px;right: 4px;cursor: pointer;" onclick="closeFightWrapper()">
                <img src="/images/statusbar/base_normal.png" style="height: 32px;width: 32px">
            </a>
        </div>
    </div>
</nav>
<style>
    .statusbar {
        height: 38px;
        background-image: url('/images/statusbar/base.png');
        background-repeat: repeat;
    }

    button {
        border: none;
    }

    @media only screen and (max-device-width: 540px) {
        #mainMenuDesktop {
            display: none !important;
        }

        #mainMenuMobile {
            display: block !important;
        }

        #fightMenuDesktop {
            display: none !important;
        }

        #fightMenuMobile {
            display: block !important;
        }

        #attContainer_activeFight {
            left: 60px !important;
        }

        #pvpContainer {
            left: 95px !important;
        }
    }

    .progress {
        background-color: transparent;
        -webkit-box-shadow: none;
        box-shadow: none;
    }
</style>
