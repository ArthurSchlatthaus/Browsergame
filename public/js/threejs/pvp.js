const THREE = window.THREE;
const FBXLoader = window.FBXLoader;
const OrbitControls = window.OrbitControls;
import {pack, unpack} from 'https://cdn.skypack.dev/jsonpack';

let camera, sceneAttacker, sceneDefender, attackerRenderer, defenderRenderer
const clock = new THREE.Clock()
let attackerMixer, defenderMixer

let attackerModelPath = ""
let attackerModel = ""
let attackerWeaponPath = ""
let attackerWeapon = ""
let attackerAnimationPath = ""
let attackerAnimation = ""

let defenderModelPath = ""
let defenderModel = ""
let defenderWeaponPath = ""
let defenderWeapon = ""
let defenderAnimationPath = ""
let defenderAnimation = ""

let globalAttackerObject = null
let globalAttackerChild = null
let globalAttackerSecondWeaponChild = null
let globalAttackerWeapon = null
let globalAttackerSecondWeapon = null

let globalDefenderObject = null
let globalDefenderChild = null
let globalDefenderSecondWeaponChild = null
let globalDefenderWeapon = null
let globalDefenderSecondWeapon = null

let camPosX = 0
let camPosY = 10
let camPosZ = 200

let globalAttackerHairVar = null
let playerAttackerHair = null
let globalAttackerHair = null

let globalDefenderHairVar = null
let playerDefenderHair = null
let globalDefenderHair = null

let hitsAttackerLoaded = null
let comboAttackerClips = []
let prevAttackerAction = null

let hitsDefenderLoaded = null
let comboDefenderClips = []
let prevDefenderAction = null
const loadingScreen = document.getElementById('loading-screen');
const manager = new THREE.LoadingManager(() => {
    if (loadingScreen != null) {
        loadingScreen.classList.add('fade-out');
    }
});

const animationAttackerLoader = new FBXLoader()
const animationDefenderLoader = new FBXLoader()

const animationAttackerPreLoader = new FBXLoader(manager)
const animationDefenderPreLoader = new FBXLoader(manager)


export function setVarsAttacker(modelPath_n, model_n, weaponPath_n, weapon_n, animationPath_n, animation_n, hair) {
    attackerModelPath = modelPath_n
    attackerModel = model_n
    attackerWeaponPath = weaponPath_n
    attackerWeapon = weapon_n
    attackerAnimationPath = animationPath_n
    attackerAnimation = animation_n
    globalAttackerHairVar = hair
}

export function setVarsDefender(modelPath_n, model_n, weaponPath_n, weapon_n, animationPath_n, animation_n, hair) {
    defenderModelPath = modelPath_n
    defenderModel = model_n
    defenderWeaponPath = weaponPath_n
    defenderWeapon = weapon_n
    defenderAnimationPath = animationPath_n
    defenderAnimation = animation_n
    globalDefenderHairVar = hair
}

export function setCamPos(x, y, z) {
    camPosX = x
    camPosY = y
    camPosZ = z
}


function loadModelAttacker(localScene) {
    localScene.remove(globalAttackerObject)
    const loader = new FBXLoader()
    loader.setResourcePath('/models' + attackerModelPath)
    loader.setPath('/models' + attackerModelPath)
    loader.load(attackerModel, function (object) {
        globalAttackerObject = object
        object.scale.set(1.1, 1.1, 1.1)
        if (attackerAnimationPath !== 'none') {
            rotateObject(object, -90, 0, 60)
            object.translateZ(-75)
            loadAnimationAttacker(object)
        }
        let issetHair = false
        globalAttackerObject.traverse(function (child) {
            if (child.isMesh) {
                child.castShadow = true
                child.receiveShadow = true
            }
            if (parseInt(attackerWeapon) >= 2000 && parseInt(attackerWeapon) < 3000) { // bow
                if (child.name === "equip_left") {
                    globalAttackerChild = child
                    loadWeaponAttacker(false)
                }
            } else {
                if (child.name === "equip_right" || child.name === "equip_right_hand") {
                    globalAttackerChild = child
                    loadWeaponAttacker(false)
                }
            }
            if (parseInt(attackerWeapon) >= 1000 && parseInt(attackerWeapon) < 2000) {//dagger
                if (child.name === "equip_left") {
                    globalAttackerSecondWeaponChild = child
                    loadSecondWeaponAttacker()
                }
            }
            if (attackerModelPath.includes('warrior') && child.name === 'warrior_hair') {
                playerAttackerHair = child
            }
            if (attackerModelPath.includes('ninja') && child.name === 'assassin_hair01') {
                playerAttackerHair = child
            }
            if (!issetHair && child.name === 'Bip01_Head') {
                globalAttackerHair = child
                issetHair = true
            }
        })
        loadHairAttacker(attackerModelPath)
        localScene.add(object)
    })
}

function loadModelDefender(localScene) {
    localScene.remove(globalDefenderObject)
    const loader = new FBXLoader()
    loader.setResourcePath('/models' + defenderModelPath)
    loader.setPath('/models' + defenderModelPath)
    loader.load(defenderModel, function (object) {
        globalDefenderObject = object
        object.scale.set(1.1, 1.1, 1.1)
        if (defenderAnimationPath !== 'none') {
            rotateObject(object, -90, 0, -60)
            object.translateZ(-75)
            loadAnimationDefender(object, defenderAnimationPath, defenderAnimation)
        }
        let issetHair = false
        globalDefenderObject.traverse(function (child) {
            if (child.isMesh) {
                child.castShadow = true
                child.receiveShadow = true
            }
            if (parseInt(defenderWeapon) >= 2000 && parseInt(defenderWeapon) < 3000) { // bow
                if (child.name === "equip_left") {
                    globalDefenderChild = child
                    loadWeaponDefender(false)
                }
            } else {
                if (child.name === "equip_right" || child.name === "equip_right_hand") {
                    globalDefenderChild = child
                    loadWeaponDefender(false)
                }
            }
            if (parseInt(defenderWeapon) >= 1000 && parseInt(defenderWeapon) < 2000) {//dagger
                if (child.name === "equip_left") {
                    globalDefenderSecondWeaponChild = child
                    loadSecondWeaponDefender()
                }
            }
            if (defenderModelPath.includes('warrior') && child.name === 'warrior_hair') {
                playerDefenderHair = child
            }
            if (defenderModelPath.includes('ninja') && child.name === 'assassin_hair01') {
                playerDefenderHair = child
            }
            if (!issetHair && child.name === 'Bip01_Head') {
                globalDefenderHair = child
                issetHair = true
            }
        })
        loadHairDefender(defenderModelPath)
        localScene.add(object)
    })
}

function loadHairAttacker(modelPath) {
    globalAttackerObject.remove(playerAttackerHair)
    let hairLoader = new FBXLoader()
    hairLoader.setResourcePath('/models' + modelPath)
    hairLoader.setPath('/models' + modelPath)
    if (globalAttackerHairVar === '') {
        globalAttackerHairVar = 'SK_Hair_1_1.fbx'
    }
    hairLoader.load(globalAttackerHairVar, function (hairObject) {
        globalAttackerHair.add(hairObject)
    })
}

function loadHairDefender(modelPath) {
    globalDefenderObject.remove(playerDefenderHair)
    let hairLoader = new FBXLoader()
    hairLoader.setResourcePath('/models' + modelPath)
    hairLoader.setPath('/models' + modelPath)
    if (globalDefenderHairVar === '') {
        globalDefenderHairVar = 'SK_Hair_1_1.fbx'
    }
    hairLoader.load(globalDefenderHairVar, function (hairObject) {
        globalDefenderHair.add(hairObject)
    })
}

function loadSecondWeaponAttacker() {
    if (globalAttackerSecondWeapon != null && globalAttackerSecondWeaponChild != null) {
        globalAttackerSecondWeaponChild.remove(globalAttackerSecondWeapon)
    }
    let weaponLoader = new FBXLoader()
    weaponLoader.setResourcePath('/models' + attackerWeaponPath)
    weaponLoader.setPath('/models' + attackerWeaponPath)
    weaponLoader.load(attackerWeapon, function (weaponObj) {
        globalAttackerSecondWeapon = weaponObj
        attackerWeapon.substring(attackerWeapon.length - 4)
        if (attackerModelPath.includes('ninja')) {
            weaponObj.translateX(2)
            rotateObject(globalAttackerSecondWeapon, -90, 180, 180)
        }
        if (globalAttackerSecondWeaponChild != null) {
            globalAttackerSecondWeaponChild.add(globalAttackerSecondWeapon)
        }
    })
}

function loadSecondWeaponDefender() {
    if (globalDefenderSecondWeapon != null && globalDefenderSecondWeaponChild != null) {
        globalDefenderSecondWeaponChild.remove(globalDefenderSecondWeapon)
    }
    let weaponLoader = new FBXLoader()
    weaponLoader.setResourcePath('/models' + defenderWeaponPath)
    weaponLoader.setPath('/models' + defenderWeaponPath)
    weaponLoader.load(defenderWeapon, function (weaponObj) {
        globalDefenderSecondWeapon = weaponObj
        defenderWeapon.substring(defenderWeapon.length - 4)
        if (defenderModelPath.includes('ninja')) {
            weaponObj.translateX(2)
            rotateObject(globalDefenderSecondWeapon, -90, 180, 180)
        }
        if (globalDefenderSecondWeaponChild != null) {
            globalDefenderSecondWeaponChild.add(globalDefenderSecondWeapon)
        }
    })
}

function loadWeaponAttacker(hasNoWeapon) {
    if (globalAttackerWeapon != null && globalAttackerChild != null) {
        globalAttackerChild.remove(globalAttackerWeapon)
    }
    if (hasNoWeapon) {
        if (globalAttackerSecondWeapon != null && globalAttackerSecondWeaponChild != null) {
            globalAttackerSecondWeaponChild.remove(globalAttackerSecondWeapon)
        }
    }
    let weaponLoader = new FBXLoader()
    weaponLoader.setResourcePath('/models' + attackerWeaponPath)
    weaponLoader.setPath('/models' + attackerWeaponPath)
    if (!hasNoWeapon) {
        weaponLoader.load(attackerWeapon, function (weaponObj) {
            globalAttackerWeapon = weaponObj
            attackerWeapon.substring(attackerWeapon.length - 4)
            if (attackerModelPath.includes('warrior')) {
                rotateObject(globalAttackerWeapon, 90, 0, 0)
                if (parseInt(attackerWeapon) >= 3000 && parseInt(attackerWeapon) < 4000) { // 2hand
                    if (parseInt(attackerWeapon) === 3000) {
                        weaponObj.translateY(-75)
                    } else if (parseInt(attackerWeapon) === 3040) {
                        weaponObj.translateY(-10)
                    } else {
                        weaponObj.translateY(-35)
                    }
                }
            } else if (attackerModelPath.includes('ninja')) {
                if (attackerAnimation.includes('Wait.fbx')) {
                    if (parseInt(attackerWeapon) >= 2000 && parseInt(attackerWeapon) < 3000) { // bow
                        rotateObject(globalAttackerWeapon, 0, 0, 180)
                        if (parseInt(attackerWeapon) === 2030 || parseInt(attackerWeapon) === 2040) {
                            weaponObj.translateZ(-70)
                        } else if (parseInt(attackerWeapon) !== 2000) {
                            weaponObj.translateZ(-60)
                        }
                    } else if (parseInt(attackerWeapon) >= 1000 && parseInt(attackerWeapon) < 2000) { //dagger
                        rotateObject(globalAttackerWeapon, 90, 200, 180)
                    } else {
                        rotateObject(globalAttackerWeapon, 90, 0, 180)
                    }
                } else if (attackerAnimation.includes('Wait1.fbx')) {
                    if (parseInt(attackerWeapon) >= 2000 && parseInt(attackerWeapon) < 3000) {// bow
                        rotateObject(globalAttackerWeapon, 0, 0, 180)
                        if (parseInt(attackerWeapon) !== 2000) {
                            weaponObj.translateZ(-65)
                        }
                    } else if (parseInt(attackerWeapon) >= 1000 && parseInt(attackerWeapon) < 2000) { //dagger
                        rotateObject(globalAttackerWeapon, 90, 200, 180)
                    } else {
                        rotateObject(globalAttackerWeapon, 90, 0, 180)
                    }
                }
            }
            if (globalAttackerChild != null) {
                globalAttackerChild.add(globalAttackerWeapon)
            }
        })
    }
}

function loadWeaponDefender(hasNoWeapon) {
    if (globalDefenderWeapon != null && globalDefenderChild != null) {
        globalDefenderChild.remove(globalDefenderWeapon)
    }
    if (hasNoWeapon) {
        if (globalDefenderSecondWeapon != null && globalDefenderSecondWeaponChild != null) {
            globalDefenderSecondWeaponChild.remove(globalDefenderSecondWeapon)
        }
    }
    let weaponLoader = new FBXLoader()
    weaponLoader.setResourcePath('/models' + defenderWeaponPath)
    weaponLoader.setPath('/models' + defenderWeaponPath)
    if (!hasNoWeapon) {
        weaponLoader.load(defenderWeapon, function (weaponObj) {
            globalDefenderWeapon = weaponObj
            defenderWeapon.substring(defenderWeapon.length - 4)
            if (defenderModelPath.includes('warrior')) {
                rotateObject(globalDefenderWeapon, 90, 0, 0)
                if (parseInt(defenderWeapon) >= 3000 && parseInt(defenderWeapon) < 4000) { // 2hand
                    if (parseInt(defenderWeapon) === 3000) {
                        weaponObj.translateY(-75)
                    } else if (parseInt(defenderWeapon) === 3040) {
                        weaponObj.translateY(-10)
                    } else {
                        weaponObj.translateY(-35)
                    }
                }
            } else if (defenderModelPath.includes('ninja')) {
                if (defenderAnimation.includes('Wait.fbx')) {
                    if (parseInt(defenderWeapon) >= 2000 && parseInt(defenderWeapon) < 3000) { // bow
                        rotateObject(globalDefenderWeapon, 0, 0, 180)
                        if (parseInt(defenderWeapon) === 2030 || parseInt(defenderWeapon) === 2040) {
                            weaponObj.translateZ(-70)
                        } else if (parseInt(defenderWeapon) !== 2000) {
                            weaponObj.translateZ(-60)
                        }
                    } else {
                        rotateObject(globalDefenderWeapon, -90, 200, 180)
                    }
                } else if (defenderAnimation.includes('Wait1.fbx')) {
                    if (parseInt(defenderWeapon) >= 2000 && parseInt(defenderWeapon) < 3000) {// bow
                        rotateObject(globalDefenderWeapon, 0, 0, 180)
                        if (parseInt(defenderWeapon) !== 2000) {
                            weaponObj.translateZ(-65)
                        }
                    } else {
                        rotateObject(globalDefenderWeapon, -90, 200, 180)
                    }
                }
            }
            if (globalDefenderChild != null) {
                globalDefenderChild.add(globalDefenderWeapon)
            }
        })
    }
}

export function preLoadAnimationAttacker(hitPath, skillPath, isForceReload, storeLocal) {
    hitsAttackerLoaded = localStorage.getItem('hitsAttackerLoaded')
    if (isForceReload || hitsAttackerLoaded == null
        || (skillPath.includes('ninja') && !hitsAttackerLoaded.includes('ninja'))
        || (skillPath.includes('warrior') && !hitsAttackerLoaded.includes('warrior'))
    ) {
        animationAttackerPreLoader.setResourcePath('/models' + hitPath)
        animationAttackerPreLoader.setPath('/models' + hitPath)
        comboAttackerClips = []
        if (!hitPath.includes('bow')) {
            let hitArray = ['A_Combo01.fbx', 'A_Combo02.fbx', 'A_Combo03.fbx']
            hitArray.forEach(tmp => {
                animationAttackerPreLoader.load(tmp, (obj) => {
                        let tmp2 = obj.animations[0]
                        tmp2.name = tmp
                        comboAttackerClips.push(tmp2)
                        if (storeLocal) {
                            try {
                                localStorage.setItem('attacker/' + tmp2.name, pack(tmp2.toJSON()))
                            } catch (err) {
                                console.log(err)
                            }
                        }
                    }
                )
            })
        }
        if (storeLocal) {
            animationAttackerPreLoader.setResourcePath('/models' + skillPath)
            animationAttackerPreLoader.setPath('/models' + skillPath)
            let skillArray
            if (skillPath.includes('ninja')) {
                skillArray = ['A_Amseup.fbx', 'A_Eunhyeong.fbx']
            } else if (skillPath.includes('warrior')) {
                skillArray = ['A_Geomgyeong.fbx', 'A_Palbang.fbx', 'A_Cheongeun.fbx', 'A_Daejin.fbx']
            }
            skillArray.forEach(tmp => {
                animationAttackerPreLoader.load(tmp, (obj) => {
                        let tmp2 = obj.animations[0]
                        tmp2.name = tmp
                        try {
                            localStorage.setItem('attacker/' + tmp2.name, pack(tmp2.toJSON()))
                        } catch (err) {
                            console.log(err)
                        }
                    }
                )
            })
        }
        if (skillPath.includes('ninja')) {
            if (hitPath.includes('bow')) {
                hitsAttackerLoaded = 'ninja_bow'
            } else if (hitPath.includes('dagger')) {
                hitsAttackerLoaded = 'ninja_dagger'
            } else if (hitPath.includes('onehand')) {
                hitsAttackerLoaded = 'ninja_onehand'
            }
        } else {
            if (hitPath.includes('onehand')) {
                hitsAttackerLoaded = 'warrior_onehand'
            } else if (hitPath.includes('twohand')) {
                hitsAttackerLoaded = 'warrior_twohand'
            }
        }
        localStorage.setItem('hitsAttackerLoaded', hitsAttackerLoaded)
    }
}

export function preLoadAnimationDefender(hitPath, skillPath, isForceReload) {
    hitsDefenderLoaded = localStorage.getItem('hitsDefenderLoaded')
    if (isForceReload || hitsDefenderLoaded === null
        || (skillPath.includes('ninja') && !hitsDefenderLoaded.includes('ninja'))
        || (skillPath.includes('warrior') && !hitsDefenderLoaded.includes('warrior'))
    ) {
        animationDefenderPreLoader.setResourcePath('/models' + hitPath)
        animationDefenderPreLoader.setPath('/models' + hitPath)
        let hitArray
        if (skillPath.includes('ninja')) {
            hitArray = ['A_Combo01.fbx', 'A_Combo02.fbx', 'A_Combo03.fbx', 'A_Shoot_Once.fbx']
        } else {
            hitArray = ['A_Combo01.fbx', 'A_Combo02.fbx', 'A_Combo03.fbx']
        }
        comboDefenderClips = []
        hitArray.forEach(tmp => {
            animationDefenderPreLoader.load(tmp, (obj) => {
                    let tmp2 = obj.animations[0]
                    tmp2.name = tmp
                    comboDefenderClips.push(tmp2)
                    try {
                        localStorage.setItem('defender/' + tmp, pack(tmp2.toJSON()))
                    } catch (err) {
                        console.log(err)
                    }
                }
            )
        })
        animationDefenderPreLoader.setResourcePath('/models' + skillPath)
        animationDefenderPreLoader.setPath('/models' + skillPath)

        let skillArray
        if (skillPath.includes('ninja')) {
            skillArray = ['A_Amseup.fbx', 'A_Eunhyeong.fbx']
        } else {
            skillArray = ['A_Geomgyeong.fbx', 'A_Palbang.fbx', 'A_Cheongeun.fbx', 'A_Daejin.fbx']
        }
        skillArray.forEach(tmp => {
            animationDefenderPreLoader.load(tmp, (obj) => {
                    let tmp2 = obj.animations[0]
                    tmp2.name = tmp
                    comboDefenderClips.push(tmp2)
                    try {
                        localStorage.setItem('defender/' + tmp, pack(tmp2.toJSON()))
                    } catch (err) {
                        console.log(err)
                    }
                }
            )
        })
        if (skillPath.includes('ninja')) {
            if (hitPath.includes('bow')) {
                hitsDefenderLoaded = 'ninja_bow'
            } else if (hitPath.includes('dagger')) {
                hitsDefenderLoaded = 'ninja_dagger'
            } else if (hitPath.includes('onehand')) {
                hitsDefenderLoaded = 'ninja_onehand'
            }
        } else {
            if (hitPath.includes('onehand')) {
                hitsDefenderLoaded = 'warrior_onehand'
            } else if (hitPath.includes('twohand')) {
                hitsDefenderLoaded = 'warrior_twohand'
            }
        }
        localStorage.setItem('hitsDefenderLoaded', hitsDefenderLoaded)
    }
}

function loadAnimationAttacker() {
    animationAttackerLoader.setResourcePath('/models' + attackerAnimationPath)
    animationAttackerLoader.setPath('/models' + attackerAnimationPath)
    animationAttackerLoader.load(attackerAnimation, (animationObj) => {
        attackerMixer = new THREE.AnimationMixer(globalAttackerObject)
        const action = attackerMixer.clipAction(animationObj.animations[0])
        if (attackerAnimation === 'A_Dead.fbx') {
            action.setLoop(THREE.LoopOnce);
            action.clampWhenFinished = true;
        }
        action.play()
    })
}

function loadAnimationDefender(object, animationPath, animation) {
    animationDefenderLoader.setResourcePath('/models' + animationPath)
    animationDefenderLoader.setPath('/models' + animationPath)
    animationDefenderLoader.load(animation, (animationObj) => {
        defenderMixer = new THREE.AnimationMixer(object)
        let action = defenderMixer.clipAction(animationObj.animations[0])
        if (animation === 'A_Dead.fbx' || animationPath.includes('/skill/')) {
            action.setLoop(THREE.LoopOnce);
            action.clampWhenFinished = true;
        }
        action.play()
    })

}


export function startHitAnimationAttacker(skillPath, skillAnimation) {
    if (skillAnimation === 'none') {
        playAttacker('A_Combo01.fbx').then(
            function () {
                playAttacker('A_Combo02.fbx').then(
                    function () {
                        playAttacker('A_Combo03.fbx').then()
                    }
                )
            }
        )
    } else if (skillPath.includes('/skill/')) {
        playAttacker(skillAnimation, true).then()
    } else {
        setAnimationAttacker(skillPath, skillAnimation)
    }
}

export function startHitAnimationDefender(skillPath, skillAnimation) {
    if (skillAnimation === 'none') {
        playDefender('A_Combo01.fbx', false).then(
            function () {
                playDefender('A_Combo02.fbx', false).then(
                    function () {
                        playDefender('A_Combo03.fbx', false).then()
                    }
                )
            }
        )
    } else if (skillAnimation.includes('A_Shoot_Once')) {
        playDefender(skillAnimation, true).then(
            function () {
                playDefender(skillAnimation, true).then()
            }
        )
    } else if (skillPath.includes('/skill/')) {
        playDefender(skillAnimation, true).then()
    } else {
        setAnimationDefender(skillPath, skillAnimation)
    }
}

export function setAnimationAttacker(newAnimationPath, newAnimation) {
    attackerAnimationPath = newAnimationPath
    attackerAnimation = newAnimation
    loadAnimationAttacker(globalAttackerObject, attackerAnimationPath, attackerAnimation)
}

export function setAnimationDefender(newAnimationPath, newAnimation) {
    defenderAnimationPath = newAnimationPath
    defenderAnimation = newAnimation
    loadAnimationDefender(globalDefenderObject, defenderAnimationPath, defenderAnimation)
}

function playAttacker(name) {
    return new Promise(resolve => {
        let clip
        if (localStorage.getItem('hitsAttackerLoaded') == null) {
            let playerContent = window.getGlobalPlayer()
            preLoadAnimationAttacker(playerContent.animationPath, playerContent.skillPath, true, false)
        } else {
            if (localStorage.getItem('attacker/' + name) == null) {
                clip = THREE.AnimationClip.findByName(comboAttackerClips, name)
            } else {
                clip = THREE.AnimationClip.parse(unpack(localStorage.getItem('attacker/' + name)))
            }
        }
        let action = attackerMixer.clipAction(clip)
        action.reset()
        action.setLoop(THREE.LoopOnce)
        if (prevAttackerAction) {
            prevAttackerAction.fadeOut(5)
        }
        action.play()
        attackerMixer.addEventListener('finished', () => {
            prevAttackerAction = action
            resolve('done')
        })

    })
}

function playDefender(name, isSkill) {
    return new Promise(resolve => {
        let clip
        if (localStorage.getItem('hitsDefenderLoaded') == null) {
            clip = THREE.AnimationClip.findByName(comboDefenderClips, name)
        } else {
            clip = THREE.AnimationClip.parse(unpack(localStorage.getItem('defender/' + name)))
        }
        let action = defenderMixer.clipAction(clip)
        action.setLoop(THREE.LoopOnce)
        if (prevDefenderAction) {
            prevDefenderAction.fadeOut(5)
        }
        action.play()
        action.reset()
        defenderMixer.addEventListener('finished', () => {
            if (!isSkill) {
                prevDefenderAction = action
            }
            resolve('done')
        })

    })
}

export function setWeaponAttacker(newWeaponPath, newWeapon) {
    attackerWeaponPath = newWeaponPath
    attackerWeapon = newWeapon
    if (attackerWeapon === 'none') {
        loadWeaponAttacker(true)
    } else {
        loadWeaponAttacker(false)
    }
}

export function setWeaponDefender(newWeaponPath, newWeapon) {
    defenderWeaponPath = newWeaponPath
    defenderWeapon = newWeapon
    if (defenderWeapon === 'none') {
        loadWeaponDefender(true)
    } else {
        loadWeaponDefender(false)
    }
}

export function init() {
    console.warn = () => {
    }

    let mainWrapper = document.getElementById('mainWrapper')
    let loadingText = document.getElementById('loadingText')

    manager.onStart = function (url, itemsLoaded, itemsTotal) {
        loadingScreen.style.display = 'block'
        if (loadingText != null) {
            loadingText.innerText = itemsLoaded + '/' + itemsTotal
        }
        if (mainWrapper != null) {
            mainWrapper.style.visibility = 'hidden'
        }
    };

    manager.onLoad = function () {
        loadingScreen.style.display = 'none'
        if (mainWrapper != null) {
            mainWrapper.style.visibility = 'visible'
        }
    };

    manager.onProgress = function (url, itemsLoaded, itemsTotal) {
        loadingScreen.style.display = 'block'

        if (mainWrapper != null) {
            mainWrapper.style.visibility = 'hidden'
        }
        if (loadingText != null) {
            loadingText.innerText = itemsLoaded + '/' + itemsTotal
        }
    };

    manager.onError = function (url) {
        loadingScreen.style.display = 'none'
        console.log('There was an error loading ' + url);
    };


    let attackerContainer = document.getElementById('attackerContainer')
    attackerContainer.innerHTML = ''
    let defenderContainer = document.getElementById('defenderContainer')
    defenderContainer.innerHTML = ''

    camera = new THREE.PerspectiveCamera(30, 4.0 / 3.0, 1, 1000)
    camera.position.set(camPosX, camPosY, camPosZ)

    sceneAttacker = new THREE.Scene()
    sceneAttacker.background = null
    sceneDefender = new THREE.Scene()
    sceneDefender.background = null

    let hemiLight = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight.position.set(100, 200, 100)
    let hemiLight2 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight2.position.set(200, 100, 200)
    let hemiLight3 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight3.position.set(-200, -100, 200)
    let light = new THREE.AmbientLight(0x404040)

    sceneAttacker.add(light, hemiLight, hemiLight2)

    hemiLight = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight.position.set(100, 200, 100)
    hemiLight2 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight2.position.set(200, 100, 200)
    hemiLight3 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight3.position.set(-200, -100, 200)
    light = new THREE.AmbientLight(0x404040)

    sceneDefender.add(light, hemiLight, hemiLight2)

    loadModelAttacker(sceneAttacker)
    loadModelDefender(sceneDefender)

    attackerRenderer = new THREE.WebGLRenderer({alpha: true, antialias: true})
    attackerRenderer.setPixelRatio(window.devicePixelRatio)
    attackerRenderer.setSize((100 / 3 * 4), 100)
    attackerRenderer.setClearColor(0xffffff, 0)
    attackerRenderer.gammaInput = true
    attackerRenderer.gammaOutput = true
    attackerContainer.appendChild(attackerRenderer.domElement)

    defenderRenderer = new THREE.WebGLRenderer({alpha: true, antialias: true})
    defenderRenderer.setPixelRatio(window.devicePixelRatio)
    defenderRenderer.setSize((100 / 3 * 4), 100)
    defenderRenderer.setClearColor(0xffffff, 0)
    defenderRenderer.gammaInput = true
    defenderRenderer.gammaOutput = true
    defenderContainer.appendChild(defenderRenderer.domElement)

    new OrbitControls(camera, attackerRenderer.domElement)

}

function rotateObject(object, degreeX = 0, degreeY = 0, degreeZ = 0) {
    object.rotateX(THREE.Math.degToRad(degreeX))
    object.rotateY(THREE.Math.degToRad(degreeY))
    object.rotateZ(THREE.Math.degToRad(degreeZ))
}

export function animate() {
    requestAnimationFrame(animate)
    const delta = clock.getDelta() * 1.1
    if (attackerMixer) attackerMixer.update(delta)
    if (defenderMixer) defenderMixer.update(delta)
    if (attackerRenderer) attackerRenderer.render(sceneAttacker, camera)
    if (defenderRenderer) defenderRenderer.render(sceneDefender, camera)
}
