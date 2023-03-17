<script type="module">
    import {setHair} from "/js/threejs/start.js";

    window.setHairVar = function setHairVar(number) {
        setHair('SK_Hair_1_' + number + '.fbx')
        $.ajax({
            url: "/setHair",
            method: 'POST',
            data: {
                hair: number,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                if (result.player != null) {
                    window.setGlobalPlayer(result.player)
                }
            }
        });
    }
    window.setDance = function setDance(dance) {
        $.ajax({
            url: "/setDance",
            method: 'POST',
            data: {
                dance: dance,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                if (result.player != null) {
                    window.setGlobalPlayer(result.player)
                }
            }
        });
    }
    let playerTmp = window.getGlobalPlayer()
    if (playerTmp != null) {
        const $hairSelect = document.querySelector('#hairSelect');
        $hairSelect.value = playerTmp.hair.substr(0, playerTmp.hair.indexOf('.')).slice(-1)

        const $danceSelect = document.querySelector('#danceSelect');
        $danceSelect.value = playerTmp.winningDance.substr(0, playerTmp.winningDance.indexOf('.'))
    }
</script>
<div style="min-width: 300px;min-height:510px;color: lightgray;display:flex;align-items: center; flex-direction: column;">
    <x-language></x-language>
    <div style="position: relative">
        <select id="hairSelect" class="form-select mb-3" onclick="window.setHairVar($(this).val())">
            <option id="hair1" value="1">{{ __('custom.hair')}} 1</option>
            <option id="hair2" value="2">{{ __('custom.hair')}} 2</option>
            <option id="hair3" value="3">{{ __('custom.hair')}} 3</option>
            <option id="hair4" value="4">{{ __('custom.hair')}} 4</option>
        </select>
    </div>
    <div style="position: relative">
        <select id="danceSelect" class="form-select mb-3" onclick="window.setDance($(this).val())">
            <option id="dance1" value="Chicken_Danceout">Chicken dance</option>
            <option id="dance2" value="Gangnam_Styleout">Gangnam Style</option>
            <option id="dance3" value="Macarena_Danceout">Macarena Dance</option>
            <option id="dance4" value="Shufflingout">Shuffling</option>
            <option id="dance5" value="Tut_Hip_Hop_Danceout">Hip Hop Dance</option>
            <option id="dance6" value="Twist_Danceout">Twist Dance</option>
        </select>
    </div>
    <div class="p-2">
        @csrf
        <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="$('#settingsModal').click()"
                class="delete-btn">{{ __('custom.delete_player_title') }}</button>
    </div>
    <form method="POST" action="{{url('logout')}}" style="position:relative;">
        @csrf
        <img src="/images/button_dark.png">
        <button type="submit" onclick="localStorage.clear(); window.clearFightScene();"
                style="color: lightgrey;position:absolute;top: 5px; left: 20px; font-size: 10pt">{{ __('custom.navbar_logout') }}</button>
    </form>
    <a>Players Count: {{\App\Models\Player::all()->count()}}</a>
    <a>Users Count: {{\App\Models\User::all()->count()}}</a>
    <a>User online today: {{App\Models\Player::getTodayOnlineCount()}}</a>
    <a>User online now: {{App\Models\Player::where('isLoggedIn',1)->count()}}</a>

    <a
            href="https://discord.gg/Hqe9aRQhX6" target="_blank">
        <i style='font-size:24px'
           class='fab'>&#xf392;</i>
    </a>
    <a href="https://www.buymeacoffee.com/ludus2" target="_blank"
       rel="noopener noreferrer">
        <img src="https://cdn.buymeacoffee.com/buttons/v2/default-green.png"
             alt="Buy Me A Coffee"
             style="height:40px!important;">
    </a>
    <a href="https://www.patreon.com/ludus2" target="_blank"
       rel="noopener noreferrer">
        <img class="img-thumbnail" style="height:40px!important;"
             src="https://www.kindpng.com/picc/m/110-1103169_patreon-supoort-us-logo-patreon-png-transparent-png.png">
    </a>

</div>
<style>
    .delete-btn {
        padding: 0.5em 1em;
        background-color: #eccfc9;
        color: #c5391a;
        border: 2px solid #ea3f1b;
        border-radius: 5px;
        font-weight: bold;
        font-size: 16px;
        text-transform: uppercase;
        cursor: pointer;
    }
</style>