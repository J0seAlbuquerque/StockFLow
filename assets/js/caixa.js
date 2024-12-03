document.addEventListener("DOMContentLoaded", function () {
    let carrinho = [];

    function adicionarCarrinho(codigo, nome, preco, estoque) {
        const tabelaCarrinho = document.querySelector("#carrinho tbody");

        if (!tabelaCarrinho) {
            console.error("Erro: Tabela do carrinho não encontrada.");
            return;
        }

        const itemExistente = carrinho.find(item => item.codigo === codigo);
        if (itemExistente) {
            if (itemExistente.quantidade < estoque) {
                itemExistente.quantidade++;
            } else {
                alert("Estoque insuficiente.");
            }
        } else {
            carrinho.push({ codigo, nome, preco, quantidade: 1 });
        }
        atualizarCarrinho();
    }

    function atualizarCarrinho() {
        const tabela = document.querySelector("#carrinho tbody");
        tabela.innerHTML = "";
        let totalGeral = 0;

        carrinho.forEach(item => {
            const total = item.preco * item.quantidade;
            totalGeral += total;

            const row = tabela.insertRow();
            row.innerHTML = `
                <td>${item.nome}</td>
                <td>
                    <input type="number" value="${item.quantidade}" min="1" onchange="alterarQuantidade('${item.codigo}', this.value)">
                </td>
                <td>R$ ${item.preco.toFixed(2)}</td>
                <td>R$ ${total.toFixed(2)}</td>
                <td><button onclick="removerCarrinho('${item.codigo}')">Remover</button></td>
            `;
        });

        document.getElementById("total-geral").innerText = totalGeral.toFixed(2);
        atualizarValorParcelas();
    }

    window.adicionarCarrinho = adicionarCarrinho;

    function alterarQuantidade(codigo, novaQuantidade) {
        const item = carrinho.find(item => item.codigo === codigo);
        item.quantidade = parseInt(novaQuantidade);
        atualizarCarrinho();
    }

    function removerCarrinho(codigo) {
        carrinho = carrinho.filter(item => item.codigo !== codigo);
        atualizarCarrinho();
    }

    function atualizarValorParcelas() {
        const totalGeral = parseFloat(document.getElementById("total-geral").innerText);
        const parcelasSelect = document.getElementById("parcelas");
        if (parcelasSelect) {
            const parcelas = parseInt(parcelasSelect.value);
            const valorParcela = totalGeral / parcelas;
            document.getElementById("valor-parcela").innerText = `R$ ${valorParcela.toFixed(2)}`;
        }
    }

    document.getElementById("forma-pagamento").addEventListener("change", function () {
        const parcelasContainer = document.getElementById("parcelas-container");
        if (this.value === "cartao_credito") {
            parcelasContainer.style.display = "block";
        } else {
            parcelasContainer.style.display = "none";
        }
    });

    document.getElementById("parcelas").addEventListener("change", atualizarValorParcelas);
});
