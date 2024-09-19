document.getElementById('onderwijsFormulier').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('verwerk_aanvraag.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Aanvraag succesvol verstuurd!');
        } else {
            alert('Fout: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});
