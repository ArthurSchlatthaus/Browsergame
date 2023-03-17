@if($monster!=null)
    <div class="p-2 d-flex" style="justify-self: flex-end">
        <div style="padding-right: 30px">
            <h5 style="backdrop-filter: blur(10px);">{{ $monster->getMonsterName($monster->id).' ('.__('custom.level').' '.$monster->level.')' }}
                :</h5>
            <label style="color:wheat">{{ __('custom.life') }}:</label>
            <a style="color: red">{{$monsterHp.'/'.$monster->hp}}</a>
        </div>
    </div>
@endif