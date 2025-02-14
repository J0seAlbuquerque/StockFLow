document.getElementById('searchInput').addEventListener('keyup', filterTable);
document.getElementById('paymentFilter').addEventListener('change', filterTable);

function filterTable() {
    var input = document.getElementById('searchInput').value.toLowerCase();
    var filter = document.getElementById('paymentFilter').value;
    var table = document.getElementById('salesTable');
    var tr = table.getElementsByTagName('tr');

    for (var i = 1; i < tr.length; i++) {
        var tdId = tr[i].getElementsByTagName('td')[0];
        var tdName = tr[i].getElementsByTagName('td')[1];
        var tdPayment = tr[i].getElementsByTagName('td')[2];
        if (tdId || tdName || tdPayment) {
            var idValue = tdId.textContent || tdId.innerText;
            var nameValue = tdName.textContent || tdName.innerText;
            var paymentValue = tdPayment.textContent || tdPayment.innerText;
            if ((idValue.toLowerCase().indexOf(input) > -1 || nameValue.toLowerCase().indexOf(input) > -1) &&
                (filter === "" || paymentValue === filter)) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function showReceipt(saleId) {
    fetch(`../api/get_receipt.php?sale_id=${saleId}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('receiptContent').innerHTML = data;
            document.getElementById('receiptModal').style.display = 'block';
        })
        .catch(error => console.error('Erro ao carregar o recibo:', error));
}

function printReceipt() {
    var receiptContent = document.getElementById('receiptContent').innerHTML;
    var originalContent = document.body.innerHTML;

    document.body.innerHTML = receiptContent;
    window.print();
    document.body.innerHTML = originalContent;
    location.reload(); // Reload the page to restore the original content
}

// Fecha o modal quando o usuário clica no botão de fechar
document.querySelector('.close').addEventListener('click', function() {
    document.getElementById('receiptModal').style.display = 'none';
});

// Fecha o modal quando o usuário clica fora do modal
window.addEventListener('click', function(event) {
    if (event.target == document.getElementById('receiptModal')) {
        document.getElementById('receiptModal').style.display = 'none';
    }
});
