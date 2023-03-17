function showWindow(id) {
    let windows = ['charWindow', 'skillsWindow']
    windows.forEach((item) => {
        if (id !== item && document.getElementById(item) != null) {
            document.getElementById(item).style.display = 'none'
        } else {
            document.getElementById(id).style.display = 'block';
        }
    })
}

function setStatus(statusId) {
    $.ajax({
        url: "/setStatus",
        method: 'POST',
        data: {
            statusId: statusId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (result) {
            if (result.player != null) {
                window.setGlobalPlayer(result.player)
                window.setPlayerHpSp(result.player, false)
                setPlayerValues()
            }
        }
    });
}

window.setPlayerValues = async function setPlayerValues() {
    let promise = new Promise((resolve) => {
        if (window.getGlobalPlayer() != null) {
            let player = window.getGlobalPlayer()
            if (player.race === 1) {
                document.getElementById("profile_warrior").style.display = 'block'
            } else if (player.race === 2) {
                document.getElementById("profile_ninja").style.display = 'block'
            } else if (player.race === 3) {
                document.getElementById("profile_sura").style.display = 'block'
            } else if (player.race === 4) {
                document.getElementById("profile_shaman").style.display = 'block'
            }
            document.getElementById("player_name").innerText = player.name
            document.getElementById("player_level").innerText = player.level
            document.getElementById("player_exp").innerText = Math.round(player.exp)
            document.getElementById("player_expMax").innerText = Math.round(player.level * 200)
            document.getElementById("player_vit").innerText = player.vit
            document.getElementById("player_int").innerText = player.int
            document.getElementById("player_str").innerText = player.str
            document.getElementById("player_dex").innerText = player.dex
            document.getElementById("playerProfileHp").innerText = Math.max(0, Math.round(player.hp)) + '/' + Math.max(0, Math.round(player.maxHp))
            document.getElementById("playerProfileSp").innerText = Math.max(0, Math.round(player.sp)) + '/' + Math.max(0, Math.round(player.maxSp))
            document.getElementById("player_damage").innerText = Math.round(player.damage)
            document.getElementById("player_def").innerText = Math.round(player.defense)
            document.getElementById("player_freestatus").innerText = player.freestatus
            if (parseInt(player.freestatus) > 0) {
                document.getElementById("freestatusform").style.display = "block"
            } else {
                document.getElementById("freestatusform").style.display = "none"
            }
        }
        resolve("done")
    });
    return await promise
}

