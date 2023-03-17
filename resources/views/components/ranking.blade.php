<script>
    let playerRanking = null
    let modelPath = null
    let model = null
    let weaponPath = null
    let weapon = null
    let animationPath = null
    let animation = null
    let lastFight = null
    let equipment = null
    let hair = null
    let won = null
    let lost = null
    let won_pvp = null
    let lost_pvp = null
    let currentPage = 0
    $(function () {
        $(".pagination a").click(function () {
            return call_post_func($(this).attr('href'));
        });
    });

    function call_post_func(href) {
        post_this(href)
        return false;
    }

    function post_this(page_num) {
        $.post("/ranking", {
                "_token": "{{ csrf_token() }}",
                "page_num": page_num
            },
            function (result) {
                if (result.data.length < 1) {
                    return
                }
                currentPage = result.current_page
                document.getElementById("prev").setAttribute("href", Math.max(currentPage - 1, 0));
                document.getElementById("next").setAttribute("href", currentPage + 1);
                document.getElementById("rankingTableContent").innerHTML = '';
                result.data.forEach((player, index) => {
                    let tr = document.createElement("tr")
                    let place = document.createElement("td")
                    if (result.current_page > 1) {
                        if (index < 9) {
                            place.innerText = (result.current_page - 1) + '' + (index + 1)
                        } else {
                            place.innerText = (result.current_page) + '0'
                        }
                    } else {
                        if (index === 0) {
                            place.innerHTML = '<img height="25" src="images/ranking/gold.png"/>'
                        } else if (index === 1) {
                            place.innerHTML = '<img height="25" src="images/ranking/silver.png"/>'
                        } else if (index === 2) {
                            place.innerHTML = '<img height="25" src="images/ranking/bronze.png"/>'
                        } else {
                            place.innerText = index + 1
                        }
                    }
                    let name = document.createElement("td")
                    let button = document.createElement("button")
                    button.id = "rankingPlayerBtn"
                    button.innerText = player.name
                    button.onclick = function () {
                        getPlayerData(player.name)
                    }
                    name.appendChild(button)
                    let level = document.createElement("td")
                    level.innerText = player.level
                    let exp = document.createElement("td")
                    exp.innerText = player.exp
                    let gold = document.createElement("td")
                    gold.innerText = player.gold
                    gold.className = "largeScreenRanking"
                    let face = document.createElement("td")
                    if (player.race === 1) {
                        face.innerHTML = '<img height="25" src="images/face/warrior.png" alt=""/>'
                    } else if (player.race === 2) {
                        face.innerHTML = '<img height="25" src="images/face/ninja.png" alt=""/>'
                    } else if (player.race === 3) {
                        face.innerHTML = '<img height="25" src="images/face/sura.png" alt=""/>'
                    } else if (player.race === 4) {
                        face.innerHTML = '<img height="25" src="images/face/shaman.png" alt=""/>'
                    }
                    face.className = "largeScreenRanking"
                    tr.appendChild(place)
                    tr.appendChild(name)
                    tr.appendChild(level)
                    tr.appendChild(exp)
                    tr.appendChild(gold)
                    tr.appendChild(face)
                    document.getElementById("rankingTableContent").appendChild(tr)
                })
            })
    }

    post_this(1)
</script>
<div id="rankingTable" class="table-responsive-sm"
     style="display: block;overflow-x: auto;overflow-y: hidden">
    <table id="ranking" class="table" style="color: lightgrey;">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ __('custom.ranking_name') }}</th>
            <th>{{ __('custom.ranking_level') }}</th>
            <th>{{ __('custom.ranking_exp') }}</th>
            <th class="largeScreenRanking">{{ __('custom.ranking_gold') }}</th>
            <th class="largeScreenRanking">{{ __('custom.ranking_race') }}</th>
        </tr>
        </thead>
        <tbody id="rankingTableContent">
        </tbody>
    </table>
    <div class="pagination d-flex justify-content-center">
        <a id="prev" href="0">&laquo;</a>
        <a id="next" href="1">&raquo;</a>
    </div>
</div>
<style>
    .pagination {
        display: inline-block;
    }

    .pagination a {
        color: lightgray;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
    }

    #rankingPlayerBtn {
        color: lightgrey;
        background: transparent;
        border: none !important;
    }
</style>
<div id="rankingPlayer" style="display: none;width: 500px; height:350px;position: relative">
    <div style="display: flex;position: relative">
        <div id="npcContainer" style="height: 280px;"></div>
        <div class="table-responsive" style="position: absolute;left: 35%; top:-50px">
            <table class="table table-borderless"
                   style="color: lightgray;">
                <tbody>
                <tr>
                    <th scope="col">{{__('custom.ranking_name')}}</th>
                    <td id="ranking_profile_name">-</td>
                </tr>
                <tr>
                    <th scope="col">{{__('custom.ranking_level')}}</th>
                    <td id="ranking_profile_level">0</td>
                </tr>
                <tr>
                    <th scope="col">{{__('custom.ranking_exp')}}</th>
                    <td id="ranking_profile_exp">0</td>
                </tr>
                <tr>
                    <th scope="col">{{__('custom.ranking_gold')}}</th>
                    <td id="ranking_profile_gold">0</td>
                </tr>
                <tr>
                    <th scope="col">{{__('custom.last_fight_dmg')}}</th>
                    <td id="ranking_profile_dmg">0</td>
                </tr>
                <tr>
                    <th class="text-nowrap" scope="col">{{__('custom.last_fight_date')}}</th>
                    <td class="text-nowrap" id="ranking_profile_date">0</td>
                </tr>
                <tr>
                    <th class="text-nowrap" scope="col">{{__('custom.won_fights')}}</th>
                    <td id="ranking_profile_won">0</td>
                </tr>
                <tr>
                    <th class="text-nowrap" scope="col">{{__('custom.loose_fights')}}</th>
                    <td id="ranking_profile_loose">0</td>
                </tr>
                <tr>
                    <th class="text-nowrap" scope="col">{{__('custom.won_fights').' PvP'}}</th>
                    <td id="ranking_profile_won_pvp">0</td>
                </tr>
                <tr>
                    <th class="text-nowrap" scope="col">{{__('custom.loose_fights').' PvP'}}</th>
                    <td id="ranking_profile_loose_pvp">0</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div style="position: relative">
        <div style="position: absolute; left: -10px; bottom: -80px;">
            <img src="images/button_dark_120_2.png">
            <button style="width: 110px;position: absolute; top:4px;left:4px; color: lightgray"
                    onclick="document.getElementById('rankingPlayer').style.display='none';document.getElementById('rankingTable').style.display='block'">
                {{__('custom.delete_player_cancelButton')}}
            </button>
        </div>
        <input type="hidden" id="defenderName" value="">

        <div style="position: absolute; left: -10px; bottom: -40px;">
            <img src="images/button_dark_120_2.png">
            <button id="startPvpBtn" style="width: 110px;position: absolute; top:4px;left:4px; color: lightgray"
                    onclick="startPvp()">
                Fight
            </button>
        </div>
    </div>

    <script type="module">
        import {init, animate, setVars, setCamPos} from "/js/threejs/rankingProfile.js";

        window.loadPlayer = function loadPlayer() {
            setVars(modelPath, model, weaponPath, weapon, animationPath, animation, hair);
            setCamPos(0, 20, 400)
            init();
            animate();
        }
    </script>
    <script>
        function getPlayerData(name) {
            $.ajax({
                url: "/player/" + name,
                method: 'GET',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    playerName: name
                },
                success: function (result) {
                    if (result != null) {
                        playerRanking = result.rankingPlayer
                        modelPath = result.modelPath
                        model = result.model
                        weaponPath = result.weaponPath
                        weapon = result.weapon
                        animationPath = result.animationPath
                        animation = result.animation
                        lastFight = result.lastFight
                        equipment = result.equipment
                        hair = 'SK_Hair_1_' + result.rankingPlayer.hair + '.fbx'
                        won = result.battlesWon
                        lost = result.battlesLost
                        won_pvp = result.battlesWonPvp
                        lost_pvp = result.battlesLostPvp
                        let dateT = '-'
                        let dmgAvg = '-'
                        if (lastFight != null) {
                            dmgAvg = lastFight.dmgAvg

                            let date = new Date(lastFight.start_at)
                            let day = date.getDate();
                            let month = date.getMonth();
                            month = month + 1;
                            if ((String(day)).length === 1) {
                                day = '0' + day;
                            }
                            if ((String(month)).length === 1) {
                                month = '0' + month;
                            }
                            let hour = date.getHours()
                            let minute = date.getMinutes()
                            let minuteFormatted = minute < 10 ? "0" + minute : minute
                            dateT = hour + ':' + minuteFormatted + ' ' + day + '.' + month + '.' + date.getFullYear();
                        }
                        if (playerRanking) {
                            document.getElementById('rankingTable').style.display = 'none'
                            document.getElementById('rankingPlayer').style.display = 'block'
                            window.loadPlayer()
                            document.getElementById('ranking_profile_name').innerText = playerRanking.name
                            document.getElementById('ranking_profile_level').innerText = playerRanking.level
                            document.getElementById('ranking_profile_exp').innerText = playerRanking.exp + '/' + (playerRanking.level * 200)
                            document.getElementById('ranking_profile_gold').innerText = playerRanking.gold
                            document.getElementById('ranking_profile_dmg').innerText = dmgAvg
                            document.getElementById('ranking_profile_date').innerText = dateT
                            document.getElementById('ranking_profile_won').innerText = won
                            document.getElementById('ranking_profile_loose').innerText = lost
                            document.getElementById('ranking_profile_won_pvp').innerText = won_pvp
                            document.getElementById('ranking_profile_loose_pvp').innerText = lost_pvp
                            document.getElementById('defenderName').value = playerRanking.name
                            if (parseInt(playerRanking.level) < 5 || parseInt('{{auth()->user()->player->level}}') < 5 || parseInt(playerRanking.level) + 5 <= parseInt('{{auth()->user()->player->level}}') || parseInt(playerRanking.level) - 5 >= parseInt('{{auth()->user()->player->level}}')) {
                                document.getElementById('startPvpBtn').classList.add('disabled');
                            } else {
                                document.getElementById('startPvpBtn').classList.remove('disabled');
                            }
                            @if(str_contains(URL::current(),'127.0.0.1') || str_contains(URL::current(),'dev.ludus2.de'))
                            document.getElementById('startPvpBtn').classList.remove('disabled');
                            @endif
                        }

                    }
                }
            });
        }

        function startPvp() {
            $.ajax({
                url: "{{url('startPvp')}}",
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    defenderName: document.getElementById('defenderName').value
                },
                success: function (result) {
                    window.setGlobalPlayer(result.player)
                    if (typeof result.pvp != 'undefined') {
                        window.setGlobalPvPFight(result.pvp)
                    }
                    window.setPlayerStatusBar()
                    window.setPlayerValues()
                    window.closeAllWindows()
                    window.closeFightWrapper()
                    if (typeof result.pvp != 'undefined') {
                        window.initPvpValues()
                        window.cachePvPAnimation()
                        setTimeout(() => {
                            window.openWindowStatus('pvpModal')
                        }, 1000);
                    }
                }
            })
        }
    </script>

</div>
