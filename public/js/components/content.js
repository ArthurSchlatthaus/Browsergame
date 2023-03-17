import {init, animate, setVars, setCamPos, setGlobalHair, loadModel} from "/js/threejs/start.js";

let playerContent = null
window.initMainRender = async function initMainRender() {
    let promise = new Promise((resolve) => {
        playerContent = window.getGlobalPlayer()
        if (playerContent != null && playerContent.isLoggedIn === 1) {
            setVars(playerContent.modelPath, playerContent.model, playerContent.weaponPath, playerContent.weapon, playerContent.animationPath, playerContent.animation);
            setGlobalHair(playerContent.hair)
            setCamPos(0, 75, 500)
            init();
            animate();
        }
        resolve("done")
    });
    return await promise
}
window.updateMainRender = function updateMainRender() {
    playerContent = window.getGlobalPlayer()
    setVars(playerContent.modelPath, playerContent.model, playerContent.weaponPath, playerContent.weapon, playerContent.animationPath, playerContent.animation)
    setGlobalHair(playerContent.hair)
    loadModel(true)
}


//pvp
import {preLoadAnimationAttacker, preLoadAnimationDefender} from "/js/threejs/pvp.js";

window.cachePvPAnimation = function cachePvPAnimation() {
    //localStorage.clear()
    let pvp = window.getGlobalPvPFight()
    playerContent = window.getGlobalPlayer()
    if (localStorage.getItem('hitsAttackerLoaded') == null && playerContent != null) {
        preLoadAnimationAttacker(playerContent.animationPath, playerContent.skillPath, false, false)
        if (pvp != null && pvp.defender) {
            preLoadAnimationDefender(pvp.defenderAnimationPath, pvp.defenderSkillPath, false)
        }
        localStorage.setItem('textureLoaded', 'true')
    } else {
        if (localStorage.getItem('hitsAttackerLoaded') && playerContent != null
            && localStorage.getItem('hitsAttackerLoaded').split("_")[1] !== playerContent.getRace
            && localStorage.getItem('hitsAttackerLoaded').split("_")[2] !== playerContent.weaponType) {
            preLoadAnimationAttacker(playerContent.animationPath, playerContent.skillPath, true,false)
        }
        if (pvp != null && pvp.defender && localStorage.getItem('hitsDefenderLoaded') !== pvp.defenderRace) {
            preLoadAnimationDefender(pvp.defenderAnimationPath, pvp.defenderSkillPath, true)
        }
    }
}
cachePvPAnimation()

window.sellItem = function sellItem(item_id) {
    $.ajax({
        url: "/sellItem", method: 'POST', data: {
            item_id: item_id, _token: $('meta[name="csrf-token"]').attr('content')
        }, success: function (result) {
            if (result.player != null) {
                window.setGlobalPlayer(result.player)
                window.loadInventory()
            }
        }
    });
}

window.upgradeItem = function upgradeItem(item_id) {
    $.ajax({
        url: "/upgradeItem", method: 'POST', data: {
            item_id: item_id, _token: $('meta[name="csrf-token"]').attr('content')
        }, success: function (result) {
            if (result.player != null) {
                window.setGlobalPlayer(result.player)
                window.loadInventory()
            }
        }
    });
}

window.openWindow = function openWindow(id) {
    let ids = ['generalStoreContainer', 'weaponStoreContainer', 'armorStoreContainer', 'blacksmithContainer']
    ids.forEach((item) => {
        if (id !== item && document.getElementById(item) != null) {
            document.getElementById(item).style.display = 'none'
        }
    })
    if (document.getElementById(id).style.display === 'none') {
        document.getElementById(id).style.display = 'block'
    } else if (document.getElementById(id).style.display === 'block') {
        document.getElementById(id).style.display = 'none'
    }
}

window.allowDrop = function allowDrop(ev) {
    ev.preventDefault();
}

window.drag = function drag(ev) {
    ev.dataTransfer.setData("itemIdTransfer", ev.target.id);
    ev.dataTransfer.setData("previewItemTooltip", ev.target.alt);
}

window.dropBlack = function dropBlack(ev) {
    ev.preventDefault();
    document.getElementById('itemIdBlack').value = ev.dataTransfer.getData("itemIdTransfer");
    let all = ev.dataTransfer.getData("previewItemTooltip").split("//")
    let name = all[0]
    let cost = all[1]
    //blacksmith cost * 10, because sell price and refine cost are same number: refine*1000 // sell*100
    document.getElementById('tooltipItemTextBlack').innerText = 'Upgrade ' + name + ' (' + (parseInt(cost) * 10) + ' Yang) ?';
    document.getElementById('tooltipItemBlack').style.display = 'block';
}

window.dropGeneral = function dropGeneral(ev) {
    ev.preventDefault();
    document.getElementById('itemIdGeneral').value = ev.dataTransfer.getData("itemIdTransfer");
    let all = ev.dataTransfer.getData("previewItemTooltip").split("//")
    let name = all[0]
    let cost = all[1]
    document.getElementById('tooltipItemTextGeneral').innerText = 'Sell ' + name + ' for ' + cost + ' Yang ?';
    document.getElementById('tooltipItemGeneral').style.display = 'block';
}
window.dropWeapon = function dropWeapon(ev) {
    ev.preventDefault();
    document.getElementById('itemIdWeapon').value = ev.dataTransfer.getData("itemIdTransfer");
    let all = ev.dataTransfer.getData("previewItemTooltip").split("//")
    let name = all[0]
    let cost = all[1]
    document.getElementById('tooltipItemTextWeapon').innerText = 'Sell ' + name + ' for ' + cost + ' Yang ?';
    document.getElementById('tooltipItemWeapon').style.display = 'block';
}
window.dropArmor = function dropArmor(ev) {
    ev.preventDefault();
    document.getElementById('itemIdArmor').value = ev.dataTransfer.getData("itemIdTransfer");
    let all = ev.dataTransfer.getData("previewItemTooltip").split("//")
    let name = all[0]
    let cost = all[1]
    document.getElementById('tooltipItemTextArmor').innerText = 'Sell ' + name + ' for ' + cost + ' Yang ?';
    document.getElementById('tooltipItemArmor').style.display = 'block';
}

