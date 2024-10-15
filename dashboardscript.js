document.addEventListener('DOMContentLoaded', function () {
    
    function initFlatpickr(agendaData) {
        const uniqueWeeks = new Set(agendaData.map(item => item.weeknummer));

        flatpickr("#start_date", {
            locale: "nl",
            weekNumbers: true,
            dateFormat: "Y-m-d",
            disable: [
                function (date) {
                    const weekNumber = getWeekNumber(date);
                    return !uniqueWeeks.has(weekNumber) || date.getDay() === 0 || date.getDay() === 6;
                }
            ],
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                const weekNumber = getWeekNumber(dayElem.dateObj);
                const dayOfWeek = dayElem.dateObj.getDay();
                resetDayStyles(dayElem);
                if (uniqueWeeks.has(weekNumber)) {
                    if (dayOfWeek === 1) {
                        applyStyling(dayElem, 'green', 'white', 'Beschikbaar voor boeking', 'white');
                    } else if (dayOfWeek > 1 && dayOfWeek < 6) {
                        applyStyling(dayElem, 'blue', 'white', 'Weekdag binnen de aanvragen', 'green');
                        dayElem.classList.add('flatpickr-disabled');
                    }
                }
            }
        });
    }

    // Functie om aanvragen op te halen bij klikken op "Bekijk aanvragen"
    document.getElementById('submit-date').addEventListener('click', function() {
        const datumVeld = document.getElementById('start_date');
        const datumPicker = datumVeld._flatpickr;  // Verkrijg Flatpickr instance
        const geselecteerdeDatum = datumPicker.selectedDates[0];  // Geselecteerde datum

        if (!geselecteerdeDatum) {
            alert("Selecteer een datum!");
            return;
        }

        // Verstuur de geselecteerde datum via AJAX
        fetch('get_aanvragen_van_week.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ selectedDate: geselecteerdeDatum.toISOString().split('T')[0] })
        })
        .then(response => response.json())
        .then(data => {
            // Toon de aanvragen van de geselecteerde week
            displayRequests(data);
        })
        .catch(error => {
            console.error('Error fetching requests:', error);
        });
    });

    // Aanvragen weergeven
    function displayRequests(requests) {
        const requestsContainer = document.getElementById('requests-container');
        requestsContainer.innerHTML = '';

        if (requests.length === 0) {
            requestsContainer.textContent = "Geen aanvragen gevonden voor de geselecteerde week.";
            return;
        }

        requests.forEach(request => {
            const requestDiv = document.createElement('div');
            requestDiv.classList.add('request-item');

            const schoolInfo = document.createElement('p');
            schoolInfo.textContent = `School: ${request.schoolnaam} | Aantal leerlingen: ${request.aantal_leerlingen}`;
            requestDiv.appendChild(schoolInfo);

            const statusSelect = document.createElement('select');
            statusSelect.name = `status[${request.id}]`;
            ['In Optie', 'Definitief', 'Afgewezen'].forEach(status => {
                const option = document.createElement('option');
                option.value = status;
                option.textContent = status;
                if (status === request.status) {
                    option.selected = true;
                }
                statusSelect.appendChild(option);
            });
            requestDiv.appendChild(statusSelect);

            requestsContainer.appendChild(requestDiv);
        });
    }

    // Weeknummerberekening (ISO-week)
    function getWeekNumber(date) {
        const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
        const dayNum = d.getUTCDay() || 7;
        d.setUTCDate(d.getUTCDate() + 4 - dayNum);
        const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
        return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
    }

    // Kalenderdata laden via AJAX
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
});
