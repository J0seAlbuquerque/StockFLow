document.getElementById('searchInput').addEventListener('keyup', function() {
    var input = document.getElementById('searchInput');
    var filter = input.value.toLowerCase();
    var table = document.getElementById('salesTable');
    var tr = table.getElementsByTagName('tr');

    for (var i = 1; i < tr.length; i++) {
        var tdId = tr[i].getElementsByTagName('td')[0];
        var tdName = tr[i].getElementsByTagName('td')[1];
        if (tdId || tdName) {
            var idValue = tdId.textContent || tdId.innerText;
            var nameValue = tdName.textContent || tdName.innerText;
            if (idValue.toLowerCase().indexOf(filter) > -1 || nameValue.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
});
