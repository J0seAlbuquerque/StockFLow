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
