@extends('layouts.app')

@section('content')
<div class="p-6 max-w-6xl mx-auto">
<div class="h-10 bg-black/70 text-white flex justify-between items-center px-4 z-50">
    <span class="font-semibold">Panel de Administración</span>
    <div class="space-x-2">
        <button onclick="window.location.href='/desktop'" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Volver al Escritorio</button>
        <button onclick="logout()" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Cerrar sesión</button>
    </div>
</div>

    <button id="btnAdd" class="mb-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-md transition">
        + Agregar Nuevo
    </button>

    <div class="overflow-x-auto border rounded-lg shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider max-w-xs">URL</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Icono</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuarios Permitidos</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody id="iconTableBody" class="bg-white divide-y divide-gray-200 max-h-96 overflow-y-auto">
                <!-- Filas dinámicas -->
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 pointer-events-none transition-opacity z-50" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
            <h2 id="modalTitle" class="text-2xl font-semibold mb-5 text-gray-900"></h2>
            <form id="iconForm" class="space-y-5" novalidate>
                <input type="hidden" id="iconIndex">

                <div>
                    <label for="iconName" class="block font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                    <input type="text" id="iconName" required placeholder="Ejemplo: Google"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    <p class="text-red-600 text-sm mt-1 hidden" id="errorName">El nombre es obligatorio.</p>
                </div>

                <div>
                    <label for="iconUrl" class="block font-medium text-gray-700 mb-1">URL <span class="text-red-500">*</span></label>
                    <input type="url" id="iconUrl" required placeholder="https://ejemplo.com"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    <p class="text-red-600 text-sm mt-1 hidden" id="errorUrl">Ingresa una URL válida.</p>
                </div>

                <div>
                    <label for="iconImg" class="block font-medium text-gray-700 mb-1">URL Imagen (Icono) <span class="text-red-500">*</span></label>
                    <input type="url" id="iconImg" required placeholder="https://ejemplo.com/icono.png"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    <p class="text-red-600 text-sm mt-1 hidden" id="errorImg">Ingresa una URL válida para la imagen.</p>
                </div>

                <div>
                    <label for="iconUsers" class="block font-medium text-gray-700 mb-1">Usuarios Permitidos</label>
                    <select id="iconUsers" multiple size="5" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="admin">admin</option>
                        <option value="user">user</option>
                        <option value="user1">user1</option>
                        <option value="user2">user2</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-1 italic">Mantén Ctrl/Cmd para seleccionar múltiples usuarios. Dejar vacío significa "Todos".</p>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-gray-200">
                    <button type="button" id="btnCancel" class="px-5 py-2 rounded-md bg-gray-300 hover:bg-gray-400 transition font-medium">Cancelar</button>
                    <button type="submit" class="px-6 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition font-semibold">Guardar</button>
                </div>
            </form>

            <button id="btnCloseModal" aria-label="Cerrar modal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-3xl font-bold leading-none focus:outline-none">&times;</button>
        </div>
    </div>
</div>

<script>
    (() => {
        // Variables
        let icons = JSON.parse(localStorage.getItem('icons') || '[]');
        const iconTableBody = document.getElementById('iconTableBody');
        const modal = document.getElementById('modal');
        const modalTitle = document.getElementById('modalTitle');
        const iconForm = document.getElementById('iconForm');
        const iconIndex = document.getElementById('iconIndex');
        const iconName = document.getElementById('iconName');
        const iconUrl = document.getElementById('iconUrl');
        const iconImg = document.getElementById('iconImg');
        const iconUsers = document.getElementById('iconUsers');
        const btnAdd = document.getElementById('btnAdd');
        const btnCancel = document.getElementById('btnCancel');
        const btnCloseModal = document.getElementById('btnCloseModal');

        // Errores validación
        const errorName = document.getElementById('errorName');
        const errorUrl = document.getElementById('errorUrl');
        const errorImg = document.getElementById('errorImg');

        // Render tabla
        function renderTable() {
            iconTableBody.innerHTML = '';
            if (icons.length === 0) {
                iconTableBody.innerHTML = `
                    <tr><td colspan="5" class="text-center py-6 text-gray-500 italic">No hay iconos guardados.</td></tr>
                `;
                return;
            }
            icons.forEach((icon, idx) => {
                const users = icon.users && icon.users.length ? icon.users.join(', ') : 'Todos';
                iconTableBody.insertAdjacentHTML('beforeend', `
                    <tr class="hover:bg-gray-50 cursor-default">
                        <td class="px-4 py-3 border border-gray-200">${escapeHtml(icon.name)}</td>
                        <td class="px-4 py-3 border border-gray-200 max-w-xs truncate" title="${escapeHtml(icon.url)}">${escapeHtml(icon.url)}</td>
                        <td class="px-4 py-3 border border-gray-200 text-center">
                            <img src="${escapeHtml(icon.img)}" alt="icono" class="w-8 h-8 mx-auto rounded" loading="lazy" />
                        </td>
                        <td class="px-4 py-3 border border-gray-200">${escapeHtml(users)}</td>
                        <td class="px-4 py-3 border border-gray-200 text-center space-x-2">
                            <button data-idx="${idx}" class="btnEdit bg-yellow-400 hover:bg-yellow-500 px-3 py-1 rounded text-sm font-medium transition">Editar</button>
                            <button data-idx="${idx}" class="btnDelete bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm font-medium text-white transition">Eliminar</button>
                        </td>
                    </tr>
                `);
            });
            addTableEvents();
        }

        // Seguridad básica para texto HTML
        function escapeHtml(text) {
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Agregar eventos botones en tabla
        function addTableEvents() {
            document.querySelectorAll('.btnEdit').forEach(btn => {
                btn.onclick = e => {
                    const idx = e.target.dataset.idx;
                    openModal('edit', idx);
                };
            });

            document.querySelectorAll('.btnDelete').forEach(btn => {
                btn.onclick = e => {
                    const idx = e.target.dataset.idx;
                    confirmDelete(idx);
                };
            });
        }

        // Guardar icons en localStorage
        function saveIcons() {
            localStorage.setItem('icons', JSON.stringify(icons));
        }

        // Abrir modal para nuevo o editar
        function openModal(mode, idx = null) {
            resetErrors();
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.setAttribute('aria-hidden', 'false');
            if (mode === 'new') {
                modalTitle.textContent = 'Nuevo Icono';
                iconIndex.value = '';
                iconName.value = '';
                iconUrl.value = '';
                iconImg.value = '';
                for (let option of iconUsers.options) option.selected = false;
            } else if (mode === 'edit') {
                modalTitle.textContent = 'Editar Icono';
                const icon = icons[idx];
                iconIndex.value = idx;
                iconName.value = icon.name;
                iconUrl.value = icon.url;
                iconImg.value = icon.img;
                for (let option of iconUsers.options) {
                    option.selected = icon.users && icon.users.includes(option.value);
                }
            }
            iconName.focus();
        }

        // Cerrar modal
        function closeModal() {
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.setAttribute('aria-hidden', 'true');
            resetErrors();
        }

        // Resetear errores
        function resetErrors() {
            [errorName, errorUrl, errorImg].forEach(el => el.classList.add('hidden'));
        }

        // Validación simple
        function validateForm() {
            let valid = true;
            resetErrors();

            if (!iconName.value.trim()) {
                errorName.classList.remove('hidden');
                valid = false;
            }

            if (!iconUrl.value.trim() || !isValidURL(iconUrl.value.trim())) {
                errorUrl.classList.remove('hidden');
                valid = false;
            }

            if (!iconImg.value.trim() || !isValidURL(iconImg.value.trim())) {
                errorImg.classList.remove('hidden');
                valid = false;
            }

            return valid;
        }

        // Verificar URL válida (simple)
        function isValidURL(str) {
            try {
                new URL(str);
                return true;
            } catch {
                return false;
            }
        }

        // Confirmar eliminación con modal nativo
        function confirmDelete(idx) {
            if (confirm('¿Seguro que deseas eliminar este icono?')) {
                icons.splice(idx, 1);
                saveIcons();
                renderTable();
            }
        }

        // Eventos formulario
        iconForm.addEventListener('submit', e => {
            e.preventDefault();
            if (!validateForm()) return;

            const idx = iconIndex.value;
            const selectedUsers = Array.from(iconUsers.selectedOptions).map(opt => opt.value);

            const newIcon = {
                name: iconName.value.trim(),
                url: iconUrl.value.trim(),
                img: iconImg.value.trim(),
                users: selectedUsers.length ? selectedUsers : null,
            };

            if (idx === '') {
                icons.push(newIcon);
            } else {
                icons[idx] = newIcon;
            }
            saveIcons();
            renderTable();
            closeModal();
        });

        // Botones abrir/cerrar modal
        btnAdd.onclick = () => openModal('new');
        btnCancel.onclick = closeModal;
        btnCloseModal.onclick = closeModal;

        // Cerrar modal con tecla Esc
        window.addEventListener('keydown', e => {
            if (e.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') {
                closeModal();
            }
        });

        // Inicializar tabla
        renderTable();
    })();

    function logout() {
        localStorage.removeItem('user');
        window.location.href = '/login';
    }
</script>

@endsection
