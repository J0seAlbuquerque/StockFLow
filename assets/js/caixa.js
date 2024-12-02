document.addEventListener('DOMContentLoaded', () => {
    const carrinho = [];
    const tabelaCarrinho = document.getElementById('itens-carrinho');
    const totalGeralSpan = document.getElementById('total-geral');
    const parcelasSpan = document.getElementById('parcelas');
    const finalizarVendaBtn = document.getElementById('finalizar-venda');
    const formaPagamentoSelect = document.getElementById('forma-pagamento');
    const parcelamentoDiv = document.getElementById('parcelamento');
    const numParcelas = document.getElementById('num-parcelas');

    // Mostrar ou esconder o campo de parcelamento
    function atualizarParcelamento() {
        if (formaPagamentoSelect.value === "Cartão de Crédito") {
            parcelamentoDiv.style.display = "block";
        } else {
            parcelamentoDiv.style.display = "none";
            parcelasSpan.textContent = ''; // Limpa as parcelas se não for cartão
        }
    }

    formaPagamentoSelect.addEventListener('change', atualizarParcelamento);

    function atualizarCarrinho() {
        tabelaCarrinho.innerHTML = '';
        let totalGeral = 0;

        carrinho.forEach((item, index) => {
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${item.nome}</td>
                <td>
                    <input type="number" value="${item.quantidade}" min="1" data-index="${index}" class="atualizar-quantidade">
                </td>
                <td>R$ ${item.preco.toFixed(2).replace('.', ',')}</td>
                <td>R$ ${(item.preco * item.quantidade).toFixed(2).replace('.', ',')}</td>
                <td><button class="remover-item" data-index="${index}">Remover</button></td>
            `;

            totalGeral += item.preco * item.quantidade;
            tabelaCarrinho.appendChild(tr);
        });

        totalGeralSpan.textContent = totalGeral.toFixed(2).replace('.', ',');

        // Atualiza o valor das parcelas se for Cartão de Crédito
        if (formaPagamentoSelect.value === "Cartão de Crédito") {
            const parcelas = numParcelas.value;
            const valorParcelas = (totalGeral / parcelas).toFixed(2);
            parcelasSpan.textContent = `Parcelas: ${parcelas}x R$ ${valorParcelas.replace('.', ',')}`;
        } else {
            parcelasSpan.textContent = ''; // Limpa as parcelas se não for cartão
        }
    }

    // Função para adicionar produto ao carrinho
    document.querySelectorAll('.adicionar-carrinho').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const nome = btn.dataset.nome;
            const preco = parseFloat(btn.dataset.preco);

            const existente = carrinho.find(item => item.id === id);

            if (existente) {
                existente.quantidade++;
            } else {
                carrinho.push({ id, nome, preco, quantidade: 1 });
            }
            atualizarCarrinho();
        });
    });

    // Atualiza a quantidade dos produtos no carrinho
    tabelaCarrinho.addEventListener('input', e => {
        if (e.target.classList.contains('atualizar-quantidade')) {
            const index = e.target.dataset.index;
            const novaQuantidade = parseInt(e.target.value);

            if (novaQuantidade > 0) {
                carrinho[index].quantidade = novaQuantidade;
            } else {
                carrinho[index].quantidade = 1;
                e.target.value = 1;
            }

            atualizarCarrinho();
        }
    });

    // Remove itens do carrinho
    tabelaCarrinho.addEventListener('click', e => {
        if (e.target.classList.contains('remover-item')) {
            const index = e.target.dataset.index;
            carrinho.splice(index, 1);
            atualizarCarrinho();
        }
    });

    // Finalizar venda
    finalizarVendaBtn.addEventListener('click', () => {
        if (carrinho.length > 0) {
            const produtos = JSON.stringify(carrinho);
            const formaPagamento = formaPagamentoSelect.value;
            const valorTotal = parseFloat(totalGeralSpan.textContent.replace(',', '.'));
            const parcelas = formaPagamento === "Cartão de Crédito" ? numParcelas.value : 1;

            fetch('../process/processa_venda.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    produtos,
                    forma_pagamento: formaPagamento,
                    valor_total: valorTotal,
                    parcelas: parcelas
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    location.reload();
                }
            })
            .catch(error => console.error('Erro:', error));
        } else {
            alert("Adicione pelo menos um produto ao carrinho.");
        }
    });

    // Inicializa o estado do parcelamento
    atualizarParcelamento();
});
