// JavaScript para abrir e fechar o pop-up
document.getElementById('open-popup-btn').addEventListener('click', function() {
    document.getElementById('popup').style.display = 'block';
    document.getElementById('popup-overlay').style.display = 'block';
});

document.getElementById('close-popup-btn').addEventListener('click', function() {
    document.getElementById('popup').style.display = 'none';
    document.getElementById('popup-overlay').style.display = 'none';
});

document.getElementById('popup-overlay').addEventListener('click', function() {
    document.getElementById('popup').style.display = 'none';
    document.getElementById('popup-overlay').style.display = 'none';
});

function openEditModal(produto) {
    document.getElementById('editId').value = produto.product_id;
    document.getElementById('editName').value = produto.name;
    document.getElementById('editCode').value = produto.code;
    document.getElementById('editSupplier').value = produto.supplier;
    document.getElementById('editCategory').value = produto.category;
    document.getElementById('editCostPrice').value = produto.cost_price;
    document.getElementById('editSalePrice').value = produto.sale_price;
    document.getElementById('editQuantity').value = produto.quantity;
    document.getElementById('editModal').style.display = 'block';
    document.getElementById('popup-overlay').style.display = 'block';
}

document.getElementById('close-edit-popup-btn').addEventListener('click', function() {
    document.getElementById('editModal').style.display = 'none';
    document.getElementById('popup-overlay').style.display = 'none';
});

document.getElementById('popup-overlay').addEventListener('click', function() {
    document.getElementById('editModal').style.display = 'none';
    document.getElementById('popup-overlay').style.display = 'none';
});

function toggleVencimento() {
    const vencimentoGroup = document.getElementById('vencimento-group');
    const temVencimento = document.getElementById('tem_vencimento').checked;
    vencimentoGroup.style.display = temVencimento ? 'block' : 'none';
}