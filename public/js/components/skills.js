let playerSkills = null
let skill1 = null
let skill2 = null

window.setPlayerSkills = function setPlayerSkills() {
    playerSkills = window.getGlobalPlayer()
    if (playerSkills != null) {
        skill1 = playerSkills.skill1
        skill2 = playerSkills.skill2
        if (skill1 != null) {
            document.getElementById("skill1Container").style.display = "block"
            document.getElementById("skill_1_1").src = "images/skills/" + skill1.id + "_1.png"
            document.getElementById("skill_1_2").src = "images/skills/" + skill1.id + "_2.png"
            document.getElementById("skill_1_3").src = "images/skills/" + skill1.id + "_3.png"
        } else {
            document.getElementById("skill1Container").style.display = "none"
        }
        if (skill2 != null) {
            document.getElementById("skill2Container").style.display = "block"
            document.getElementById("skill_2_1").src = "images/skills/" + skill2.id + "_1.png"
            document.getElementById("skill_2_2").src = "images/skills/" + skill2.id + "_2.png"
            document.getElementById("skill_2_3").src = "images/skills/" + skill2.id + "_3.png"
        } else {
            document.getElementById("skill2Container").style.display = "none"
        }
        if (playerSkills.class === 0 && playerSkills.level > 4) {
            document.getElementById("classContainer").style.display = "block"
            document.getElementById("className1").innerText = playerSkills.className1
            document.getElementById("className2").innerText = playerSkills.className2
        } else {
            document.getElementById("classContainer").style.display = "none"
        }
        if (playerSkills.class > 0 && playerSkills.level > 4) {
            document.getElementById("resetClassContainer").style.display = "block"
        } else {
            document.getElementById("resetClassContainer").style.display = "none"
        }
        setSkillsInfos()
        document.getElementById("freeSkillPoints").innerText = window.trans('custom.free_skillpoints') + ':' + playerSkills.freeSkillPoints
    }
}


function resetClass() {
    $.ajax({
        url: "/resetClass",
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (result) {
            window.setGlobalPlayer(result.player)
            window.setPlayerSkills()
            window.setPlayerStatusBar()
        }
    });
}

function setClass(classId) {
    $.ajax({
        url: "/setClass",
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            classId: classId
        },
        success: function (result) {
            window.setGlobalPlayer(result.player)
            window.setPlayerSkills()
            window.setPlayerStatusBar()
        }
    });
}

function setSkillsInfos() {
    if (parseInt(playerSkills.skill0level) < 17) {
        if (document.getElementById('skill_1_1')) {
            document.getElementById('skill_1_1').style.opacity = "1.0"
            document.getElementById('skill_1_1').style.filter = "none"
            document.getElementById('skill_1_1').style.pointerEvents = "auto"
            document.getElementById('skill_1_1').style.cursor = "pointer"
        }
        if (document.getElementById('skill_1_1_label')) {
            document.getElementById('skill_1_1_label').innerText = playerSkills.skill0level
        }
    } else {
        if (document.getElementById('skill_1_2')) {
            document.getElementById('skill_1_2').style.opacity = "1.0"
            document.getElementById('skill_1_2').style.filter = "none"
        }
        if (document.getElementById('skill_1_2_label')) {
            document.getElementById('skill_1_2_label').innerText = "M1"
        }
    }
    if (parseInt(playerSkills.skill1level) < 17) {
        if (document.getElementById('skill_2_1')) {
            document.getElementById('skill_2_1').style.opacity = "1.0"
            document.getElementById('skill_2_1').style.filter = "none"
            document.getElementById('skill_2_1').style.pointerEvents = "auto"
            document.getElementById('skill_2_1').style.cursor = "pointer"
        }
        if (document.getElementById('skill_2_1_label')) {
            document.getElementById('skill_2_1_label').innerText = playerSkills.skill1level
        }
    } else {
        if (document.getElementById('skill_2_2')) {
            document.getElementById('skill_2_2').style.opacity = "1.0"
            document.getElementById('skill_2_2').style.filter = "none"
        }
        if (document.getElementById('skill_2_2_label')) {
            document.getElementById('skill_2_2_label').innerText = "M1"
        }
    }
}

function setSkill(skillId) {
    if (skillId === 1) {
        if (playerSkills.skill0level > 18) {
            return
        }
    }
    if (skillId === 2) {
        if (playerSkills.skill1level > 18) {
            return
        }
    }

    if (playerSkills.class > 0 && playerSkills.freeSkillPoints > 0 && playerSkills.level >= 5) {
        $.ajax({
            url: "/setSkill",
            method: 'POST',
            data: {
                skill: skillId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                if (result.skillId != null && result.skillLvl != null) {
                    document.getElementById('skill_' + result.skillId + '_1_label').innerText = result.skillLvl
                    document.getElementById('freeSkillPoints').innerText = window.trans('custom.free_skillpoints') + ': ' + result.free
                }
                if (result.player != null) {
                    window.setGlobalPlayer(result.player)
                    window.setPlayerValues()
                    window.setPlayerStatusBar()
                }
            }
        });
    }

}