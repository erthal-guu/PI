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
// Sistema de Edição de Perfil - Self Med

document.addEventListener('DOMContentLoaded', function() {
    const editButton = document.querySelector('.btn-edit');
    const personalInfoForm = document.getElementById('personal-info-form');
    const formInputs = personalInfoForm.querySelectorAll('input');
    const formActions = personalInfoForm.querySelector('.form-actions');

    console.log('Profile.js carregado!'); // Debug

    // Função para habilitar edição
    if (editButton) {
        editButton.addEventListener('click', function() {
            console.log('Botão Editar clicado!'); // Debug
            
            // Habilita todos os campos
            formInputs.forEach(input => {
                input.disabled = false;
                input.style.backgroundColor = '#ffffff';
                input.style.cursor = 'text';
                input.style.border = '1px solid #d1d5db';
            });

            // Mostra os botões de ação (Cancelar e Salvar)
            if (formActions) {
                formActions.style.display = 'flex';
                formActions.style.justifyContent = 'flex-end';
                formActions.style.gap = '10px';
                formActions.style.marginTop = '20px';
            }
            
            // Esconde o botão editar
            editButton.style.display = 'none';
        });
    } else {
        console.error('Botão Editar não encontrado!');
    }

    // Função para cancelar edição (definida globalmente)
    window.cancelEdit = function(formId) {
        console.log('Cancelar edição'); // Debug
        
        // Desabilita todos os campos
        formInputs.forEach(input => {
            input.disabled = true;
            input.style.backgroundColor = '#f5f5f5';
            input.style.cursor = 'not-allowed';
            input.style.border = '1px solid #e5e7eb';
        });

        // Esconde os botões de ação
        if (formActions) {
            formActions.style.display = 'none';
        }
        
        // Mostra o botão editar novamente
        if (editButton) {
            editButton.style.display = 'inline-flex';
        }

        // Recarrega a página para restaurar os valores originais
        location.reload();
    };

    // Adiciona estilos iniciais aos campos desabilitados
    formInputs.forEach(input => {
        if (input.disabled) {
            input.style.backgroundColor = '#f5f5f5';
            input.style.cursor = 'not-allowed';
            input.style.border = '1px solid #e5e7eb';
        }
    });

    // Submissão do formulário de informações pessoais
    personalInfoForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(personalInfoForm);

        // Mostra loading no botão
        const submitButton = personalInfoForm.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
        submitButton.disabled = true;

        // Envia os dados via AJAX
        fetch('../controller/update_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Atualiza a sidebar com os novos dados
                const firstName = formData.get('firstName');
                const lastName = formData.get('lastName');
                const email = formData.get('email');
                
                document.getElementById('sidebar-name').textContent = firstName + ' ' + lastName;
                document.getElementById('sidebar-email').textContent = email;

                // Desabilita os campos novamente
                formInputs.forEach(input => {
                    input.disabled = true;
                    input.style.backgroundColor = '#f5f5f5';
                    input.style.cursor = 'not-allowed';
                    input.style.border = '1px solid #e5e7eb';
                });

                // Esconde os botões de ação
                if (formActions) {
                    formActions.style.display = 'none';
                }
                
                // Mostra o botão editar
                if (editButton) {
                    editButton.style.display = 'inline-flex';
                }

                // Mostra mensagem de sucesso
                showNotification('✅ Perfil atualizado com sucesso!', 'success');
            } else {
                showNotification('❌ ' + (data.message || 'Erro ao atualizar perfil'), 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showNotification('❌ Erro ao atualizar perfil. Tente novamente.', 'error');
        })
        .finally(() => {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        });
    });

    // Toggle de visualização de senha
    window.togglePassword = function(inputId) {
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
    };

    // Formulário de alteração de senha
    const passwordForm = document.getElementById('password-form');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmNewPassword').value;

            // Valida se as senhas coincidem
            if (newPassword !== confirmPassword) {
                showNotification('❌ As senhas não coincidem!', 'error');
                return;
            }

            // Valida tamanho mínimo
            if (newPassword.length < 6) {
                showNotification('❌ A senha deve ter no mínimo 6 caracteres!', 'error');
                return;
            }

            const formData = new FormData(passwordForm);
            const submitButton = passwordForm.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Alterando...';
            submitButton.disabled = true;

            fetch('../controller/change_password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('✅ Senha alterada com sucesso!', 'success');
                    passwordForm.reset();
                } else {
                    showNotification('❌ ' + (data.message || 'Erro ao alterar senha'), 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showNotification('❌ Erro ao alterar senha. Tente novamente.', 'error');
            })
            .finally(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        });
    }

    // Navegação entre tabs
    const tabLinks = document.querySelectorAll('.profile-nav-item');
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Só previne default se for uma âncora interna (#)
            if (href && href.startsWith('#')) {
                e.preventDefault();
                const tabId = this.getAttribute('data-tab');
                
                if (tabId) {
                    // Remove active de todas as tabs
                    document.querySelectorAll('.tab-content').forEach(tab => {
                        tab.classList.remove('active');
                    });
                    document.querySelectorAll('.profile-nav-item').forEach(item => {
                        item.classList.remove('active');
                    });

                    // Adiciona active na tab selecionada
                    const targetTab = document.getElementById(tabId);
                    if (targetTab) {
                        targetTab.classList.add('active');
                        this.classList.add('active');
                    }
                }
            }
        });
    });
});

// Função para mostrar notificações
function showNotification(message, type) {
    // Remove notificação anterior se existir
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Cria nova notificação
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;

    // Adiciona estilos inline
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 24px;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 10000;
        animation: slideInRight 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        font-family: 'Inter', sans-serif;
        font-size: 15px;
        font-weight: 500;
        min-width: 300px;
    `;

    document.body.appendChild(notification);

    // Remove após 4 segundos
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        setTimeout(() => notification.remove(), 400);
    }, 4000);
}

// Adiciona animações CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    .notification i {
        font-size: 20px;
    }

    /* Melhora visual dos campos quando habilitados */
    input:not(:disabled):focus {
        outline: none;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Transições suaves */
    input {
        transition: all 0.2s ease;
    }

    .btn-edit, .btn-save, .btn-cancel {
        transition: all 0.2s ease;
    }

    .btn-edit:hover, .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-cancel:hover {
        background-color: #dc2626;
        transform: translateY(-2px);
    }
`;
document.head.appendChild(style);