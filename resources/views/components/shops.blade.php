<div class="d-flex flex-column align-items-center" style="width: 100%">
    <div class="d-flex flex-row justify-content-center justify-items-center">
        <div class="d-flex" id="npcContainer" style="height: 350px"></div>
    </div>
    <div class="d-flex flex-row">
        <div class="container2" style="position:relative;">
            @foreach(\App\Models\Shop::where('npcId',1)->get() as $shopItem)
                @php
                    $item = \App\Models\Item::where('vnum',$shopItem->itemId)->first();
                    $posLeft=8;
                    $posTop=30;
                    if($shopItem->pos<5){
                        $posLeft+=$shopItem->pos*32;
                    }elseif($shopItem->pos<10){
                        $posLeft+=($shopItem->pos-5)*32;
                        $posTop+=32;
                    }elseif($shopItem->pos<15){
                        $posLeft+=($shopItem->pos-10)*32;
                        $posTop+=64;
                    }elseif($shopItem->pos<20){
                        $posLeft+=($shopItem->pos-15)*32;
                        $posTop+=96;
                    }elseif($shopItem->pos<25){
                        $posLeft+=($shopItem->pos-20)*32;
                        $posTop+=128;
                    }elseif($shopItem->pos<30){
                        $posLeft+=($shopItem->pos-25)*32;
                        $posTop+=160;
                    }elseif($shopItem->pos<35){
                        $posLeft+=($shopItem->pos-30)*32;
                        $posTop+=192;
                    }elseif($shopItem->pos<40){
                        $posLeft+=($shopItem->pos-35)*32;
                        $posTop+=224;
                    }
                @endphp
                <div class="item"
                     style="margin-left: <?=$posLeft?>px;margin-top: <?=$posTop?>px;position: absolute;width: 32px;height: <?=$item->size*32?>px">
                    @if($shopItem->count > 0)
                        @php
                            if(isset($item) and \App\Http\Controllers\Controller::getType($item) === 'weapon') {
                                $title=\App\Http\Controllers\PlayerController::weapon_tooltip($shopItem->itemId);
                            }
                            elseif(isset($item) and \App\Http\Controllers\Controller::getType($item) === 'body'){
                                $title=\App\Http\Controllers\PlayerController::body_tooltip($shopItem->itemId);
                            }
                            else{
                                $name = \App\Http\Controllers\Controller::getType(\App\Models\Item::where('vnum',$shopItem->itemId)->first())=='potion'?__('items.item_id_'.$shopItem->itemId):__('items.item_id_' . substr_replace($shopItem->itemId, '', -1)) . substr($shopItem->itemId, -1);
                                $title=$name.PHP_EOL.$shopItem->price.' Yang';
                            }
                        @endphp
                        @if($shopItem->price > auth()->user()->player->gold)
                            <button class="tooltipItem"
                                    style="width:32px;
                                     height: <?=$item->size*32?>px;"
                            >
                                <img
                                        src="/images/items/{{str_pad($shopItem->itemId,5,'0',STR_PAD_LEFT)}}.png"
                                        width="32" height="{$item->size*32}"
                                        data-file-width="32"
                                        data-file-height="32"
                                        style="filter: grayscale(100%);"
                            </button>
                        @else
                            <button type="submit"
                                    class="btn tooltipItem"
                                    style="width:32px;
                                        height: <?=$item->size*32?>px;"
                                    data-bs-toggle="modal" data-bs-target="#buyModal"
                                    onclick="document.getElementById('shopIndex').value='{{$shopItem->id}}';document.getElementById('buyModalBody').innerText= '{{__('custom.want_to_buy',['count'=> $shopItem->count, 'name'=> \App\Http\Controllers\Controller::getType(\App\Models\Item::where('vnum',$shopItem->itemId)->first())=='potion'?__('items.item_id_'.$shopItem->itemId):__('items.item_id_' . substr_replace($shopItem->itemId, '', -1)) . substr($shopItem->itemId, -1), 'price'=> $shopItem->price] ) }}'"
                            >
                                <img
                                        src="/images/items/{{str_pad($shopItem->itemId,5,'0',STR_PAD_LEFT)}}.png"
                                        width="32" height="{$item->size*32}"
                                        data-file-width="32"
                                        data-file-height="32">
                                @endif
                                <div style="pointer-events:none;">
                                    @if(is_array($title))
                                        <p>
                                            <span style="color:{{$title['name'][1]}}">{{$title['name'][0]}}</span>
                                        </p>
                                        <p>
                                            <span style="color:{{$title['level'][1]}}">{{$title['level'][0]}}</span>
                                        </p>
                                        <a>
                                            <span style="color:{{$title['damage'][1]??$title['defense'][1]}}">{{$title['damage'][0]??$title['defense'][0]}}</span>
                                        </a>
                                        <br>
                                        <p>
                                            <span style="color:{{$title['races'][1]}}">{{$title['races'][0]}}</span>
                                        </p>
                                        <p>
                                            <span style="color:<?php if($shopItem->price > auth()->user()->player->gold){echo "red";}else{echo "green";}?>">{{$shopItem->price.' Yang'}}</span>
                                        </p>
                                    @else
                                        <p>{{$title}}</p>
                                    @endif
                                </div>
                            </button>
                            @if(\App\Http\Controllers\Controller::getType($item)=='potion')
                                <a id="count">{{ $shopItem->count }}</a>
                            @else
                                <a id="count">+{{ substr($item->vnum,-1) }}</a>
                            @endif
                        @endif

                </div>
            @endforeach
        </div>
        <div class="container2" style="position:relative;">
            @foreach(\App\Models\Shop::where('npcId',2)->get() as $shopItem)
                @php
                    $item = \App\Models\Item::where('vnum',$shopItem->itemId)->first();
                    $posLeft=8;
                    $posTop=30;
                    if($shopItem->pos<5){
                        $posLeft+=$shopItem->pos*32;
                    }elseif($shopItem->pos<10){
                        $posLeft+=($shopItem->pos-5)*32;
                        $posTop+=32;
                    }elseif($shopItem->pos<15){
                        $posLeft+=($shopItem->pos-10)*32;
                        $posTop+=64;
                    }elseif($shopItem->pos<20){
                        $posLeft+=($shopItem->pos-15)*32;
                        $posTop+=96;
                    }elseif($shopItem->pos<25){
                        $posLeft+=($shopItem->pos-20)*32;
                        $posTop+=128;
                    }elseif($shopItem->pos<30){
                        $posLeft+=($shopItem->pos-25)*32;
                        $posTop+=160;
                    }elseif($shopItem->pos<35){
                        $posLeft+=($shopItem->pos-30)*32;
                        $posTop+=192;
                    }elseif($shopItem->pos<40){
                        $posLeft+=($shopItem->pos-35)*32;
                        $posTop+=224;
                    }
                @endphp
                <div class="item"
                     style="margin-left: <?=$posLeft?>px;margin-top: <?=$posTop?>px;position: absolute;width: 32px;height: <?=$item->size*32?>px">
                    @if($shopItem->count > 0)
                        @php
                            if(isset($item) and \App\Http\Controllers\Controller::getType($item) === 'weapon') {
                                $title=\App\Http\Controllers\PlayerController::weapon_tooltip($shopItem->itemId);
                            }
                            elseif(isset($item) and \App\Http\Controllers\Controller::getType($item) === 'body'){
                                $title=\App\Http\Controllers\PlayerController::body_tooltip($shopItem->itemId);
                            }
                            else{
                                $name = \App\Http\Controllers\Controller::getType(\App\Models\Item::where('vnum',$shopItem->itemId)->first())=='potion'?__('items.item_id_'.$shopItem->itemId):__('items.item_id_' . substr_replace($shopItem->itemId, '', -1)) . substr($shopItem->itemId, -1);
                                $title=$name.PHP_EOL.$shopItem->price.' Yang';
                            }
                        @endphp
                        @if($shopItem->price > auth()->user()->player->gold)
                            <button class="tooltipItem"
                                    style="width:32px;
                                     height: <?=$item->size*32?>px;"
                            >
                                <img
                                        src="/images/items/{{str_pad($shopItem->itemId,5,'0',STR_PAD_LEFT)}}.png"
                                        width="32" height="{$item->size*32}"
                                        data-file-width="32"
                                        data-file-height="32"
                                        style="filter: grayscale(100%);"
                            </button>
                        @else
                            <button type="submit"
                                    class="btn tooltipItem"
                                    style="width:32px;
                                        height: <?=$item->size*32?>px;"
                                    data-bs-toggle="modal" data-bs-target="#buyModal"
                                    onclick="document.getElementById('shopIndex').value='{{$shopItem->id}}';document.getElementById('buyModalBody').innerText= '{{__('custom.want_to_buy',['count'=> $shopItem->count, 'name'=> \App\Http\Controllers\Controller::getType(\App\Models\Item::where('vnum',$shopItem->itemId)->first())=='potion'?__('items.item_id_'.$shopItem->itemId):__('items.item_id_' . substr_replace($shopItem->itemId, '', -1)) . substr($shopItem->itemId, -1), 'price'=> $shopItem->price] ) }}'"
                            >
                                <img
                                        src="/images/items/{{str_pad($shopItem->itemId,5,'0',STR_PAD_LEFT)}}.png"
                                        width="32" height="{$item->size*32}"
                                        data-file-width="32"
                                        data-file-height="32">
                                @endif
                                <div style="pointer-events:none;">
                                    @if(is_array($title))
                                        <p>
                                            <span style="color:{{$title['name'][1]}}">{{$title['name'][0]}}</span>
                                        </p>
                                        <p>
                                            <span style="color:{{$title['level'][1]}}">{{$title['level'][0]}}</span>
                                        </p>
                                        <a>
                                            <span style="color:{{$title['damage'][1]??$title['defense'][1]}}">{{$title['damage'][0]??$title['defense'][0]}}</span>
                                        </a>
                                        <br>
                                        <p>
                                            <span style="color:{{$title['races'][1]}}">{{$title['races'][0]}}</span>
                                        </p>
                                        <p>
                                            <span style="color:<?php if($shopItem->price > auth()->user()->player->gold){echo "red";}else{echo "green";}?>">{{$shopItem->price.' Yang'}}</span>
                                        </p>
                                    @else
                                        <p>{{$title}}</p>
                                    @endif
                                </div>
                            </button>
                            @if(\App\Http\Controllers\Controller::getType($item)=='potion')
                                <a id="count">{{ $shopItem->count }}</a>
                            @else
                                <a id="count">+{{ substr($item->vnum,-1) }}</a>
                            @endif
                        @endif

                </div>
            @endforeach
        </div>
        <div class="container2" style="position:relative;">
            @foreach(\App\Models\Shop::where('npcId',3)->get() as $shopItem)
                @php
                    $item = \App\Models\Item::where('vnum',$shopItem->itemId)->first();
                    $posLeft=8;
                    $posTop=30;
                    if($shopItem->pos<5){
                        $posLeft+=$shopItem->pos*32;
                    }elseif($shopItem->pos<10){
                        $posLeft+=($shopItem->pos-5)*32;
                        $posTop+=32;
                    }elseif($shopItem->pos<15){
                        $posLeft+=($shopItem->pos-10)*32;
                        $posTop+=64;
                    }elseif($shopItem->pos<20){
                        $posLeft+=($shopItem->pos-15)*32;
                        $posTop+=96;
                    }elseif($shopItem->pos<25){
                        $posLeft+=($shopItem->pos-20)*32;
                        $posTop+=128;
                    }elseif($shopItem->pos<30){
                        $posLeft+=($shopItem->pos-25)*32;
                        $posTop+=160;
                    }elseif($shopItem->pos<35){
                        $posLeft+=($shopItem->pos-30)*32;
                        $posTop+=192;
                    }elseif($shopItem->pos<40){
                        $posLeft+=($shopItem->pos-35)*32;
                        $posTop+=224;
                    }
                @endphp
                <div class="item"
                     style="margin-left: <?=$posLeft?>px;margin-top: <?=$posTop?>px;position: absolute;width: 32px;height: <?=$item->size*32?>px">
                    @if($shopItem->count > 0)
                        @php
                            if(isset($item) and \App\Http\Controllers\Controller::getType($item) === 'weapon') {
                                $title=\App\Http\Controllers\PlayerController::weapon_tooltip($shopItem->itemId);
                            }
                            elseif(isset($item) and \App\Http\Controllers\Controller::getType($item) === 'body'){
                                $title=\App\Http\Controllers\PlayerController::body_tooltip($shopItem->itemId);
                            }
                            else{
                                $name = \App\Http\Controllers\Controller::getType(\App\Models\Item::where('vnum',$shopItem->itemId)->first())=='potion'?__('items.item_id_'.$shopItem->itemId):__('items.item_id_' . substr_replace($shopItem->itemId, '', -1)) . substr($shopItem->itemId, -1);
                                $title=$name.PHP_EOL.$shopItem->price.' Yang';
                            }
                        @endphp
                        @if($shopItem->price > auth()->user()->player->gold)
                            <button class="tooltipItem"
                                    style="width:32px;
                                     height: <?=$item->size*32?>px;"
                            >
                                <img
                                        src="/images/items/{{str_pad($shopItem->itemId,5,'0',STR_PAD_LEFT)}}.png"
                                        width="32" height="{$item->size*32}"
                                        data-file-width="32"
                                        data-file-height="32"
                                        style="filter: grayscale(100%);"
                            </button>
                        @else
                            <button type="submit"
                                    class="btn tooltipItem"
                                    style="width:32px;
                                        height: <?=$item->size*32?>px;"
                                    data-bs-toggle="modal" data-bs-target="#buyModal"
                                    onclick="document.getElementById('shopIndex').value='{{$shopItem->id}}';document.getElementById('buyModalBody').innerText= '{{__('custom.want_to_buy',['count'=> $shopItem->count, 'name'=> \App\Http\Controllers\Controller::getType(\App\Models\Item::where('vnum',$shopItem->itemId)->first())=='potion'?__('items.item_id_'.$shopItem->itemId):__('items.item_id_' . substr_replace($shopItem->itemId, '', -1)) . substr($shopItem->itemId, -1), 'price'=> $shopItem->price] ) }}'"
                            >
                                <img
                                        src="/images/items/{{str_pad($shopItem->itemId,5,'0',STR_PAD_LEFT)}}.png"
                                        width="32" height="{$item->size*32}"
                                        data-file-width="32"
                                        data-file-height="32">
                                @endif
                                <div style="pointer-events:none;">
                                    @if(is_array($title))
                                        <p>
                                            <span style="color:{{$title['name'][1]}}">{{$title['name'][0]}}</span>
                                        </p>
                                        <p>
                                            <span style="color:{{$title['level'][1]}}">{{$title['level'][0]}}</span>
                                        </p>
                                        <a>
                                            <span style="color:{{$title['damage'][1]??$title['defense'][1]}}">{{$title['damage'][0]??$title['defense'][0]}}</span>
                                        </a>
                                        <br>
                                        <p>
                                            <span style="color:{{$title['races'][1]}}">{{$title['races'][0]}}</span>
                                        </p>
                                        <p>
                                            <span style="color:<?php if($shopItem->price > auth()->user()->player->gold){echo "red";}else{echo "green";}?>">{{$shopItem->price.' Yang'}}</span>
                                        </p>
                                    @else
                                        <p>{{$title}}</p>
                                    @endif
                                </div>
                            </button>
                            @if(\App\Http\Controllers\Controller::getType($item)=='potion')
                                <a id="count">{{ $shopItem->count }}</a>
                            @else
                                <a id="count">+{{ substr($item->vnum,-1) }}</a>
                            @endif
                        @endif

                </div>
            @endforeach
        </div>
    </div>
    <a>Your Yang: {{ auth()->user()->player->gold }}</a>
    <div class="d-flex flex-column ">
        <table class="table table-borderless table-responsive">
            <tbody>
            @foreach(auth()->user()->player->inventory as $inventoryItem)
                @php($item = \App\Models\Item::where('vnum',$inventoryItem->vnum)->first())
                @if(isset($item) and $inventoryItem->isEquipped==0 and \App\Http\Controllers\Controller::getType($item) === 'weapon' or isset($item) and $inventoryItem->isEquipped==0 and \App\Http\Controllers\Controller::getType($item) === 'body')
                    <tr>
                        <form method="POST" action="{{url('sellItem')}}">
                            @csrf
                            <th class="align-middle">
                                @if(\App\Http\Controllers\Controller::getType($item) === 'weapon')
                                    {{\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['name'][0]}}
                                    @if(isset(\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['bonus1'][0]))
                                        <br>
                                        <span style="color:{{\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['bonus1'][1]}};">{{\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['bonus1'][0]}}</span>
                                    @endif
                                    @if(isset(\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['bonus2'][0]))
                                        <br>
                                        <span style="color:{{\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['bonus2'][1]}};">{{\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['bonus2'][0]}}</span>
                                    @endif
                                    @if(isset(\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['bonus3'][0]))
                                        <br>
                                        <span style="color:{{\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['bonus3'][1]}};">{{\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['bonus3'][0]}}</span>
                                    @endif
                                    @if(isset(\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['bonus4'][0]))
                                        <br>
                                        <span style="color:{{\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['bonus4'][1]}};">{{\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['bonus4'][0]}}</span>
                                    @endif
                                    @if(isset(\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['bonus5'][0]))
                                        <br>
                                        <span style="color:{{\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['bonus5'][1]}};">{{\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem)['bonus5'][0]}}</span>
                                    @endif
                                @else
                                    {{\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['name'][0]}}
                                    @if(isset(\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['bonus1'][0]))
                                        <br>
                                        <span style="color:{{\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['bonus1'][1]}};">{{\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['bonus1'][0]}}</span>
                                    @endif
                                    @if(isset(\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['bonus2'][0]))
                                        <br>
                                        <span style="color:{{\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['bonus2'][1]}};">{{\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['bonus2'][0]}}</span>
                                    @endif
                                    @if(isset(\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['bonus3'][0]))
                                        <br>
                                        <span style="color:{{\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['bonus3'][1]}};">{{\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['bonus3'][0]}}</span>
                                    @endif
                                    @if(isset(\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['bonus4'][0]))
                                        <br>
                                        <span style="color:{{\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['bonus4'][1]}};">{{\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['bonus4'][0]}}</span>
                                    @endif
                                    @if(isset(\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['bonus5'][0]))
                                        <br>
                                        <span style="color:{{\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['bonus5'][1]}};">{{\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem)['bonus5'][0]}}</span>
                                    @endif
                                @endif
                            </th>
                            <input name="item_id" type="hidden" value="{{$inventoryItem->id}}">
                            <td>
                                <button type="submit" class="btn btn-dark btn-outline-success">
                                    Sell for ({{(intval(substr($inventoryItem->vnum,-1))+1)*100}} Yang)
                                </button>
                            </td>
                        </form>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" id="buyModalBody"></div>
            <div class="modal-footer justify-content-center justify-content-evenly">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" action="{{url('buyItem')}}">
                    @csrf
                    <input name="shopIndex" id="shopIndex" type="hidden" value="">
                    <button type="submit" class="btn btn-success">buy</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    @-moz-document url-prefix() {
        #count {
            margin-left: -10px !important;
        }
    }

    #count {
        position: absolute;
        top: 0;
        margin-left: -10px;
    }

    .container2 {
        background-image: url("/images/shop.png");
        background-size: cover;
        width: 178px;
        height: 298px;
    }


    .item {
        background: transparent;
        text-align: center;
        font-size: 8px;
        color: white;
    }

    button {
        background-color: transparent;
        background-repeat: no-repeat;
        border: none;
        cursor: pointer;
        overflow: hidden;
        outline: none;
        padding: 0 !important;
        margin: 0 !important;
    }
</style>
