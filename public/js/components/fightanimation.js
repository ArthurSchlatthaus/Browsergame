import {
    init,
    animate,
    setVars,
    setMonster,
    setGlobalHair,
    setAnimation,
    setWeapon,
    setMonsterAnimation,
    startHitAnimation,
    clearScene, playEffect
} from "/js/threejs/fight.js";

window.initFightAnimation = async function initFightAnimation() {
    let promise = new Promise((resolve) => {
        let element = document.getElementById("fightContainer");
        if (element != null) {
            element.style.width = (parseInt(element.style.height) / 3 * 4) + 'px';//element.clientHeight
        }
        let playertmp = window.getGlobalPlayer()
        if (playertmp != null) {
            let modelPath = playertmp.modelPath
            let model = playertmp.model
            let weaponPath = playertmp.weaponPath
            let weapon = playertmp.weapon
            let animationPath = playertmp.animationPath
            let animation = playertmp.animation
            let monsterId1 = null
            let monsterId2 = null
            let monsterId3 = null
            let monsterAnimation = null
            let monsterHp1 = null
            let monsterHp2 = null
            let monsterHp3 = null
            if (window.getGlobalMonsters().monster1 != null) {
                monsterId1 = window.getGlobalMonsters().monster1.id
                monsterId2 = window.getGlobalMonsters().monster2.id
                monsterId3 = window.getGlobalMonsters().monster3.id
                monsterAnimation = window.getGlobalMonsters().monsterAnimation
            }
            if (window.getGlobalFight() != null) {
                monsterHp1 = window.getGlobalFight().monster1Hp
                monsterHp2 = window.getGlobalFight().monster2Hp
                monsterHp3 = window.getGlobalFight().monster3Hp
            }
            let hair = playertmp.hair
            setVars(modelPath, model, weaponPath, weapon, animationPath, animation)
            setGlobalHair(hair)
            init()
            animate()
            if (window.getGlobalMonsters().monster1 != null && window.getGlobalFight() != null) {
                setMonster(monsterId1, monsterId2, monsterId3, monsterAnimation)
                if (monsterHp1 <= 0 && monsterHp2 <= 0 && monsterHp3 <= 0) {
                    setWinLooseAnimation(1)
                } else if (parseInt(playertmp.hp) <= 0) {
                    setWinLooseAnimation(0)
                }
            }
            let monsterRenderer = document.getElementById('monsterRenderer')
            if (monsterRenderer != null) {
                monsterRenderer.style.marginTop = '-150px'
                monsterRenderer.style.marginLeft = '150px'
            }
            let fightRenderer = document.getElementById('fightRenderer')
            if (fightRenderer != null) {
                fightRenderer.style.position = 'absolute'
                fightRenderer.style.left = '20%'
            }
        }
        resolve("done")
    });
    return await promise
}

window.clearFightScene = function clearFightScene() {
    clearScene()
    window.setRounds(0)
    window.setBuffHits(0)
}

function setWinLooseAnimation(isWinner) {
    if (isWinner === 1) {
        setWeapon(window.getGlobalPlayer().weaponPath, 'none')
        setAnimation(window.getGlobalPlayer().animationPath, 'A_Congratulation.fbx')
    } else {
        setWeapon(window.getGlobalPlayer().weaponPath, 'none')
        setAnimation(window.getGlobalPlayer().animationPath, 'A_Dead.fbx')
    }
}

window.clearDmg = function clearDmg() {
    for (let i = 0; i < 8; i++) {
        for (let j = 0; j < 9; j++) {
            if (document.getElementById('playerDmg' + i + '_' + j) != null) {
                document.getElementById('playerDmg' + i + '_' + j).src = ""
                document.getElementById('playerDmg' + i + '_' + j).style.visibility = "hidden";
            }
        }
    }
    for (let i = 0; i < 6; i++) {
        for (let j = 0; j < 5; j++) {
            if (document.getElementById('monsterDmg' + i + '_' + j) != null) {
                document.getElementById('monsterDmg' + i + '_' + j).src = ""
                document.getElementById('monsterDmg' + i + '_' + j).style.visibility = "hidden";
            }
        }
    }
}

function setAnimationMonster(isHit = false, result) {
    let monster1Animation;
    let monster2Animation;
    let monster3Animation;
    let rand;
    if (isHit) {
        rand = Math.floor(Math.random() * 101);
        if (rand < 33) {
            monster1Animation = 'hit_1';
        } else if (rand < 66) {
            monster1Animation = 'hit_2';
        } else {
            monster1Animation = 'hit_3';
        }
        rand = Math.floor(Math.random() * 101);
        if (rand < 33) {
            monster2Animation = 'hit_1';
        } else if (rand < 66) {
            monster2Animation = 'hit_2';
        } else {
            monster2Animation = 'hit_3';
        }
        rand = Math.floor(Math.random() * 101);
        if (rand < 33) {
            monster3Animation = 'hit_1';
        } else if (rand < 66) {
            monster3Animation = 'hit_2';
        } else {
            monster3Animation = 'hit_3';
        }
        let monster1HpElement = document.getElementById('monster1Hp')
        let monster2HpElement = document.getElementById('monster2Hp')
        let monster3HpElement = document.getElementById('monster3Hp')
        if (monster1HpElement != null && parseInt(monster1HpElement.value) <= 0) {
            monster1Animation = 'none'
        }
        if (monster2HpElement != null && parseInt(monster2HpElement.value) <= 0) {
            monster2Animation = 'none'
        }
        if (monster3HpElement != null && parseInt(monster3HpElement.value) <= 0) {
            monster3Animation = 'none'
        }
    } else {
        if (result.fight != null && result.fight.monster1Hp <= 0) {
            let rand = Math.floor(Math.random() * 101);
            if (rand >= 50) {
                monster1Animation = 'dead_1';
            } else {
                monster1Animation = 'dead_2';
            }
        } else {
            rand = Math.floor(Math.random() * 101);
            if (rand < 33) {
                monster1Animation = 'wait_1';
            } else if (rand < 66) {
                monster1Animation = 'wait_2';
            } else {
                monster1Animation = 'wait_3';
            }
        }
        if (result.fight != null && result.fight.monster2Hp <= 0) {
            rand = Math.floor(Math.random() * 101);
            if (rand >= 50) {
                monster2Animation = 'dead_1';
            } else {
                monster2Animation = 'dead_2';
            }
        } else {
            rand = Math.floor(Math.random() * 101);
            if (rand < 33) {
                monster2Animation = 'wait_1';
            } else if (rand < 66) {
                monster2Animation = 'wait_2';
            } else {
                monster2Animation = 'wait_3';
            }
        }
        if (result.fight != null && result.fight.monster3Hp <= 0) {
            rand = Math.floor(Math.random() * 101);
            if (rand >= 50) {
                monster3Animation = 'dead_1';
            } else {
                monster3Animation = 'dead_2';
            }
        } else {
            rand = Math.floor(Math.random() * 101);
            if (rand < 33) {
                monster3Animation = 'wait_1';
            } else if (rand < 66) {
                monster3Animation = 'wait_2';
            } else {
                monster3Animation = 'wait_3';
            }
        }
    }
    setMonsterAnimation(monster1Animation, monster2Animation, monster3Animation);
}

function onOffPotions(state) {
    if (state) {
        window.setButtonActive("redPotion_active", true)
        window.setButtonActive("redPotion_active_mobile", true)
        window.setButtonActive("redPotion_inActive", false)
        window.setButtonActive("redPotion_inActive_mobile", false)

        window.setButtonActive("bluePotion_active", true)
        window.setButtonActive("bluePotion_active_mobile", true)
        window.setButtonActive("bluePotion_inActive", false)
        window.setButtonActive("bluePotion_inActive_mobile", false)
    } else {
        window.setButtonActive("redPotion_active", false)
        window.setButtonActive("redPotion_active_mobile", false)
        window.setButtonActive("redPotion_inActive", true)
        window.setButtonActive("redPotion_inActive_mobile", true)

        window.setButtonActive("bluePotion_active", false)
        window.setButtonActive("bluePotion_active_mobile", false)
        window.setButtonActive("bluePotion_inActive", true)
        window.setButtonActive("bluePotion_inActive_mobile", true)
    }
}

window.onOffButtons = function onOffButtons(state) {
    if (state) {
        window.setButtonActive("attBtn_active", true)
        window.setButtonActive("attBtn_inActive", false)
        window.setButtonActive("attBtn_mobile_active", true)
        window.setButtonActive("attBtn_mobile_inActive", false)
        window.setButtonActive("skillBtn1_active", true)
        window.setButtonActive("skillBtn1_inActive", false)
        window.setButtonActive("skillBtn2_active", true)
        window.setButtonActive("skillBtn2_inActive", false)
        window.setButtonActive("skillBtn1_mobile_active", true)
        window.setButtonActive("skillBtn1_mobile_inActive", false)
        window.setButtonActive("skillBtn2_mobile_active", true)
        window.setButtonActive("skillBtn2_mobile_inActive", false)
    } else {
        window.setButtonActive("attBtn_active", false)
        window.setButtonActive("attBtn_inActive", true)
        window.setButtonActive("attBtn_mobile_active", false)
        window.setButtonActive("attBtn_mobile_inActive", true)
        window.setButtonActive("skillBtn1_active", false)
        window.setButtonActive("skillBtn1_inActive", true)
        window.setButtonActive("skillBtn2_active", false)
        window.setButtonActive("skillBtn2_inActive", true)
        window.setButtonActive("skillBtn1_mobile_active", false)
        window.setButtonActive("skillBtn1_mobile_inActive", true)
        window.setButtonActive("skillBtn2_mobile_active", false)
        window.setButtonActive("skillBtn2_mobile_inActive", true)
    }
    onOffPotions(state)
    if (state) {
        window.checkCanUseSkill()
    }
}

function checkAfterAttack(result) {
    window.setGlobalFight(result.fight)
    window.setGlobalPlayer(result.player)

    if (result.fight === null) {
        window.cancelFight()
        return
    }

    let end = false
    if (typeof result.win != "undefined") {
        if (result.win === true) {
            setWinLooseAnimation(1)
        } else {
            setWinLooseAnimation(0)
        }
        end = true
    } else {
        setAnimation(result.player.animationPath, result.player.animation);
        onOffButtons(true)
    }

    setAnimationMonster(false, result)
    showDamageAndHP(result)
    window.setPlayerHpSp(result.player, end)
    window.setFightAndMonster(result.fight, result.monsters)
    window.setPlayerValues()
    window.loadInventory()
    window.setPlayerStatusBar()
}

function showDamageAndHP(result) {
    let playerProfileHpElement = document.getElementById('playerProfileHp')
    let playerProfileSpElement = document.getElementById('playerProfileSp')
    let healthBarMonster1 = document.getElementById('healthBarMonster1')
    let healthBarMonster2 = document.getElementById('healthBarMonster2')
    let healthBarMonster3 = document.getElementById('healthBarMonster3')
    if (playerProfileHpElement != null) {
        playerProfileHpElement.innerText = Math.max(0, Math.round(result.player.hp)) + '/' + Math.max(0, Math.round(result.player.maxHp))
    }
    if (playerProfileSpElement != null) {
        playerProfileSpElement.innerText = Math.max(0, Math.round(result.player.sp)) + '/' + Math.max(0, Math.round(result.player.maxSp))
    }
    if (healthBarMonster1 != null && window.getGlobalMonsters().monster1 != null) {
        healthBarMonster1.innerText = Math.round(result.fight.monster1Hp) + '/' + Math.round(window.getGlobalMonsters().monster1.hp)
    }
    if (healthBarMonster2 != null && window.getGlobalMonsters().monster2 != null) {
        healthBarMonster2.innerText = Math.round(result.fight.monster2Hp) + '/' + Math.round(window.getGlobalMonsters().monster2.hp)
    }
    if (healthBarMonster3 != null && window.getGlobalMonsters().monster3 != null) {
        healthBarMonster3.innerText = Math.round(result.fight.monster3Hp) + '/' + Math.round(window.getGlobalMonsters().monster3.hp)
    }
    let playerMonsterDmgArray = result.fight.playerMonsterDmgArray
    if (playerMonsterDmgArray.includes('//')) {
        playerMonsterDmgArray = playerMonsterDmgArray.split("//")
        let playerDmgArray = playerMonsterDmgArray[0].split(",")
        let monsterDmgArray = playerMonsterDmgArray[1].split(",")
        for (let i = 0; i < playerDmgArray.length; i++) {
            if (playerDmgArray[i] !== '') {
                let len
                if (playerDmgArray[i].includes('.')) {
                    len = playerDmgArray[i].split(".")[0].length
                } else {
                    len = playerDmgArray[i].length
                }

                for (let j = 0; j < len; j++) {
                    if (document.getElementById('playerDmg' + i + '_' + j) != null) {
                        document.getElementById('playerDmg' + i + '_' + j).src = "/images/damage/target_" + playerDmgArray[i].charAt(j) + ".png"
                        document.getElementById('playerDmg' + i + '_' + j).style.visibility = "visible";
                    }
                }
            }
        }
        for (let i = 0; i < monsterDmgArray.length; i++) {
            if (monsterDmgArray[i] !== '') {
                let len
                if (monsterDmgArray[i].includes('.')) {
                    len = monsterDmgArray[i].split(".")[0].length
                } else {
                    len = monsterDmgArray[i].length
                }
                for (let j = 0; j < len; j++) {
                    if (document.getElementById('monsterDmg' + i + '_' + j) != null) {
                        document.getElementById('monsterDmg' + i + '_' + j).src = "/images/damage/damage_" + monsterDmgArray[i].charAt(j) + ".png"
                        document.getElementById('monsterDmg' + i + '_' + j).style.visibility = "visible";
                    }
                }
            }
        }
    }
}

window.sendAttack = function sendAttack() {
    onOffButtons(false)
    clearDmg()
    if (window.getGlobalPlayer().weaponPath.includes('bow')) {
        setAnimation(window.getGlobalPlayer().animationPath, window.getGlobalPlayer().hitAnimation);
    } else {
        startHitAnimation()
    }
    setAnimationMonster(true, null)
    setTimeout(() => {
        $.ajax({
            url: "/sendAttack",
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                checkAfterAttack(result)
            }
        });
    }, 1000);
}


$("#skillBtn1_active").click(function (e) {
    onOffButtons(false)
    clearDmg()
    let playertmp = window.getGlobalPlayer()
    if (playertmp.race === 1) {//warrior
        playEffect(playertmp.skill1.id)
    }
    setAnimation(playertmp.skillPath, playertmp.skill1Animation)
    setAnimationMonster(true, null)
    e.preventDefault();
    setTimeout(() => {
        $.ajax({
            url: "/sendSkill",
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                skill_id: playertmp.skill1.id,
            },
            success: function (result) {
                checkAfterAttack(result)
            }
        });
    }, 1000);
});
$("#skillBtn1_mobile_active").click(function (e) {
    onOffButtons(false)
    clearDmg()
    let playertmp = window.getGlobalPlayer()
    setAnimation(playertmp.skillPath, playertmp.skill1Animation)
    setAnimationMonster(true, null)
    e.preventDefault();
    setTimeout(() => {
        $.ajax({
            url: "/sendSkill",
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                skill_id: playertmp.skill1.id,
            },
            success: function (result) {
                checkAfterAttack(result)
            }
        });
    }, 1000);
});
$("#skillBtn2_active").click(function (e) {
    onOffButtons(false)
    clearDmg()
    let playertmp = window.getGlobalPlayer()
    setAnimation(playertmp.skillPath, playertmp.skill2Animation)
    setAnimationMonster(true, null)
    e.preventDefault();
    setTimeout(() => {
        $.ajax({
            url: "/sendSkill",
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                skill_id: playertmp.skill2.id,
            },
            success: function (result) {
                checkAfterAttack(result)
            }
        });
    }, 1000);
});
$("#skillBtn2_mobile_active").click(function (e) {
    onOffButtons(false)
    clearDmg()
    let playertmp = window.getGlobalPlayer()
    setAnimation(playertmp.skillPath, playertmp.skill2Animation)
    setAnimationMonster(true, null)
    e.preventDefault();
    setTimeout(() => {
        $.ajax({
            url: "/sendSkill",
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                skill_id: playertmp.skill2.id,
            },
            success: function (result) {
                checkAfterAttack(result)
            }
        });
    }, 1000);
});

