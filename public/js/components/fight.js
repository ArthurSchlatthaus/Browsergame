let playerFight = null
let fight = null
let playerDmgArray = null
let monsterDmgArray = null
window.initPlayerFight = function initPlayerFight() {
    playerFight = window.getGlobalPlayer()
    if (playerFight != null) {
        fight = window.getGlobalFight()
        if (fight != null) {
            let playerMonsterDmgArray = fight.playerMonsterDmgArray
            if (playerMonsterDmgArray.length > 2) {
                playerDmgArray = playerMonsterDmgArray.split("//")[0].split(",")
                playerDmgArray.splice(-1)
                monsterDmgArray = playerMonsterDmgArray.split("//")[1].split(",")
                monsterDmgArray.splice(-1)
                playerDmg()
                monsterDmg()
            }
        }
    }
}

function monsterDmg() {
    if (monsterDmgArray != null) {
        monsterDmgArray.forEach((damage, index) => {
            if (damage > 0) {
                Math.round(damage).toString().split('').forEach((number, index2) => {
                    document.getElementById('monsterDmg' + index + '_' + index2).src = "/images/damage/damage_" + number + ".png"
                    document.getElementById('monsterDmg' + index + '_' + index2).style.visibility = "visible";
                })
            }
        })
    }
}

function playerDmg() {
    if (playerDmgArray != null) {
        playerDmgArray.forEach((damage, index) => {
            if (damage > 0) {
                Math.round(damage).toString().split('').forEach((number, index2) => {
                    document.getElementById('playerDmg' + index + '_' + index2).src = "/images/damage/target_" + number + ".png"
                    document.getElementById('playerDmg' + index + '_' + index2).style.visibility = "visible";
                })
            }
        })
    }
}

window.loadFightMonsterInfos = function loadFightMonsterInfos() {
    if (window.getGlobalMonsters().monster1 != null && window.getGlobalMonsters().monster2 != null && window.getGlobalMonsters().monster3 != null) {
        document.getElementById('monster1Label').innerText = window.trans('custom.level') + ' ' + window.getGlobalMonsters().monster1.level + ' ' + window.getGlobalMonsters().monster1.name
        document.getElementById('monster2Label').innerText = window.trans('custom.level') + ' ' + window.getGlobalMonsters().monster2.level + ' ' + window.getGlobalMonsters().monster2.name
        document.getElementById('monster3Label').innerText = window.trans('custom.level') + ' ' + window.getGlobalMonsters().monster3.level + ' ' + window.getGlobalMonsters().monster3.name
    }
    if (window.getGlobalMonsters().monster1 != null && window.getGlobalMonsters().monster2 != null && window.getGlobalMonsters().monster3 != null && window.getGlobalFight() != null) {
        document.getElementById('healthBarMonster1').setAttribute('style', 'width:' + Number(Math.round(window.getGlobalFight().monster1Hp / window.getGlobalMonsters().monster1.hp * 100)) + '%')
        document.getElementById('healthBarMonster1').setAttribute('aria-valuenow', window.getGlobalFight().monster1Hp / window.getGlobalMonsters().monster1.hp * 100);
        document.getElementById('healthBarMonster1').setAttribute('aria-valuemax', window.getGlobalMonsters().monster1.hp)
        document.getElementById('healthBarMonster1').textContent = Math.round(window.getGlobalFight().monster1Hp / window.getGlobalMonsters().monster1.hp * 100) + '%'

        document.getElementById('healthBarMonster2').setAttribute('style', 'width:' + Number(Math.round(window.getGlobalFight().monster2Hp / window.getGlobalMonsters().monster2.hp * 100)) + '%')
        document.getElementById('healthBarMonster2').setAttribute('aria-valuenow', window.getGlobalFight().monster2Hp / window.getGlobalMonsters().monster2.hp * 100);
        document.getElementById('healthBarMonster2').setAttribute('aria-valuemax', window.getGlobalMonsters().monster2.hp)
        document.getElementById('healthBarMonster2').textContent = Math.round(window.getGlobalFight().monster2Hp / window.getGlobalMonsters().monster2.hp * 100) + '%'

        document.getElementById('healthBarMonster3').setAttribute('style', 'width:' + Number(Math.round(window.getGlobalFight().monster3Hp / window.getGlobalMonsters().monster3.hp * 100)) + '%')
        document.getElementById('healthBarMonster3').setAttribute('aria-valuenow', window.getGlobalFight().monster3Hp / window.getGlobalMonsters().monster3.hp * 100);
        document.getElementById('healthBarMonster3').setAttribute('aria-valuemax', window.getGlobalMonsters().monster3.hp)
        document.getElementById('healthBarMonster3').textContent = Math.round(window.getGlobalFight().monster3Hp / window.getGlobalMonsters().monster3.hp * 100) + '%'

        document.getElementById('monster1Hp').value = Math.round(window.getGlobalMonsters().monster1.hp)
        document.getElementById('monster2Hp').value = Math.round(window.getGlobalMonsters().monster2.hp)
        document.getElementById('monster3Hp').value = Math.round(window.getGlobalMonsters().monster3.hp)

    }
}
window.setHpProgressMonster1 = function setHpProgressMonster1(percent) {
    document.getElementById('healthBarMonster1').setAttribute('aria-valuenow', percent);
    document.getElementById('healthBarMonster1').setAttribute('style', 'width:' + Number(percent) + '%');
    document.getElementById('healthBarMonster1').textContent = Number(percent) + '%';
}
window.setHpProgressMonster2 = function setHpProgressMonster2(percent) {
    document.getElementById('healthBarMonster2').setAttribute('aria-valuenow', percent);
    document.getElementById('healthBarMonster2').setAttribute('style', 'width:' + Number(percent) + '%');
    document.getElementById('healthBarMonster2').textContent = Number(percent) + '%';
}
window.setHpProgressMonster3 = function setHpProgressMonster3(percent) {
    document.getElementById('healthBarMonster3').setAttribute('aria-valuenow', percent);
    document.getElementById('healthBarMonster3').setAttribute('style', 'width:' + Number(percent) + '%');
    document.getElementById('healthBarMonster3').textContent = Number(percent) + '%';
}
