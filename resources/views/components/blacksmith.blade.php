<div class="d-flex flex-column align-items-center" style="width: 100%">
    <h5>{{__('custom.blacksmith_info')}}</h5>
    <!--<div id="imgContainer" >
        <img id="previewItem" width="32" height="96">
    </div>-->
    <form method="POST" action="{{url('upgradeItem')}}">
        @csrf
        <input type="hidden" name="item_id" id="itemId" value="">
        <button type="submit" style="display: none" id="tooltipItemText"
                class="btn btn-dark btn-block p-2 m-2"></button>
    </form>


    <div class="container2" style="position:relative; padding-top: 7px;">
        @foreach(auth()->user()->player->inventory as $inventoryItem)
            @php
                $item=\App\Models\Item::where('vnum',$inventoryItem->vnum)->first();
                $posLeft=8;
                $posTop=0;
                if($inventoryItem->pos<5){
                    $posLeft+=$inventoryItem->pos*32;
                }elseif($inventoryItem->pos<10){
                    $posLeft+=($inventoryItem->pos-5)*32;
                    $posTop+=32;
                }elseif($inventoryItem->pos<15){
                    $posLeft+=($inventoryItem->pos-10)*32;
                    $posTop+=64;
                }elseif($inventoryItem->pos<20){
                    $posLeft+=($inventoryItem->pos-15)*32;
                    $posTop+=96;
                }elseif($inventoryItem->pos<25){
                    $posLeft+=($inventoryItem->pos-20)*32;
                    $posTop+=128;
                }elseif($inventoryItem->pos<30){
                    $posLeft+=($inventoryItem->pos-25)*32;
                    $posTop+=160;
                }elseif($inventoryItem->pos<35){
                    $posLeft+=($inventoryItem->pos-30)*32;
                    $posTop+=192;
                }
                elseif($inventoryItem->pos<40){
                    $posLeft+=($inventoryItem->pos-35)*32;
                    $posTop+=224;
                }
            @endphp
            <div class="item"
                 style="margin-left: <?=$posLeft?>px;margin-top: <?=$posTop?>px;position: absolute;width: 32px;height: <?=$item->size*32?>px">
                @if($inventoryItem->count > 0 and $inventoryItem->isEquipped == 0)
                    @php
                        $item = \App\Models\Item::where('vnum',$inventoryItem->vnum)->first();
                        if(isset($item)){
                            if(\App\Http\Controllers\Controller::getType($item) === 'weapon') {
                                $title=\App\Http\Controllers\PlayerController::weapon_tooltip($inventoryItem->vnum,$inventoryItem);
                            }
                            elseif(\App\Http\Controllers\Controller::getType($item) === 'body'){
                                $title=\App\Http\Controllers\PlayerController::body_tooltip($inventoryItem->vnum,$inventoryItem);
                            }
                            else{
                                $title=\App\Http\Controllers\Controller::getType($item)=='potion'?__('items.item_id_'.$inventoryItem->vnum):__('items.item_id_' . substr_replace($inventoryItem->vnum, '', -1)) . substr($inventoryItem->vnum, -1);
                            }
                        }else{
                            $title=\App\Http\Controllers\Controller::getType($item)=='potion'?__('items.item_id_'.$inventoryItem->vnum):__('items.item_id_' . substr_replace($inventoryItem->vnum, '', -1)) . substr($inventoryItem->vnum, -1);
                        }
                    @endphp
                    @if(isset($item) and \App\Http\Controllers\Controller::getType($item) === 'weapon' or isset($item) and \App\Http\Controllers\Controller::getType($item) === 'body')
                        @if(str_ends_with(strval($item->vnum), '9'))
                            <img style="filter: grayscale(100%);"
                                 src="/images/items/{{str_pad(substr_replace($inventoryItem->vnum,'0',-1),5,'0',STR_PAD_LEFT)}}.png"
                                 width="32"
                                 height="{{$item->size*32}}"
                                 data-file-width="32"
                                 data-file-height="32"
                                 draggable="false"
                            >
                        @else
                            <button type="submit"
                                    class="btn tooltipItem"
                                    style="width:32px;
                                    height: <?=$item->size*32?>px;"

                            >
                                <img src="/images/items/{{str_pad(substr_replace($inventoryItem->vnum,'0',-1),5,'0',STR_PAD_LEFT)}}.png"
                                     width="32"
                                     height="{{$item->size*32}}"
                                     data-file-width="32"
                                     data-file-height="32"
                                     draggable="true" ondragstart="drag(event)"
                                     id="{{$inventoryItem->id}}"
                                     alt="{{$title['name'][0].'//'.(intval(substr($inventoryItem->vnum,-1))+1)*1000}}"
                                >

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
                                            <span style="color:{{$title['bonus1'][1]}}">{{$title['bonus1'][0]}}</span>
                                            @if(isset($title['bonus2'][0]))
                                                <br>
                                                <span style="color:{{$title['bonus2'][1]}}">{{$title['bonus2'][0]}}</span>
                                            @endif
                                            @if(isset($title['bonus3'][0]))
                                                <br>
                                                <span style="color:{{$title['bonus3'][1]}}">{{$title['bonus3'][0]}}</span>
                                            @endif
                                            @if(isset($title['bonus4'][0]))
                                                <br>
                                                <span style="color:{{$title['bonus4'][1]}}">{{$title['bonus4'][0]}}</span>
                                            @endif
                                            @if(isset($title['bonus5'][0]))
                                                <br>
                                                <span style="color:{{$title['bonus5'][1]}}">{{$title['bonus5'][0]}}</span>
                                            @endif
                                        </p>
                                        <p>
                                            <span style="color:{{$title['races'][1]}}">{{$title['races'][0]}}</span>
                                        </p>
                                    @else
                                        <p>{{$title}}</p>
                                    @endif
                                </div>
                            </button>
                        @endif
                    @else
                        <img style="filter: grayscale(100%);"
                             src="/images/items/{{str_pad($inventoryItem->vnum,5,'0',STR_PAD_LEFT)}}.png"
                             width="32"
                             height="{{$item->size*32}}"
                             data-file-width="32"
                             data-file-height="32"
                             draggable="false"
                        >
                    @endif
                    @if(\App\Http\Controllers\Controller::getType($item)!='potion')
                        <a id="count">+{{ substr($inventoryItem->vnum,-1) }}</a>
                    @endif
                @endif
            </div>
        @endforeach
    </div>

    <script>

    </script>

</div>
