document.addEventListener("DOMContentLoaded", function () {
    const cart = [];
    const cartTableBody = document.querySelector('#cartSummary tbody');
    const totalAmount = document.getElementById('totalAmount');
    const paymentMethodSelect = document.getElementById("paymentMethod");
    const creditCardInstallments = document.getElementById("installments");
    const installmentValueDisplay = document.getElementById("installmentValue");
    const creditCardOptions = document.getElementById("creditCardOptions");

    const proceedToPaymentButton = document.getElementById("proceedToPayment");

    if (proceedToPaymentButton) {
        proceedToPaymentButton.addEventListener('click', function() {
            document.getElementById('paymentPopup').style.display = 'block';
        });
    }

    const closePopupButton = document.getElementById('closePopup');
    if (closePopupButton) {
        closePopupButton.addEventListener('click', function() {
            document.getElementById('paymentPopup').style.display = 'none';
        });
    }

    // Buscar os produtos do banco de dados quando a página carregar
    fetch('../api/get_products.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao carregar produtos');
            }
            return response.json();
        })
        .then(data => {
            console.log('Dados recebidos da API:', data); // Log para verificar os dados recebidos
            if (data.success) {
                const productsList = document.querySelector("#productsList tbody");
                if (productsList) {
                    data.products.forEach(product => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${product.name}</td>
                            <td>${product.code}</td>
                            <td>R$ ${parseFloat(product.sale_price).toFixed(2).replace('.', ',')}</td>
                            <td>${product.quantity}</td>
                            <td><button class="add-to-cart" data-id="${product.product_id}" data-price="${product.sale_price}" data-name="${product.name}">+</button></td>
                        `;
                        productsList.appendChild(row);
                    });

                    document.querySelectorAll('.add-to-cart').forEach(button => {
                        button.addEventListener('click', function() {
                            const productId = this.getAttribute('data-id');
                            const productName = this.getAttribute('data-name');
                            const productPrice = parseFloat(this.getAttribute('data-price'));

                            // Verifica se o produto já está no carrinho
                            const existingProduct = cart.find(item => item.id === productId);
                            if (existingProduct) {
                                existingProduct.quantity += 1;
                            } else {
                                cart.push({ id: productId, name: productName, price: productPrice, quantity: 1 });
                            }

                            // Atualiza a tabela do carrinho
                            updateCartTable();
                        });
                    });
                }
            } else {
                console.error('Erro ao carregar produtos:', data.error);
            }
        })
        .catch(error => console.error('Erro ao carregar produtos:', error));

    function updateCartTable() {
        cartTableBody.innerHTML = '';
        let total = 0;

        cart.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>R$ ${item.price.toFixed(2).replace('.', ',')}</td>
                <td>R$ ${(item.price * item.quantity).toFixed(2).replace('.', ',')}</td>
                <td><button class="remove-from-cart" data-id="${item.id}">-</button></td>
            `;
            cartTableBody.appendChild(row);

            total += item.price * item.quantity;
        });

        totalAmount.textContent = `Total: R$ ${total.toFixed(2).replace('.', ',')}`;

        // Adiciona evento de clique para remover itens do carrinho
        document.querySelectorAll('.remove-from-cart').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const productIndex = cart.findIndex(item => item.id === productId);

                if (productIndex > -1) {
                    cart[productIndex].quantity -= 1;
                    if (cart[productIndex].quantity === 0) {
                        cart.splice(productIndex, 1);
                    }
                }

                updateCartTable();
            });
        });
    }
});