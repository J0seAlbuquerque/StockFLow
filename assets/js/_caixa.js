document.addEventListener("DOMContentLoaded", function () {
    const cart = [];
    const cartTableBody = document.querySelector('#cartSummary tbody');
    const cartTableBodyModal = document.querySelector('#cartSummaryModal');
    const totalAmount = document.getElementById('totalAmount');
    const paymentMethodSelect = document.getElementById("paymentMethod");
    const creditCardInstallments = document.getElementById("installments");
    const installmentValueDisplay = document.getElementById("installmentValue");
    const creditCardOptions = document.getElementById("creditCardOptions");
    const searchBar = document.getElementById('searchBar');
    let products = [];

    // Modal elements
    const paymentModal = document.getElementById("paymentModal");
    const proceedToPaymentButton = document.getElementById("proceedToPayment");
    const closeModalButton = document.querySelector(".close");
    const totalAmountModal = document.getElementById("totalAmountModal");

    proceedToPaymentButton.addEventListener("click", function() {
        updateCartTable();
        paymentModal.style.display = "block";
    });

    closeModalButton.addEventListener("click", function() {
        paymentModal.style.display = "none";
    });

    window.addEventListener("click", function(event) {
        if (event.target == paymentModal) {
            paymentModal.style.display = "none";
        }
    });

    paymentMethodSelect.addEventListener("change", function() {
        if (this.value === "credito") {
            creditCardOptions.style.display = "block";
        } else {
            creditCardOptions.style.display = "none";
        }
        updateInstallmentValue();
    });

    creditCardInstallments.addEventListener("change", updateInstallmentValue);

    function updateInstallmentValue() {
        const total = parseFloat(totalAmountModal.textContent.replace('Total: R$ ', '').replace(',', '.'));
        const installments = parseInt(creditCardInstallments.value);
        const installmentValue = total / installments;
        installmentValueDisplay.textContent = `Valor da Parcela: R$ ${installmentValue.toFixed(2).replace('.', ',')}`;
    }

    function updateCartTable() {
        cartTableBody.innerHTML = '';
        cartTableBodyModal.innerHTML = '';
        let total = 0;

        cart.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>R$ ${item.price.toFixed(2).replace('.', ',')}</td>
                <td>R$ ${(item.price * item.quantity).toFixed(2).replace('.', ',')}</td>
                <td>
                    <button class="remove-from-cart" data-id="${item.id}">-</button>
                    <button class="remove-all-from-cart" data-id="${item.id}">Remover Tudo</button>
                </td>
            `;
            cartTableBody.appendChild(row);

            const rowModal = document.createElement('tr');
            rowModal.innerHTML = `
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>R$ ${(item.price * item.quantity).toFixed(2).replace('.', ',')}</td>
            `;
            cartTableBodyModal.appendChild(rowModal);

            total += item.price * item.quantity;
        });

        totalAmount.textContent = `Total: R$ ${total.toFixed(2).replace('.', ',')}`;
        totalAmountModal.textContent = `Total: R$ ${total.toFixed(2).replace('.', ',')}`;
        updateInstallmentValue();

        // Adiciona evento de clique para remover uma unidade do carrinho
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

        // Adiciona evento de clique para remover todas as unidades do carrinho
        document.querySelectorAll('.remove-all-from-cart').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const productIndex = cart.findIndex(item => item.id === productId);

                if (productIndex > -1) {
                    cart.splice(productIndex, 1);
                }

                updateCartTable();
            });
        });
    }

    function filterProducts() {
        const query = searchBar.value.toLowerCase();
        const filteredProducts = products.filter(product => 
            product.name.toLowerCase().includes(query) || 
            product.code.toLowerCase().includes(query)
        );
        displayProducts(filteredProducts);
    }

    function displayProducts(productsToDisplay) {
        const productsList = document.querySelector("#productsList tbody");
        productsList.innerHTML = '';
        productsToDisplay.forEach(product => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${product.name}</td>
                <td>${product.code}</td>
                <td>${product.category}</td>
                <td>R$ ${parseFloat(product.sale_price).toFixed(2).replace('.', ',')}</td>
                <td>${product.quantity}</td>
                <td><button class="add-to-cart" data-id="${product.product_id}" data-price="${product.sale_price}" data-name="${product.name}" data-quantity="${product.quantity}">+</button></td>
            `;
            productsList.appendChild(row);
        });

        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const productName = this.getAttribute('data-name');
                const productPrice = parseFloat(this.getAttribute('data-price'));
                const productQuantity = parseInt(this.getAttribute('data-quantity'));

                // Verifica se o produto já está no carrinho
                const existingProduct = cart.find(item => item.id === productId);
                if (existingProduct) {
                    if (existingProduct.quantity < productQuantity) {
                        existingProduct.quantity += 1;
                    } else {
                        alert('Quantidade em estoque insuficiente.');
                    }
                } else {
                    if (productQuantity > 0) {
                        cart.push({ id: productId, name: productName, price: productPrice, quantity: 1 });
                    } else {
                        alert('Quantidade em estoque insuficiente.');
                    }
                }

                // Atualiza a tabela do carrinho
                updateCartTable();
            });
        });
    }

    // Fetch products and handle cart operations
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
                products = data.products;
                displayProducts(products);
            } else {
                console.error('Erro ao carregar produtos:', data.error);
            }
        })
        .catch(error => console.error('Erro ao carregar produtos:', error));

    searchBar.addEventListener('input', filterProducts);
});