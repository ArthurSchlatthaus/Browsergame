let inventory = null
window.loadInventory = async function loadInventory() {
    let promise = new Promise((resolve) => {
        let playerInv = window.getGlobalPlayer()
        if (playerInv != null) {
            if (document.getElementById("playerGold") != null) {
                document.getElementById("playerGold").innerText = 'Yang: ' + Math.round(playerInv.gold)
            }
            inventory = playerInv.inventory
            document.querySelectorAll('.item').forEach(e => e.remove());
            inventory.forEach((inventoryItem) => {
                let posLeft = 8;
                let posTop = 8;
                if (inventoryItem.pos < 5) {
                    posLeft += inventoryItem.pos * 32
                } else if (inventoryItem.pos < 10) {
                    posLeft += (inventoryItem.pos - 5) * 32;
                    posTop += 32;
                } else if (inventoryItem.pos < 15) {
                    posLeft += (inventoryItem.pos - 10) * 32;
                    posTop += 64;
                } else if (inventoryItem.pos < 20) {
                    posLeft += (inventoryItem.pos - 15) * 32;
                    posTop += 96;
                } else if (inventoryItem.pos < 25) {
                    posLeft += (inventoryItem.pos - 20) * 32;
                    posTop += 128;
                } else if (inventoryItem.pos < 30) {
                    posLeft += (inventoryItem.pos - 25) * 32;
                    posTop += 160;
                } else if (inventoryItem.pos < 35) {
                    posLeft += (inventoryItem.pos - 30) * 32;
                    posTop += 192;
                } else if (inventoryItem.pos < 40) {
                    posLeft += (inventoryItem.pos - 35) * 32;
                    posTop += 224;
                }

                let item = document.createElement("div")
                item.id = "item_" + inventoryItem.id
                item.className = "item"
                item.style.marginLeft = posLeft + 'px'
                item.style.marginTop = posTop + 'px'
                item.style.position = "absolute"
                item.style.width = "32px"
                item.style.height = inventoryItem.size * 32 + "px"
                document.getElementById("itemContainer").appendChild(item)
                if (inventoryItem.count > 0 && inventoryItem.isEquipped === 0) {
                    let tmp = document.createElement("div")
                    item.appendChild(tmp)
                    let btn = document.createElement("button")
                    btn.type = "submit"
                    btn.className = "btn tooltipItem"
                    btn.onclick = function () {
                        equipItem(inventoryItem.id)
                    }
                    btn.style.width = "32px"
                    btn.style.height = inventoryItem.size * 32 + "px"
                    let image = document.createElement("img")
                    let vnum = inventoryItem.vnum
                    if (inventoryItem.type === 0 || inventoryItem.type === 1) {
                        vnum = vnum.toString().replace(/.$/, "0").padStart(5, "0")
                    } else {
                        vnum = vnum.toString().padStart(5, "0")
                    }
                    image.src = "/images/items/" + vnum + ".png"
                    image.width = "32"
                    image.height = inventoryItem.size * 32
                    image.datafilewidth = "32"
                    image.datafileheight = "32"
                    image.draggable = "false"
                    image.id = inventoryItem.id
                    image.ondragstart = function () {
                        window.drag(event)
                    }
                    let titleDiv = document.createElement("div")
                    titleDiv.style = "pointer-events:none"
                    let title
                    if (typeof inventoryItem.title === 'object') {
                        image.alt = inventoryItem.title['name'][0] + '//' + (parseInt(inventoryItem.vnum.toString().slice(-1)) + 1) * 100
                        title = document.createElement("a")
                        title = appendValues(inventoryItem, title, 'name', 'p')
                        title = appendValues(inventoryItem, title, 'level', 'p')
                        if (typeof inventoryItem.title['damage'] != 'undefined') {
                            title = appendValues(inventoryItem, title, 'damage', 'a')
                        } else if (typeof inventoryItem.title['defense'] != 'undefined') {
                            title = appendValues(inventoryItem, title, 'defense', 'a')
                        }
                        title.appendChild(document.createElement('br'))
                        let bonuscontainer = document.createElement("p")
                        if (inventoryItem.title['bonus1'][0] != null) {
                            appendValues(inventoryItem, bonuscontainer, 'bonus1', 'a')
                            bonuscontainer.appendChild(document.createElement('br'))
                        }
                        if (inventoryItem.title['bonus2'][0] != null) {
                            appendValues(inventoryItem, bonuscontainer, 'bonus2', 'a')
                            bonuscontainer.appendChild(document.createElement('br'))
                        }
                        if (inventoryItem.title['bonus3'][0] != null) {
                            appendValues(inventoryItem, bonuscontainer, 'bonus3', 'a')
                            bonuscontainer.appendChild(document.createElement('br'))
                        }
                        if (inventoryItem.title['bonus4'][0] != null) {
                            appendValues(inventoryItem, bonuscontainer, 'bonus4', 'a')
                            bonuscontainer.appendChild(document.createElement('br'))
                        }
                        if (inventoryItem.title['bonus5'][0] != null) {
                            appendValues(inventoryItem, bonuscontainer, 'bonus5', 'a')
                            bonuscontainer.appendChild(document.createElement('br'))
                        }
                        title.appendChild(bonuscontainer)
                        title = appendValues(inventoryItem, title, 'races', 'p')
                    } else {
                        image.alt = inventoryItem.title + '//' + (parseInt(inventoryItem.vnum.toString().slice(-1)) + 1) * 100

                        title = document.createElement("p")
                        title.innerText = inventoryItem.title
                    }
                    titleDiv.appendChild(title)
                    btn.appendChild(image)
                    btn.appendChild(titleDiv)
                    tmp.appendChild(btn)
                    let count = document.createElement("a")
                    count.className = "count"
                    count.id = "invItemText_" + inventoryItem.id
                    if (inventoryItem.type === 0 || inventoryItem.type === 1) {
                        count.innerText = '+' + inventoryItem.vnum.toString().slice(-1)
                    } else {
                        count.innerText = inventoryItem.count
                    }
                    tmp.appendChild(count)
                }

            })
        }
        resolve("done")
    });
    return await promise
}

function appendValues(inventoryItem, title, value, type) {
    let container = document.createElement(type)
    let span = document.createElement("span")
    span.style.color = inventoryItem.title[value][1]
    span.innerText = inventoryItem.title[value][0]
    container.appendChild(span)
    title.appendChild(container)
    return title
}

window.equipItem = function equipItem(inventoryId) {
    $.ajax({
        url: "/equipItem",
        method: 'POST',
        data: {
            inventoryId: inventoryId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },

        success: function (result) {
            if (result.player != null) {
                if (typeof result.usedItem != 'undefined' && result.usedItem != null) {
                    if (document.getElementById('invItemText_' + result.usedItem[0])) {
                        document.getElementById('invItemText_' + result.usedItem[0]).innerText = result.usedItem[1]
                    }
                }
                window.setPlayerHpSp(result.player, false)
                window.setGlobalPlayer(result.player)
                window.setPlayerValues()
                if (typeof result.change != 'undefined' && parseInt(result.change) === 1) {
                    window.updateMainRender()
                    window.cachePvPAnimation()
                }
                window.loadInventory()
                window.loadEquipment()
            }
        }
    });
}
