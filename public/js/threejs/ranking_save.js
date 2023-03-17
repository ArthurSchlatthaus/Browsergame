const THREE = window.THREE;
const FBXLoader = window.FBXLoader;

let camera, scene, renderer
const clock = new THREE.Clock()


let camPosX = 0
let camPosY = 50
let camPosZ = 200

export function setCamPos(x, y, z) {
    camPosX = x
    camPosY = y
    camPosZ = z
}

async function loadModel(scene, camera, modelPath_n, model_n, weaponPath_n, weapon_n) {
    return new Promise((resolve) => {
        const loader = new FBXLoader()
        loader.setResourcePath('/models' + modelPath_n)
        loader.setPath('/models' + modelPath_n)
        loader.load(model_n, function (object) {
            rotateObject(object, -90, 0, 0)
            object.traverse(function (child) {
                if (parseInt(weapon_n) >= 2000 && parseInt(weapon_n) < 3000) { // bow
                    if (child.name === "Bip01_R_Finger1Nub" && weapon_n !== 'none') {
                        loadWeapon(child, false, weaponPath_n, weapon_n, modelPath_n)
                    }
                } else {
                    if (child.name === "Bip01_R_Finger1Nub" && weapon_n !== 'none') {
                        loadWeapon(child, false, weaponPath_n, weapon_n, modelPath_n)
                    }
                }
            })
            scene.add(object)
            resolve([scene, camera, object])
        })
    })
}

function loadWeapon(child, hasNoWeapon, weaponPath_n, weapon_n, modelPath_n) {
    let weaponLoader = new FBXLoader()
    weaponLoader.setResourcePath('/models' + weaponPath_n)
    weaponLoader.setPath('/models' + weaponPath_n)
    if (!hasNoWeapon) {
        weaponLoader.load(weapon_n, function (weaponObj) {
            if (modelPath_n.includes('warrior')) {
                weapon_n.substring(weapon_n.length - 4)
                rotateObject(weaponObj, 90, 0, 0)
                if (parseInt(weapon_n) >= 3000 && parseInt(weapon_n) < 4000) {
                    weaponObj.translateY(-60);
                }
            } else {
                rotateObject(weaponObj, 90, 0, 180)
            }
            child.add(weaponObj)
        })
    }
}

export async function init(modelPath_n, model_n, weaponPath_n, weapon_n, animationPath_n, animation_n, playerId) {
    console.warn = () => {
    }

    let container = document.getElementById('npcContainer' + playerId)

    camera = new THREE.PerspectiveCamera(30, 4.0 / 3.0, 1, 1000)
    camera.position.set(camPosX, camPosY, camPosZ)

    scene = new THREE.Scene()
    scene.background = null;

    const hemiLight = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight.position.set(100, 200, 100)
    const hemiLight2 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight2.position.set(200, 100, 200)
    const hemiLight3 = new THREE.HemisphereLight(0xffffbb, 0x080820, 1)
    hemiLight3.position.set(-200, -100, 200)
    const light = new THREE.AmbientLight(0x404040)
    scene.add(light, hemiLight, hemiLight2)

    let returnArray = await loadModel(scene, camera, modelPath_n, model_n, weaponPath_n, weapon_n)
    scene = returnArray[0]
    camera = returnArray[1]
    let object = returnArray[2]
    renderer = new THREE.WebGLRenderer({alpha: true, antialias: true})
    renderer.setPixelRatio(window.devicePixelRatio)
    renderer.setSize((container.clientHeight / 3 * 4), container.clientHeight);
    renderer.setClearColor(0xffffff, 0);
    renderer.gammaInput = true;
    renderer.gammaOutput = true;
    container.appendChild(renderer.domElement)

    let player = {
        camera: camera,
        scene: scene,
        mixer: null,
        renderer: renderer,
        object: object,
        animationPath: animationPath_n,
        animation: animation_n,
        action: null,
        initAnimate: function (rendererTmp, sceneTmp, cameraTmp, mixerTmp) {
            player.rendererTmp = rendererTmp
            player.sceneTmp = sceneTmp
            player.cameraTmp = cameraTmp
            player.mixerTmp = mixerTmp
        },
        loadAnimation: async function (object, animationPath, animation) {
            return new Promise((resolve) => {
                let animationLoader = new FBXLoader()
                animationLoader.setResourcePath('/models' + animationPath)
                animationLoader.setPath('/models' + animationPath)
                animationLoader.load(animation, async (animationObj) => {
                    let mixer = new THREE.AnimationMixer(object)
                    const action = mixer.clipAction(animationObj.animations[0])
                    action.play()
                    resolve([mixer, action])
                })
            })
        },
        loopAnimate: function () {
            requestAnimationFrame(player.loopAnimate)
            const delta = clock.getDelta()
            if (player.mixerTmp && typeof player.mixerTmp._root !== 'undefined') {
                player.mixerTmp.update(delta)
            }
            if (typeof player.rendererTmp != 'undefined') {
                player.rendererTmp.render(player.sceneTmp, player.cameraTmp)
            }
        }
    };
    return player
}

function rotateObject(object, degreeX = 0, degreeY = 0, degreeZ = 0) {
    object.rotateX(THREE.Math.degToRad(degreeX))
    object.rotateY(THREE.Math.degToRad(degreeY))
    object.rotateZ(THREE.Math.degToRad(degreeZ))
}
