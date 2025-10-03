function abrirModalLogin() {
    document.getElementById("loginModal").style.display = "block";
}
function fecharModalLogin() {
    document.getElementById("loginModal").style.display = "none";
}


function abrirModalCadastro() {
    document.getElementById("registerModal").style.display = "block";
}
function fecharModalCadastro() {
    document.getElementById("registerModal").style.display = "none";
}

function trocarParaCadastro() {
    fecharModalLogin();
    abrirModalCadastro();
}
function trocarParaLogin() {
    fecharModalCadastro();
    abrirModalLogin();
}
window.onclick = function (event) {
    const loginModal = document.getElementById("loginModal");
    const registerModal = document.getElementById("registerModal");
    if (event.target === loginModal) fecharModalLogin();
    if (event.target === registerModal) fecharModalCadastro();
}
function confirmarSenha() {
    var senha = document.getElementById("registerPassword").value;
    var confirmarSenha = document.getElementById("confirmPassword").value;

    if (senha !== confirmarSenha) {
        alert("As senhas não coincidem. Por favor, tente novamente.");
        return false;
    }
    return true;
}
window.onload = function () {
    const params = new URLSearchParams(window.location.search);
    if (params.has("emailExists")) {
        alert("Este email já está cadastrado. Por favor, use outro email.");
        abrirModalCadastro();
    }
}
document.querySelectorAll(".password-toggle").forEach(button => {
    button.addEventListener("click", function () {
        const input = this.previousElementSibling;
        const icon = this.querySelector("i");

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    });
});
function toggleProfileMenu() {
    var profileMenu = document.getElementById("profile-menu");
    if (profileMenu.style.display === "block") {
        profileMenu.style.display = "none";
    } else {
        profileMenu.style.display = "block";
    }
}

function logout() {

    window.location.href = "../../controller/logout.php";
}

function toggleEdit(tabId) {
    const form = document.getElementById(tabId + "-form");
    const actions = form.querySelector(".form-actions");
    const inputs = form.querySelectorAll("input");
    const editButton = document.querySelector(".tab-content.active .btn-edit");

    const isEditable = !inputs[0].disabled;

    inputs.forEach(input => {
        input.disabled = isEditable;
    });

    if (isEditable) {
        actions.style.display = "none";
        editButton.innerHTML = '<i class="fas fa-edit"></i> Editar';
    } else {
        actions.style.display = "flex";
        editButton.innerHTML = '<i class="fas fa-times"></i> Cancelar';
    }
}

function cancelEdit(tabId) {
    const form = document.getElementById(tabId + "-form");
    const inputs = form.querySelectorAll("input");
    
    inputs.forEach(input => {
        input.value = input.defaultValue;
    });

    toggleEdit(tabId);
}

document.querySelectorAll('.profile-nav-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.profile-nav-item').forEach(nav => nav.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        
        this.classList.add('active');
        const tabId = this.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
    });
});

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector("i");
    
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}


function toggleProfileMenu() {
    var profileMenu = document.getElementById("profile-menu");
    if (profileMenu.style.display === "block") {
        profileMenu.style.display = "none";
    } else {
        profileMenu.style.display = "block";
    }
}






