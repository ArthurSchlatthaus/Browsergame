<script>
    let attacker = null
    let defender = false
    let globalPvp = null
    window.initPvpValues = function initPvpValues() {
        let globalPvp = window.getGlobalPvPFight()
        if (globalPvp != null && globalPvp.isActive !== 0) {
            document.getElementById("pvp").style.display = "block"
            document.getElementById("noPvp").style.display = "none"
            attacker = window.getGlobalPlayer()
            defender = globalPvp.defender
            if (attacker != null && defender) {
                document.getElementById("attackerNamePvP").innerText = attacker.name
                document.getElementById("defenderNamePvP").innerText = globalPvp.defenderName

                document.getElementById("attackerHpPvP").innerText = globalPvp.attackerHp + '/' + attacker.maxHp
                document.getElementById("defenderHpPvP").innerText = globalPvp.defenderHp + '/' + globalPvp.defenderMaxHp

                document.getElementById("attackerSpPvP").innerText = globalPvp.attackerSp + '/' + attacker.maxSp
                document.getElementById("defenderSpPvP").innerText = globalPvp.defenderSp + '/' + globalPvp.defenderMaxSp

                document.getElementById("attackerDmgPvP").innerText = globalPvp.attackerDmg
                document.getElementById("defenderDmgPvP").innerText = globalPvp.defenderDmg

                document.getElementById("attackerBuffPvP").innerText = globalPvp.attackerBuffId + '(' + globalPvp.attackerBuffDuration + ' Rounds)'
                document.getElementById("defenderBuffPvP").innerText = globalPvp.defenderBuffId + '(' + globalPvp.defenderBuffDuration + ' Rounds)'

                document.getElementById("roundsPvP").innerText = globalPvp.rounds

                window.preLoadAnimations()
            }
        } else {
            document.getElementById("noPvp").style.display = "block"
            document.getElementById("pvp").style.display = "none"
        }
    }
    let fightIsOver = false

    function cancelPvp() {
        $.ajax({
            url: "{{url('cancelPvp')}}",
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (result) {
                window.setGlobalPlayer(result.player)
                window.setGlobalPvPFight(result.pvp)
                window.setPlayerStatusBar()
                window.setPlayerValues()
                window.closeAllWindows()
                window.initPvpValues()
                fightIsOver = false
            }
        })
    }
</script>
<div id="pvp" style="display: none;padding: 4px;margin-top:-36px;min-width: 400px;min-height: 350px">
    <div class="table-responsive">
        <table class="table table-borderless" style="color: lightgrey; ">
            <thead>
            <tr>
                <th></th>
                <th>Attacker</th>
                <th>Defender</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th>Name</th>
                <td id="attackerNamePvP"></td>
                <td id="defenderNamePvP"></td>
            </tr>
            <tr>
                <th>Hp</th>
                <td id="attackerHpPvP"></td>
                <td id="defenderHpPvP"></td>
            </tr>
            <tr>
                <th>Sp</th>
                <td id="attackerSpPvP"></td>
                <td id="defenderSpPvP"></td>
            </tr>
            <tr>
                <th>DMG</th>
                <td style="word-wrap: break-word; max-width: 150px" id="attackerDmgPvP"></td>
                <td style="word-wrap: break-word; max-width: 150px" id="defenderDmgPvP"></td>
            </tr>
            <tr>
                <th>Buff</th>
                <td id="attackerBuffPvP"></td>
                <td id="defenderBuffPvP"></td>
            </tr>
            <tr>
                <th>Rounds</th>
                <td id="roundsPvP"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div style="display: flex; flex-direction: row; justify-content: center;">
        <img src="/images/pvp/arena.png" width="300">
        <div id="attackerContainer"
             style="position: absolute;left: 115px;bottom:50px;height: 100px;width: 100px"></div>
        <div id="defenderContainer"
             style="position: absolute;right: 150px;bottom:50px;height: 100px;width: 100px"></div>
    </div>
    <div style="position: absolute; left: 50%;transform: translateX(-50%); bottom: 10px">
        <button id="sendAttackBtn"
                onclick="sendPvpAttack()" class="btn btn-dark">{{__('custom.attack')}}</button>
        <button id="sendAttackBtn"
                onclick="cancelPvp()" class="btn btn-dark">{{__('custom.cancel_fight')}}</button>
    </div>
</div>

<script type="module">
    import {
        init,
        animate,
        setVarsAttacker,
        setVarsDefender,
        setCamPos,
        setAnimationAttacker,
        setAnimationDefender,
        startHitAnimationAttacker,
        startHitAnimationDefender,
        setWeaponAttacker,
        setWeaponDefender,
        preLoadAnimationAttacker,
        preLoadAnimationDefender
    } from "/js/threejs/pvp.js";

    window.preLoadAnimations = function preLoadAnimations() {
        globalPvp = window.getGlobalPvPFight()
        attacker = window.getGlobalPlayer()
        if (attacker != null && globalPvp != null) {
            setVarsAttacker(attacker.modelPath, attacker.model, attacker.weaponPath, attacker.weapon, attacker.animationPath, attacker.animation, attacker.hair);
            setVarsDefender(globalPvp.defenderModelPath, globalPvp.defenderModel, globalPvp.defenderWeaponPath, globalPvp.defenderWeapon, globalPvp.defenderAnimationPath, globalPvp.defenderAnimation, globalPvp.defenderHair);
            setCamPos(0, 20, 400)
            init();
        }
    }

    window.animatePvP = function animatePvP() {
        globalPvp = window.getGlobalPvPFight()
        attacker = window.getGlobalPlayer()
        if (globalPvp != null && attacker != null) {
            document.getElementById('sendAttackBtn').style.display = 'none'
            if (globalPvp.winner === globalPvp.attackerId) {
                setAnimationAttacker(attacker.animationPathWinner, attacker.winningDance);
                setAnimationDefender(globalPvp.defenderAnimationPath, 'A_Dead.fbx');
                setWeaponAttacker(attacker.weaponPath, 'none')
                setWeaponDefender(globalPvp.defenderWeaponPath, 'none')
            } else if (globalPvp.winner === globalPvp.defenderId) {
                setAnimationAttacker(attacker.animationPath, 'A_Dead.fbx');
                setAnimationDefender(globalPvp.defenderAnimationPathWinner, globalPvp.defenderWinningDance);
                setWeaponAttacker(attacker.weaponPath, 'none')
                setWeaponDefender(globalPvp.defenderWeaponPath, 'none')
            } else {
                setAnimationAttacker(attacker.animationPath, attacker.animation);
                setAnimationDefender(globalPvp.defenderAnimationPath, globalPvp.defenderAnimation);
                document.getElementById('sendAttackBtn').style.display = 'block'
                setWeaponAttacker(attacker.weaponPath, attacker.weapon)
                setWeaponDefender(globalPvp.defenderWeaponPath, globalPvp.defenderWeapon)
            }

        }
        animate();
    }

    window.sendPvpAttack = function sendPvpAttack() {
        globalPvp = window.getGlobalPvPFight()
        attacker = window.getGlobalPlayer()
        document.getElementById('sendAttackBtn').style.display = 'none'
        if (fightIsOver) {
            return
        }
        if (globalPvp != null) {
            $.ajax({
                url: "{{url('getPvpAttack')}}",
                method: 'GET',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (result) {
                    if (result !== 'none') {
                        startHitAnimationAttacker(result.attacker.path, result.attacker.animation)
                        startHitAnimationDefender(result.defender.path, result.defender.animation)
                    } else {
                        startHitAnimationAttacker(globalPvp.attackerSkillPath, 'none')
                        startHitAnimationDefender(globalPvp.defenderSkillPath, 'none')
                    }
                    $.ajax({
                        url: "{{url('sendPvpAttack')}}",
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function (result) {
                            if (typeof result.pvp != 'undefined') {
                                window.setGlobalPvPFight(result.pvp)
                            }
                            if (typeof result.end == 'undefined' || !result.end) {
                                setAnimationAttacker(globalPvp.attackerAnimationPath, globalPvp.attackerAnimation)
                                setAnimationDefender(globalPvp.defenderAnimationPath, globalPvp.defenderAnimation)
                            }
                            if (typeof result.pvp != 'undefined' && result.pvp != null) {
                                document.getElementById('attackerHpPvP').innerText = result.pvp.attackerHp + '/' + attacker.maxHp
                                document.getElementById('defenderHpPvP').innerText = result.pvp.defenderHp + '/' + globalPvp.defenderMaxHp
                                document.getElementById('attackerSpPvP').innerText = result.pvp.attackerSp + '/' + attacker.maxSp
                                document.getElementById('defenderSpPvP').innerText = result.pvp.defenderSp + '/' + globalPvp.defenderMaxSp
                                document.getElementById('attackerDmgPvP').innerText = result.pvp.attackerDmg
                                document.getElementById('defenderDmgPvP').innerText = result.pvp.defenderDmg
                                document.getElementById('attackerBuffPvP').innerText = result.pvp.attackerBuffId + '(' + result.pvp.attackerBuffDuration + ' Rounds)'
                                document.getElementById('defenderBuffPvP').innerText = result.pvp.defenderBuffId + '(' + result.pvp.defenderBuffDuration + ' Rounds)'
                                document.getElementById('roundsPvP').innerText = result.pvp.rounds
                                document.getElementById('sendAttackBtn').style.display = 'block'
                            }

                            if (typeof result.end != 'undefined' && result.pvp != null) {
                                if (result.end) {
                                    document.getElementById('sendAttackBtn').style.display = 'none'
                                    fightIsOver = true
                                    setWeaponAttacker(attacker.weaponPath, 'none')
                                    setWeaponDefender(globalPvp.defenderWeaponPath, 'none')
                                    if (result.pvp.winner === globalPvp.attackerId) {
                                        setAnimationAttacker(attacker.animationPathWinner, attacker.winningDance);
                                        setAnimationDefender(globalPvp.defenderAnimationPath, 'A_Dead.fbx');
                                    } else if (result.pvp.winner === globalPvp.defenderId) {
                                        setAnimationAttacker(globalPvp.attackerAnimationPath, 'A_Dead.fbx');
                                        setAnimationDefender(globalPvp.defenderAnimationPathWinner, globalPvp.defenderWinningDance);
                                    } else {
                                        setAnimationAttacker(globalPvp.attackerAnimationPath, globalPvp.attackerAnimation);
                                        setAnimationDefender(globalPvp.defenderAnimationPath, globalPvp.defenderAnimation);
                                    }
                                }
                            }
                        }
                    })
                }
            })
        }

    }
</script>

<div id="noPvp" style="display: none;width: 150px; height: 100px; padding: 8px">
    <h6>No active PVP-Fight</h6>
</div>
