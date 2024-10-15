document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Voorkom dat het formulier op de traditionele manier wordt verstuurd

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const csrfToken = document.querySelector('input[name="csrf_token"]').value;


        // Simpele e-mailvalidatie
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Voer een geldig e-mailadres in.');
            return false;  // Blokkeer verzending van het formulier
        }

        if (password.trim() === '') {
            alert('Voer een wachtwoord in.');
            return false;  // Blokkeer verzending van het formulier
        }

        // AJAX-verzoek maken
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'inlog.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Verwerking van de respons van PHP
                const response = JSON.parse(xhr.responseText);

                if (response.success) {
                    window.location.href = 'dashboard.php';  // Stuur door naar het dashboard bij succes
                } else {
                    alert('Ongeldige inloggegevens!');  // Toon foutmelding
                }
            }
        };

        // Verzend de inloggegevens via AJAX
        const data = `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&csrf_token=${encodeURIComponent(csrfToken)}`;
        xhr.send(data);
    });
});
