<div class="character">
    <img alt="" src="images/profile/character.png">

    <img alt="" src="images/profile/buttons_profile.png"
         style="position: absolute; left: -1px;  top: -6px; z-index: 10">
    <a style="position:absolute;left: 10px;bottom: 13px; z-index: 11">
        <button class="btn btn-sm" style="background-color: transparent; border:none; color: wheat"
                onclick="showWindow('charWindow')">{{ __('custom.ranking_profile') }}
        </button>
    </a>
    <a style="position:absolute;left: 75px;bottom: 13px; z-index: 11">
        <button class="btn btn-sm" style="background-color: transparent; border:none; color: wheat"
                onclick="showWindow('skillsWindow')">{{ __('custom.skills_short') }}
        </button>
    </a>

    <a style="top:9px;left:115px"> {{ __('custom.character') }} </a>
    <a class="red" style="top:60px;left:15px"> {{ __('custom.level') }} </a>
    <a class="red" style="top:60px;left:85px"> {{ __('custom.exp') }} </a>
    <a class="red no_wrap" style="top:60px;left:155px"> {{ __('custom.needed') . ' ' . __('custom.exp') }} </a>
    <a class="wheat" style="top:107px;left:18px"> {{ __('custom.character') . '-' . __('custom.status') }} </a>
    <a class="orange" style="top:107px;left:145px"> {{ __('custom.available')}} [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;] </a>
    <a style="top:132px;left:20px"> {{ __('custom.vit') }} </a>
    <a style="top:155px;left:20px"> {{ __('custom.int') }} </a>
    <a style="top:178px;left:20px"> {{ __('custom.str') }} </a>
    <a style="top:202px;left:20px"> {{ __('custom.dex') }} </a>
    <a style="top:132px;left:105px"> {{ __('custom.hp') }} </a>
    <a style="top:155px;left:105px"> {{ __('custom.sp') }} </a>
    <a style="top:175px;left:105px"> {{ __('custom.avg') }} </a>
    <a style="top:185px;left:105px"> {{ __('custom.attack') }} </a>
    <a style="top:202px;left:105px;"> {{ __('custom.defense') }} </a>
    <a class="wheat" style="top:226px;left:18px"> {{ __('custom.properties') }} </a>
    <a class="smaller" style="top:250px;left:10px;"> {{ __('custom.move') . 's-' }} </a>
    <a class="smaller" style="top:258px;left:10px;"> {{ __('custom.speed') }} </a>
    <a class="smaller" style="top:274px;left:10px;"> {{ __('custom.attack') . 's-' }} </a>
    <a class="smaller" style="top:282px;left:10px;"> {{ __('custom.speed') }} </a>
    <a class="smaller" style="top:298px;left:10px;"> {{ __('custom.spell') . '-' }} </a>
    <a class="smaller" style="top:306px;left:10px;"> {{ __('custom.speed') }} </a>
    <a class="smaller" style="top:250px;left:125px;"> {{ __('custom.magic') . '-' }} </a>
    <a class="smaller" style="top:258px;left:125px;"> {{ __('custom.attack') }} </a>
    <a class="smaller" style="top:274px;left:125px;"> {{ __('custom.magic') . '-' }} </a>
    <a class="smaller" style="top:282px;left:125px;"> {{ __('custom.defense') }} </a>
    <a class="smaller" style="top:298px;left:125px;"> {{ __('custom.dodge') . '-' }} </a>
    <a class="smaller" style="top:306px;left:125px;"> {{ __('custom.value') }} </a>

    @php($fight=auth()->user()->player->getActiveFight()!=null?auth()->user()->player->getActiveFight():auth()->user()->player->getInactiveFight())
    <button style="position: absolute;color: orange;top:170px;left:228px;" type="button"
            class="btn btn-sm btn-primary-outline" data-bs-toggle="tooltip"
            title="{{__('custom.tooltip_dmg').__('custom.group')}}{{isset($fight)?$fight->groupId:'-'/*#FixMe Group Name*/}}">
        &#63;
    </button>

</div>

<style>
    .character img {
        left: 0;
        right: 0;
        margin-left: auto;
        margin-right: auto;
        position: relative !important;
    }

    .character a {
        color: white !important;
        position: absolute;
        font-size: 10px !important;
    }

    .smaller {
        font-size: 8px !important;
        text-decoration: none;
    }

    .no_wrap {
        white-space: nowrap;
        text-decoration: none;
    }

    .character .red {
        color: crimson !important;
        text-decoration: none;
    }

    .character .wheat {
        color: wheat !important;
        text-decoration: none;
        white-space: nowrap;
    }

    .character .orange {
        color: orange !important;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn:focus, .btn:active {
        outline: none !important;
        box-shadow: none;
    }
</style>