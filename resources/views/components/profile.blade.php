<div class="profile" style="position: absolute;left: 5px;bottom: 100px;width: 250px; height: 350px">
    <button style="border: none;background-color: transparent;" onclick="window.loadElement('profileContainer')">
        <img src="/images/close_new.png" style="position: absolute;right: 5px;top: 29px;z-index: 10">
    </button>
    <div id="charWindow" style="display:block;position: relative">
        <x-character></x-character>
        <img id="profile_warrior" style="display: none;top:12px;left: 12px;" height="40"
             src="images/face/warrior.png">
        <img id="profile_ninja" style="display: none;top:12px;left: 12px;" height="40" src="images/face/ninja.png">
        <img id="profile_sura" style="display: none;top:12px;left: 12px;" height="40" src="images/face/sura.png">
        <img id="profile_shaman" style="display: none;top:12px;left: 12px;" height="40"
             src="images/face/shaman.png">

        <a id="player_name" style="top:28px;left: 65px;font-size: 12pt">-</a>
        <a id="player_level" style="top:75px;left: 20px;">-</a>
        <a id="player_exp" style="top:75px;left: 75px;">-</a>
        <a id="player_expMax" style="top:75px;left: 175px;">-</a>
        <a id="player_vit" style="top:130px;left: 60px;">-</a>
        <a id="player_int" style="top:152px;left: 60px;">-</a>
        <a id="player_str" style="top:175px;left: 60px;">-</a>
        <a id="player_dex" style="top:198px;left: 60px;">-</a>
        <a id="playerProfileHp" style="top:130px;left: 158px;">-</a>
        <a id="playerProfileSp" style="top:152px;left: 163px;">-</a>
        <a id="player_damage" style="top:175px;left: 195px;">-</a>
        <a id="player_def" style="top:198px;left: 195px;">-</a>
        <a id="player_freestatus" class="orange" style="top:105px;left:200px">-</a>
        <div id="freestatusform" style="display:none;">
            <button style="top:132px;left: 89px;" class="plus_btn" type="submit"
                    onclick="setStatus(1)"></button>
            <button style="top:155px;left: 89px;" class="plus_btn" type="submit"
                    onclick="setStatus(2)"></button>
            <button style="top:178px;left: 89px;" class="plus_btn" type="submit"
                    onclick="setStatus(3)"></button>
            <button style="top:202px;left: 89px;" class="plus_btn" type="submit"
                    onclick="setStatus(4)"></button>
        </div>

    </div>
    <div id="skillsWindow" style="display: none;position: relative">
        <x-skills></x-skills>
    </div>
</div>

<style>
    .profile a {
        color: white !important;
        position: absolute;
        font-size: 13px;
        text-decoration: none;
    }

    .profile img {
        position: absolute;
    }

    .orange {
        color: orange !important;
        text-decoration: none;
        white-space: nowrap;
    }

    .plus_btn {
        background-image: url("/images/plus.png");
        width: 13px;
        height: 13px;
        position: absolute;
        padding: 0;
        border: none;
    }
</style>