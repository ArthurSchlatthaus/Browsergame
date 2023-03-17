<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/images/logo_short.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{ url('') }}">

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GGMP5K3GEL"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'G-GGMP5K3GEL');
        window.trans = function trans(key, replace = {}) {
            let translation = key.split('.').reduce((t, i) => t[i] || null, {!! \Cache::get('translations') !!})
            for (let placeholder in replace) {
                translation = translation.replace(`:${placeholder}`, replace[placeholder])
            }
            return translation
        }
    </script>

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GGMP5K3GEL"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-GGMP5K3GEL');
    </script>

    <title id="title">Ludus2 ~ Metin2 fan project </title>

    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
            crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
            integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
            integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
            crossorigin="anonymous"></script>

    <script src="https://kit.fontawesome.com/df6bba4bc2.js" crossorigin="anonymous" async defer></script>
    <script type="module">
        import * as THREE from '../../../js/threejs/three.module.js'
        window.THREE = THREE;
        import {FBXLoader} from 'https://cdn.skypack.dev/three@0.127.0/examples/jsm/loaders/FBXLoader'
        window.FBXLoader = FBXLoader;
        import {OrbitControls} from 'https://unpkg.com/three@0.127.0/examples/jsm/controls/OrbitControls.js'
        window.OrbitControls = OrbitControls;
    </script>
    <script type="module" src="{{ URL::asset('js/components/fightanimation.js')}}"></script>
    <script type="module" src="{{ URL::asset('js/components/content.js')}}"></script>
    <script src="{{ URL::asset('js/components/inventory.js')}}"></script>
    <script src="{{ URL::asset('js/components/profile.js')}}"></script>
    <script src="{{ URL::asset('js/components/statusbar.js')}}"></script>
    <script src="{{ URL::asset('js/components/equipment.js')}}"></script>
    <script src="{{ URL::asset('js/components/fight.js')}}"></script>
    <script src="{{ URL::asset('js/components/welcome.js')}}"></script>
    <script src="{{ URL::asset('js/components/skills.js')}}"></script>

    <script src="{{ URL::asset('js/effekseer/effekseer.js')}}"></script>
    <style>
        @import url("https://use.typekit.net/hsw6iqo.css");

        * {
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            background: black
        }

        body {
            min-height: 100%;
            font-family: pt-serif, serif;
            background-image: url("images/background/maps/map1/red_4k.jpg");
            background-position: top;
            background-repeat: no-repeat;
            background-size: cover;
            background-color: black;
            background-attachment: fixed;
            overflow: hidden !important;
        }

        @media only screen and (max-device-width: 555px) and (orientation: landscape) {
            #missionsModal {
                height: 100%;
            }
        }

        @media only screen and (max-device-width: 555px) {
            #rendererMain {
                height: 40vh !important;
            }

            #rendererBlack {
                top: 42% !important;
                left: 5% !important;
            }

            #rendererArmor {
                top: 42% !important;
                right: 5% !important;
            }

            #rankingModal {
                left: auto !important;
                transform: none !important;
            }

            .largeScreenRanking {
                display: none !important;
            }

            #fightRenderer {
                left: -100px !important;
            }

            #monsterRenderer {
                position: absolute !important;
                left: -90px;
                top: 150px;
                margin: 0 !important;
                height: 300px !important;
                width: 400px !important;
            }

            #fightDmgContainer {
                flex-direction: row !important;
                flex-wrap: nowrap !important
            }

            #fightContainer {
                width: 50% !important;
            }

            #monsterInfo {
                flex-wrap: wrap;
                flex-direction: column;
            }
        }


        h1, h2, h3, h4, h5, h6 {
            font-weight: bold;
            font-family: pt-serif-caption, serif !important;
        }

        .bg-gray {
            --bs-bg-opacity: 1;
            background-color: rgba(var(--bs-secondary-rgb), var(--bs-bg-opacity)) !important;
        }

        button.tooltipItem div {
            text-align: center;
            min-width: 200px;
            margin-left: -200px;
            transform: translate(0, -50%);
            padding: 10px 10px;
            color: white;
            background-color: rgba(68, 68, 68, 0.9);
            font-weight: normal;
            font-size: 13px;
            position: absolute;
            box-sizing: border-box;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 999;
        }

        button.tooltipItem:hover div {
            display: block;
        }
    </style>
</head>
<body>
<div style="align-self: flex-start;display: flex; flex-direction: row; flex-wrap: wrap">
    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif
</div>
@guest()
    <div class="d-flex align-items-center flex-column"
         style="padding-top:25%;">
        <x-loginregister></x-loginregister>
    </div>
@else
    <div class="d-flex align-items-center justify-content-center"
         style="position: relative;padding-bottom: 50px;padding-top: 20px; width: 100%;height: 100%">
        <x-error></x-error>
        <x-info></x-info>
        <x-success></x-success>
        <x-notifytoast></x-notifytoast>
        @if(Auth::user()->isAdmin())
            <x-admin></x-admin>
        @endif
        <x-content></x-content>
    </div>
@endguest
</body>
</html>
@if(auth()->user() !== null)
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        Echo.private('notifyChannel.{{auth()->user()->api_token}}')
            .listen('.sendNotify', (e) => {
                if (document.getElementById("notifyText") != null) {
                    document.getElementById("notifyText").innerHTML = e.data;
                    let toast = $('#notifyToast').toast();
                    toast.show();
                }
            });
        Echo.private('successChannel.{{auth()->user()->api_token}}')
            .listen('.sendSuccess', (e) => {
                if (document.getElementById("successAlert") != null) {
                    document.getElementById("successAlert").innerHTML = e.data;
                    document.getElementById("successAlert").style.display = "block";
                }
            });
        Echo.private('infoChannel.{{auth()->user()->api_token}}')
            .listen('.sendInfo', (e) => {
                if (document.getElementById("infoAlert") != null) {
                    document.getElementById("infoAlert").innerHTML = e.data;
                    document.getElementById("infoAlert").style.display = "block";
                }
            });
        Echo.private('errorChannel.{{auth()->user()->api_token}}')
            .listen('.sendError', (e) => {
                if (document.getElementById("errorAlert") != null) {
                    document.getElementById("errorAlert").innerHTML = e.data;
                    document.getElementById("errorAlert").style.display = "block";
                }
            });
    </script>
@endif
