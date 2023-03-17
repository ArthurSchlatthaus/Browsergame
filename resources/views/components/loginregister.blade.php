<div style="display: flex; justify-content: center;align-items: center; flex-wrap: wrap; color: white; font-size: 12px">
    <form method="POST" action="{{url('login')}}" style="position:relative;">
        @csrf
        <img src="/images/login/loginbackground.png">
        <div style="position: absolute; top: 17px; left: 5px">
            <a>{{__('custom.email')}}</a>
        </div>
        <div style="position: absolute; top: 14px; left: 73px">
            <input type="email" id="email-login" class="form-control" name="email" required autofocus
                   style="font-size: smaller; background-color: transparent; width: 120px;height: 19px; color: white">
        </div>

        <div style="position: absolute; top: 42px; left: 5px">
            <a>{{__('custom.password')}}</a>
        </div>
        <div style="position: absolute; top: 41px; left: 73px">
            <input type="password" id="password-login" class="form-control" name="password" required
                   style="font-size: smaller; background-color: transparent; width: 120px;height: 19px; color: white">
        </div>
        <div style="position: absolute; top: 65px; left: 73px">
            <img src="/images/login/button.png">
        </div>
        <div style="position: absolute; top: 65px; left: 75px">
            <button type="submit"
                    style="color: white;height: 20px; width: 75px;border: none; background-color: transparent">{{__('custom.login')}}</button>
        </div>
    </form>

    <form method="POST" action="{{url('register')}}" style="position:relative;">
        @csrf
        <img src="/images/login/registerbackground.png">
        <div style="position: absolute; top: 17px; left: 5px">
            <a>{{__('custom.email')}}</a>
        </div>
        <div style="position: absolute; top: 14px; left: 73px">
            <input type="email" id="email-register" class="form-control" name="email" required autofocus
                   style="font-size: smaller; background-color: transparent; width: 120px;height: 19px; color: white">
        </div>
        <div style="position: absolute; top: 42px; left: 5px">
            <a>{{__('custom.repeat')}}</a>
        </div>
        <div style="position: absolute; top: 41px; left: 73px">
            <input type="email" id="email-register_confirmation" class="form-control" name="email_confirmation" required
                   autofocus
                   style="font-size: smaller; background-color: transparent; width: 120px;height: 19px; color: white">
        </div>

        <div style="position: absolute; top: 65px; left: 5px">
            <a>{{__('custom.password')}}</a>
        </div>
        <div style="position: absolute; top: 64px; left: 73px">
            <input type="password" id="password-register" class="form-control" name="password" required
                   style="font-size: smaller; background-color: transparent; width: 120px;height: 19px; color: white">
        </div>
        <div style="position: absolute; top: 90px; left: 5px">
            <a>{{__('custom.repeat')}}</a>
        </div>
        <div style="position: absolute; top: 91px; left: 73px">
            <input type="password" id="password-register_confirmation" class="form-control" name="password_confirmation"
                   required
                   style="font-size: smaller; background-color: transparent; width: 120px;height: 19px; color: white">
        </div>

        <div style="position: absolute; top: 115px; left: 73px">
            <img src="/images/login/button.png">
        </div>
        <div style="position: absolute; top: 115px; left: 75px">
            <button type="submit"
                    style="color: white;height: 20px; width: 75px;border: none; background-color: transparent">{{__('custom.register')}}</button>
        </div>
    </form>
</div>
<style>

    body {
        background-image: url({{url('images/login/background_test_4k.jpg')}}) !important;
        background-size: contain;
        background-position: center;
    }


    input {
        border: 1px solid transparent !important;
    }

    input:focus {
        outline: none !important;
        border: 1px solid transparent !important;
        box-shadow: 0 0 10px wheat !important;
    }
</style>
