let equipmentEQ = null
let weaponEQ = null
let weaponTitle = ""
let bodyEQ = null
let bodyTitle = ""
let playerEQ = null
window.loadEquipment = async function loadEquipment() {
    let promise = new Promise((resolve) => {
        playerEQ = window.getGlobalPlayer()
        if (playerEQ != null) {
            equipmentEQ = playerEQ.equipment
            window.equipmentEQ = equipmentEQ
            if (equipmentEQ.weaponItem != null) {
                weaponEQ = equipmentEQ.weaponItem
                let vnum = weaponEQ.vnum.toString().replace(/.$/, "0").padStart(5, "0")
                document.getElementById("weaponContainer").style.display = 'block'
                document.getElementById("weaponImg").src = "/images/items/" + vnum + ".png"
            } else {
                document.getElementById("weaponContainer").style.display = 'none'
                document.getElementById("weaponImg").src = "/images/items/0.png"
            }
            if (equipmentEQ.weaponTitle != null) {
                weaponTitle = equipmentEQ.weaponTitle
                if (typeof weaponTitle === 'object') {
                    fillValue("weaponTitleName", 'name', weaponTitle)
                    fillValue("weaponTitleLevel", 'level', weaponTitle)
                    fillValue("weaponTitleDamage", 'damage', weaponTitle)
                    for (let i = 1; i < 6; i++) {
                        fillBonus(weaponTitle, 'bonus' + i, "weaponTitleBonus" + i)
                    }
                    fillValue("weaponTitleRace", 'races', weaponTitle)
                } else {

                }
            }
            if (equipmentEQ.bodyItem != null) {
                bodyEQ = equipmentEQ.bodyItem
                let vnum = bodyEQ.vnum.toString().replace(/.$/, "0").padStart(5, "0")
                document.getElementById("bodyContainer").style.display = 'block'
                document.getElementById("bodyImg").src = "/images/items/" + vnum + ".png"
            } else {
                document.getElementById("bodyContainer").style.display = 'none'
                document.getElementById("bodyImg").src = "/images/items/0.png"
            }
            if (equipmentEQ.bodyTitle != null) {
                bodyTitle = equipmentEQ.bodyTitle
                if (typeof bodyTitle === 'object') {
                    fillValue("bodyTitleName", 'name', bodyTitle)
                    fillValue("bodyTitleLevel", 'level', bodyTitle)
                    fillValue("bodyTitleDefense", 'defense', bodyTitle)
                    for (let i = 1; i < 6; i++) {
                        fillBonus(bodyTitle, 'bonus' + i, "bodyTitleBonus" + i)
                    }
                    fillValue("bodyTitleRace", 'races', bodyTitle)
                }
            }
        }
        resolve("done")
    });
    return await promise
}

function fillBonus(array, value, id) {
    if (array[value][0] != null) {
        fillValue(id, value, array)
    } else {
        document.getElementById(id).style.display = 'none'
    }
}

function fillValue(id, value, array) {
    document.getElementById(id).style.display = "block"
    document.getElementById(id).innerText = array[value][0]
    document.getElementById(id).style.color = array[value][1]
}

function unEquipItem(inventoryId) {
    $.ajax({
        url: "/unEquipItem",
        method: 'POST',
        data: {
            inventoryId: inventoryId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (result) {
            if (result.player != null) {
                window.setPlayerHpSp(result.player, false)
                window.setGlobalPlayer(result.player)
                window.setPlayerValues()
                if (typeof result.change != 'undefined' && parseInt(result.change) === 1) {
                    window.updateMainRender()
                }
                window.loadEquipment()
                window.loadInventory()
            }
        }
    });
}