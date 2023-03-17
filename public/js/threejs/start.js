const THREE = window.THREE;
const FBXLoader = window.FBXLoader;


let cameraMain, sceneMain, rendererMain
let sceneGeneral, rendererGeneral
let sceneBlack, rendererBlack
let sceneWeapon, rendererWeapon
let sceneArmor, rendererArmor
const clock = new THREE.Clock()
let mixer = undefined

let generalStoreMixer = undefined
let weaponStoreMixer = undefined
let armorStoreMixer = undefined
let blacksmithMixer = undefined

let modelPath = ""
let model = ""
let weaponPath = ""
let weapon = ""
let animationPath = ""
let animation = ""
let globalObject = null
let globalChild = null
let globalHair = null
let globalSecondDaggerChild = null
let globalWeapon = null
let globalSecondDagger = null
let camPosX = 0
let camPosY = 50
let camPosZ = 200
let globalHairVar = 'SK_Hair_1_1.fbx'
let playerHair = null

export function setVars(modelPath_n, model_n, weaponPath_n, weapon_n, animationPath_n, animation_n) {
    modelPath = modelPath_n
    model = model_n
    weaponPath = weaponPath_n
    weapon = weapon_n
    animationPath = animationPath_n
    animation = animation_n
}

export function setCamPos(x, y, z) {
    camPosX = x
    camPosY = y
    camPosZ = z
}

export function setGlobalHair(hair) {
    globalHairVar = hair
}

export function loadModel(short) {
    sceneMain.remove(globalObject)
    const loader = new FBXLoader()
    loader.setResourcePath('/models' + modelPath)
    loader.setPath('/models' + modelPath)
    loader.load(model, function (object) {
        globalObject = object
        object.scale.set(0.7, 0.7, 0.7)
        if (animationPath !== 'none') {
            rotateObject(object, -90, 0, 0)
            object.translateZ(-25)
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
        sceneMain.add(object)
    })
    if (!short) {
        loadGeneralStore(sceneGeneral)
        loadWeaponStore(sceneWeapon)
        loadArmorStore(sceneArmor)
        loadBlacksmith(sceneBlack)
    }
}

export function setHair(hair) {
    globalHairVar = hair
    loadHair(modelPath)
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

function loadGeneralStore() {
    const loader = new FBXLoader()
    loader.setResourcePath('/models/npc/goods/')
    loader.setPath('/models/npc/goods/')
    loader.load('wait_1.fbx', function (object) {
        rotateObject(object, 10, 0, 0)
        object.translateY(-25)
        object.scale.set(3.5, 3.5, 3.5)
        generalStoreMixer = new THREE.AnimationMixer(object)
        const action = generalStoreMixer.clipAction(object.animations[0])
        action.play()
        sceneGeneral.add(object)
    })
}

function loadWeaponStore() {
    const loader = new FBXLoader()
    loader.setResourcePath('/models/npc/arms/')
    loader.setPath('/models/npc/arms/')
    loader.load('wait_1.fbx', function (object) {
        object.scale.set(1.4, 1.4, 1.4)
        rotateObject(object, 10, -20, 0)
        object.translateY(-50)
        weaponStoreMixer = new THREE.AnimationMixer(object)
        const action = weaponStoreMixer.clipAction(object.animations[0])
        action.play()
        sceneWeapon.add(object)
    })
}

function loadArmorStore() {
    const loader = new FBXLoader()
    loader.setResourcePath('/models/npc/defence/')
    loader.setPath('/models/npc/defence/')
    loader.load('wait_1.fbx', function (object) {
        rotateObject(object, 10, -30, 0)
        object.scale.set(1.5, 1.5, 1.5)
        object.translateY(-40)

        armorStoreMixer = new THREE.AnimationMixer(object)
        const action = armorStoreMixer.clipAction(object.animations[0])
        action.play()
        sceneArmor.add(object)
    })
}

function loadBlacksmith() {
    const loader = new FBXLoader()
    loader.setResourcePath('/models/npc/blacksmith/')
    loader.setPath('/models/npc/blacksmith/')
    loader.load('wait_1.fbx', function (object) {
        object.scale.set(3, 3, 3)
        rotateObject(object, 10, 40, 0)
        object.translateY(-40)
        blacksmithMixer = new THREE.AnimationMixer(object)
        const action = blacksmithMixer.clipAction(object.animations[0])
        action.play()
        sceneBlack.add(object)
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
    console.error = () => {
    }
    let mainContainer = document.getElementById('rendererMain')
    let generalContainer = document.getElementById('rendererGeneral')
    let blackContainer = document.getElementById('rendererBlack')
    let armorContainer = document.getElementById('rendererArmor')
    let weaponContainer = document.getElementById('rendererWeapon')

    cameraMain = new THREE.PerspectiveCamera(30, 4.0 / 3.0, 1, 1000)
    cameraMain.position.set(camPosX, camPosY, camPosZ)

    sceneMain = new THREE.Scene()
    sceneMain.background = null
    sceneGeneral = new THREE.Scene()
    sceneGeneral.background = null
    sceneBlack = new THREE.Scene()
    sceneBlack.background = null
    sceneArmor = new THREE.Scene()
    sceneArmor.background = null
    sceneWeapon = new THREE.Scene()
    sceneWeapon.background = null

    let hemiLight = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight.position.set(100, 200, 100)
    let hemiLight2 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight2.position.set(200, 100, 200)
    let light = new THREE.AmbientLight(0x404040)
    sceneMain.add(light, hemiLight, hemiLight2)
    const hemiLight_ = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight_.position.set(100, 200, 100)
    const hemiLight2_ = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight2_.position.set(200, 100, 200)
    const light_ = new THREE.AmbientLight(0x404040)
    sceneGeneral.add(light_, hemiLight_, hemiLight2_)
    const hemiLight_1 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight_1.position.set(100, 200, 100)
    const hemiLight2_1 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight2_1.position.set(200, 100, 200)
    const light_1 = new THREE.AmbientLight(0x404040)
    sceneBlack.add(light_1, hemiLight_1, hemiLight2_1)
    const hemiLight_2 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight_2.position.set(100, 200, 100)
    const hemiLight2_2 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight2_2.position.set(200, 100, 200)
    const light_2 = new THREE.AmbientLight(0x404040)
    sceneArmor.add(light_2, hemiLight_2, hemiLight2_2)
    const hemiLight_3 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight_3.position.set(100, 200, 100)
    const hemiLight2_3 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight2_3.position.set(200, 100, 200)
    const light_3 = new THREE.AmbientLight(0x404040)
    sceneWeapon.add(light_3, hemiLight_3, hemiLight2_3)

    loadModel(false)

    rendererMain = new THREE.WebGLRenderer({alpha: true, antialias: true, powerPreference: "high-performance"})
    rendererMain.setPixelRatio(window.devicePixelRatio)
    rendererMain.setSize((mainContainer.clientHeight / 3 * 4), mainContainer.clientHeight)
    rendererMain.setClearColor(0xffffff, 0)
    rendererMain.gammaInput = true
    rendererMain.gammaOutput = true

    rendererGeneral = new THREE.WebGLRenderer({alpha: true, antialias: true, powerPreference: "high-performance"})
    rendererGeneral.setPixelRatio(window.devicePixelRatio)
    rendererGeneral.setSize((generalContainer.clientHeight / 3 * 4), generalContainer.clientHeight)
    rendererGeneral.setClearColor(0xffffff, 0)
    rendererGeneral.gammaInput = true
    rendererGeneral.gammaOutput = true
    rendererGeneral.domElement.onclick = function () {
        openWindow('generalStoreContainer')
    }
    rendererBlack = new THREE.WebGLRenderer({alpha: true, antialias: true, powerPreference: "high-performance"})
    rendererBlack.setPixelRatio(window.devicePixelRatio)
    rendererBlack.setSize((blackContainer.clientHeight / 3 * 4), blackContainer.clientHeight)
    rendererBlack.setClearColor(0xffffff, 0)
    rendererBlack.gammaInput = true
    rendererBlack.gammaOutput = true

    rendererArmor = new THREE.WebGLRenderer({alpha: true, antialias: true, powerPreference: "high-performance"})
    rendererArmor.setPixelRatio(window.devicePixelRatio)
    rendererArmor.setSize((armorContainer.clientHeight / 3 * 4), armorContainer.clientHeight)
    rendererArmor.setClearColor(0xffffff, 0)
    rendererArmor.gammaInput = true
    rendererArmor.gammaOutput = true
    rendererArmor.domElement.onclick = function () {
        openWindow('armorStoreContainer')
    }
    rendererWeapon = new THREE.WebGLRenderer({alpha: true, antialias: true, powerPreference: "high-performance"})
    rendererWeapon.setPixelRatio(window.devicePixelRatio)
    rendererWeapon.setSize((weaponContainer.clientHeight / 3 * 4), weaponContainer.clientHeight)
    rendererWeapon.setClearColor(0xffffff, 0)
    rendererWeapon.gammaInput = true
    rendererWeapon.gammaOutput = true
    rendererWeapon.domElement.onclick = function () {
        openWindow('weaponStoreContainer')
    }
    blackContainer.appendChild(rendererBlack.domElement)
    generalContainer.appendChild(rendererGeneral.domElement)
    mainContainer.appendChild(rendererMain.domElement)
    weaponContainer.appendChild(rendererWeapon.domElement)
    armorContainer.appendChild(rendererArmor.domElement)

    window.addEventListener('resize', onWindowResize, false);

}


function onWindowResize() {
    if (typeof mainContainer != 'undefined') {
        rendererMain.setSize((mainContainer.clientHeight / 3 * 4), mainContainer.clientHeight)
    }
    if (typeof generalContainer != 'undefined') {
        rendererGeneral.setSize((generalContainer.clientHeight / 3 * 4), generalContainer.clientHeight)
    }
    if (typeof blackContainer != 'undefined') {
        rendererBlack.setSize((blackContainer.clientHeight / 3 * 4), blackContainer.clientHeight)
    }
    if (typeof armorContainer != 'undefined') {
        rendererArmor.setSize((armorContainer.clientHeight / 3 * 4), armorContainer.clientHeight)
    }
    if (typeof weaponContainer != 'undefined') {
        rendererWeapon.setSize((weaponContainer.clientHeight / 3 * 4), weaponContainer.clientHeight)
    }
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
    if (generalStoreMixer && typeof generalStoreMixer != 'undefined') generalStoreMixer.update(delta)
    if (weaponStoreMixer && typeof weaponStoreMixer != 'undefined') weaponStoreMixer.update(delta)
    if (armorStoreMixer && typeof armorStoreMixer != 'undefined') armorStoreMixer.update(delta)
    if (blacksmithMixer && typeof blacksmithMixer != 'undefined') blacksmithMixer.update(delta)
    rendererMain.render(sceneMain, cameraMain)
    rendererGeneral.render(sceneGeneral, cameraMain)
    rendererBlack.render(sceneBlack, cameraMain)
    rendererArmor.render(sceneArmor, cameraMain)
    rendererWeapon.render(sceneWeapon, cameraMain)
}
