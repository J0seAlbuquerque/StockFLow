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