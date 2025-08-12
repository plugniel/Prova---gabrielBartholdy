// Aguarda o carregamento completo do DOM antes de executar o código
document.addEventListener("DOMContentLoaded", function () {
    let dropdowns = document.querySelectorAll(".dropdown");

    dropdowns.forEach(function (dropdown) {
        dropdown.addEventListener("mouseover", function () {
            let submenu = this.querySelector(".dropdown-menu");
            submenu.style.display = "block";
        });

        dropdown.addEventListener("mouseout", function () {
            let submenu = this.querySelector(".dropdown-menu");
            submenu.style.display = "none";
        });
    });
});

// Função para buscar sugestões de usuários enquanto digita
function buscarSugestoes() {
    let busca = document.getElementById("busca_usuario").value;
    
    // Se o usuário digitou menos de 2 caracteres, limpa as sugestões
    if (busca.length < 2) {
        document.getElementById("sugestoes").innerHTML = "";
        return;
    }

    // Faz uma requisição para buscar_sugestoes.php passando o termo de busca
    fetch("buscar_sugestoes.php?busca=" + encodeURIComponent(busca))
        .then(response => response.json())
        .then(data => {
            let sugestoesHTML = "<ul>";
            
            // Corrigido: Uso correto de interpolação de strings com template literals
            data.forEach(usuario => {
                sugestoesHTML += `<li onclick="selecionarUsuario('${usuario.id_usuario}', '${usuario.nome}')">${usuario.nome}</li>`;
            });

            sugestoesHTML += "</ul>";
            document.getElementById("sugestoes").innerHTML = sugestoesHTML;
        })
        .catch(error => console.error("Erro ao buscar sugestões:", error)); // Adicionado tratamento de erro
}

// Função para selecionar um usuário da lista de sugestões
function selecionarUsuario(id, nome) {
    document.getElementById("busca_usuario").value = nome;
    document.getElementById("sugestoes").innerHTML = "";
}