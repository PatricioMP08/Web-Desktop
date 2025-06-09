@extends('layouts.app')

@section('content')
<!-- Menú de inicio -->
<div id="startMenu" 
    class="fixed bottom-12 left-2 bg-white/70 backdrop-blur-md rounded-3xl shadow-2xl p-4 w-80 max-h-[70vh] overflow-auto hidden flex flex-col"
    style="border: 1px solid rgba(255,255,255,0.3); z-index: 60;">
    <!-- Logo y título -->
    <div class="flex items-center mb-4 space-x-2">
        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5f/Windows_logo_-_2021.svg" alt="Logo" class="w-8 h-8" />
        <h2 class="text-gray-800 font-bold text-lg select-none">Inicio</h2>
    </div>
    <!-- Barra de búsqueda -->
    <input id="startSearch" type="search" placeholder="Buscar..."
        class="mb-4 p-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />

    <!-- Sección Aplicaciones -->
    <div>
        <h3 class="text-gray-700 font-semibold mb-2">Aplicaciones</h3>
        <ul id="appList" class="space-y-1 max-h-40 overflow-y-auto"></ul>
    </div>
    <!-- Sección Fondos personalizados -->
    <div class="mt-4">
        <h3 class="text-gray-700 font-semibold mb-2">Fondos personalizados</h3>
        <button class="w-full text-left hover:bg-green-100 rounded px-2 py-1" onclick="toggleWallpaperSelector()">Cambiar fondo de pantalla</button>
        
    </div>
    <!-- Sección Favoritos -->
    <div class="mt-4">
        <h3 class="text-gray-700 font-semibold mb-2">Favoritos</h3>
        <ul id="favoritesList" class="space-y-1 max-h-32 overflow-y-auto">
            <li><button class="w-full text-left hover:bg-blue-100 rounded px-2 py-1" onclick="openAppByName('Correo')">Correo</button></li>
            <li><button class="w-full text-left hover:bg-blue-100 rounded px-2 py-1" onclick="openAppByName('Notas')">Notas</button></li>
        </ul>
    </div>

    <!-- Sección Documentos recientes -->
    <div class="mt-4">
        <h3 class="text-gray-700 font-semibold mb-2">Documentos recientes</h3>
        <ul id="recentDocs" class="space-y-1 max-h-32 overflow-y-auto">
            <li><button class="w-full text-left hover:bg-gray-100 rounded px-2 py-1">Proyecto1.docx</button></li>
            <li><button class="w-full text-left hover:bg-gray-100 rounded px-2 py-1">Reporte2025.pdf</button></li>
        </ul>
    </div>
</div>

<div class="h-screen w-screen relative bg-[url('/wallpaper.jpg')] bg-cover overflow-hidden select-none">

    <!-- Barra superior -->
    <div id="topBar" class="absolute top-0 w-full h-10 bg-black/60 flex items-center justify-between px-4 text-white z-50">
        <div>Bienvenido, <span id="username"></span></div>
        <div class="space-x-2">
            <button id="adminBtn" onclick="window.location.href='/admin'" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded hidden">Mantenedor</button>
            <button onclick="logout()" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Cerrar sesión</button>
        </div>
    </div>

    <!-- Área de escritorio para íconos -->
    <div id="iconGrid" class="absolute left-4 right-4 top-[40px] bottom-[64px] grid grid-cols-[repeat(auto-fill,minmax(80px,1fr))] gap-4 p-4 overflow-auto"></div>

    <!-- Contenedor para ventanas flotantes -->
    <div id="windowsContainer" class="fixed top-10 left-0 right-0 bottom-12 pointer-events-none"></div>

    <!-- Barra inferior -->
    <div id="bottomBar" class="absolute bottom-0 w-full h-11 bg-black/50 flex items-center px-2 overflow-x-auto space-x-2 z-50">
        <button id="startBtn" class="text-white hover:bg-white/10 rounded p-2" onclick="toggleStartMenu()">
             <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        </button>
        <div id="taskbarButtons" class="flex space-x-2 flex-grow overflow-x-auto"></div>
        <div class="ml-auto flex items-center space-x-2 text-white relative">
            <button id="clockBtn" class="hover:bg-white/10 rounded px-3 py-1 font-mono"></button>

            <!-- Calendario oculto -->
            <div id="calendarPopup" class="absolute bottom-12 right-0 bg-white text-black p-4 rounded-lg shadow-xl hidden z-50 w-64">
                <div id="calendarHeader" class="text-center font-semibold mb-2"></div>
                <div id="calendarGrid" class="grid grid-cols-7 gap-1 text-sm text-center"></div>
            </div>
        </div>
    </div>

</div>
<div id="wallpaperSelector"
    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-lg shadow-2xl rounded-2xl p-4 z-[999] hidden max-w-md w-full"
    style="border: 1px solid rgba(0, 0, 0, 0.1);">
    <h3 class="text-xl font-semibold mb-4 text-gray-800">Elige un fondo de pantalla</h3>
    <div id="wallpaperOptions" class="grid grid-cols-2 gap-4">
        <!-- Fondos generados dinámicamente -->
    </div>
    <div class="mt-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Sube tu propia imagen</label>
        <input type="file" accept="image/*" onchange="handleCustomWallpaperUpload(event)" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
    </div>

    <button onclick="closeWallpaperSelector()" class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Cerrar</button>
</div>

<script>
const icons = JSON.parse(localStorage.getItem('icons') || '[]');
const user = localStorage.getItem('user') || 'admin';

if (user === 'admin') document.getElementById('adminBtn').classList.remove('hidden');
document.getElementById('username').innerText = user;

const iconGrid = document.getElementById('iconGrid');
const startMenu = document.getElementById('startMenu');
const startBtn = document.getElementById('startBtn');
const startSearch = document.getElementById('startSearch');
const appList = document.getElementById('appList');
const favoritesList = document.getElementById('favoritesList');
const taskbarButtons = document.getElementById('taskbarButtons');
const windowsContainer = document.getElementById('windowsContainer');

let windows = []; // Ventanas abiertas (un solo ejemplar por icono)
let zIndexCounter = 100;

function logout() {
    localStorage.removeItem('user');
    window.location.href = '/login';
}

// Renderizar iconos escritorio
function renderIcons() {
    iconGrid.innerHTML = '';
    icons.forEach(icon => {
        if (!icon.users || icon.users.includes(user)) {
            const div = document.createElement('div');
            div.className = `
                flex flex-col items-center text-center cursor-pointer select-none
                w-[100px] p-1.5 rounded-md
                transition-transform duration-200
                hover:scale-105
                hover:bg-black/30 hover:border hover:border-white/40
                overflow-visible
            `.replace(/\s+/g, ' ').trim();
            div.style.height = '90px';
            div.onclick = () => openWindow(icon);
            div.innerHTML = `
                <img src="${icon.img || 'https://via.placeholder.com/48'}" alt="${icon.name}"
                     class="w-10 h-10 rounded shadow-sm mb-1" />
                <span class="text-white bg-black/60 px-2 py-0.5 rounded text-xs truncate block w-full">${icon.name}</span>
            `;
            iconGrid.appendChild(div);
        }
    });
}

// Renderizar lista apps menú inicio
function renderAppList() {
    appList.innerHTML = '';
    icons.forEach(icon => {
        if (!icon.users || icon.users.includes(user)) {
            const li = document.createElement('li');
            li.className = "cursor-pointer px-2 py-1 rounded hover:bg-blue-200";
            li.textContent = icon.name;
            li.onclick = () => openWindow(icon);
            appList.appendChild(li);
        }
    });
}

// Filtrar apps menú inicio
function filterApps(searchTerm) {
    const apps = appList.querySelectorAll('li');
    apps.forEach(app => {
        const text = app.innerText.toLowerCase();
        app.style.display = text.includes(searchTerm.toLowerCase()) ? '' : 'none';
    });
}

startSearch.oninput = e => filterApps(e.target.value);

// Alternar menú inicio
function toggleStartMenu() {
    if (startMenu.classList.contains('hidden')) {
        startMenu.classList.remove('hidden');
        startSearch.focus();
    } else {
        startMenu.classList.add('hidden');
        startSearch.value = '';
        filterApps('');
    }
}
startBtn.onclick = toggleStartMenu;

// Abrir ventana solo si no está abierta ya
function openWindow(icon) {
    // Verificar si ventana ya está abierta
    const existing = windows.find(w => w.name === icon.name);
    if (existing) {
        if (existing.minimized) restoreWindow(existing);
        bringToFront(existing);
        return;
    }

    // Crear ventana
    const win = document.createElement('div');
    win.className = 'floating-window fixed bg-white/20 backdrop-blur-md border border-white/20 shadow-2xl rounded-2xl flex flex-col pointer-events-auto';
    win.style.width = '800px';
    win.style.height = `calc(100vh - 112px)`; // Altura entre barras
    win.style.top = '56px';
    win.style.left = `calc(50% - 400px)`;
    win.style.zIndex = ++zIndexCounter;
    win.dataset.name = icon.name;
    win.dataset.id = Date.now();

    // Barra título
    const titleBar = document.createElement('div');
    titleBar.className = 'bg-gradient-to-r from-gray-800 via-gray-700 to-gray-900 text-white flex items-center justify-between px-4 cursor-move rounded-t-2xl h-10 shadow-inner select-none';
    titleBar.innerHTML = `
        <span class="truncate max-w-[calc(100%-120px)] font-semibold">${icon.name}</span>
        <div class="flex space-x-1">
            <button title="Minimizar" class="w-8 h-8 flex items-center justify-center hover:bg-gray-600 rounded minimize-btn">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
            </button>
            <button title="Maximizar" class="w-8 h-8 flex items-center justify-center hover:bg-gray-600 rounded maximize-btn">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="4" y="4" width="16" height="16" />
                </svg>
            </button>
            <button title="Cerrar" class="w-8 h-8 flex items-center justify-center hover:bg-red-700 rounded close-btn">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
    `;

    // Contenido iframe
    const iframe = document.createElement('iframe');
    iframe.src = icon.url;
    iframe.className = 'flex-grow w-full border-0 rounded-b-2xl bg-white/80 overflow-hidden';
    iframe.style.height = '100%';

    win.appendChild(titleBar);
    win.appendChild(iframe);
    windowsContainer.appendChild(win);

    // Guardar datos ventana
    const winData = {
        id: win.dataset.id,
        name: icon.name,
        element: win,
        icon: icon.img || 'https://via.placeholder.com/24',
        minimized: false,
        maximized: false,
        normalPos: { top: win.style.top, left: win.style.left, width: win.style.width, height: win.style.height }
    };
    windows.push(winData);

    // Agregar botón en barra tareas con solo icono
    addTaskbarButton(winData);

    // Eventos botones
    const btnMinimize = titleBar.querySelector('.minimize-btn');
    const btnMaximize = titleBar.querySelector('.maximize-btn');
    const btnClose = titleBar.querySelector('.close-btn');

    btnMinimize.onclick = () => toggleMinimize(winData);
    btnMaximize.onclick = () => toggleMaximize(winData);
    btnClose.onclick = () => closeWindow(winData);

    // Al hacer click, traer ventana al frente
    win.addEventListener('mousedown', () => {
        bringToFront(winData);
    });

    // Hacer ventana draggable por barra título
    makeDraggable(win, titleBar);

    // Cerrar menú inicio si abierto
    if (!startMenu.classList.contains('hidden')) toggleStartMenu();

    bringToFront(winData);
}

// Añadir botón icono en barra tareas
function addTaskbarButton(winData) {
    const btn = document.createElement('button');
    btn.className = 'taskbar-btn bg-white/20 hover:bg-white/40 rounded p-1 flex items-center justify-center';
    btn.title = winData.name;
    btn.dataset.id = winData.id;

    const img = document.createElement('img');
    img.src = winData.icon;
    img.alt = winData.name;
    img.className = 'w-6 h-6 select-none pointer-events-none rounded';

    btn.appendChild(img);

    btn.onclick = () => {
        if (winData.minimized) {
            restoreWindow(winData);
        } else {
            minimizeWindow(winData);
        }
    };

    winData.taskbarBtn = btn;
    taskbarButtons.appendChild(btn);
}

// Minimizar ventana
function minimizeWindow(winData) {
    winData.element.style.display = 'none';
    winData.minimized = true;
    winData.taskbarBtn.classList.add('opacity-50');
}

// Restaurar ventana
function restoreWindow(winData) {
    winData.element.style.display = 'flex';
    winData.minimized = false;
    winData.taskbarBtn.classList.remove('opacity-50');
    bringToFront(winData);
}

// Alternar minimizar
function toggleMinimize(winData) {
    if (winData.minimized) {
        restoreWindow(winData);
    } else {
        minimizeWindow(winData);
    }
}

// Alternar maximizar/restaurar
function toggleMaximize(winData) {
    if (winData.maximized) {
        // Restaurar tamaño normal
        winData.element.style.top = winData.normalPos.top;
        winData.element.style.left = winData.normalPos.left;
        winData.element.style.width = winData.normalPos.width;
        winData.element.style.height = winData.normalPos.height;
        winData.maximized = false;
        updateMaximizeIcon(winData.element.querySelector('.maximize-btn'), false);
    } else {
        // Guardar posición actual para restaurar después
        winData.normalPos = {
            top: winData.element.style.top,
            left: winData.element.style.left,
            width: winData.element.style.width,
            height: winData.element.style.height
        };
        // Maximizar ventana exactamente entre barras
        winData.element.style.top = '56px';
        winData.element.style.left = '0px';
        winData.element.style.width = '100vw';
        winData.element.style.height = 'calc(100vh - 112px)';
        winData.maximized = true;
        updateMaximizeIcon(winData.element.querySelector('.maximize-btn'), true);
        restoreWindow(winData);
        bringToFront(winData);
    }
}

// Actualizar ícono botón maximizar
function updateMaximizeIcon(btn, maximized) {
    if (maximized) {
        btn.title = 'Restaurar';
        btn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8 3H5a2 2 0 0 0-2 2v3"/>
                <path d="M16 21h3a2 2 0 0 0 2-2v-3"/>
                <rect x="8" y="8" width="8" height="8" />
            </svg>
        `;
    } else {
        btn.title = 'Maximizar';
        btn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="4" y="4" width="16" height="16" />
            </svg>
        `;
    }
}

// Cerrar ventana
function closeWindow(winData) {
    windowsContainer.removeChild(winData.element);
    if (winData.taskbarBtn) taskbarButtons.removeChild(winData.taskbarBtn);
    windows = windows.filter(w => w.id !== winData.id);
}

// Llevar ventana al frente (mayor z-index)
function bringToFront(winData) {
    zIndexCounter++;
    winData.element.style.zIndex = zIndexCounter;
}

// Llevar ventana al frente dado elemento
function bringToFrontByElement(element) {
    const winData = windows.find(w => w.element === element);
    if (winData) bringToFront(winData);
}

// Hacer ventana draggable (solo barra título)
function makeDraggable(element, handle) {
    let posX = 0, posY = 0, mouseX = 0, mouseY = 0;
    handle.style.cursor = 'move';

    handle.onmousedown = dragMouseDown;

    function dragMouseDown(e) {
        e.preventDefault();
        mouseX = e.clientX;
        mouseY = e.clientY;
        document.onmouseup = closeDragElement;
        document.onmousemove = elementDrag;
        bringToFrontByElement(element);
    }

    function elementDrag(e) {
        e.preventDefault();
        posX = mouseX - e.clientX;
        posY = mouseY - e.clientY;
        mouseX = e.clientX;
        mouseY = e.clientY;
        const topRaw = (element.offsetTop - posY);
        const leftRaw = (element.offsetLeft - posX);

        const barraSuperiorAltura = 56;
        const barraInferiorAltura = 56;

        const maxTop = window.innerHeight - barraInferiorAltura - element.offsetHeight;
        const minTop = barraSuperiorAltura;
        const maxLeft = window.innerWidth - element.offsetWidth;
        const minLeft = 0;

        element.style.top = Math.min(Math.max(minTop, topRaw), maxTop) + "px";
        element.style.left = Math.min(Math.max(minLeft, leftRaw), maxLeft) + "px";
    }

    function closeDragElement() {
        document.onmouseup = null;
        document.onmousemove = null;
    }
}

// Actualizar hora reloj
function updateClock() {
    const clockBtn = document.getElementById('clockBtn');
    const now = new Date();
    clockBtn.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}
setInterval(updateClock, 1000);
updateClock();

// Inicializar UI
renderIcons();
renderAppList();
document.addEventListener('click', function (event) {
    const startMenu = document.getElementById('startMenu');
    const startBtn = document.getElementById('startBtn');

    // Si el menú no está visible, no hacemos nada
    if (startMenu.classList.contains('hidden')) return;

    // Si el clic fue dentro del menú o del botón de inicio, no lo cerramos
    if (startMenu.contains(event.target) || startBtn.contains(event.target)) return;

    // Si el clic fue fuera, lo cerramos
    startMenu.classList.add('hidden');
    document.getElementById('startSearch').value = '';
    const apps = document.querySelectorAll('#appList li');
    apps.forEach(app => app.style.display = '');
});

const defaultWallpapers = [
    'https://wallpaperaccess.com/full/317501.jpg',
    'https://images.unsplash.com/photo-1506744038136-46273834b3fb',
    'https://images.unsplash.com/photo-1517816743773-6e0fd518b4a6',
    'https://images.unsplash.com/photo-1506765515384-028b60a970df',
];

function toggleWallpaperSelector() {
    const selector = document.getElementById('wallpaperSelector');
    renderWallpaperOptions();
    selector.classList.toggle('hidden');
}

function closeWallpaperSelector() {
    document.getElementById('wallpaperSelector').classList.add('hidden');
}

function renderWallpaperOptions() {
    const container = document.getElementById('wallpaperOptions');
    container.innerHTML = '';

    const wallpapers = JSON.parse(localStorage.getItem('wallpapers')) || defaultWallpapers;

    wallpapers.forEach((url, index) => {
        const img = document.createElement('img');
        img.src = url;
        img.className = "w-full h-28 object-cover rounded-xl cursor-pointer hover:ring-4 hover:ring-blue-500";
        img.onclick = () => setWallpaper(url);
        container.appendChild(img);
    });
}

function setWallpaper(url) {
    document.body.style.backgroundImage = `url(${url})`;
    localStorage.setItem(`wallpaper_${user}`, url); // guardar por usuario
    closeWallpaperSelector();
}


function loadSavedWallpaper() {
    const saved = localStorage.getItem(`wallpaper_${user}`);
    if (saved) {
        document.body.style.backgroundImage = `url(${saved})`;
    }
}


window.onload = loadSavedWallpaper;
function handleCustomWallpaperUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const base64Image = e.target.result;
        saveCustomWallpaper(base64Image);
        setWallpaper(base64Image); // Aplica de inmediato
    };
    reader.readAsDataURL(file);
}

function saveCustomWallpaper(base64Image) {
    let wallpapers = JSON.parse(localStorage.getItem('wallpapers')) || defaultWallpapers;

    // Evitar duplicados
    if (!wallpapers.includes(base64Image)) {
        wallpapers.unshift(base64Image); // Añadir al principio
        // Limitar a los últimos 10 fondos para no saturar localStorage
        wallpapers = wallpapers.slice(0, 10);
        localStorage.setItem('wallpapers', JSON.stringify(wallpapers));
    }
}
</script>

<style>
  /* Scroll para iconGrid y otras mejoras */
  #iconGrid::-webkit-scrollbar {
    width: 8px;
  }
  #iconGrid::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.3);
    border-radius: 4px;
  }

  /* Estilos para ventanas */
  .floating-window {
    user-select: none;
  }

  /* Scroll para taskbar si hay muchas ventanas */
  #taskbarButtons::-webkit-scrollbar {
    height: 6px;
  }
  #taskbarButtons::-webkit-scrollbar-thumb {
    background-color: rgba(255,255,255,0.4);
    border-radius: 3px;
  }
</style>

@endsection
