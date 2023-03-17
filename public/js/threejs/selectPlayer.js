const THREE = window.THREE;
const FBXLoader = window.FBXLoader;
const OrbitControls = window.OrbitControls;


let camera, scene1, renderer1, scene2, renderer2, scene3, renderer3, scene4, renderer4
const clock = new THREE.Clock()

let mixer1 = new THREE.AnimationMixer()
let mixer2 = new THREE.AnimationMixer()
let mixer3 = new THREE.AnimationMixer()
let mixer4 = new THREE.AnimationMixer()

let modelPath1 = ""
let model1 = ""
let weaponPath1 = ""
let weapon1 = ""
let animationPath1 = ""
let animation1 = ""
let hair1 = 'SK_Hair_1_1.fbx'
let globalObject1 = null
let globalChild1 = null
let globalSecondDaggerChild1 = null
let globalWeapon1 = null
let globalSecondDagger1 = null
let enable1 = false

let modelPath2 = ""
let model2 = ""
let weaponPath2 = ""
let weapon2 = ""
let animationPath2 = ""
let animation2 = ""
let hair2 = 'SK_Hair_1_1.fbx'
let globalObject2 = null
let globalChild2 = null
let globalSecondDaggerChild2 = null
let globalWeapon2 = null
let globalSecondDagger2 = null
let enable2 = false

let modelPath3 = ""
let model3 = ""
let weaponPath3 = ""
let weapon3 = ""
let animationPath3 = ""
let animation3 = ""
let hair3 = 'SK_Hair_1_1.fbx'
let globalObject3 = null
let globalChild3 = null
let globalSecondDaggerChild3 = null
let globalWeapon3 = null
let globalSecondDagger3 = null
let enable3 = false

let modelPath4 = ""
let model4 = ""
let weaponPath4 = ""
let weapon4 = ""
let animationPath4 = ""
let animation4 = ""
let hair4 = 'SK_Hair_1_1.fbx'
let globalObject4 = null
let globalChild4 = null
let globalSecondDaggerChild4 = null
let globalWeapon4 = null
let globalSecondDagger4 = null
let enable4 = false

let camPosX = 0
let camPosY = 50
let camPosZ = 200

let ninjaHair
let noweapon = true

let context = null
let effects = {};

let isLogin = false

export function setLogin(login) {
    isLogin = login
}

export function playEffect() {
    //context.play(effects["ring2"], 0, -30, 10);
    //context.play(effects["ring1"], 0, -30, 10);
}

function setEffect() {
    if (isLogin) {
        effects = {};
        effekseer.initRuntime('/js/effekseer/effekseer.wasm', () => {
            context = effekseer.createContext();
            context.setRestorationOfStatesFlag(false);
            context.init(renderer1.getContext());

            let effect = effects["ring1"] = context.loadEffect(
                "/js/effekseer/Resources/01_Pierre02/red_spear.efkefc",
                3.0,
                () => {
                    let handle = context.play(effect, 0, -13, 0);
                    //handle.setTargetLocation(globalChild1.x,globalChild1.y,globalChild1.z)
                },
                (m, url) => {
                    console.log(m + " " + url);
                }
            )
        })
    }
}

export function setVars1(modelPath_n, model_n, weaponPath_n, weapon_n, animationPath_n, animation_n, enable_n) {
    modelPath1 = modelPath_n
    model1 = model_n
    weaponPath1 = weaponPath_n
    weapon1 = weapon_n
    animationPath1 = animationPath_n
    animation1 = animation_n
    enable1 = enable_n
}

export function setVars2(modelPath_n, model_n, weaponPath_n, weapon_n, animationPath_n, animation_n, enable_n) {
    modelPath2 = modelPath_n
    model2 = model_n
    weaponPath2 = weaponPath_n
    weapon2 = weapon_n
    animationPath2 = animationPath_n
    animation2 = animation_n
    enable2 = enable_n
}

export function setVars3(modelPath_n, model_n, weaponPath_n, weapon_n, animationPath_n, animation_n, enable_n) {
    modelPath3 = modelPath_n
    model3 = model_n
    weaponPath3 = weaponPath_n
    weapon3 = weapon_n
    animationPath3 = animationPath_n
    animation3 = animation_n
    enable3 = enable_n
}

export function setVars4(modelPath_n, model_n, weaponPath_n, weapon_n, animationPath_n, animation_n, enable_n) {
    modelPath4 = modelPath_n
    model4 = model_n
    weaponPath4 = weaponPath_n
    weapon4 = weapon_n
    animationPath4 = animationPath_n
    animation4 = animation_n
    enable4 = enable_n
}


export function setCamPos(x, y, z) {
    camPosX = x
    camPosY = y
    camPosZ = z
}

function loadModel(localScene, globalObject, mixer, globalChild, globalSecondDaggerChild, globalSecondDagger, animationPath, animation, weaponPath, weapon, modelPath, model, globalWeapon, isRegister) {
    if (modelPath.includes('warrior')) {
        scene1.remove(globalObject1)
    } else if (modelPath.includes('ninja')) {
        scene2.remove(globalObject2)
    } else if (modelPath.includes('sura')) {
        scene3.remove(globalObject3)
    } else if (modelPath.includes('shaman')) {
        scene4.remove(globalObject4)
    }
    const loader = new FBXLoader()
    loader.setResourcePath('/models' + modelPath)
    loader.setPath('/models' + modelPath)
    loader.load(model, function (object) {
        if (modelPath.includes('warrior')) {
            globalObject1 = object
        } else if (modelPath.includes('ninja')) {
            globalObject2 = object
        } else if (modelPath.includes('sura')) {
            globalObject3 = object
        } else if (modelPath.includes('shaman')) {
            globalObject4 = object
        }
        globalObject = object
        object.scale.set(0.18, 0.18, 0.18)
        object.translateY(-13);
        rotateObject(object, -90, 0, 0)
        loadAnimation(globalObject, animationPath, animation, mixer)
        let issetHair = false

        globalObject.traverse(function (child) {
                if (child.isMesh) {
                    child.castShadow = true
                    child.receiveShadow = true
                }
                if (!noweapon) {
                    if (parseInt(weapon) >= 2000 && parseInt(weapon) < 3000) { // bow
                        if (child.name === "equip_left" || child.name === "equip_left_hand") {//Bip01_L_Finger1Nub
                            globalChild = child
                            loadWeapon(noweapon, globalWeapon, weaponPath, weapon, modelPath, animation, globalChild)
                        }
                    } else {
                        if (child.name === "equip_right" || child.name === "equip_right_hand") {//Bip01_R_Finger1Nub
                            globalChild = child
                            loadWeapon(noweapon, globalWeapon, weaponPath, weapon, modelPath, animation, globalChild)
                        }
                    }
                    if (child.name === "equip_left" && modelPath.includes('ninja')) {//Bip01_L_Finger1Nub
                        globalSecondDaggerChild = child
                        loadSecondDagger(globalSecondDagger, globalSecondDaggerChild, weaponPath, weapon, modelPath)
                    }
                }

                if (!issetHair && child.name === "Bip01_Head") {
                    issetHair = true
                    let hairVar = 'SK_Hair_1_1.fbx'
                    if (modelPath.includes('warrior')) {
                        if (isRegister) {
                            globalChild1 = child
                            hairVar = hair1
                        } else {
                            globalChild1 = child
                            hairVar = hair1
                        }
                    } else if (modelPath.includes('ninja')) {
                        if (isRegister) {
                            globalChild2 = child
                            hairVar = hair2
                        } else {
                            globalChild1 = child
                            hairVar = hair1
                        }
                    } else if (modelPath.includes('sura')) {
                        if (isRegister) {
                            globalChild3 = child
                            hairVar = hair3
                        } else {
                            globalChild1 = child
                            hairVar = hair1
                        }
                    } else if (modelPath.includes('shaman')) {
                        if (isRegister) {
                            globalChild4 = child
                            hairVar = hair4
                        } else {
                            globalChild1 = child
                            hairVar = hair1
                        }
                    }

                    loadHair(child, modelPath, hairVar, true)
                }
            }
        )
        setEffect()
        if (typeof localScene != 'undefined') {
            localScene.add(object)
        }
    })

}

function loadHair(child, modelPath, hairVar, isSelect) {
    if (child != null && child.name === "Bip01_Head") {
        if (!isSelect) {
            child.traverse(function (child2) {
                if (!child2.isBone) {
                    if (!modelPath.includes('ninja')) {
                        child.remove(child2)
                    } else if (typeof ninjaHair !== 'undefined') {
                        child.remove(ninjaHair)
                    }
                }
            })
        }
        let hairLoader = new FBXLoader()
        hairLoader.setResourcePath('/models' + modelPath)
        hairLoader.setPath('/models' + modelPath)
        hairLoader.load(hairVar, function (hairObject) {
            child.add(hairObject)
            if (modelPath.includes('ninja')) {
                ninjaHair = hairObject
            }
        })
    }
}

export function setHair(modelNumber, hair, isSelect) {
    if (modelNumber === '1') {
        hair1 = hair
        loadHair(globalChild1, modelPath1, hair1, isSelect)
    } else if (modelNumber === '2') {
        hair2 = hair
        loadHair(globalChild2, modelPath2, hair2, isSelect)
    } else if (modelNumber === '3') {
        hair3 = hair
        loadHair(globalChild3, modelPath3, hair3, isSelect)
    } else if (modelNumber === '4') {
        hair4 = hair
        loadHair(globalChild4, modelPath4, hair4, isSelect)
    }

}

function loadSecondDagger(globalSecondDagger, globalSecondDaggerChild, weaponPath, weapon, modelPath) {
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

export function allowWeapon(allowWeapon) {
    noweapon = !allowWeapon
}

function loadWeapon(hasNoWeapon, globalWeapon, weaponPath, weapon, modelPath, animation, globalChild) {
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
                        weaponObj.translateX(5)
                        weaponObj.translateY(5)
                        if (parseInt(weapon) === 2030 || parseInt(weapon) === 2040) {
                            weaponObj.translateZ(-70)
                        } else if (parseInt(weapon) !== 2000) {
                            weaponObj.translateZ(-60)
                        }
                    } else {
                        rotateObject(globalWeapon, 90, 0, 180)
                    }
                } else if (animation.includes('Wait1.fbx')) {
                    if (parseInt(weapon) >= 2000 && parseInt(weapon) < 3000) {// bow
                        rotateObject(globalWeapon, 0, 0, 90)
                        if (parseInt(weapon) === 2000) {
                            weaponObj.translateX(10)
                        } else {
                            weaponObj.translateZ(-65)
                            weaponObj.translateX(10)
                        }
                    } else {
                        rotateObject(globalWeapon, 90, 0, 180)
                    }
                }
            }
            if (globalChild != null) {
                globalChild.add(globalWeapon)
            }
        })
    }
}

function loadAnimation(object, animationPath, animation, mixer) {
    let animationLoader = new FBXLoader()
    animationLoader.setResourcePath('/models' + animationPath)
    animationLoader.setPath('/models' + animationPath)
    animationLoader.load(animation, (animationObj) => {
        if (mixer === '1') {
            mixer1 = new THREE.AnimationMixer(object)
            const action = mixer1.clipAction(animationObj.animations[0])
            action.play()
        } else if (mixer === '2') {
            mixer2 = new THREE.AnimationMixer(object)
            const action = mixer2.clipAction(animationObj.animations[0])
            action.play()
        } else if (mixer === '3') {
            mixer3 = new THREE.AnimationMixer(object)
            const action = mixer3.clipAction(animationObj.animations[0])
            action.play()
        } else if (mixer === '4') {
            mixer4 = new THREE.AnimationMixer(object)
            const action = mixer4.clipAction(animationObj.animations[0])
            action.play()
        }
    })
}

export function init(isRegister) {
    console.warn = () => {
    }
    console.error = () => {
    }
    let preview1 = null
    if (document.getElementById('warriorPreview')) {
        preview1 = document.getElementById('warriorPreview')
    } else if (document.getElementById('player1Preview')) {
        preview1 = document.getElementById('player1Preview')
    }
    let preview2 = null
    if (document.getElementById('ninjaPreview')) {
        preview2 = document.getElementById('ninjaPreview')
    } else if (document.getElementById('player2Preview')) {
        preview2 = document.getElementById('player2Preview')
    }
    let preview3 = null
    if (document.getElementById('suraPreview')) {
        preview3 = document.getElementById('suraPreview')
    } else if (document.getElementById('player3Preview')) {
        preview3 = document.getElementById('player3Preview')
    }
    let preview4 = null
    if (document.getElementById('shamanPreview')) {
        preview4 = document.getElementById('shamanPreview')
    } else if (document.getElementById('player4Preview')) {
        preview4 = document.getElementById('player4Preview')
    }
    var body = document.body,
        html = document.documentElement;
    var height = Math.max(body.scrollHeight, body.offsetHeight,
        html.clientHeight, html.scrollHeight, html.offsetHeight);
    let canvasHeight = height//400;

    camera = new THREE.PerspectiveCamera(30, 4.0 / 3.0, 1, 1000)
    camera.position.set(camPosX, camPosY, camPosZ)

    scene1 = new THREE.Scene()
    scene1.background = null
    scene2 = new THREE.Scene()
    scene2.background = null
    scene3 = new THREE.Scene()
    scene3.background = null
    scene4 = new THREE.Scene()
    scene4.background = null

    const hemiLight = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight.position.set(100, 200, 100)
    const hemiLight2 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight2.position.set(200, 100, 200)
    const hemiLight3 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight3.position.set(-200, -100, 200)
    const light = new THREE.AmbientLight(0x404040)
    scene1.add(light, hemiLight, hemiLight2)
    const hemiLight_ = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight_.position.set(100, 200, 100)
    const hemiLight2_ = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight2_.position.set(200, 100, 200)
    const hemiLight3_ = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight3_.position.set(-200, -100, 200)
    const light_ = new THREE.AmbientLight(0x404040)
    scene2.add(light_, hemiLight_, hemiLight2_)
    const hemiLight__ = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight__.position.set(100, 200, 100)
    const hemiLight2__ = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight2__.position.set(200, 100, 200)
    const hemiLight3__ = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight3__.position.set(-200, -100, 200)
    const light__ = new THREE.AmbientLight(0x404040)
    scene3.add(light__, hemiLight__, hemiLight2__)
    const hemiLight___ = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight___.position.set(100, 200, 100)
    const hemiLight2___ = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight2___.position.set(200, 100, 200)
    const hemiLight3___ = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight3___.position.set(-200, -100, 200)
    const light___ = new THREE.AmbientLight(0x404040)
    scene4.add(light___, hemiLight___, hemiLight2___)

    if (preview1 != null && enable1) {
        loadModel(scene1, globalObject1, '1', globalChild1, globalSecondDaggerChild1, globalSecondDagger1, animationPath1, animation1, weaponPath1, weapon1, modelPath1, model1, globalWeapon1, isRegister)
    }
    if (preview2 != null && enable2) {
        loadModel(scene2, globalObject2, '2', globalChild2, globalSecondDaggerChild2, globalSecondDagger2, animationPath2, animation2, weaponPath2, weapon2, modelPath2, model2, globalWeapon2, isRegister)
    }
    if (preview3 != null && enable3) {
        loadModel(scene3, globalObject3, '3', globalChild3, globalSecondDaggerChild3, globalSecondDagger3, animationPath3, animation3, weaponPath3, weapon3, modelPath3, model3, globalWeapon3, isRegister)
    }
    if (preview4 != null && enable4) {
        loadModel(scene4, globalObject4, '4', globalChild4, globalSecondDaggerChild4, globalSecondDagger4, animationPath4, animation4, weaponPath4, weapon4, modelPath4, model4, globalWeapon4, isRegister)
    }
    renderer1 = new THREE.WebGLRenderer({alpha: true, antialias: true})
    renderer1.setPixelRatio(window.devicePixelRatio)
    renderer1.setSize((canvasHeight / 3 * 4), canvasHeight)
    renderer1.setClearColor(0xffffff, 0)
    renderer1.gammaInput = true
    renderer1.gammaOutput = true
    if (preview1 != null) {
        preview1.appendChild(renderer1.domElement)
    }
    renderer2 = new THREE.WebGLRenderer({alpha: true, antialias: true})
    renderer2.setPixelRatio(window.devicePixelRatio)
    renderer2.setSize((canvasHeight / 3 * 4), canvasHeight)
    renderer2.setClearColor(0xffffff, 0)
    renderer2.gammaInput = true
    renderer2.gammaOutput = true
    if (preview2 != null) {
        preview2.appendChild(renderer2.domElement)
    }
    renderer3 = new THREE.WebGLRenderer({alpha: true, antialias: true})
    renderer3.setPixelRatio(window.devicePixelRatio)
    renderer3.setSize((canvasHeight / 3 * 4), canvasHeight)
    renderer3.setClearColor(0xffffff, 0)
    renderer3.gammaInput = true
    renderer3.gammaOutput = true
    if (preview3 != null) {
        preview3.appendChild(renderer3.domElement)
    }
    renderer4 = new THREE.WebGLRenderer({alpha: true, antialias: true})
    renderer4.setPixelRatio(window.devicePixelRatio)
    renderer4.setSize((canvasHeight / 3 * 4), canvasHeight)
    renderer4.setClearColor(0xffffff, 0)
    renderer4.gammaInput = true
    renderer4.gammaOutput = true
    if (preview4 != null) {
        preview4.appendChild(renderer4.domElement)
    }
    new OrbitControls(camera, renderer1.domElement)
    new OrbitControls(camera, renderer2.domElement)
    new OrbitControls(camera, renderer3.domElement)
    new OrbitControls(camera, renderer4.domElement)
}

function rotateObject(object, degreeX = 0, degreeY = 0, degreeZ = 0) {
    object.rotateX(THREE.Math.degToRad(degreeX))
    object.rotateY(THREE.Math.degToRad(degreeY))
    object.rotateZ(THREE.Math.degToRad(degreeZ))
}

export function animate() {
    requestAnimationFrame(animate)
    const delta = clock.getDelta()
    if (mixer1) mixer1.update(delta)
    if (mixer2) mixer2.update(delta)
    if (mixer3) mixer3.update(delta)
    if (mixer4) mixer4.update(delta)
    renderer1.render(scene1, camera)
    renderer2.render(scene2, camera)
    renderer3.render(scene3, camera)
    renderer4.render(scene4, camera)
    if (context != null) {
        context.update(clock.getDelta() * 1500.0);
        context.setProjectionMatrix(camera.projectionMatrix.elements);
        context.setCameraMatrix(camera.matrixWorldInverse.elements);
        context.draw();
        renderer1.resetState();
    }
}
