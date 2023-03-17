<div style="display: flex;flex-direction: row; justify-content: center; align-items: center">
    <div id="missionTable" class="table-responsive" style="margin-top: -30px;">
        @php
            $missions=auth()->user()->player->missions;
            $groups=\App\Models\Group::all();
        @endphp

        <table class="table table-borderless" style="color: lightgrey; ">
            <thead>
            <tr>
                <th scope="col">Map1</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    @foreach($groups as $group)
                        <div class="p-2" style="position: relative">
                            @php
                                if(isset($group->monster1Id)){
                                    $monster1=\App\Models\Monster::find($group->monster1Id);
                                }
                                if(isset($group->monster2Id)){
                                    $monster2=\App\Models\Monster::find($group->monster2Id);
                                }
                                if(isset($group->monster3Id)){
                                    $monster3=\App\Models\Monster::find($group->monster3Id);
                                }
                                $avgLevel="/";
                                if(isset($monster1) && isset($monster2) && isset($monster3)){
                                    $avgLevel=number_format(($monster1->level +$monster2->level+$monster3->level)/3);
                                }
                            @endphp
                            <img src="images/button_dark_120_2.png">
                            <button style="color: lightgrey;font-size: 9pt;position: absolute; left:30px;top:5px;"
                                    onclick="sendFight('{{$group->id}}')">
                                {{ __('custom.fight') . ' ' . __('custom.group').' '.$group->id}}
                                <br>{{' ( '.__('custom.level').$avgLevel.' )'}}
                                @if($group->id === 10)
                                    <a> 💀</a>
                                @endif
                            </button>
                        </div>
                    @endforeach
                </td>
            </tr>
            </tbody>
        </table>

    </div>
    <script>
        function sendFight(groupId) {
            $.ajax({
                url: "{{url('sendFight')}}",
                method: 'POST',
                data: {
                    groupId: groupId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    if (result.player != null) {
                        window.setGlobalPlayer(result.player)
                        window.setPlayerHpSp(result.player, false)
                        window.setPlayerValues()
                    }
                    if (result.fight != null) {
                        window.setGlobalFight(result.fight)
                        window.enableAttBtn()
                        document.getElementById('fightWrapper').style.visibility = 'visible'
                        document.getElementById('fightDmgContainerLayout').style.display = 'block'
                        document.getElementById('mainWrapper').style.visibility = 'hidden'
                    } else {
                        document.getElementById('fightDmgContainerLayout').style.display = 'none'
                    }
                    if (result.monsters != null) {
                        window.setGlobalMonsters(result.monsters)
                        window.loadFightMonsterInfos()
                        window.initFightAnimation()
                    }
                    if(result.monsters != null && result.fight != null){
                        window.loadFightMonsterInfos()
                    }
                    window.closeAllWindows()
                    window.checkStatusBar()
                    window.clearDmg()
                    window.checkCanUseSkill()
                }
            });
        }
    </script>
</div>
