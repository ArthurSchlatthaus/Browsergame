<div id="playerSelectCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false"
     style="position: absolute; top:10%">
    <div class="carousel-inner">
        <div class="carousel-item active" id="warriorPreview"></div>
        <div class="carousel-item" id="ninjaPreview"></div>
        <div class="carousel-item" id="suraPreview"></div>
        <div class="carousel-item" id="shamanPreview"></div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#playerSelectCarousel" data-bs-slide="prev"
            style="height: 200px">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#playerSelectCarousel" data-bs-slide="next"
            style="height: 200px">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<script type="module">
    import {
        init,
        animate,
        setVars1,
        setVars2,
        setVars3,
        setVars4,
        setCamPos,
        setHair
    } from "/js/threejs/selectPlayer.js";

    setVars1('/warrior/', 'special.fbx', 'none', 'none', '/warrior/animation/intro/', 'A_Selected.fbx', true);
    setVars2('/ninja/', 'special.fbx', 'none', 'none', '/ninja/animation/intro/', 'A_Selected.fbx', true);
    setVars3('/sura/', 'special.fbx', 'none', 'none', '/sura/animation/intro/', 'A_Selected.fbx', true);
    setVars4('/shaman/', 'special.fbx', 'none', 'none', '/shaman/animation/intro/', 'A_Selected.fbx', true);
    setCamPos(0, 25, 150)
    init(true);
    animate();

    window.setHairVar = function setHairVar(number) {
        setHair('1', 'SK_Hair_1_' + number + '.fbx', false)
        setHair('2', 'SK_Hair_1_' + number + '.fbx', false)
        setHair('3', 'SK_Hair_1_' + number + '.fbx', false)
        setHair('4', 'SK_Hair_1_' + number + '.fbx', false)
    }
</script>
<script>
    $('#playerSelectCarousel').on('slide.bs.carousel', function (event) {
        document.getElementById('submitBtn').disabled = false;
        if (event.to === 0) {
            document.getElementById('raceInput').value = 1
        } else if (event.to === 1) {
            document.getElementById('raceInput').value = 2
        } else if (event.to === 2) {
            document.getElementById('raceInput').value = 3
            document.getElementById('submitBtn').disabled = true;
        } else if (event.to === 3) {
            document.getElementById('raceInput').value = 4
            document.getElementById('submitBtn').disabled = true;
        }
    })
</script>
<div style="position: absolute; bottom: 60px; left: 10%;background-image: url('/images/board_2/board_base.png');border-radius: 10px">
    <div style="background-image: url('/images/board_2/board_corner_lefttop.png');position: absolute; top: 0; left: 0; width: 32px; height: 32px;"></div>
    <div style="background-image: url('/images/board_2/board_line_left.png');position: absolute; top: 32px; bottom: 32px;left: 0; width: 32px; "></div>
    <div style="background-image: url('/images/board_2/board_corner_righttop.png');position: absolute; top: 0; right: 0; width: 32px; height: 32px;"></div>
    <div style="background-image: url('/images/board_2/board_line_right.png');position: absolute; top: 32px; bottom: 32px;right: 0; width: 32px;"></div>
    <div style="background-image: url('/images/board_2/board_corner_leftbottom.png');position: absolute; bottom: 0; left: 0; width: 32px; height: 32px;"></div>
    <div style="background-image: url('/images/board_2/board_line_top.png');position: absolute; top: 0; left: 32px;right: 32px; height: 32px;"></div>
    <div style="background-image: url('/images/board_2/board_corner_rightbottom.png');position: absolute; bottom: 0; right: 0; width: 32px; height: 32px;"></div>
    <div style="background-image: url('/images/board_2/board_line_bottom.png');position: absolute; left: 32px; bottom: 0;right: 32px;height: 32px;"></div>
    <div class="p-4">
        <h3 class="text-center" style="color: lightgray">{{__('custom.create_player')}}</h3>
        <div class="card-body">
            <form method="POST" action="{{url('registerPlayer')}}">
                @csrf
                <div class="form-group mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                </div>
                <input id="raceInput" name="race" type="hidden" value="1">
                <select id="hairSelect" class="form-select mb-3" aria-label="hair" name="hair"
                        onclick="window.setHairVar($(this).val())">
                    <option selected value="1">{{ __('custom.hair')}} 1</option>
                    <option value="2">{{ __('custom.hair')}} 2</option>
                    <option value="3">{{ __('custom.hair')}} 3</option>
                    <option value="4">{{ __('custom.hair')}} 4</option>
                </select>
                <div class="d-grid mx-auto" style="position: relative">
                    <img style="position: absolute;margin: auto;left: 0;right: 0;" src="/images/button_dark_120.png">
                    <button id="submitBtn" style="white-space: nowrap;width: 80px;color: lightgrey;position: absolute;margin: auto;left: -10px;right: 0;top: -2px; background-color: transparent; border: none"
                            type="submit">{{__('custom.create')}}</button>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <x-language></x-language>
        </div>
    </div>
</div>
<style>
    body {
        background-image: url({{url('images/select/background.jpg')}}) !important;
        background-size: contain;
        background-position: center;
    }
</style>

