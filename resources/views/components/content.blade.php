@if(isset(auth()->user()->player))
    @php
        $player=auth()->user()->player;
        $pvp = $player->getActivePvp(true)??$player->getLastPvp();
        if(isset($pvp)){
            $defender = \App\Models\Player::find($pvp->defenderId);
        }
    @endphp
    @if(auth()->user()->player->isLoggedIn)
        <section id="loading-screen" style="display: none">
            <div id="loader"></div>
            <a id="loadingText"
               style="color: lightgray;position: relative;left: 50%;top: 60%;"></a>
        </section>
        <style>
            #loading-screen {
                position: absolute;
                z-index: 2;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: #000000;
                opacity: 1;
                transition: 1s opacity;
            }

            #loading-screen.fade-out {
                opacity: 0;
            }

            #loader {
                display: block;
                position: relative;
                left: 50%;
                top: 50%;
                width: 150px;
                height: 150px;
                margin: -75px 0 0 -75px;
                border-radius: 50%;
                border: 3px solid transparent;
                border-top-color: #9370DB;
                -webkit-animation: spin 2s linear infinite;
                animation: spin 2s linear infinite;
            }

            #loader:before {
                content: "";
                position: absolute;
                top: 5px;
                left: 5px;
                right: 5px;
                bottom: 5px;
                border-radius: 50%;
                border: 3px solid transparent;
                border-top-color: #BA55D3;
                -webkit-animation: spin 3s linear infinite;
                animation: spin 3s linear infinite;
            }

            #loader:after {
                content: "";
                position: absolute;
                top: 15px;
                left: 15px;
                right: 15px;
                bottom: 15px;
                border-radius: 50%;
                border: 3px solid transparent;
                border-top-color: #FF00FF;
                -webkit-animation: spin 1.5s linear infinite;
                animation: spin 1.5s linear infinite;
            }

            @-webkit-keyframes spin {
                0% {
                    -webkit-transform: rotate(0deg);
                    -ms-transform: rotate(0deg);
                    transform: rotate(0deg);
                }
                100% {
                    -webkit-transform: rotate(360deg);
                    -ms-transform: rotate(360deg);
                    transform: rotate(360deg);
                }
            }

            @keyframes spin {
                0% {
                    -webkit-transform: rotate(0deg);
                    -ms-transform: rotate(0deg);
                    transform: rotate(0deg);
                }
                100% {
                    -webkit-transform: rotate(360deg);
                    -ms-transform: rotate(360deg);
                    transform: rotate(360deg);
                }
            }
        </style>

        <div id="fightWrapper" style="visibility: hidden">
            <x-fight></x-fight>
        </div>
        <div id="mainWrapper" style="visibility: visible">
            <div id="rendererMain" style="height: 50vh"></div>
            <div id="rendererGeneral" style="height: 12vh;"
                 ondrop="dropGeneral(event)"
                 ondragover="allowDrop(event)">
                <input type="hidden" name="item_id" id="itemIdGeneral" value="">
                <div id="tooltipItemGeneral" class="rounded text-center"
                     style="background-color: #212529;color:lightgray;position: absolute;top:-100px;display: none;width: 200px">
                    <div id="tooltipItemTextGeneral"></div>
                    <button class="btn btn-outline-danger btn-block p-2 m-2"
                            onclick="document.getElementById('tooltipItemGeneral').style.display='none';">
                        ⤫
                    </button>
                    <button class="btn btn-outline-success btn-block p-2 m-2"
                            onclick="document.getElementById('tooltipItemGeneral').style.display='none';sellItem(document.getElementById('itemIdGeneral').value)">
                        ✓
                    </button>
                </div>
            </div>
            <div id="rendererBlack" style="height: 12vh;"
                 ondrop="dropBlack(event)"
                 ondragover="allowDrop(event)">
                <input type="hidden" name="item_id" id="itemIdBlack" value="">
                <div id="tooltipItemBlack" class="rounded text-center"
                     style="background-color: #212529;color:lightgray;position: absolute;top:-100px;display: none;width: 200px">
                    <div id="tooltipItemTextBlack"></div>
                    <button class="btn btn-outline-danger btn-block p-2 m-2"
                            onclick="document.getElementById('tooltipItemBlack').style.display='none';">
                        ⤫
                    </button>
                    <button class="btn btn-outline-success btn-block p-2 m-2"
                            onclick="document.getElementById('tooltipItemBlack').style.display='none';upgradeItem(document.getElementById('itemIdBlack').value)">
                        ✓
                    </button>
                </div>
            </div>
            <div id="rendererWeapon" style="height: 12vh;"
                 ondrop="dropWeapon(event)"
                 ondragover="allowDrop(event)">
                <input type="hidden" name="item_id" id="itemIdWeapon" value="">
                <div id="tooltipItemWeapon" class="rounded text-center"
                     style="background-color: #212529;color:lightgray;position: absolute;top:-100px;display: none;width: 200px">
                    <div id="tooltipItemTextWeapon"></div>
                    <button class="btn btn-outline-danger btn-block p-2 m-2"
                            onclick="document.getElementById('tooltipItemWeapon').style.display='none';">
                        ⤫
                    </button>
                    <button class="btn btn-outline-success btn-block p-2 m-2"
                            onclick="document.getElementById('tooltipItemWeapon').style.display='none';sellItem(document.getElementById('itemIdWeapon').value)">
                        ✓
                    </button>
                </div>
            </div>
            <div id="rendererArmor" style="height: 12vh;"
                 ondrop="dropArmor(event)"
                 ondragover="allowDrop(event)">
                <input type="hidden" name="item_id" id="itemIdArmor" value="">
                <div id="tooltipItemArmor" class="rounded text-center"
                     style="background-color: #212529;color:lightgray;position: absolute;top:-100px;display: none;width: 200px">
                    <div id="tooltipItemTextArmor"></div>
                    <button class="btn btn-outline-danger btn-block p-2 m-2"
                            onclick="document.getElementById('tooltipItemArmor').style.display='none';">
                        ⤫
                    </button>
                    <button class="btn btn-outline-success btn-block p-2 m-2"
                            onclick="document.getElementById('tooltipItemArmor').style.display='none';sellItem(document.getElementById('itemIdArmor').value)">
                        ✓
                    </button>
                </div>
            </div>
            <div id="generalStoreContainer" style="display: none">
                <x-generalstore></x-generalstore>
            </div>
            <div id="weaponStoreContainer" style="display: none">
                <x-weaponstore></x-weaponstore>
            </div>
            <div id="armorStoreContainer" style="display: none">
                <x-armorstore></x-armorstore>
            </div>
        </div>

        <div id="profileContainer" style="display: none">
            <x-profile></x-profile>
        </div>
        <div id="inventoryContainer" style="display: none">
            <x-inventory></x-inventory>
        </div>
        <x-statusbar></x-statusbar>

        <div class="modal fade" id="changelogModal" tabindex="-1" aria-labelledby="changelogModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="min-width: 800px">
                    <div class="modal-body" id="changelogModalBody">
                        <x-changelog></x-changelog>
                    </div>
                    <div class="modal-footer justify-content-center justify-content-evenly">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="settingsModal"
             style="z-index: 999;left: 50%;transform: translateX(-50%);display: none;padding: 8px;color: lightgray;background-image: url('/images/board_2/board_base.png');border-radius: 10px;top:0;position: absolute; ">
            <div
                style="background-image: url('/images/board_2/board_corner_lefttop.png');position: absolute; top: 0; left: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_left.png');position: absolute; top: 32px; bottom: 32px;left: 0; width: 32px; "></div>
            <div
                style="background-image: url('/images/board_2/board_corner_righttop.png');position: absolute; top: 0; right: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_right.png');position: absolute; top: 32px; bottom: 32px;right: 0; width: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_corner_leftbottom.png');position: absolute; bottom: 0; left: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_top.png');position: absolute; top: 0; left: 32px;right: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_corner_rightbottom.png');position: absolute; bottom: 0; right: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_bottom.png');position: absolute; left: 32px; bottom: 0;right: 32px;height: 32px;"></div>
            <button style="border: none;background-color: transparent;" onclick="window.closeAllWindows()">
                <img src="/images/close.png"
                     style="position: absolute;right: 2px;top: 1px; width: 25px;z-index: 999">
            </button>
            <x-settings></x-settings>
        </div>

        <div id="messagesModal"
             style="z-index: 999;overflow:scroll;display: none;padding: 28px;color: lightgray;background-image: url('/images/board_2/board_base.png');border-radius: 10px;position: absolute;bottom:0;right:0;top: 0;left: 0; ">
            <div
                style="background-image: url('/images/board_2/board_corner_lefttop.png');position: absolute; top: 0; left: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_left.png');position: absolute; top: 32px; bottom: 32px;left: 0; width: 32px; "></div>
            <div
                style="background-image: url('/images/board_2/board_corner_righttop.png');position: absolute; top: 0; right: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_right.png');position: absolute; top: 32px; bottom: 32px;right: 0; width: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_corner_leftbottom.png');position: absolute; bottom: 0; left: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_top.png');position: absolute; top: 0; left: 32px;right: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_corner_rightbottom.png');position: absolute; bottom: 0; right: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_bottom.png');position: absolute; left: 32px; bottom: 0;right: 32px;height: 32px;"></div>
            <button style="border: none;background-color: transparent;" onclick="window.closeAllWindows()">
                <img src="/images/close.png"
                     style="position: absolute;right: 2px;top: 1px; width: 25px;z-index: 999">
            </button>
            <x-messages></x-messages>
        </div>

        <div id="rankingModal"
             style="z-index: 999;left: 50%;transform: translateX(-50%);top: 0;display: none;padding: 28px;color: lightgray;background-image: url('/images/board_2/board_base.png');border-radius: 10px;position:absolute;">
            <div
                style="background-image: url('/images/board_2/board_corner_lefttop.png');position: absolute; top: 0; left: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_left.png');position: absolute; top: 32px; bottom: 32px;left: 0; width: 32px; "></div>
            <div
                style="background-image: url('/images/board_2/board_corner_righttop.png');position: absolute; top: 0; right: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_right.png');position: absolute; top: 32px; bottom: 32px;right: 0; width: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_corner_leftbottom.png');position: absolute; bottom: 0; left: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_top.png');position: absolute; top: 0; left: 32px;right: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_corner_rightbottom.png');position: absolute; bottom: 0; right: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_bottom.png');position: absolute; left: 32px; bottom: 0;right: 32px;height: 32px;"></div>
            <button style="border: none;background-color: transparent;" onclick="window.closeAllWindows()">
                <img src="/images/close.png"
                     style="position: absolute;right: 2px;top: 1px; width: 25px;z-index: 999">
            </button>
            <x-ranking></x-ranking>
        </div>

        <div id="pvpModal"
             style="z-index: 999;overflow:hidden;left: 50%;transform: translateX(-50%);top:0;display: none;padding: 28px;color: lightgray;background-image: url('/images/board_2/board_base.png');border-radius: 10px;position: absolute; ">
            <div
                style="background-image: url('/images/board_2/board_corner_lefttop.png');position: absolute; top: 0; left: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_left.png');position: absolute; top: 32px; bottom: 32px;left: 0; width: 32px; "></div>
            <div
                style="background-image: url('/images/board_2/board_corner_righttop.png');position: absolute; top: 0; right: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_right.png');position: absolute; top: 32px; bottom: 32px;right: 0; width: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_corner_leftbottom.png');position: absolute; bottom: 0; left: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_top.png');position: absolute; top: 0; left: 32px;right: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_corner_rightbottom.png');position: absolute; bottom: 0; right: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_bottom.png');position: absolute; left: 32px; bottom: 0;right: 32px;height: 32px;"></div>
            <button style="border: none;background-color: transparent;"
                    onclick="window.closeAllWindows();window.initPvpValues()">
                <img src="/images/close.png"
                     style="position: absolute;right: 2px;top: 1px; width: 25px;z-index: 999">
            </button>
            <x-pvp></x-pvp>
        </div>
        <div id="missionsModal"
             style="z-index: 999;overflow:hidden;overflow-y: scroll;left: 50%;transform: translateX(-50%);top:0;display: none;padding: 28px;color: lightgray;background-image: url('/images/board_2/board_base.png');border-radius: 10px;position: absolute; ">
            <div
                style="background-image: url('/images/board_2/board_corner_lefttop.png');position: absolute; top: 0; left: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_left.png');position: absolute; top: 32px; bottom: 32px;left: 0; width: 32px; "></div>
            <div
                style="background-image: url('/images/board_2/board_corner_righttop.png');position: absolute; top: 0; right: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_right.png');position: absolute; top: 32px; bottom: 32px;right: 0; width: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_corner_leftbottom.png');position: absolute; bottom: 0; left: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_top.png');position: absolute; top: 0; left: 32px;right: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_corner_rightbottom.png');position: absolute; bottom: 0; right: 0; width: 32px; height: 32px;"></div>
            <div
                style="background-image: url('/images/board_2/board_line_bottom.png');position: absolute; left: 32px; bottom: 0;right: 32px;height: 32px;"></div>
            <button style="border: none;background-color: transparent;" onclick="window.closeAllWindows()">
                <img src="/images/close.png"
                     style="position: absolute;right: 2px;top: 1px; width: 25px;z-index: 999">
            </button>
            <x-missions></x-missions>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body" id="deleteModalBody">
                        <a>{{__('custom.delete_player_text')}}</a>
                    </div>
                    <div class="modal-footer justify-content-center justify-content-evenly">
                        <button type="button" class="btn btn-success"
                                data-bs-dismiss="modal">{{__('custom.delete_player_cancelButton')}}</button>
                        <form method="POST" action="{{url('deletePlayer')}}">
                            @csrf
                            <input name="player_id" id="shopIndex" type="hidden"
                                   value="{{auth()->user()->player->id}}">
                            <button type="submit"
                                    class="btn btn-danger">{{__('custom.delete_player_okButton')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>

        </script>
        <style>
            #missionsModal::-webkit-scrollbar {
                display: none;
            }

            #missionsModal {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            #rendererMain {
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
                top: 25%;
            }

            #rendererGeneral {
                position: absolute;
                top: 30%;
                left: 25%;
            }

            #rendererBlack {
                position: absolute;
                top: 32%;
                left: 10%;
            }

            #rendererWeapon {
                position: absolute;
                top: 30%;
                right: 25%;
            }

            #rendererArmor {
                position: absolute;
                top: 32%;
                right: 10%;
            }
        </style>
    @else
        <x-selectplayer></x-selectplayer>
    @endif
@else
    <x-registerplayer></x-registerplayer>
@endif
