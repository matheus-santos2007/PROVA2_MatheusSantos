function validarCliente() {
    let nome = document.getElementById("nome").value.trim();
    let email = document.getElementById("email").value.trim();

    if (nome.length < 3) {
        alert("O nome do cliente deve ter pelo menos 3 caracteres.");
        return false;
    }

    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        alert("Digite um e-mail válido.");
        return false;
    }

    // Se existir campo de senha (no cadastro)
    let senhaInput = document.getElementById("senha");
    if (senhaInput && senhaInput.value.length < 4) {
        alert("A senha deve ter pelo menos 4 caracteres.");
        return false;
    }

    return true;

    
}
