@php($player=auth()->user()->player)
<div id="player1Preview" style="padding-top: 10%"></div>
<div style="position: absolute; bottom: 60px; left: 10%;color: lightgray;text-align:center;">
    <img src="/images/select/border.png">
    <h3 style="position: absolute;top:10px;left: 0; right: 0; font-size: 15pt">{{$player->name}}</h3>
    <p style="position: absolute;top:50px;left: 0; right: 0;">{{__('custom.level').' '.$player->level}}</p>
    <form method="POST" action="{{url('loginPlayer')}}" style="position: absolute;top:90px; left: 0; right: 0">
        @csrf
        <img style="position: absolute;margin: auto;left: 0;right: 0;" src="/images/button_dark_120.png">
        <button style="white-space: nowrap;width: 80px;color: lightgrey;position: absolute;margin: auto;left: -10px;right: 0;top: -2px; background-color: transparent; border: none"
                type="submit">{{__('custom.login')}}</button>
    </form>
    <div style="position: absolute;top:130px;left: -8px;">
        <x-language></x-language>
    </div>
</div>
<style>
    body {
        background-image: url({{url('images/select/background.jpg')}}) !important;
        background-size: contain;
        background-position: center;
    }

    @media screen and (min-width: 1200px) {
        #player1Preview {
            padding-top: 10% !important;
        }
    }

    @media screen and (min-width: 1600px) {
        #player1Preview {
            padding-top: 15% !important;
        }
    }
</style>


<script type="module">
    import {
        init,
        animate,
        setVars1,
        setVars2,
        setVars3,
        setVars4,
        setCamPos,
        setHair,
        allowWeapon, setLogin
    } from "/js/threejs/selectPlayer.js";
    setLogin(true)
    allowWeapon(true);
    setVars1('{{$player->getModelPath()}}', '{{$player->getModel()}}', '{{$player->getWeaponPath()}}', 'none', '{{$player->getAnimationPathWinner()}}', '{{$player->getWinningDance()}}', true);
    setVars2('', '', '', '', '', '', false)
    setVars3('', '', '', '', '', '', false)
    setVars4('', '', '', '', '', '', false)
    setCamPos(0, 25, 150)
    init(false);
    animate();
    setHair('1', '{{$player->getHair()}}', true)
    /*setTimeout(()=>{
        playEffect()
    },3000)*/

</script>