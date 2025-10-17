<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>吧唧制作与展示</title>
    <style>
        :root {
            color-scheme: light dark;
            --accent: #ff6f61;
            --panel-bg: rgba(255, 255, 255, 0.8);
            --panel-bg-dark: rgba(25, 25, 25, 0.85);
            --text-dark: #222;
            --text-light: #f3f3f3;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: "Segoe UI", "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", sans-serif;
            background: linear-gradient(180deg, #dceeff 0%, #fefefe 60%);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            padding: 1rem 1.5rem;
            background: rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(6px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        header h1 {
            font-size: 1.5rem;
            margin: 0;
        }
        header .status {
            font-size: 0.9rem;
            opacity: 0.75;
        }
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 0 1rem 1.5rem;
        }
        .workspace {
            flex: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 1rem;
            position: relative;
        }
        .canvas-wrapper {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.08);
            min-height: 50vh;
        }
        #three-canvas {
            width: 100%;
            height: 100%;
            display: block;
            background: radial-gradient(circle at top, rgba(255, 255, 255, 0.7), rgba(200, 220, 240, 0.9));
        }
        .panel {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: min(320px, 90vw);
            max-height: calc(100% - 2rem);
            overflow-y: auto;
            padding: 1rem 1.25rem;
            border-radius: 16px;
            background: var(--panel-bg);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .panel.hidden {
            pointer-events: none;
            opacity: 0;
            transform: translateY(-8px);
        }
        .panel h2 {
            margin-top: 0;
            font-size: 1.1rem;
        }
        .panel p {
            font-size: 0.9rem;
            line-height: 1.5;
        }
        .panel label {
            display: block;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        .panel input[type="range"],
        .panel input[type="file"],
        .panel button,
        .panel select {
            width: 100%;
        }
        .panel button {
            border: none;
            border-radius: 999px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #fff;
            background: var(--accent);
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }
        .panel button.secondary {
            background: rgba(0, 0, 0, 0.1);
            color: inherit;
        }
        .panel button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .panel button:not(:disabled):hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(255, 111, 97, 0.35);
        }
        .panel .slider-group {
            margin: 1rem 0;
        }
        .panel .slider-group input[type="range"] {
            margin-bottom: 0.25rem;
        }
        .panel .slider-value {
            font-weight: 600;
            margin-left: 0.25rem;
        }
        .badge-list {
            list-style: none;
            padding: 0;
            margin: 0.5rem 0 0;
            display: grid;
            gap: 0.5rem;
        }
        .badge-list li {
            padding: 0.5rem 0.75rem;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.06);
            font-size: 0.85rem;
        }
        .crop-area {
            margin-top: 0.75rem;
            border-radius: 14px;
            background: rgba(0, 0, 0, 0.05);
            padding: 0.75rem;
        }
        .crop-area canvas {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 12px;
            background: #f4f4f4;
        }
        .crop-controls {
            display: grid;
            gap: 0.75rem;
            margin-top: 0.75rem;
        }
        .crop-controls.hidden {
            display: none;
        }
        .action-group {
            display: grid;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .tips {
            font-size: 0.8rem;
            opacity: 0.7;
            margin-top: 0.5rem;
            line-height: 1.4;
        }
        @media (max-width: 960px) {
            .panel {
                position: static;
                width: 100%;
                max-height: none;
                transform: none !important;
                opacity: 1 !important;
                margin-top: 1rem;
            }
            .canvas-wrapper {
                min-height: 45vh;
            }
            .workspace {
                display: flex;
                flex-direction: column;
            }
        }
        @media (prefers-color-scheme: dark) {
            body {
                background: radial-gradient(circle at top, #1f2836, #0d1117 70%);
                color: var(--text-light);
            }
            header {
                background: rgba(255, 255, 255, 0.05);
            }
            .panel {
                background: var(--panel-bg-dark);
                color: var(--text-light);
            }
            .badge-list li {
                background: rgba(255, 255, 255, 0.06);
            }
            .crop-area {
                background: rgba(255, 255, 255, 0.04);
            }
            .panel button.secondary {
                background: rgba(255, 255, 255, 0.12);
                color: var(--text-light);
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>三维吧唧制作演示</h1>
        <span class="status">场景 1：展示 &amp; 布局 · 场景 2：贴图裁剪</span>
    </header>
    <main>
        <div class="workspace">
            <div class="canvas-wrapper">
                <canvas id="three-canvas"></canvas>
                <section class="panel" id="scene1-panel">
                    <h2>场景一 · 平地展示</h2>
                    <p>查看所有已经摆放好的吧唧，点击下方按钮进入制作场景。</p>
                    <button id="create-badge">创建吧唧</button>
                    <div class="placement" id="placement-section">
                        <div class="slider-group">
                            <label for="posX">X 坐标<span class="slider-value" id="posXValue">0.00</span></label>
                            <input type="range" id="posX" min="-5" max="5" step="0.01" disabled />
                        </div>
                        <div class="slider-group">
                            <label for="posZ">Z 坐标<span class="slider-value" id="posZValue">0.00</span></label>
                            <input type="range" id="posZ" min="-5" max="5" step="0.01" disabled />
                        </div>
                        <button id="confirm-placement" disabled>保存吧唧到场景</button>
                        <p class="tips">拖动滑块可以微调刚刚制作的吧唧位置，点击保存后即可持久化到服务端。</p>
                        <p class="tips" id="scene1-status"></p>
                    </div>
                    <h3>现有吧唧</h3>
                    <ul class="badge-list" id="badge-list">
                        <li>加载中...</li>
                    </ul>
                </section>
                <section class="panel hidden" id="scene2-panel">
                    <h2>场景二 · 吧唧贴图制作</h2>
                    <p>上传图片并裁剪后实时覆盖到模型贴图上，满意后点击确定返回场景一。</p>
                    <label class="file-picker">
                        <span class="panel button secondary" id="upload-proxy">上传贴图文件</span>
                        <input type="file" id="texture-upload" accept="image/*" style="display:none" />
                    </label>
                    <div class="crop-area">
                        <canvas id="crop-canvas" width="512" height="512"></canvas>
                        <div class="crop-controls hidden" id="crop-controls">
                            <div>
                                <label for="crop-scale">缩放</label>
                                <input type="range" id="crop-scale" min="0.5" max="2.5" step="0.01" value="1" />
                            </div>
                            <div>
                                <label for="crop-offset-x">水平偏移</label>
                                <input type="range" id="crop-offset-x" min="-256" max="256" step="1" value="0" />
                            </div>
                            <div>
                                <label for="crop-offset-y">垂直偏移</label>
                                <input type="range" id="crop-offset-y" min="-256" max="256" step="1" value="0" />
                            </div>
                        </div>
                    </div>
                    <div class="action-group">
                        <button class="secondary" id="cancel-scene2">返回场景一</button>
                        <button id="confirm-texture" disabled>确定贴图</button>
                    </div>
                    <p class="tips" id="scene2-status">请先上传图片文件进行裁剪。</p>
                </section>
            </div>
        </div>
    </main>
    <script type="module">
        import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.156/build/three.module.js';
        import { OrbitControls } from 'https://cdn.jsdelivr.net/npm/three@0.156/examples/jsm/controls/OrbitControls.js';
        import { GLTFLoader } from 'https://cdn.jsdelivr.net/npm/three@0.156/examples/jsm/loaders/GLTFLoader.js';

        const canvas = document.getElementById('three-canvas');
        const renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
        renderer.setPixelRatio(window.devicePixelRatio || 1);
        const initialWidth = canvas.clientWidth || canvas.parentElement?.clientWidth || window.innerWidth;
        const initialHeight = canvas.clientHeight || canvas.parentElement?.clientHeight || window.innerHeight * 0.6;
        renderer.setSize(initialWidth, initialHeight, false);
        renderer.outputColorSpace = THREE.SRGBColorSpace;
        renderer.toneMapping = THREE.ACESFilmicToneMapping;

        const scene1 = new THREE.Scene();
        scene1.background = new THREE.Color('#b8d8ff');
        const camera1 = new THREE.PerspectiveCamera(60, canvas.clientWidth / canvas.clientHeight, 0.1, 100);
        camera1.position.set(4, 4, 4);

        const scene2 = new THREE.Scene();
        scene2.background = new THREE.Color('#1d1f2b');
        const camera2 = new THREE.PerspectiveCamera(60, canvas.clientWidth / canvas.clientHeight, 0.1, 100);
        camera2.position.set(1.4, 1.2, 1.4);

        const controls1 = new OrbitControls(camera1, renderer.domElement);
        controls1.target.set(0, 0.2, 0);
        controls1.enableDamping = true;

        const controls2 = new OrbitControls(camera2, renderer.domElement);
        controls2.target.set(0, 0.05, 0);
        controls2.enableDamping = true;

        const ambient1 = new THREE.AmbientLight(0xffffff, 0.6);
        const dir1 = new THREE.DirectionalLight(0xffffff, 0.7);
        dir1.position.set(4, 6, 2);
        scene1.add(ambient1, dir1);

        const ambient2 = new THREE.AmbientLight(0xffffff, 0.8);
        const dir2 = new THREE.DirectionalLight(0xffffff, 0.8);
        dir2.position.set(2, 3, 2);
        scene2.add(ambient2, dir2);

        const groundGeometry = new THREE.PlaneGeometry(20, 20);
        const groundMaterial = new THREE.MeshStandardMaterial({ color: 0xf6f6f6, roughness: 0.85, metalness: 0.1 });
        const ground = new THREE.Mesh(groundGeometry, groundMaterial);
        ground.rotation.x = -Math.PI / 2;
        ground.receiveShadow = true;
        scene1.add(ground);

        const gridHelper = new THREE.GridHelper(20, 20, 0x4a90e2, 0xdddddd);
        gridHelper.position.y = 0.01;
        scene1.add(gridHelper);

        const loader = new GLTFLoader();
        const textureLoader = new THREE.TextureLoader();
        const existingBadgeInstances = [];
        let pendingBadge = null;
        let activeScene = 1;
        let scene2Model = null;
        let baseGltf = null;
        let baseGltfPromise = null;

        const scene1Panel = document.getElementById('scene1-panel');
        const scene2Panel = document.getElementById('scene2-panel');
        const createBadgeBtn = document.getElementById('create-badge');
        const confirmPlacementBtn = document.getElementById('confirm-placement');
        const badgeList = document.getElementById('badge-list');
        const posXInput = document.getElementById('posX');
        const posZInput = document.getElementById('posZ');
        const posXValue = document.getElementById('posXValue');
        const posZValue = document.getElementById('posZValue');
        const scene1Status = document.getElementById('scene1-status');

        const uploadProxy = document.getElementById('upload-proxy');
        const uploadInput = document.getElementById('texture-upload');
        const cropCanvas = document.getElementById('crop-canvas');
        const cropCtx = cropCanvas.getContext('2d');
        const cropControls = document.getElementById('crop-controls');
        const cropScale = document.getElementById('crop-scale');
        const cropOffsetX = document.getElementById('crop-offset-x');
        const cropOffsetY = document.getElementById('crop-offset-y');
        const scene2Status = document.getElementById('scene2-status');
        const confirmTextureBtn = document.getElementById('confirm-texture');
        const cancelScene2Btn = document.getElementById('cancel-scene2');

        let cropImage = null;
        let currentTextureDataUrl = null;

        function resizeRenderer() {
            const width = canvas.clientWidth || canvas.parentElement?.clientWidth || window.innerWidth;
            const height = canvas.clientHeight || canvas.parentElement?.clientHeight || window.innerHeight * 0.6;
            if (!width || !height) {
                return;
            }
            renderer.setSize(width, height, false);
            camera1.aspect = width / height;
            camera1.updateProjectionMatrix();
            camera2.aspect = width / height;
            camera2.updateProjectionMatrix();
        }

        window.addEventListener('resize', resizeRenderer);
        resizeRenderer();

        function render() {
            requestAnimationFrame(render);
            if (activeScene === 1) {
                controls1.update();
                renderer.render(scene1, camera1);
            } else {
                controls2.update();
                renderer.render(scene2, camera2);
            }
        }
        render();

        function showScene(index) {
            activeScene = index;
            if (index === 1) {
                scene1Panel.classList.remove('hidden');
                scene2Panel.classList.add('hidden');
                scene2Status.textContent = '请先上传图片文件进行裁剪。';
            } else {
                scene1Panel.classList.add('hidden');
                scene2Panel.classList.remove('hidden');
            }
        }

        async function loadTexture(source) {
            return await new Promise((resolve, reject) => {
                textureLoader.load(source, (texture) => {
                    texture.flipY = false;
                    texture.colorSpace = THREE.SRGBColorSpace;
                    const maxAnisotropy = typeof renderer.capabilities.getMaxAnisotropy === 'function'
                        ? renderer.capabilities.getMaxAnisotropy()
                        : 1;
                    texture.anisotropy = Math.min(8, maxAnisotropy || 1);
                    resolve(texture);
                }, undefined, (err) => reject(err));
            });
        }

        async function getBaseGltf() {
            if (baseGltf) {
                return baseGltf;
            }
            if (!baseGltfPromise) {
                baseGltfPromise = new Promise((resolve, reject) => {
                    loader.load('anime_badge_on_backpack.glb', (gltf) => {
                        baseGltf = gltf;
                        resolve(baseGltf);
                    }, undefined, reject);
                });
            }
            return baseGltfPromise;
        }

        async function buildBadgeMesh(textureSource) {
            const [gltf, texture] = await Promise.all([
                getBaseGltf(),
                loadTexture(textureSource)
            ]);
            const group = gltf.scene.clone(true);
            group.traverse((child) => {
                if (child.isMesh) {
                    child.material = new THREE.MeshStandardMaterial({
                        map: texture,
                        metalness: 0.2,
                        roughness: 0.8,
                        transparent: false
                    });
                    child.material.map.needsUpdate = true;
                }
            });
            return group;
        }

        function clearExistingBadges() {
            while (existingBadgeInstances.length) {
                const mesh = existingBadgeInstances.pop();
                scene1.remove(mesh);
            }
        }

        async function loadExistingBadges() {
            badgeList.innerHTML = '<li>加载中...</li>';
            clearExistingBadges();
            try {
                const response = await fetch('api/get_badges.php');
                const badges = await response.json();
                if (!Array.isArray(badges) || badges.length === 0) {
                    badgeList.innerHTML = '<li>还没有吧唧，快来创建一个吧！</li>';
                    return;
                }
                badgeList.innerHTML = '';
                for (const badge of badges) {
                    const listItem = document.createElement('li');
                    listItem.textContent = `贴图：${badge.texturePath} · 位置：(${Number(badge.position?.x || 0).toFixed(2)}, ${Number(badge.position?.z || 0).toFixed(2)})`;
                    badgeList.appendChild(listItem);
                    try {
                        const mesh = await buildBadgeMesh(badge.texturePath);
                        mesh.position.set(Number(badge.position?.x || 0), 0.02, Number(badge.position?.z || 0));
                        scene1.add(mesh);
                        existingBadgeInstances.push(mesh);
                    } catch (err) {
                        console.error('加载贴图失败', err);
                    }
                }
            } catch (error) {
                console.error(error);
                badgeList.innerHTML = '<li>无法加载现有数据，请稍后重试。</li>';
            }
        }

        function resetPlacementControls() {
            posXInput.value = 0;
            posZInput.value = 0;
            posXValue.textContent = '0.00';
            posZValue.textContent = '0.00';
            posXInput.disabled = true;
            posZInput.disabled = true;
            confirmPlacementBtn.disabled = true;
            scene1Status.textContent = '';
        }

        function removePendingBadge() {
            if (pendingBadge?.group) {
                scene1.remove(pendingBadge.group);
            }
            pendingBadge = null;
            resetPlacementControls();
        }

        async function preparePendingBadge(texturePath, previewSource) {
            removePendingBadge();
            pendingBadge = {
                texturePath,
                previewSource,
                group: null
            };
            try {
                const mesh = await buildBadgeMesh(previewSource || texturePath);
                mesh.position.set(0, 0.03, 0);
                scene1.add(mesh);
                pendingBadge.group = mesh;
                posXInput.disabled = false;
                posZInput.disabled = false;
                confirmPlacementBtn.disabled = false;
                updatePendingBadgePosition();
                scene1Status.textContent = '使用滑块调整位置后点击保存完成摆放。';
            } catch (error) {
                console.error('预览模型构建失败', error);
                scene2Status.textContent = '生成预览失败，请重新上传。';
                pendingBadge = null;
            }
        }

        function updatePendingBadgePosition() {
            const x = Number(posXInput.value || 0);
            const z = Number(posZInput.value || 0);
            posXValue.textContent = x.toFixed(2);
            posZValue.textContent = z.toFixed(2);
            if (pendingBadge?.group) {
                pendingBadge.group.position.set(x, 0.03, z);
            }
        }

        posXInput.addEventListener('input', updatePendingBadgePosition);
        posZInput.addEventListener('input', updatePendingBadgePosition);

        async function applyTextureToScene2(source, enableConfirm = true) {
            if (!scene2Model) {
                return;
            }
            try {
                const texture = await loadTexture(source);
                scene2Model.traverse((child) => {
                    if (child.isMesh) {
                        child.material = new THREE.MeshStandardMaterial({
                            map: texture,
                            metalness: 0.2,
                            roughness: 0.75
                        });
                        child.material.map.needsUpdate = true;
                    }
                });
                currentTextureDataUrl = source;
                if (enableConfirm) {
                    confirmTextureBtn.disabled = false;
                    scene2Status.textContent = '贴图已应用，如需调整请移动滑块后再次确认。';
                }
            } catch (error) {
                console.error('应用贴图失败', error);
                scene2Status.textContent = '贴图应用失败，请重试。';
            }
        }

        function redrawCropCanvas() {
            if (!cropImage) {
                cropCtx.fillStyle = '#f0f0f0';
                cropCtx.fillRect(0, 0, cropCanvas.width, cropCanvas.height);
                return;
            }
            cropCtx.clearRect(0, 0, cropCanvas.width, cropCanvas.height);
            cropCtx.fillStyle = '#fdfdfd';
            cropCtx.fillRect(0, 0, cropCanvas.width, cropCanvas.height);
            const scale = Number(cropScale.value || 1);
            const offsetX = Number(cropOffsetX.value || 0);
            const offsetY = Number(cropOffsetY.value || 0);
            const drawWidth = cropImage.width * scale;
            const drawHeight = cropImage.height * scale;
            const dx = (cropCanvas.width - drawWidth) / 2 + offsetX;
            const dy = (cropCanvas.height - drawHeight) / 2 + offsetY;
            cropCtx.drawImage(cropImage, dx, dy, drawWidth, drawHeight);
            currentTextureDataUrl = cropCanvas.toDataURL('image/png');
            applyTextureToScene2(currentTextureDataUrl);
        }

        function resetCropper() {
            cropImage = null;
            cropControls.classList.add('hidden');
            cropCtx.fillStyle = '#f0f0f0';
            cropCtx.fillRect(0, 0, cropCanvas.width, cropCanvas.height);
            currentTextureDataUrl = null;
            confirmTextureBtn.disabled = true;
            scene2Status.textContent = '请先上传图片文件进行裁剪。';
        }

        uploadProxy.addEventListener('click', () => uploadInput.click());
        uploadInput.addEventListener('change', (event) => {
            const file = event.target.files?.[0];
            if (!file) {
                return;
            }
            const reader = new FileReader();
            reader.onload = (e) => {
                cropImage = new Image();
                cropImage.onload = () => {
                    cropScale.value = '1';
                    cropOffsetX.value = '0';
                    cropOffsetY.value = '0';
                    cropControls.classList.remove('hidden');
                    redrawCropCanvas();
                    scene2Status.textContent = '调整缩放与偏移，模型会实时更新贴图。';
                };
                cropImage.src = e.target?.result;
            };
            reader.readAsDataURL(file);
        });

        [cropScale, cropOffsetX, cropOffsetY].forEach((input) => {
            input.addEventListener('input', () => {
                if (cropImage) {
                    redrawCropCanvas();
                }
            });
        });

        confirmTextureBtn.addEventListener('click', async () => {
            if (!currentTextureDataUrl) {
                scene2Status.textContent = '请先上传并裁剪贴图。';
                return;
            }
            scene2Status.textContent = '正在上传贴图，请稍候...';
            confirmTextureBtn.disabled = true;
            try {
                const response = await fetch('api/upload_texture.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ imageData: currentTextureDataUrl })
                });
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result.error || '上传失败');
                }
                scene2Status.textContent = '贴图上传成功，返回场景一放置吧唧。';
                await preparePendingBadge(result.path, currentTextureDataUrl);
                showScene(1);
            } catch (error) {
                console.error(error);
                scene2Status.textContent = '贴图上传失败：' + error.message;
                confirmTextureBtn.disabled = false;
            }
        });

        cancelScene2Btn.addEventListener('click', () => {
            resetCropper();
            showScene(1);
        });

        createBadgeBtn.addEventListener('click', () => {
            resetCropper();
            showScene(2);
        });

        confirmPlacementBtn.addEventListener('click', async () => {
            if (!pendingBadge?.group || !pendingBadge.texturePath) {
                return;
            }
            confirmPlacementBtn.disabled = true;
            scene1Status.textContent = '正在保存吧唧...';
            try {
                const payload = {
                    texturePath: pendingBadge.texturePath,
                    position: { x: Number(posXInput.value || 0), z: Number(posZInput.value || 0) }
                };
                const response = await fetch('api/save_badge.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const result = await response.json();
                if (!response.ok || !result.success) {
                    throw new Error(result.error || '保存失败');
                }
                scene1Status.textContent = '保存成功，吧唧已加入场景。';
                removePendingBadge();
                await loadExistingBadges();
            } catch (error) {
                console.error(error);
                confirmPlacementBtn.disabled = false;
                scene1Status.textContent = '保存失败：' + error.message;
            }
        });

        async function initScene2Model() {
            return await new Promise((resolve, reject) => {
                getBaseGltf().then((gltf) => {
                    scene2Model = gltf.scene.clone(true);
                    scene2Model.position.set(0, 0, 0);
                    scene2Model.scale.set(1.6, 1.6, 1.6);
                    scene2.add(scene2Model);
                    resolve();
                }).catch(reject);
            });
        }

        async function applyDefaultTexture() {
            try {
                await applyTextureToScene2('assets/textures/default_badge.png', false);
                scene2Status.textContent = '默认贴图已加载，上传图片开始制作。';
            } catch (error) {
                console.error('默认贴图加载失败', error);
            }
        }

        initScene2Model().then(applyDefaultTexture);
        loadExistingBadges();
        resetPlacementControls();
    </script>
</body>
</html>
