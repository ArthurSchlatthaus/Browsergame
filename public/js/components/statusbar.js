window.setButtonActive = function setButtonActive(id, active) {
    if (active) {
        if (document.getElementById(id) != null) {
            document.getElementById(id).style.display = 'block'
        }
    } else {
        if (document.getElementById(id) != null) {
            document.getElementById(id).style.display = 'none'
        }
    }
}
window.setExpProgress = function setExpProgress(percent) {
    if (percent <= 25) {
        document.getElementById('expBubble1').style.clipPath = "inset(" + (25 - percent) + "px 0 0 0)";
        document.getElementById('expBubble2').style.clipPath = "inset(20px 0 0 0)";
        document.getElementById('expBubble3').style.clipPath = "inset(20px 0 0 0)";
        document.getElementById('expBubble4').style.clipPath = "inset(20px 0 0 0)";
    } else if (percent <= 50) {
        document.getElementById('expBubble1').style.clipPath = "inset(0 0 0 0)";
        document.getElementById('expBubble2').style.clipPath = "inset(" + (50 - percent) + "px 0 0 0)";
        document.getElementById('expBubble3').style.clipPath = "inset(20px 0 0 0)";
        document.getElementById('expBubble4').style.clipPath = "inset(20px 0 0 0)";
    } else if (percent <= 75) {
        document.getElementById('expBubble1').style.clipPath = "inset(0 0 0 0)";
        document.getElementById('expBubble2').style.clipPath = "inset(0 0 0 0)";
        document.getElementById('expBubble3').style.clipPath = "inset(" + (75 - percent) + "px 0 0 0)";
        document.getElementById('expBubble4').style.clipPath = "inset(20px 0 0 0)";
    } else if (percent <= 100) {
        document.getElementById('expBubble1').style.clipPath = "inset(0 0 0 0)";
        document.getElementById('expBubble2').style.clipPath = "inset(0 0 0 0)";
        document.getElementById('expBubble3').style.clipPath = "inset(0 0 0 0)";
        document.getElementById('expBubble4').style.clipPath = "inset(" + (100 - percent) + "px 0 0 0)";
    } else {
        document.getElementById('expBubble1').style.clipPath = "inset(0 0 0 0)";
        document.getElementById('expBubble2').style.clipPath = "inset(0 0 0 0)";
        document.getElementById('expBubble3').style.clipPath = "inset(0 0 0 0)";
        document.getElementById('expBubble4').style.clipPath = "inset(0 0 0 0)";
    }
}

let redPotionVnum = 0
let bluePotionVnum = 0
let playerStatus = null
let counter = 0
window.setPlayerStatusBar = async function setPlayerStatusBar() {
    counter++
    let promise = new Promise((resolve) => {
        playerStatus = window.getGlobalPlayer()
        if (playerStatus != null) {
            redPotionVnum = playerStatus.redPotionVnum
            bluePotionVnum = playerStatus.bluePotionVnum
            if (playerStatus.hasUnreadMsg) {
                if (document.getElementById("hasUnreadMsg") != null) {
                    document.getElementById("hasUnreadMsg").style.display = "block"
                }
                if (document.getElementById("hasUnreadMsg_mobile") != null) {
                    document.getElementById("hasUnreadMsg_mobile").style.display = "block"
                }
            } else {
                if (document.getElementById("hasUnreadMsg") != null) {
                    document.getElementById("hasUnreadMsg").style.display = "none"
                }
                if (document.getElementById("hasUnreadMsg_mobile") != null) {
                    document.getElementById("hasUnreadMsg_mobile").style.display = "none"
                }
            }
            if (playerStatus.class > 0) {
                document.getElementById("skills").style.display = "block"
                if (playerStatus.skill1 != null && playerStatus.skill0level > 0) {
                    document.getElementById("skill1").style.display = "block"
                    document.getElementById("skill1Img").src = "images/skills/" + playerStatus.skill1.id + "_1.png"
                }
                if (playerStatus.skill2 != null && playerStatus.skill1level > 0) {
                    document.getElementById("skill2").style.display = "block"
                    document.getElementById("skill2Img").src = "images/skills/" + playerStatus.skill2.id + "_1.png"
                }
            } else {
                document.getElementById("skill1").style.display = "none"
                document.getElementById("skill2").style.display = "none"
                document.getElementById("skill1Img").src = "images/skills/0_1.png"
                document.getElementById("skill2Img").src = "images/skills/0_1.png"
            }
            window.checkCanUseSkill()
            window.setHpProgress(Math.round(playerStatus.hp / playerStatus.maxHp * 100), false)
            window.setSpProgress(Math.round(playerStatus.sp / playerStatus.maxSp * 100), false)
            setExpProgress((playerStatus.exp / (playerStatus.level * 200)) * 100)
            checkStatusBar()
            window.enableAttBtn()
        } else {
            if (counter < 2) {
                setTimeout(function () {
                    setPlayerStatusBar()
                }, 3000);
            }
        }
        resolve("done")
    });
    return await promise
}

let playerHp = null
let playerMaxHp = null
let playerSp = null
let playerMaxSp = null
let i = 0;

window.setHpProgress = function setHpProgress(percent, end) {
    document.getElementById('healthBar').setAttribute('aria-valuenow', percent);
    document.getElementById('healthBar').setAttribute('style', 'width:' + Number(percent) + '%');
    document.getElementById('healthBar').textContent = Number(percent) + '%';
    if (!end) {
        if (redPotionVnum === 0) {
            setButtonActive('redPotion_inActive', true)
            setButtonActive('redPotion_active', false)
            setButtonActive('redPotion_inActive_mobile', true)
            setButtonActive('redPotion_active_mobile', false)
        } else if (percent <= 80) {
            setButtonActive('redPotion_inActive', false)
            setButtonActive('redPotion_active', true)
            setButtonActive('redPotion_inActive_mobile', false)
            setButtonActive('redPotion_active_mobile', true)
        } else {
            setButtonActive('redPotion_inActive', true)
            setButtonActive('redPotion_active', false)
            setButtonActive('redPotion_inActive_mobile', true)
            setButtonActive('redPotion_active_mobile', false)
        }
    } else {
        setButtonActive('redPotion_inActive', true)
        setButtonActive('redPotion_active', false)
        setButtonActive('redPotion_inActive_mobile', true)
        setButtonActive('redPotion_active_mobile', false)
    }
}

window.setSpProgress = function setSpProgress(percent, end) {
    document.getElementById('manaBar').setAttribute('aria-valuenow', percent);
    document.getElementById('manaBar').setAttribute('style', 'width:' + Number(percent) + '%');
    document.getElementById('manaBar').textContent = Number(percent) + '%';
    if (!end) {
        if (bluePotionVnum === 0) {
            setButtonActive('bluePotion_inActive', true)
            setButtonActive('bluePotion_active', false)
            setButtonActive('bluePotion_inActive_mobile', true)
            setButtonActive('bluePotion_active_mobile', false)
        } else if (percent <= 80) {
            setButtonActive('bluePotion_inActive', false)
            setButtonActive('bluePotion_active', true)
            setButtonActive('bluePotion_inActive_mobile', false)
            setButtonActive('bluePotion_active_mobile', true)
        } else {
            setButtonActive('bluePotion_inActive', true)
            setButtonActive('bluePotion_active', false)
            setButtonActive('bluePotion_inActive_mobile', true)
            setButtonActive('bluePotion_active_mobile', false)
        }
    } else {
        setButtonActive('bluePotion_inActive', true)
        setButtonActive('bluePotion_active', false)
        setButtonActive('bluePotion_inActive_mobile', true)
        setButtonActive('bluePotion_active_mobile', false)
    }
}
window.setRounds = function setRounds(rounds) {
    document.getElementById('roundCount').textContent = window.trans('custom.rounds') + ':' + rounds
}
window.setBuffHits = function setBuffHits(duration) {
    document.getElementById('buffCount').textContent = window.trans('custom.buff_duration') + ':' + duration
}

window.checkStatusBar = function checkStatusBar() {
    document.getElementById('activeFight').style.display = 'none'
    document.getElementById('openFight').style.display = 'none'
    document.getElementById('closeFight').style.display = 'none'
    document.getElementById('openMissionModal').style.display = 'none'
    document.getElementById('activeFight_Mobile').style.display = 'none'
    document.getElementById('openFight_Mobile').style.display = 'none'
    document.getElementById('closeFight_Mobile').style.display = 'none'
    document.getElementById('openMissionModal_Mobile').style.display = 'none'
    let fightTmp = window.getGlobalFight()
    if (fightTmp != null && fightTmp.isActive === 1) {
        if (document.getElementById('fightWrapper').style.visibility !== 'hidden') {
            document.getElementById('fightDmgContainerLayout').style.display = 'block'
            document.getElementById('activeFight').style.display = 'block'
            document.getElementById('activeFight_Mobile').style.display = 'block'
        } else {
            document.getElementById('openFight').style.display = 'block'
            document.getElementById('openFight_Mobile').style.display = 'block'
            document.getElementById('fightDmgContainerLayout').style.display = 'none'
        }
        //document.getElementById('closeFight').style.display = 'block'
    } else {
        setTimeout(function () {
            window.clearFightScene()
        }, 2000);
        document.getElementById('openMissionModal').style.display = 'block'
        document.getElementById('openMissionModal_Mobile').style.display = 'block'
    }
}

window.enableAttBtn = function enableAttBtn() {
    let fight = window.getGlobalFight()
    if (fight != null) {
        if (fight.monster1Hp > 0 || fight.monster2Hp > 0 || fight.monster3Hp > 0) {
            setButtonActive('attBtn_active', true)
            setButtonActive('attBtn_inActive', false)
            setButtonActive('attBtn_mobile_active', true)
            setButtonActive('attBtn_mobile_inActive', false)
        } else {
            setButtonActive('attBtn_active', false)
            setButtonActive('attBtn_inActive', true)
            setButtonActive('attBtn_mobile_active', false)
            setButtonActive('attBtn_mobile_inActive', true)
        }
    }
}
window.checkCanUseSkill = function checkCanUseSkill() {
    if (playerStatus != null && playerStatus.canUseSkill1 === 'yes') {
        setButtonActive('skillBtn1_mobile_active', true)
        setButtonActive('skillBtn1_mobile_inActive', false)
        setButtonActive('skillBtn1_active', true)
        setButtonActive('skillBtn1_inActive', false)
    } else {
        setButtonActive('skillBtn1_mobile_inActive', true)
        setButtonActive('skillBtn1_mobile_active', false)
        setButtonActive('skillBtn1_inActive', true)
        setButtonActive('skillBtn1_active', false)
    }

    if (playerStatus != null && playerStatus.canUseSkill2 === 'yes') {
        setButtonActive('skillBtn2_mobile_active', true)
        setButtonActive('skillBtn2_mobile_inActive', false)
        setButtonActive('skillBtn2_active', true)
        setButtonActive('skillBtn2_inActive', false)
    } else {
        setButtonActive('skillBtn2_mobile_inActive', true)
        setButtonActive('skillBtn2_mobile_active', false)
        setButtonActive('skillBtn2_inActive', true)
        setButtonActive('skillBtn2_active', false)
    }

}

$(document).ready(function () {
    checkCanUseSkill()
    if (window.location.href.indexOf("sort") > -1) {
        openWindowStatus('rankingModal');
    }
});

window.closeAllWindows = function closeAllWindows() {
    let windows = ['messagesModal', 'settingsModal', 'rankingModal', 'missionsModal', 'rankingProfileModal', 'pvpModal']
    windows.forEach(window => {
        if (document.getElementById(window) != null) {
            document.getElementById(window).style.display = 'none'
        }
    })
}

function openFightWrapper() {
    let fightWrapper = document.getElementById('fightWrapper')
    let mainWrapper = document.getElementById('mainWrapper')
    if (fightWrapper != null) {
        fightWrapper.style.visibility = 'visible'
        document.getElementById('fightDmgContainerLayout').style.display = 'block'
    }
    if (mainWrapper != null) {
        mainWrapper.style.visibility = 'hidden'
    }
    checkStatusBar()
}

window.closeFightWrapper = function closeFightWrapper() {
    let fightWrapper = document.getElementById('fightWrapper')
    let mainWrapper = document.getElementById('mainWrapper')
    if (fightWrapper != null) {
        fightWrapper.style.visibility = 'hidden'
        document.getElementById('fightDmgContainerLayout').style.display = 'none'
    }
    if (mainWrapper != null) {
        mainWrapper.style.visibility = 'visible'
    }
    checkStatusBar()
}

window.cancelFight = function cancelFight() {
    $.ajax({
        url: "/cancelFight",
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (result) {
            if (result.player != null) {
                window.setGlobalPlayer(result.player)
                window.setPlayerHpSp(result.player, true)
                window.setPlayerValues()
            }
            if (result.fight != null) {
                window.setGlobalFight(result.fight)
            }
            closeFightWrapper()
            window.clearFightScene()
            window.onOffButtons(true)
            window.clearDmg()
        }
    });
}

function openWindowStatus(windowID) {
    if (document.getElementById(windowID) != null) {
        if (document.getElementById(windowID).style.display === 'none') {
            closeAllWindows()
            document.getElementById(windowID).style.display = 'inline-block'
            if (windowID === 'pvpModal' && window.getGlobalPvPFight() != null) {
                window.animatePvP()
            }
        } else {
            document.getElementById(windowID).style.display = 'none'
        }
    }

}

function sendHeal(potionVnum) {
    window.onOffButtons(false)
    $.ajax({
        url: "/sendHeal",
        method: 'POST',
        data: {
            potion_id: potionVnum,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (result) {
            if (result.player != null) {
                window.setGlobalPlayer(result.player)
                window.setGlobalFight(result.fight)
                if (typeof result.usedItem != 'undefined' && result.usedItem != null) {
                    if (document.getElementById('invItemText_' + result.usedItem[0])) {
                        document.getElementById('invItemText_' + result.usedItem[0]).innerText = result.usedItem[1]
                    }
                }
                if (result.fight.isActive === 1) {
                    window.onOffButtons(true)
                }
                window.setPlayerHpSp(result.player, false)
                window.setPlayerValues()
            }
        }
    });
}

function loadElement(id) {
    if (document.getElementById(id).style.display === 'none') {
        document.getElementById(id).style.display = 'block'
    } else if (document.getElementById(id).style.display === 'block') {
        document.getElementById(id).style.display = 'none'
    }
}

window.setPlayerHpSp = function setPlayerHpSp(player, end) {
    if (player != null) {
        playerHp = Math.round(player.hp)
        playerMaxHp = Math.round(player.maxHp)
        playerSp = Math.round(player.sp)
        playerMaxSp = Math.round(player.maxSp)
        percentHp = Math.round((playerHp / playerMaxHp) * 100)
        percentSp = Math.round((playerSp / playerMaxSp) * 100)
        window.setHpProgress(percentHp, end)
        window.setSpProgress(percentSp, end)
    }
}
window.setFightAndMonster = async function setFightAndMonster(fight, monster) {
    let promise = new Promise((resolve) => {
        if (fight != null && monster != null) {
            window.setRounds(fight.rounds)
            window.setBuffHits(fight.buffDuration)
            document.getElementById('monster1Hp').value = fight.monster1Hp
            document.getElementById('monster2Hp').value = fight.monster2Hp
            document.getElementById('monster3Hp').value = fight.monster3Hp
            window.setHpProgressMonster1(Math.round((fight.monster1Hp / monster.monster1.hp) * 100))
            window.setHpProgressMonster2(Math.round((fight.monster2Hp / monster.monster2.hp) * 100))
            window.setHpProgressMonster3(Math.round((fight.monster3Hp / monster.monster3.hp) * 100))
        }
        resolve("done")
    });
    return await promise
}

//window.setFightAndMonster(window.getGlobalFight(), window.getGlobalMonsters())
