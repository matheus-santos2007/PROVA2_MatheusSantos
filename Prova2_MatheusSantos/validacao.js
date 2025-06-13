function validarCliente() {
    // Obter valores dos campos
    const nome = document.getElementById('nome').value.trim();
    const telefone = document.getElementById('telefone').value.trim();
    const email = document.getElementById('email').value.trim();
    const endereco = document.getElementById('endereco').value.trim();
    const senha = document.getElementById('senha').value;
    const idPerfil = document.getElementById('id_perfil').value;

    // Validar nome
    if (nome.length < 3) {
        alert('Nome deve ter pelo menos 3 caracteres');
        return false;
    }

    document.getElementById('telefone').addEventListener('input', function(e) {
        // Remove tudo que não é dígito
        let value = e.target.value.replace(/\D/g, '');
        
        // Limita a 11 dígitos (DDD + 9 dígitos)
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        
        // Aplica a formatação
        if (value.length > 0) {
            value = value.replace(/^(\d{0,2})(\d{0,5})(\d{0,4})/, function(match, g1, g2, g3) {
                let result = '';
                if (g1) result += '(' + g1;
                if (g2) result += ') ' + g2;
                if (g3) result += '-' + g3;
                return result;
            });
        }
        
        e.target.value = value;
    });

    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('E-mail inválido');
        return false;
    }

    // Validar endereço
    if (endereco.length < 5) {
        alert('Endereço deve ter pelo menos 5 caracteres');
        return false;
    }

    // Validar senha
    if (senha.length < 6) {
        alert('Senha deve ter pelo menos 6 caracteres');
        return false;
    }

    // Validar perfil
    if (!idPerfil) {
        alert('Selecione um perfil');
        return false;
    }

    return true;
}