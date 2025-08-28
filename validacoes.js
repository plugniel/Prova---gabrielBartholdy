function validarFuncionario() {
    let nome = document.getElementById("nome_funcionario").value;
    let telefone = document.getElementById("telefone").value;
    let email = document.getElementById("email").value;


    if (nome.length < 3) {
        alert("O nome do funcionário deve ter pelo menos 3 caracteres.");
        return false;
    }

    let regexTelefone = /^[0-9]{10,11}$/;
    if (!regexTelefone.test(telefone)) {
        alert("Digite um telefone válido (10 ou 11 dígitos).");
        return false;
    }

    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        alert("Digite um e-mail válido.");
        return false;
    }

    return true;
}

function validarNome(){
    let nome = document.getElementById("nome").value;

    nome = nome.replace(/[0-9]/g, "");
    document.getElementById("nome").value = nome;

    return true;
}

function validarTelefone(){
    let variavel = document.getElementById("telefone").value;
    variavel = variavel.replace(/\D/g, "");
    variavel = variavel.replace(/^(\d\d)(\d)/g, "($1) $2"); // adiciona parenteses em volta dos dois primeiros dígitos
    variavel = variavel.replace(/(\d{5})(\d)/, "$1-$2"); // adiciona o hífen entre o quarto e o quinto dígito
    document.getElementById("telefone").value = variavel;
    if (variavel.length > 15) {
        alert("O telefone deve ter no máximo 15 caracteres.");
        return false;
    }
    return variavel;
}

function validarEmail() {
    let email = document.getElementById("email").value;
    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!regexEmail.test(email)) {
        alert("Digite um e-mail válido.");
        return false;
    }

    return true;
}
function validarNomeFornecedor(){
    let nome_fornecedor = document.getElementById("nome_fornecedor").value;

    nome_fornecedor = nome.replace(/[0-9]/g, "");
    document.getElementById("nome_fornecedor").value = nome_fornecedor;

    return true;
}