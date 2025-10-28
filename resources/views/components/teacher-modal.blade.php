@props([])

<div id="teacherModal" class="fixed inset-0 bg-indigo-900 bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="relative max-w-2xl w-full mx-4">
        <!-- Soft decorative blur -->
        <div aria-hidden class="absolute -inset-6 rounded-2xl bg-indigo-100 opacity-20 blur-[40px] pointer-events-none"></div>
    <div aria-hidden class="absolute -inset-2 rounded-2xl bg-indigo-900 opacity-6 blur-[24px] pointer-events-none"></div>

        <div class="relative bg-white rounded-xl shadow-[0_30px_80px_rgba(2,6,23,0.12)] border-2 border-indigo-200 ring-1 ring-indigo-50 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 hidden" id="modalContent">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-indigo-100 bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-white shadow-md">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h3 id="modalTitle" class="text-lg font-semibold text-indigo-700">
                            Nuevo Docente
                        </h3>
                    </div>
                    <button onclick="closeTeacherModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Form -->
            <form id="teacherForm" class="p-6 space-y-6" method="POST" action="/docentes">
                @csrf
                <input type="hidden" name="_method" value="POST">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Nombre Completo</label>
                        <input type="text" name="name" required placeholder="Ej: Juan Pérez" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Email</label>
                        <input type="email" name="email" required placeholder="email@universidad.edu" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Código de Docente</label>
                        <input type="text" name="code" required placeholder="DOC-001" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Tipo de Docente</label>
                        <select name="type" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                            <option value="">Seleccionar tipo...</option>
                            <option value="titular">Docente Titular</option>
                            <option value="invitado">Docente Invitado</option>
                            <option value="auxiliar">Docente Auxiliar</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Contraseña <span id="passwordRequiredLabel" class="text-red-600">*</span></label>
                        <input type="password" name="password" id="passwordField" placeholder="Mínimo 8 caracteres" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                        <p class="text-xs text-gray-500 mt-1" id="passwordHint">Requerido al crear. Dejar vacío si no desea cambiarla al editar.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Confirmar Contraseña <span id="passwordConfirmRequiredLabel" class="text-red-600">*</span></label>
                        <input type="password" name="password_confirmation" id="passwordConfirmField" placeholder="Confirmar contraseña" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Teléfono</label>
                        <input type="text" name="phone" placeholder="+591 12345678" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Estado</label>
                        <select name="status" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                            <option value="active" selected>Activo</option>
                            <option value="inactive">Inactivo</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Especialidades</label>
                    <textarea name="specialties" rows="3" placeholder="Ej: Programación, Base de Datos, Sistemas de Información" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800"></textarea>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" class="flex-1 px-4 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                        <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span id="submitButtonText">Crear Docente</span>
                    </button>
                    <button type="button" onclick="closeTeacherModal()" class="px-4 py-3 rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-colors">
                        <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openTeacherModal(isEdit = false, teacherId = null) {
    const modal = document.getElementById('teacherModal');
    const modalContent = document.getElementById('modalContent');
    const form = document.getElementById('teacherForm');
    const modalTitle = document.getElementById('modalTitle');
    const submitButtonText = document.getElementById('submitButtonText');

    modal.classList.remove('hidden');

    // Trigger animation
    setTimeout(() => {
        modalContent.classList.remove('hidden', 'scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);

    // If editing, load teacher data
    if (isEdit && teacherId) {
        modalTitle.textContent = 'Editar Docente';
        submitButtonText.textContent = 'Actualizar Docente';
        
        // Password is optional when editing
        document.getElementById('passwordField').removeAttribute('required');
        document.getElementById('passwordConfirmField').removeAttribute('required');
        document.getElementById('passwordRequiredLabel').classList.add('hidden');
        document.getElementById('passwordConfirmRequiredLabel').classList.add('hidden');
        
        // Fetch teacher data and populate form (use same-origin credentials and accept JSON)
        fetch(`/docentes/${teacherId}`, {
            headers: { 'Accept': 'application/json' },
            credentials: 'include'
        })
            .then(response => {
                console.log('docentes.show response status:', response.status);
                const contentType = response.headers.get('content-type') || '';
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                if (!contentType.includes('application/json')) {
                    return response.text().then(text => {
                        console.error('Expected JSON but received:', text);
                        throw new Error('Invalid JSON response');
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('docentes.show JSON payload:', data);
                form.querySelector('[name="name"]').value = data.name ?? '';
                form.querySelector('[name="email"]').value = data.email ?? '';
                form.querySelector('[name="code"]').value = data.code ?? '';
                form.querySelector('[name="phone"]').value = data.phone ?? '';
                form.querySelector('[name="type"]').value = data.type ?? '';
                form.querySelector('[name="status"]').value = data.status ?? 'active';
                form.querySelector('[name="specialties"]').value = data.specialties ?? '';
                form.action = `/docentes/${teacherId}`;
                form.querySelector('input[name="_method"]').value = 'PUT';
            })
            .catch(error => {
                console.error('Error loading teacher data:', error);
                alert('Error al cargar los datos del docente. Comprueba que estás autenticado.');
            });
    } else {
        // Reset form for new teacher
        modalTitle.textContent = 'Nuevo Docente';
        submitButtonText.textContent = 'Crear Docente';
        form.reset();
        form.action = '/docentes';
        form.querySelector('input[name="_method"]').value = 'POST';
        form.querySelector('[name="status"]').value = 'active';
        
        // Password is required when creating
        document.getElementById('passwordField').setAttribute('required', 'required');
        document.getElementById('passwordConfirmField').setAttribute('required', 'required');
        document.getElementById('passwordRequiredLabel').classList.remove('hidden');
        document.getElementById('passwordConfirmRequiredLabel').classList.remove('hidden');
    }
}

function closeTeacherModal() {
    const modal = document.getElementById('teacherModal');
    const modalContent = document.getElementById('modalContent');

    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modalContent.classList.add('hidden');
        modal.classList.add('hidden');
    }, 300);
}

// Close modal when clicking outside
document.getElementById('teacherModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTeacherModal();
    }
});

// Form validation
document.getElementById('teacherForm').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('border-red-300', 'focus:border-red-400', 'focus:ring-red-300');
            isValid = false;
        } else {
            field.classList.remove('border-red-300', 'focus:border-red-400', 'focus:ring-red-300');
        }
    });

    // Email validation
    const emailField = this.querySelector('[name="email"]');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (emailField && emailField.value && !emailRegex.test(emailField.value)) {
        emailField.classList.add('border-red-300', 'focus:border-red-400', 'focus:ring-red-300');
        isValid = false;
    }

    // Password validation
    const passwordField = this.querySelector('[name="password"]');
    const passwordConfirmField = this.querySelector('[name="password_confirmation"]');
    
    if (passwordField.value || passwordConfirmField.value) {
        if (passwordField.value.length < 8) {
            passwordField.classList.add('border-red-300', 'focus:border-red-400', 'focus:ring-red-300');
            isValid = false;
            if (!passwordField.value) {
                alert('La contraseña debe tener al menos 8 caracteres.');
            }
        }
        
        if (passwordField.value !== passwordConfirmField.value) {
            passwordConfirmField.classList.add('border-red-300', 'focus:border-red-400', 'focus:ring-red-300');
            isValid = false;
            alert('Las contraseñas no coinciden.');
        }
    }

    if (!isValid) {
        e.preventDefault();
        if (this.querySelectorAll('[required]').length > 0) {
            alert('Por favor complete todos los campos requeridos correctamente.');
        }
    }
});
</script>