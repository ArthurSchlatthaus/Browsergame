const THREE = window.THREE;
const FBXLoader = window.FBXLoader;

let camera, scene, renderer, mixer
let monsterCamera, monsterScene, monsterRenderer
const clock = new THREE.Clock()
let mixerMonster = []

let modelPath = ""
let model = ""
let weaponPath = ""
let weapon = ""
let animationPath = ""
let animation = ""
let globalObject = null
let globalChild = null
let globalWeapon = null
let globalSecondDagger = null
let globalSecondDaggerChild = null

let monsterId1 = "0"
let monsterId2 = "0"
let monsterId3 = "0"
let monsterAnimation = ["none", "none", "none"]
let globalMonsterObject1 = null
let globalMonsterObject2 = null
let globalMonsterObject3 = null
let globalHairVar = 'SK_Hair_1_1.fbx'

let hitsLoaded = false
let comboClips = []
let prevAction = null
let context = null
let effects = []

export function playEffect(id) {
    context.play(effects[id]);
}

function setEffect(modelPath) {
    if (modelPath.includes('warrior')) {
        effects = {};
        effekseer.initRuntime('/js/effekseer/effekseer.wasm', () => {
            context = effekseer.createContext();
            context.setRestorationOfStatesFlag(false);
            context.init(renderer.getContext());
            effects[2] = context.loadEffect(
                "/js/effekseer/Resources/02_Tktk03/Light.efkefc", 8.0, () => {
                },
                (m, url) => {
                    console.log(m + " " + url);
                }
            )
            effects[3] = context.loadEffect(
                "/js/effekseer/Resources/02_Tktk03/Light.efkefc", 8.0, () => {
                },
                (m, url) => {
                    console.log(m + " " + url);
                }
            )
        })
    }
}

export function setVars(modelPath_n, model_n, weaponPath_n, weapon_n, animationPath_n, animation_n) {
    modelPath = modelPath_n
    model = model_n
    weaponPath = weaponPath_n
    weapon = weapon_n
    animationPath = animationPath_n
    animation = animation_n

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

export function setMonsterAnimation(monster1Animation_n, monster2Animation_n, monster3Animation_n) {
    monsterAnimation[0] = monster1Animation_n
    monsterAnimation[1] = monster2Animation_n
    monsterAnimation[2] = monster3Animation_n
    if (monster1Animation_n !== 'none') {
        loadAnimationMonster1(globalMonsterObject1, '/monster/' + monsterId1 + '/', monsterId1 + "_" + monster1Animation_n + '.fbx')
    }
    if (monster2Animation_n !== 'none') {
        loadAnimationMonster2(globalMonsterObject2, '/monster/' + monsterId2 + '/', monsterId2 + "_" + monster2Animation_n + '.fbx')
    }
    if (monster3Animation_n !== 'none') {
        loadAnimationMonster3(globalMonsterObject3, '/monster/' + monsterId3 + '/', monsterId3 + "_" + monster3Animation_n + '.fbx')
    }
}

export function setMonster(monsterId1_n, monsterId2_n, monsterId3_n, monsterAnimation_n) {
    monsterId1 = monsterId1_n
    monsterId2 = monsterId2_n
    monsterId3 = monsterId3_n
    monsterAnimation = monsterAnimation_n
    loadMonster1()
    loadMonster2()
    loadMonster3()
}

export function setAnimation(newAnimationPath, newAnimation) {
    animationPath = newAnimationPath
    animation = newAnimation
    loadAnimation(globalObject, animationPath, animation)
}

export function setGlobalHair(hair) {
    globalHairVar = hair
}

function loadModel() {
    scene.remove(globalObject)
    const loader = new FBXLoader()
    loader.setResourcePath('/models' + modelPath)
    loader.setPath('/models' + modelPath)
    loader.load(model, function (object) {
        globalObject = object
        rotateObject(object, -80, 0, 70)
        hitsLoaded = false
        loadAnimation(globalObject, animationPath, animation)
        object.scale.set(0.3, 0.3, 0.3)
        object.translateX(20)
        let issetHair = false
        globalObject.traverse(function (child) {
            if (child.isMesh) {
                child.castShadow = true
                child.receiveShadow = true
            }
            if (modelPath.includes('ninja')) {
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
            if (!issetHair && child.name === 'Bip01_Head') {
                issetHair = true
                let hairLoader = new FBXLoader()
                hairLoader.setResourcePath('/models' + modelPath)
                hairLoader.setPath('/models' + modelPath)
                hairLoader.load(globalHairVar, function (hairObject) {
                    child.add(hairObject)
                })
            }
        })
        scene.add(object)
    })
    setEffect(modelPath)
}


function loadMonster1() {
    if (globalMonsterObject1 != null) {
        scene.remove(globalMonsterObject1)
    }
    if (monsterId1 < 1 || monsterAnimation[0] === "none") {
        return;
    }

    const loader = new FBXLoader();
    loader.setResourcePath('/models/monster/' + monsterId1 + '/');
    loader.setPath('/models/monster/' + monsterId1 + '/');
    loader.load(monsterId1 + "_" + monsterAnimation[0] + '.fbx', function (object) {
        globalMonsterObject1 = object
        rotateObject(object, 0, -10, 0);
        object.scale.set(0.15, 0.15, 0.15)
        object.translateX(-30);
        object.translateZ(-60);
        let mixerMonster1 = new THREE.AnimationMixer(object);
        const action = mixerMonster1.clipAction(object.animations[0]);
        if (monsterAnimation[0] === 'dead_1' || monsterAnimation[0] === 'dead_2') {
            action.setLoop(THREE.LoopOnce);
            action.clampWhenFinished = true;
            action.enable = true;
        }
        mixerMonster[0] = mixerMonster1
        action.play();
        monsterScene.add(object);
    });
}

function loadMonster2() {
    if (globalMonsterObject2 != null) {
        scene.remove(globalMonsterObject2)
    }
    if (monsterId2 < 1 || monsterAnimation[1] === "none") {
        return;
    }

    const loader = new FBXLoader();
    loader.setResourcePath('/models/monster/' + monsterId2 + '/');
    loader.setPath('/models/monster/' + monsterId2 + '/');
    loader.load(monsterId2 + "_" + monsterAnimation[1] + '.fbx', function (object) {
        globalMonsterObject2 = object
        rotateObject(object, 0, -65, 0);
        object.scale.set(0.15, 0.15, 0.15)
        object.translateX(-50);
        object.translateZ(-20);
        let mixerMonster2 = new THREE.AnimationMixer(object);
        const action = mixerMonster2.clipAction(object.animations[0]);
        if (monsterAnimation[1] === 'dead_1' || monsterAnimation[1] === 'dead_2') {
            action.setLoop(THREE.LoopOnce);
            action.clampWhenFinished = true;
            action.enable = true;
        }
        mixerMonster[1] = mixerMonster2
        action.play();
        monsterScene.add(object);
    });
}

function loadMonster3() {
    if (globalMonsterObject3 != null) {
        scene.remove(globalMonsterObject3)
    }
    if (monsterId3 < 1 || monsterAnimation[2] === "none") {
        return;
    }

    const loader = new FBXLoader();
    loader.setResourcePath('/models/monster/' + monsterId3 + '/');
    loader.setPath('/models/monster/' + monsterId3 + '/');
    loader.load(monsterId3 + "_" + monsterAnimation[2] + '.fbx', function (object) {
        globalMonsterObject3 = object
        rotateObject(object, 0, -65, 0);
        object.scale.set(0.15, 0.15, 0.15)
        object.translateX(10);
        object.translateZ(0);
        let mixerMonster3 = new THREE.AnimationMixer(object);
        const action = mixerMonster3.clipAction(object.animations[0]);
        if (monsterAnimation[2] === 'dead_1' || monsterAnimation[2] === 'dead_2') {
            action.setLoop(THREE.LoopOnce);
            action.clampWhenFinished = true;
            action.enable = true;
        }
        mixerMonster[2] = mixerMonster3
        action.play();
        monsterScene.add(object);
    });
}

function loadAnimationMonster1(globalObject, animationPath_n, animation_n) {
    let animationLoader = new FBXLoader()
    animationLoader.setResourcePath('/models' + animationPath_n)
    animationLoader.setPath('/models' + animationPath_n)
    animationLoader.load(animation_n, (animationObj) => {
        let mixerMonster1 = new THREE.AnimationMixer(globalObject)
        const action = mixerMonster1.clipAction(animationObj.animations[0])
        if (monsterAnimation[0] === 'dead_1' || monsterAnimation[0] === 'dead_2') {
            action.setLoop(THREE.LoopOnce);
            action.clampWhenFinished = true;
            action.enable = true;
        }
        mixerMonster[0] = mixerMonster1
        action.play()
    })
}

function loadAnimationMonster2(globalObject, animationPath_n, animation_n) {
    let animationLoader = new FBXLoader()
    animationLoader.setResourcePath('/models' + animationPath_n)
    animationLoader.setPath('/models' + animationPath_n)
    animationLoader.load(animation_n, (animationObj) => {
        let mixerMonster2 = new THREE.AnimationMixer(globalObject)
        const action = mixerMonster2.clipAction(animationObj.animations[0])
        if (monsterAnimation[1] === 'dead_1' || monsterAnimation[1] === 'dead_2') {
            action.setLoop(THREE.LoopOnce);
            action.clampWhenFinished = true;
            action.enable = true;
        }
        mixerMonster[1] = mixerMonster2
        action.play()
    })
}

function loadAnimationMonster3(globalObject, animationPath_n, animation_n) {
    let animationLoader = new FBXLoader()
    animationLoader.setResourcePath('/models' + animationPath_n)
    animationLoader.setPath('/models' + animationPath_n)
    animationLoader.load(animation_n, (animationObj) => {
        let mixerMonster3 = new THREE.AnimationMixer(globalObject)
        const action = mixerMonster3.clipAction(animationObj.animations[0])
        if (monsterAnimation[2] === 'dead_1' || monsterAnimation[2] === 'dead_2') {
            action.setLoop(THREE.LoopOnce);
            action.clampWhenFinished = true;
            action.enable = true;
        }
        mixerMonster[2] = mixerMonster3
        action.play()
    })
}

export function startHitAnimation() {
    play('A_Combo01.fbx').then(
        function () {
            play('A_Combo02.fbx').then(
                function () {
                    play('A_Combo03.fbx').then(
                        function () {
                            play('A_Combo04.fbx').then(
                                function () {
                                    play('A_Combo05.fbx').then()
                                }
                            )
                        }
                    )
                }
            )
        }
    )
}

function loadWeapon(hasNoWeapon) {
    if (globalWeapon != null && globalChild != null) {
        globalChild.remove(globalWeapon)
    }
    if (globalSecondDagger != null && globalSecondDaggerChild != null) {
        globalSecondDaggerChild.remove(globalSecondDagger)
    }
    let weaponLoader = new FBXLoader()
    weaponLoader.setResourcePath('/models' + weaponPath)
    weaponLoader.setPath('/models' + weaponPath)
    if (!hasNoWeapon) {
        weaponLoader.load(weapon, function (weaponObj) {
            globalWeapon = weaponObj
            if (modelPath.includes('warrior')) {
                weapon.substring(weapon.length - 4)
                rotateObject(globalWeapon, 90, 0, 0)
                if (parseInt(weapon) >= 3000 && parseInt(weapon) < 4000) {//2hand
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

function loadAnimation(globalObject, animationPath_n, animation_n) {
    let animationLoader = new FBXLoader()
    animationLoader.setResourcePath('/models' + animationPath_n)
    animationLoader.setPath('/models' + animationPath_n)
    if (!hitsLoaded) {
        hitsLoaded = true
        let hitArray = ['A_Combo01.fbx', 'A_Combo02.fbx', 'A_Combo03.fbx', 'A_Combo04.fbx']
        comboClips = []
        hitArray.forEach(tmp => {
            animationLoader.load(tmp, (obj) => {
                    let tmp2 = obj.animations[0]
                    tmp2.name = tmp
                    comboClips.push(tmp2)
                }
            )
        })
    }
    animationLoader.load(animation_n, (obj) => {
            mixer = new THREE.AnimationMixer(globalObject)
            let action = mixer.clipAction(obj.animations[0])
            if (animation_n === 'A_Dead.fbx') {
                action.setLoop(THREE.LoopOnce);
                action.clampWhenFinished = true;
            }
            action.play()
        }
    )
}

function play(name) {
    return new Promise(resolve => {
        const clip = THREE.AnimationClip.findByName(comboClips, name)
        let action = mixer.clipAction(clip)
        if (action != null) {
            action.reset()
            action.setLoop(THREE.LoopOnce)
            if (prevAction) {
                prevAction.fadeOut(5)
            }
            action.play()
            mixer.addEventListener('finished', () => {
                prevAction = action
                resolve('done')
            })
        }
    })
}

export function init() {
    console.warn = () => {
    }
    console.error = () => {
    }
    const container = document.getElementById('fightContainer')
    let containerHeight = container.style.height.slice(0, -2)

    camera = new THREE.PerspectiveCamera(10, 4.0 / 3.0, 1, 1000)
    camera.position.set(0, 30, 400)
    monsterCamera = new THREE.PerspectiveCamera(10, 4.0 / 3.0, 1, 1000)
    monsterCamera.position.set(0, 15, 400)
    scene = new THREE.Scene()
    scene.background = null;

    monsterScene = new THREE.Scene()
    monsterScene.background = null;

    const hemiLight = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight.position.set(100, 200, 100)
    const hemiLight2 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight2.position.set(200, 100, 200)
    const hemiLight3 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight3.position.set(-200, -100, 200)
    const light = new THREE.AmbientLight(0x404040)
    scene.add(light, hemiLight, hemiLight2)

    const hemiLight_ = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight_.position.set(100, 200, 100)
    const hemiLight2_ = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight2_.position.set(200, 100, 200)
    const hemiLight3_ = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight3_.position.set(-200, -100, 200)
    const light_ = new THREE.AmbientLight(0x404040)
    monsterScene.add(light_, hemiLight_, hemiLight2_)

    loadModel()

    renderer = new THREE.WebGLRenderer({alpha: true, antialias: true, powerPreference: "high-performance"})
    renderer.setPixelRatio(window.devicePixelRatio)
    renderer.setSize((containerHeight / 3 * 4) / 2.2, containerHeight / 2.2);
    renderer.setClearColor(0xffffff, 0);
    renderer.domElement.id = 'fightRenderer';
    renderer.gammaInput = true;
    renderer.gammaOutput = true;

    monsterRenderer = new THREE.WebGLRenderer({alpha: true, antialias: true, powerPreference: "high-performance"})
    monsterRenderer.setPixelRatio(window.devicePixelRatio)
    monsterRenderer.setSize((containerHeight / 3 * 4), containerHeight);
    monsterRenderer.setClearColor(0xffffff, 0);
    monsterRenderer.domElement.id = 'monsterRenderer';
    monsterRenderer.gammaInput = true;
    monsterRenderer.gammaOutput = true;

    container.appendChild(monsterRenderer.domElement)
    container.appendChild(renderer.domElement)

}

export function clearScene() {
    if (typeof scene != "undefined") {
        while (scene.children.length > 0) {
            scene.remove(scene.children[0]);
        }
        monsterRenderer.clear()
        renderer.clear()
        let container = document.getElementById('fightContainer')
        if (document.getElementById('monsterRenderer') != null) {
            container.removeChild(document.getElementById('monsterRenderer'))
        }
        if (document.getElementById('fightRenderer') != null) {
            container.removeChild(document.getElementById('fightRenderer'))
        }
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
    mixerMonster.forEach(mixerTmp => {
        if (mixerTmp) {
            setTimeout(function () {
                mixerTmp.update(delta)
            }, Math.floor(Math.random() * 250));
        }
    })
    renderer.render(scene, camera)
    monsterRenderer.render(monsterScene, monsterCamera)
    if (context != null) {
        context.update(clock.getDelta() * 1500.0);
        context.setProjectionMatrix(camera.projectionMatrix.elements);
        context.setCameraMatrix(camera.matrixWorldInverse.elements);
        context.draw();
        renderer.resetState();
    }
}


