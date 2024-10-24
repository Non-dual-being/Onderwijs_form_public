document.addEventListener('DOMContentLoaded', function () {
    flatpickr.localize(flatpickr.l10ns.nl);
    // Initialiseer Flatpickr kalender met agendaData
    function initFlatpickr(agendaData) {
        const uniqueWeeks = new Set(agendaData.map(item => item.weeknummer));

        flatpickr("#start_date", {
            locale: "nl",  // Stel in op Nederlands
            weekNumbers: true,  // Toon weeknummers
            dateFormat: "d F Y",  // Dag, maand, jaar
            disable: [
                function (date) {
                    const weekNumber = getWeekNumber(date);  // Bereken weeknummer
                    // Disable alle dagen die niet in de juiste week vallen of weekenden
                    return !uniqueWeeks.has(weekNumber) || date.getDay() === 0 || date.getDay() === 6;
                }
            ],
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                const weekNumber = getWeekNumber(dayElem.dateObj);
                const dayOfWeek = dayElem.dateObj.getDay();
                resetDayStyles(dayElem);  // Reset stijlen

                if (dayElem.classList.contains('selected')) {
                    dayElem.style.fontWeight = 'bold';
                    dayElem.style.color = 'white';
                    dayElem.style.border = '2px solid white';
                    dayElem.style.background = 'linear-gradient(135deg, rgba(255, 215, 0, 0.7), rgba(8, 21, 64, 0.7))';  // Goud en donkerblauw
                    dayElem.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.4)';  // Donkere schaduw
                }
                if (uniqueWeeks.has(weekNumber)) {
                    if (dayOfWeek === 1) {
                        // Maandagen groen en enabled
                        applyStyling(dayElem, 'green', 'white', 'Beschikbaar voor boeking', 'white');
                    } else if (dayOfWeek > 1 && dayOfWeek < 6) {
                        // Andere weekdagen blauw en disabled
                        applyStyling(dayElem, '#32CD32', 'white', 'Weekdag binnen de aanvragen', 'green');
                        dayElem.classList.add('flatpickr-disabled');
                    }
                }
                if (!dayElem.classList.contains('flatpickr-disabled')) {
                    // Hover effect
                    dayElem.addEventListener('mouseenter', () => {
                        dayElem.style.transition = 'box-shadow 0.3s ease, transform 0.3s ease';
                        dayElem.style.boxShadow = '0 6px 12px rgba(0, 0, 0, 0.4)';
                        dayElem.style.borderRadius = '30px'; // Maak de hoeken ronder bij hover
                    });
        
                    dayElem.addEventListener('mouseleave', () => {
                        dayElem.style.boxShadow = 'none';
                        dayElem.style.transform = 'scale(1)';
                        dayElem.style.borderRadius = '30px'; // Herstel de cirkelvorm
                    });
        
                }
                const originalBorderColor = window.getComputedStyle(dayElem).borderColor;
                if (dayElem.classList.contains('selected')) {   
                    dayElem.style.fontWeight = 'bold';
                    dayElem.style.color = 'white';
                    dayElem.style.border = '2px solid white';
                    dayElem.style.background = 'linear-gradient(135deg, rgba(255, 215, 0, 0.7), rgba(8, 21, 64, 0.7))'; // Goud en donkerblauw
                    dayElem.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.4)'; // Iets donkerdere schaduw
                    
                }
            },
            firstDayOfWeek: 1
        });
    }

    // Event listener voor de knop "Bekijk aanvragen"
    let aanvragenData = {};  // Variabele om de aanvragen op te slaan

    document.querySelector('button[name="weekaanvragen"]').addEventListener('click', function () {
        const datumVeld = document.getElementById('start_date');
        const datumPicker = datumVeld._flatpickr;  // Verkrijg Flatpickr instance
        const geselecteerdeDatum = datumPicker.selectedDates[0];  // Geselecteerde datum
    
        if (!geselecteerdeDatum) {
            alert("Selecteer een datum!");
            return;
        }
    
        // Handmatige datumformattering zonder tijdzoneverschuiving
        const year = geselecteerdeDatum.getFullYear();
        const month = String(geselecteerdeDatum.getMonth() + 1).padStart(2, '0');
        const day = String(geselecteerdeDatum.getDate()).padStart(2, '0');
        const formattedDate = `${year}-${month}-${day}`;
    
        console.log("Geselecteerde datum:", geselecteerdeDatum);
        console.log("Geformatteerde datum:", formattedDate);
    
        // Verstuur de correct geformatteerde datum naar de backend via AJAX
        fetch('get_aanvragen_van_week.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ selectedDate: formattedDate })
        })
        .then(response => response.json())
        .then(data => {
            // Sla de ontvangen data op in aanvragenData
            aanvragenData = data;  // Hier worden de aanvragen opgeslagen
            displayRequests(data);
        })
        .catch(error => {
            console.error('Error fetching requests:', error);
        });
    });

    document.querySelector('button[name="In optie"]').addEventListener('click', function () {
        // Verstuur alleen de status 'In optie' naar de backend
        fetch('get_in_optie_aanvragen.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: 'In optie' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success === false) {
                alert(data.message);
                return;
            }
    
            // Sla de ontvangen data op in aanvragenData
            aanvragenData = data;  // Hier worden de aanvragen opgeslagen
            console.log(data);
            displayRequests(data);
        })
        .catch(error => {
            console.error('Error fetching "In optie" requests:', error);
        });
    });
    
    
    // Functie om de aanvragen weer te geven
   // Functie om de aanvragen weer te geven, gegroepeerd per datum
   function displayRequests(requests) {
    const requestsContainer = document.getElementById('requests-container');
    requestsContainer.innerHTML = '';  // Maak de container leeg

    if (requests.length === 0) {
        requestsContainer.textContent = "Geen aanvragen gevonden voor de geselecteerde week.";
        return;
    }

    // Eerst sorteren op bezoekdatum
    requests.sort((a, b) => new Date(a.bezoekdatum) - new Date(b.bezoekdatum));

    // Groepeer de aanvragen per datum
    let currentDate = '';
    let dateContainer;

    requests.forEach(request => {
        if (request.bezoekdatum !== currentDate) {
            currentDate = request.bezoekdatum;
            dasboardDate = new Date(request.bezoekdatum);

            const maandNamen = [
                'januari', 'februari', 'maart', 'april', 'mei', 'juni',
                'juli', 'augustus', 'september', 'oktober', 'november', 'december'
            ];
    

            const year = dasboardDate.getFullYear();
            const monthIndex = dasboardDate.getMonth(); // Maand index (0-11)
            const month = maandNamen[monthIndex]; // Haal de maandnaam op uit de array
            const day = String(dasboardDate.getDate()).padStart(2, '0');
            const displayDate = `${day} ${month} ${year}`; // Datum weergegeven als dd-mm-jjjj

            // Maak een container voor elke nieuwe datum
            dateContainer = document.createElement('div');
            dateContainer.classList.add('date-group');
            const dateHeader = document.createElement('h4');
            dateHeader.textContent = `Bezoekdatum: ${displayDate}`;
            dateContainer.appendChild(dateHeader);

            requestsContainer.appendChild(dateContainer);
        }

        // Maak een container voor de schoolaanvraag
        const requestDiv = document.createElement('div');
        requestDiv.classList.add('request-item', 'card');

        const schoolInfo = document.createElement('p');
        schoolInfo.textContent = `School: ${request.schoolnaam} Aantal leerlingen: ${request.aantal_leerlingen}`;
        requestDiv.appendChild(schoolInfo);

        const statusSelect = document.createElement('select');
        statusSelect.name = `status[${request.id}]`;
        ['In optie', 'Definitief', 'Afgewezen'].forEach(status => {
            const option = document.createElement('option');
            option.value = status;
            option.textContent = status;
            if (status === request.status) {
                option.selected = true;
            }
            statusSelect.appendChild(option);
        });
        requestDiv.appendChild(statusSelect);

        dateContainer.appendChild(requestDiv);
    });
}



    // Zorg ervoor dat de statusupdates naar PHP gestuurd worden bij het klikken op de submit-knop
    document.getElementById('submit-statuses').addEventListener('click', function () {
        const statusUpdates = [];

        aanvragenData.forEach(request => {
            const statusSelect = document.querySelector(`select[name="status[${request.id}]"]`);
            const nieuweStatus = statusSelect.value;

            if (nieuweStatus !== request.status) {  // Alleen verzenden als de status is gewijzigd
                statusUpdates.push({ id: request.id, status: nieuweStatus });
            }
        });

        if (statusUpdates.length === 0) {
            alert('Geen statussen zijn gewijzigd.');
            return;
        }

        // Verstuur de statusupdates naar de PHP backend
        fetch('update_aanvragen_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ updates: statusUpdates })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Statussen succesvol bijgewerkt');
            } else {
                alert('Er is iets fout gegaan: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error updating statuses:', error);
        });
    });

    // Functie om weeknummer te berekenen (ISO-week)
    function getWeekNumber(date) {
        const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
        const dayNum = d.getUTCDay() || 7;
        d.setUTCDate(d.getUTCDate() + 4 - dayNum);
        const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
        return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
    }

    // Stijlen resetten voor een dag
    function resetDayStyles(dayElem) {
        dayElem.style.backgroundColor = '';
        dayElem.style.color = '';
        dayElem.title = '';
        dayElem.style.border = '';
    }

    // Stijlen toepassen op een dag
    function applyStyling(dayElem, backgroundColor, textColor, title, border) {
        dayElem.style.backgroundColor = backgroundColor;
        dayElem.style.color = textColor;
        dayElem.title = title;

        if (border) {
            dayElem.style.border = `3px solid ${border}`;
        }
    }

    // AJAX-verzoek om kalenderdata te laden
    function loadCalendarData() {
        fetch('get_aanvragen_dashboarddata.php')
            .then(response => response.json())
            .then(data => {
                initFlatpickr(data);
            })
            .catch(error => {
                console.error('Error fetching calendar data:', error);
            });
    }

    loadCalendarData();  // Laad de kalenderdata bij paginalaad

    const toggles = document.querySelectorAll(".meerInformatieToggle");

    toggles.forEach(toggle => {
        toggle.addEventListener("click", function(event) {
            event.preventDefault(); // Voorkom dat de pagina scrollt naar de top
            
            // Haal het doel-element op via de data-target attribuut
            const targetId = toggle.getAttribute("data-target");
            const targetContent = document.getElementById(targetId);
            
            // Toggle de "open" class om de inhoud te tonen of verbergen
            targetContent.classList.toggle("open");

            // Pas de tekst van de toggle-link aan
            if (targetContent.classList.contains("open")) {
                toggle.querySelector("span").textContent = "Verberg ";
            } else {
                // Voeg hier de check toe voor bezoektijdenInfo
                if (targetId === "aanvragenInfo") {
                    toggle.querySelector("span").textContent = "Meer informatie over het bekijken van de aanvragen";
                } else if (targetId =="aanvrageninoptieInfo"){
                    toggle.querySelector("span").textContent = "Meer informatie over het bekijken van de aanvragen in optie";
                }
            }
        });
    });

    document.getElementById('Uitlog_Dashboard').addEventListener('click', function(e) {
        e.preventDefault(); // Zorg ervoor dat de standaard actie niet wordt geblokkeerd.
        window.location.href = 'logout.php'; // navigeer naar de logout pagina
        
    });
    
});


