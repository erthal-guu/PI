/**
 * Funções para gerenciar o estado de edição dos formulários de perfil.
 * Estas funções devem ser adicionadas ao seu arquivo 'profile.js'.
 */

// Armazena os valores originais para a função Cancelar
const originalValues = {};

function toggleEdit(tabId) {
    const form = document.getElementById(tabId + '-form');
    const inputs = form.querySelectorAll('input:not([type="submit"]):not([type="button"])');
    const actions = form.querySelector('.form-actions');
    const editButton = document.querySelector(`#${tabId} .btn-edit`);

    const isEditing = editButton.textContent.includes('Cancelar');

    if (!isEditing) {
        editButton.innerHTML = '<i class="fas fa-times"></i> Cancelar';
        editButton.classList.add('btn-cancel');
        editButton.classList.remove('btn-edit');

        inputs.forEach(input => {
            originalValues[input.id] = input.value;
            input.disabled = false;
        });
        
        actions.style.display = 'flex';
        actions.style.justifyContent = 'flex-end'; 
    } else {
        cancelEdit(tabId);
    }
}

function cancelEdit(tabId) {
    const form = document.getElementById(tabId + '-form');
    const inputs = form.querySelectorAll('input:not([type="submit"]):not([type="button"])');
    const actions = form.querySelector('.form-actions');
    const editButton = document.querySelector(`#${tabId} .btn-edit, #${tabId} .btn-cancel`);

    editButton.innerHTML = '<i class="fas fa-edit"></i>Editar';
    editButton.classList.remove('btn-cancel');
    editButton.classList.add('btn-edit');

    inputs.forEach(input => {
        if (originalValues[input.id] !== undefined) {
            input.value = originalValues[input.id];
        }
        input.disabled = true;
    });
    actions.style.display = 'none';
}

function toggleProfileMenu() {
    const menu = document.getElementById('profile-menu');
    menu.classList.toggle('open');
}

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.parentElement.querySelector('.password-toggle');
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

//profile.js atualizado