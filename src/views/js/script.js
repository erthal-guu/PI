        function abrirModalLogin() {
            document.getElementById("loginModal").style.display = "block";
        }
        function fecharModalLogin() {
            document.getElementById("loginModal").style.display = "none";
        }

        // --- Modal Cadastro ---
        function abrirModalCadastro() {
            document.getElementById("registerModal").style.display = "block";
        }
        function fecharModalCadastro() {
            document.getElementById("registerModal").style.display = "none";
        }

        // --- Troca rápida entre login/cadastro ---
        function trocarParaCadastro() {
            fecharModalLogin();
            abrirModalCadastro();
        }
        function trocarParaLogin() {
            fecharModalCadastro();
            abrirModalLogin();
        }

        // Fechar ao clicar fora
        window.onclick = function(event) {
            const loginModal = document.getElementById("loginModal");
            const registerModal = document.getElementById("registerModal");
            if (event.target === loginModal) fecharModalLogin();
            if (event.target === registerModal) fecharModalCadastro();
        }