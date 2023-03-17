let player = null
window.getGlobalPlayer = function getGlobalPlayer() {
    return player;
}
window.setGlobalPlayer = function setGlobalPlayer(playerTmp) {
    player = playerTmp;
}
let activeFight = null
window.getGlobalFight = function getGlobalFight() {
    return activeFight;
}
window.setGlobalFight = function setGlobalFight(fightTmp) {
    activeFight = fightTmp;
}
let activePvPFight = null
window.getGlobalPvPFight = function getGlobalPvPFight() {
    return activePvPFight;
}
window.setGlobalPvPFight = function setGlobalPvPFight(fightTmp) {
    activePvPFight = fightTmp;
}
let monsters = {"monster1": null, "monster2": null, "monster3": null}
window.getGlobalMonsters = function getGlobalMonsters() {
    return monsters;
}
window.setGlobalMonsters = function setGlobalMonsters(monstersTmp) {
    monsters = monstersTmp;
}
let messages = null
window.getGlobalMessages = function getGlobalMessages() {
    return messages;
}
window.setGlobalMessages = function setGlobalMessages(messagesTmp) {
    messages = messagesTmp;
}

function getData() {
    $.ajax({
        url: "/getData", method: 'POST', data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        }, success: function (result) {
            window.setGlobalPlayer(result.player)
            if (result.player != null && result.player.isLoggedIn === 1) {
                window.setGlobalFight(result.fight)
                window.setGlobalPvPFight(result.pvp)
                window.setGlobalMonsters(result.monsters)
                window.setGlobalMessages(result.messages)
                const loadingScreen = document.getElementById('loading-screen')
                if (loadingScreen != null) {
                    let mainWrapper = document.getElementById('mainWrapper')
                    let loadingText = document.getElementById('loadingText')
                    loadingScreen.style.display = 'block'
                    if (mainWrapper != null) {
                        mainWrapper.style.visibility = 'hidden'
                    }
                    loadingText.innerText = 'loading...'
                    setTimeout(() => {
                        window.loadInventory().then(() => {
                            loadingText.innerText = 'loading ...'
                            setTimeout(() => {
                                window.loadEquipment().then(() => {
                                    loadingText.innerText = 'loading...'
                                    setTimeout(() => {
                                        window.setPlayerValues().then(() => {
                                            loadingText.innerText = 'loading ...'
                                            setTimeout(() => {
                                                window.setPlayerStatusBar().then(() => {
                                                    loadingText.innerText = 'loading...'
                                                    setTimeout(() => {
                                                        try {
                                                            window.initMainRender().then(() => {
                                                                window.initMessages()
                                                                if (result.fight != null && result.monsters != null) {
                                                                    loadingText.innerText = 'loading ...'
                                                                    setTimeout(() => {
                                                                        window.initFightAnimation().then(() => {
                                                                            loadingText.innerText = 'loading...'
                                                                            setTimeout(() => {
                                                                                window.setFightAndMonster(window.getGlobalFight(), window.getGlobalMonsters()).then(() => {
                                                                                    window.setPlayerSkills()
                                                                                    window.loadFightMonsterInfos()
                                                                                    window.initPlayerFight()
                                                                                    window.initPvpValues()
                                                                                    //window.clearFightScene()
                                                                                    loadingScreen.style.display = 'none'
                                                                                    if (mainWrapper != null) {
                                                                                        mainWrapper.style.visibility = 'visible'
                                                                                    }
                                                                                    document.getElementById("navbarContent").style.display = "block"
                                                                                })
                                                                            }, 500)

                                                                        })
                                                                    }, 500)
                                                                } else {
                                                                    window.setPlayerSkills()
                                                                    window.initPvpValues()
                                                                    loadingScreen.style.display = 'none'
                                                                    if (mainWrapper != null) {
                                                                        mainWrapper.style.visibility = 'visible'
                                                                    }
                                                                    document.getElementById("navbarContent").style.display = "block"
                                                                }
                                                            })
                                                        } catch (e) {
                                                            getData()
                                                        }
                                                    }, 900)
                                                })
                                            }, 800)
                                        })
                                    }, 700)
                                })
                            }, 600)
                        })
                    }, 500)
                }
            }

        }
    });
}

getData()
