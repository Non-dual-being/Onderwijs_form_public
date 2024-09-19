
# flatpicker angenda die de gegevens ophaalde van kalander 
```jsx
function initFlatpickr(unavailableDates, limitedAvailabilityDates, fullyBookedDates, availableDatesWithCapacity) {
        flatpickr("#bezoekdatum", {
            locale: "nl",  // Locale Nederlands instellen
            enableTime: false,
            dateFormat: "Y-m-d",
            disable: unavailableDates.map(date => new Date(date)),  // Onbeschikbare datums disablen
            appendTo: document.body,
            
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                const dayTimestamp = dayElem.dateObj.setHours(0, 0, 0, 0); // Normale timestamp zonder tijd
    
                resetDayStyles(dayElem);
    
                // Vergelijk op basis van timestamps
                const unavailableTimestamps = unavailableDates.map(date => new Date(date).setHours(0, 0, 0, 0));
                const fullyBookedTimestamps = fullyBookedDates.map(date => new Date(date).setHours(0, 0, 0, 0));
    
                // Maak een aangepast object voor beschikbaarheid met de juiste timestamps
                const availableDatesWithAdjustedCapacity = {};
                Object.entries(limitedAvailabilityDates).forEach(([key, value]) => {
                    const timestampKey = new Date(key).setHours(0, 0, 0, 0);
                    availableDatesWithAdjustedCapacity[timestampKey] = value; // Waarde behouden
                });
                
                const availableTimestamps = Object.keys(availableDatesWithCapacity).map(date => new Date(date).setHours(0, 0, 0, 0));
    
                if (unavailableTimestamps.includes(dayTimestamp)) {
                    applyStyling(dayElem, 'gray', 'white', 'Onbeschikbaar');
                } else if (fullyBookedTimestamps.includes(dayTimestamp)) {
                    applyStyling(dayElem, 'red', 'white', 'Volgeboekt');
                } else if (availableDatesWithAdjustedCapacity[dayTimestamp]) {
                    const beschikbarePlaatsen = 160 - parseInt(availableDatesWithAdjustedCapacity[dayTimestamp], 10); // Correcte plaatsen ophalen
                    console.log(`Beschikbare plaatsen voor ${dayTimestamp}: ${beschikbarePlaatsen}`);
                    applyStyling(dayElem, 'orange', 'black', `Beperkt beschikbaar (${beschikbarePlaatsen} plaatsen beschikbaar)`);
                } else if (availableTimestamps.includes(dayTimestamp)) {
                    applyStyling(dayElem, 'green', 'white', 'Beschikbaar');
                }
            }
        });
    }
    
    function resetDayStyles(dayElem) {
        dayElem.style.backgroundColor = '';  // Reset achtergrondkleur
        dayElem.style.color = '';            // Reset tekstkleur
        dayElem.title = '';                  // Reset tooltip
    }
    
    function applyStyling(dayElem, backgroundColor, textColor, title) {
        dayElem.style.backgroundColor = backgroundColor;
        dayElem.style.color = textColor;
        dayElem.title = title;
    }
    
    function loadCalendarData() {
        fetch('get_aanvragen_data.php')
            .then(response => response.json())
            .then(data => {
                const unavailableDates = [];
                const limitedAvailabilityDates = {};
                const fullyBookedDates = [];
                const availableDatesWithCapacity = {};
    
                data.forEach(item => {
                    const isoDate = new Date(item.datum).toISOString().split('T')[0];
                    if (item.status === 'onbeschikbaar' || item.status === 'niet beschikbaar') {
                        unavailableDates.push(isoDate);
                    } else if (item.status === 'volgeboekt') {
                        fullyBookedDates.push(isoDate);
                    } else if (item.status === 'beperkt beschikbaar') {
                        limitedAvailabilityDates[isoDate] = item.totale_leerlingen; // Hier haal je totale_leerlingen op
                    } else if (item.status === 'beschikbaar') {
                        availableDatesWithCapacity[isoDate] = item.totale_leerlingen; // Beschikbare plaatsen
                    }
                });
    
                console.log("Beschikbare data ontvangen:", availableDatesWithCapacity);
                initFlatpickr(unavailableDates, limitedAvailabilityDates, fullyBookedDates, availableDatesWithCapacity);
            })
            .catch(error => console.error('Error fetching calendar data:', error));
    }
    
    loadCalendarData();  // Kalenderdata wordt geladen bij het laden van de pagina
    

```