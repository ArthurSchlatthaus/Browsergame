const THREE = window.THREE;
const FBXLoader = window.FBXLoader;

let camera, scene, renderer
const clock = new THREE.Clock()
let mixer

let modelPath = ""
let model = ""
let weaponPath = ""
let weapon = ""
let animationPath = ""
let animation = ""
let globalObject = null
let globalChild = null
let globalSecondDaggerChild = null
let globalWeapon = null
let globalSecondDagger = null
let camPosX = 0
let camPosY = 10
let camPosZ = 200

let modelPath2 = ""
let modelPath3 = ""
let mixer2 = undefined
let mixer3 = undefined
let globalHairVar = null
let playerHair = null
let globalHair = null

export function moreNpc(modelPath2_n, modelPath3_n) {
    modelPath2 = modelPath2_n
    modelPath3 = modelPath3_n
}

export function setVars(modelPath_n, model_n, weaponPath_n, weapon_n, animationPath_n, animation_n, hair) {
    modelPath = modelPath_n
    model = model_n
    weaponPath = weaponPath_n
    weapon = weapon_n
    animationPath = animationPath_n
    animation = animation_n
    globalHairVar = hair
}

export function setCamPos(x, y, z) {
    camPosX = x
    camPosY = y
    camPosZ = z
}

export function setModel(newModelPath, newModel) {
    modelPath = newModelPath
    model = newModel
    loadModel()
}

export function setWeapon(newWeaponPath, newWeapon) {
    weaponPath = newWeaponPath
    weapon = newWeapon
    if (weapon === 'none') {
        loadWeapon(true)
    } else {
        loadWeapon(false)
    }
}

function loadModel(localScene) {
    localScene.remove(globalObject)
    const loader = new FBXLoader()
    loader.setResourcePath('/models' + modelPath)
    loader.setPath('/models' + modelPath)
    loader.load(model, function (object) {
        globalObject = object
        object.translateX(-50)
        object.scale.set(1.1, 1.1, 1.1)
        if (animationPath !== 'none') {
            rotateObject(object, -90, 0, 0)
            object.translateZ(-75)
            loadAnimation(object)
        } else {
            mixer = new THREE.AnimationMixer(object)
            const action = mixer.clipAction(object.animations[0])
            action.play()
        }
        let issetHair = false
        globalObject.traverse(function (child) {
            if (child.isMesh) {
                child.castShadow = true
                child.receiveShadow = true
            }
            if (parseInt(weapon) >= 2000 && parseInt(weapon) < 3000) { // bow
                if (child.name === "equip_left") {
                    globalChild = child
                    loadWeapon(false)
                }
            } else {
                if (child.name === "equip_right" || child.name === "equip_right_hand") {
                    globalChild = child
                    loadWeapon(false)
                }
            }
            if (parseInt(weapon) >= 1000 && parseInt(weapon) < 2000) {//dagger
                if (child.name === "equip_left") {
                    globalSecondDaggerChild = child
                    loadSecondDagger()
                }
            }
            if (modelPath.includes('warrior') && child.name === 'warrior_hair') {
                playerHair = child
            }
            if (modelPath.includes('ninja') && child.name === 'assassin_hair01') {
                playerHair = child
            }
            if (!issetHair && child.name === 'Bip01_Head') {
                globalHair = child
                issetHair = true
            }
        })
        loadHair(modelPath)
        localScene.add(object)
    })
}

function loadHair(modelPath) {
    globalObject.remove(playerHair)
    let hairLoader = new FBXLoader()
    hairLoader.setResourcePath('/models' + modelPath)
    hairLoader.setPath('/models' + modelPath)
    if (globalHairVar === '') {
        globalHairVar = 'SK_Hair_1_1.fbx'
    }
    hairLoader.load(globalHairVar, function (hairObject) {
        globalHair.add(hairObject)
    })
}

function loadSecondDagger() {
    if (globalSecondDagger != null && globalSecondDaggerChild != null) {
        globalSecondDaggerChild.remove(globalSecondDagger)
    }
    let weaponLoader = new FBXLoader()
    weaponLoader.setResourcePath('/models' + weaponPath)
    weaponLoader.setPath('/models' + weaponPath)
    weaponLoader.load(weapon, function (weaponObj) {
        globalSecondDagger = weaponObj
        weapon.substring(weapon.length - 4)
        if (modelPath.includes('ninja')) {
            weaponObj.translateX(2)
            rotateObject(globalSecondDagger, -90, 180, 180)
        }
        if (globalSecondDaggerChild != null) {
            globalSecondDaggerChild.add(globalSecondDagger)
        }
    })
}

function loadWeapon(hasNoWeapon) {
    if (globalWeapon != null && globalChild != null) {
        globalChild.remove(globalWeapon)
    }
    let weaponLoader = new FBXLoader()
    weaponLoader.setResourcePath('/models' + weaponPath)
    weaponLoader.setPath('/models' + weaponPath)
    if (!hasNoWeapon) {
        weaponLoader.load(weapon, function (weaponObj) {
            globalWeapon = weaponObj
            weapon.substring(weapon.length - 4)
            if (modelPath.includes('warrior')) {
                rotateObject(globalWeapon, 90, 0, 0)
                if (parseInt(weapon) >= 3000 && parseInt(weapon) < 4000) { // 2hand
                    if (parseInt(weapon) === 3000) {
                        weaponObj.translateY(-75)
                    } else if (parseInt(weapon) === 3040) {
                        weaponObj.translateY(-10)
                    } else {
                        weaponObj.translateY(-35)
                    }
                }
            } else if (modelPath.includes('ninja')) {
                if (animation.includes('Wait.fbx')) {
                    if (parseInt(weapon) >= 2000 && parseInt(weapon) < 3000) { // bow
                        rotateObject(globalWeapon, 0, 0, 180)
                        if (parseInt(weapon) === 2030 || parseInt(weapon) === 2040) {
                            weaponObj.translateZ(-70)
                        } else if (parseInt(weapon) !== 2000) {
                            weaponObj.translateZ(-60)
                        }
                    } else {
                        rotateObject(globalWeapon, -90, 200, 180)
                    }
                } else if (animation.includes('Wait1.fbx')) {
                    if (parseInt(weapon) >= 2000 && parseInt(weapon) < 3000) {// bow
                        rotateObject(globalWeapon, 0, 0, 180)
                        if (parseInt(weapon) !== 2000) {
                            weaponObj.translateZ(-65)
                        }
                    } else {
                        rotateObject(globalWeapon, -90, 200, 180)
                    }
                }
            }
            if (globalChild != null) {
                globalChild.add(globalWeapon)
            }
        })
    }
}

function loadAnimation(globalObject) {
    let animationLoader = new FBXLoader()
    animationLoader.setResourcePath('/models' + animationPath)
    animationLoader.setPath('/models' + animationPath)
    animationLoader.load(animation, (animationObj) => {
        mixer = new THREE.AnimationMixer(globalObject)
        const action = mixer.clipAction(animationObj.animations[0])
        action.play()
    })
}

export function init() {
    console.warn = () => {
    }
    let container = document.getElementById('npcContainer')
    container.innerHTML = ''
    camera = new THREE.PerspectiveCamera(30, 4.0 / 3.0, 1, 1000)
    camera.position.set(camPosX, camPosY, camPosZ)

    scene = new THREE.Scene()
    scene.background = null

    const hemiLight = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight.position.set(100, 200, 100)
    const hemiLight2 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight2.position.set(200, 100, 200)
    const hemiLight3 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight3.position.set(-200, -100, 200)
    const light = new THREE.AmbientLight(0x404040)
    scene.add(light, hemiLight, hemiLight2)
    loadModel(scene)

    renderer = new THREE.WebGLRenderer({alpha: true, antialias: true})
    renderer.setPixelRatio(window.devicePixelRatio)
    renderer.setSize((container.clientHeight / 3 * 4), container.clientHeight)
    renderer.setClearColor(0xffffff, 0)
    renderer.gammaInput = true
    renderer.gammaOutput = true

    container.appendChild(renderer.domElement)
}

function rotateObject(object, degreeX = 0, degreeY = 0, degreeZ = 0) {
    object.rotateX(THREE.Math.degToRad(degreeX))
    object.rotateY(THREE.Math.degToRad(degreeY))
    object.rotateZ(THREE.Math.degToRad(degreeZ))
}

export function animate() {
    requestAnimationFrame(animate)
    const delta = clock.getDelta()
    if (mixer) mixer.update(delta)
    if (mixer2 && typeof mixer2 != 'undefined') mixer2.update(delta)
    if (mixer3 && typeof mixer3 != 'undefined') mixer3.update(delta)
    renderer.render(scene, camera)
}
