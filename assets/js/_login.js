document.addEventListener("DOMContentLoaded", () => {
    const loginSection = document.getElementById("login-section");
    const cadastroSection = document.getElementById("cadastro-section");
    const openCadastro = document.getElementById("open-cadastro");
    const openLogin = document.getElementById("open-login");

    // Exibe apenas a seção de login por padrão
    loginSection.classList.add("active");

    // Alterna para o formulário de cadastro
    openCadastro.addEventListener("click", () => {
        loginSection.classList.remove("active");
        cadastroSection.classList.add("active");
    });

    // Alterna para o formulário de login
    openLogin.addEventListener("click", () => {
        cadastroSection.classList.remove("active");
        loginSection.classList.add("active");
    });
});