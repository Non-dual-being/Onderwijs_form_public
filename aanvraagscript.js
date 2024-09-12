document.addEventListener('DOMContentLoaded', function () {
    const onderwijsModules = {
        primairOnderwijs: {
            standaard: ["Klimaat-Experience", "Klimparcours", "Voedsel-Innovatie", "Dynamische-Globe"],
            keuze: ["Minecraft-klimaatspeurtocht", "Earth-Watch"]
        },
        voortgezetOnderbouw: {
            standaard: ["Klimaat-Experience", "Voedsel-Innovatie", "Dynamische-Globe", "Earth-Watch"],
            keuze: ["Minecraft-windenergiespeurtocht", "Stop-de-Klimaat-Klok"]
        },
        voortgezetBovenbouw: {
            standaard: ["Klimaat-Experience", "Voedsel-Innovatie", "Dynamische-Globe", "Earth-Watch", "Stop-de-Klimaat-Klok"],
            keuze: ["Crisismanagement"]
        }
    };

    const moduleDetails = {
        "Minecraft-Klimaatspeurtocht": {
            locatie: "Minecraftzaal",
            backup_locatie: "Virtual Flow zaal",
            begeleiding: "begeleid"
        },
        "Earth-Watch": {
            locatie: "Bus",
            backup_locatie: "Building Blocks Dome",
            begeleiding: "begeleid"
        },
        "Klimaat-Experience": {
            locatie: "GeoPlein",
            backup_locatie: "GeoPlein",
            begeleiding: "begeleid"
        },
        "Klimparcours": {
            locatie: "Speurtuin",
            backup_locatie: "Speurtuin",
            begeleiding: "Onbegeleid"
        },
        "Voedsel-Innovatie": {
            locatie: "Auditorium",
            backup_locatie: "Auditorium",
            begeleiding: "begeleid"
        },
        "Dynamische-Globe": {
            locatie: "Buskruid bios",
            backup_locatie: "Buskruid bios",
            begeleiding: "begeleid"
        },
        "Minecraft-Windenergiespeurtocht": {
            locatie: "Minecraftzaal",
            backup_locatie: "Virtual Flow zaal",
            begeleiding: "begeleid"
        },
        "Stop-de-Klimaat Klok": {
            locatie: "Climate Cubs Doolhof",
            backup_locatie: "Climate Cubs Doolhof",
            begeleiding: "begeleid"
        },
        "Crisismanagement": {
            locatie: "Virtual Flow zaal",
            backup_locatie: "Power House",
            begeleiding: "begeleid"
        }
    };

    const beschikbareRooster = {
        primairOnderwijs: {
            "Klimaat-Experience": {
                "3": "Voorbeeld_PrimairOnderwijs_KlimaatExperience_3.png",
                "4": "Voorbeeld_PrimairOnderwijs_KlimaatExperience_4.png",
                "5": "Voorbeeld_PrimairOnderwijs_KlimaatExperience_5.png",
                "6": "Voorbeeld_PrimairOnderwijs_KlimaatExperience_6.png",
                "7": "Voorbeeld_PrimairOnderwijs_KlimaatExperience_7.png",
                "8": "Voorbeeld_PrimairOnderwijs_KlimaatExperience_8.png",
                "9": "Voorbeeld_PrimairOnderwijs_KlimaatExperience_9.png",
                "10": "Voorbeeld_PrimairOnderwijs_KlimaatExperience_10.png"
            },
            "Minecraft-Klimaatspeurtocht": {
                "3": "Voorbeeld_PrimairOnderwijs_MinecraftKlimaatSpeurtocht_3.png",
                "4": "Voorbeeld_PrimairOnderwijs_MinecraftKlimaatSpeurtocht_4.png",
                "5": "Voorbeeld_PrimairOnderwijs_MinecraftKlimaatSpeurtocht_5.png",
                "6": "Voorbeeld_PrimairOnderwijs_MinecraftKlimaatSpeurtocht_6.png",
                "7": "Voorbeeld_PrimairOnderwijs_MinecraftKlimaatSpeurtocht_7.png",
                "8": "Voorbeeld_PrimairOnderwijs_MinecraftKlimaatSpeurtocht_8.png",
                "9": "Voorbeeld_PrimairOnderwijs_MinecraftKlimaatSpeurtocht_9.png",
                "10": "Voorbeeld_PrimairOnderwijs_MinecraftKlimaatSpeurtocht_10.png"
            },
            "Earth-Watch": {
                "3": "Voorbeeld_PrimairOnderwijs_EarthWatch_3.png",
                "4": "Voorbeeld_PrimairOnderwijs_EarthWatch_4.png",
                "5": "Voorbeeld_PrimairOnderwijs_EarthWatch_5.png",
                "6": "Voorbeeld_PrimairOnderwijs_EarthWatch_6.png",
                "7": "Voorbeeld_PrimairOnderwijs_EarthWatch_7.png",
                "8": "Voorbeeld_PrimairOnderwijs_EarthWatch_8.png",
                "9": "Voorbeeld_PrimairOnderwijs_EarthWatch_9.png",
                "10": "Voorbeeld_PrimairOnderwijs_EarthWatch_10.png"
            }
        },
        voortgezetOnderbouw: {
            "Minecraft-Windenergiespeurtocht": {
                "3": "Voorbeeld_VOOnderbouw_MinecraftWindenergie_3.png",
                "4": "Voorbeeld_VOOnderbouw_MinecraftWindenergie_4.png",
                "5": "Voorbeeld_VOOnderbouw_MinecraftWindenergie_5.png",
                "6": "Voorbeeld_VOOnderbouw_MinecraftWindenergie_6.png",
                "7": "Voorbeeld_VOOnderbouw_MinecraftWindenergie_7.png",
                "8": "Voorbeeld_VOOnderbouw_MinecraftWindenergie_8.png",
                "9": "Voorbeeld_VOOnderbouw_MinecraftWindenergie_9.png",
                "10": "Voorbeeld_VOOnderbouw_MinecraftWindenergie_10.png"
            },
            "Stop-de-Klimaat-Klok": {
                "3": "Voorbeeld_VOOnderbouw_StopDeKlimaatKlok_3.png",
                "4": "Voorbeeld_VOOnderbouw_StopDeKlimaatKlok_4.png",
                "5": "Voorbeeld_VOOnderbouw_StopDeKlimaatKlok_5.png",
                "6": "Voorbeeld_VOOnderbouw_StopDeKlimaatKlok_6.png",
                "7": "Voorbeeld_VOOnderbouw_StopDeKlimaatKlok_7.png",
                "8": "Voorbeeld_VOOnderbouw_StopDeKlimaatKlok_8.png",
                "9": "Voorbeeld_VOOnderbouw_StopDeKlimaatKlok_9.png",
                "10": "Voorbeeld_VOOnderbouw_StopDeKlimaatKlok_10.png"
            }
        },
        voortgezetBovenbouw: {
            "Crisismanagement": {
                "3": "Voorbeeld_VOBovenbouw_Crisismanagement_3.png",
                "4": "Voorbeeld_VOBovenbouw_Crisismanagement_4.png",
                "5": "Voorbeeld_VOBovenbouw_Crisismanagement_5.png",
                "6": "Voorbeeld_VOBovenbouw_Crisismanagement_6.png",
                "7": "Voorbeeld_VOBovenbouw_Crisismanagement_7.png",
                "8": "Voorbeeld_VOBovenbouw_Crisismanagement_8.png",
                "9": "Voorbeeld_VOBovenbouw_Crisismanagement_9.png",
                "10": "Voorbeeld_VOBovenbouw_Crisismanagement_10.png"
            }
        }
    };

    function werkKeuzeModulesBij1() {
        const onderwijsNiveau1 = document.getElementById('onderwijsNiveau').value;
        const keuzeModuleSelect1 = document.getElementById('keuzeModule');

        // Reset de keuzemodules
        keuzeModuleSelect1.innerHTML = '';

        if (onderwijsNiveau1 in onderwijsModules) {
            // Vul de keuzemodules in
            onderwijsModules[onderwijsNiveau1].keuze.forEach(module1 => {
                const optie1 = document.createElement('option');
                optie1.value = module1;
                optie1.textContent = module1;
                keuzeModuleSelect1.appendChild(optie1);
            });
        }

        werkGeselecteerdeModulesWeergaveBij2();
    }

    function werkGeselecteerdeModulesWeergaveBij2() {
        const onderwijsNiveau2 = document.getElementById('onderwijsNiveau').value;
        const keuzeModule2 = document.getElementById('keuzeModule').value;

        // Werk standaardmodules bij
        const standaardModulesLijst2 = document.getElementById('standaardModulesLijst');
        standaardModulesLijst2.innerHTML = '';
        if (onderwijsNiveau2 in onderwijsModules) {
            onderwijsModules[onderwijsNiveau2].standaard.forEach(module2 => {
                const lijstItem2 = document.createElement('li');
                lijstItem2.textContent = module2;
                standaardModulesLijst2.appendChild(lijstItem2);
            });
        }

        // Werk gekozen keuzemodule bij
        const gekozenKeuzeModule2 = document.getElementById('gekozenKeuzeModule');
        gekozenKeuzeModule2.textContent = keuzeModule2 || 'Geen keuzevak geselecteerd';
    }

    function haalRoosterOp() {
        const onderwijsNiveau = document.getElementById('onderwijsNiveau').value;
        const keuzeModule = document.getElementById('keuzeModule').value;
        const aantalLeerlingen3 = parseInt(document.getElementById('aantalLeerlingen').value, 10); // Haal de waarde op van het invoerveld
        let groepAantal3 = '';
    
        if (aantalLeerlingen3 >= 40 && aantalLeerlingen3 <= 50) {
            groepAantal3 = 3;
        } else if (aantalLeerlingen3 >= 51 && aantalLeerlingen3 <= 65) {
            groepAantal3 = 4;
        } else if (aantalLeerlingen3 >= 66 && aantalLeerlingen3 <= 80) {
            groepAantal3 = 5;
        } else if (aantalLeerlingen3 >= 81 && aantalLeerlingen3 <= 100) {
            groepAantal3 = 6;
        } else if (aantalLeerlingen3 >= 101 && aantalLeerlingen3 <= 120) {
            groepAantal3 = 7;
        } else if (aantalLeerlingen3 >= 121 && aantalLeerlingen3 <= 130) {
            groepAantal3 = 8;
        } else if (aantalLeerlingen3 >= 131 && aantalLeerlingen3 <= 150) {
            groepAantal3 = 9;
        } else if (aantalLeerlingen3 >= 151 && aantalLeerlingen3 <= 160) {
            groepAantal3 = 10;
        }
    
        document.getElementById('groepAantal').textContent = groepAantal3;
    
        // Controleer of alle vereiste velden correct zijn ingevuld
        if (onderwijsNiveau && keuzeModule && aantalLeerlingen3 >= 40 && aantalLeerlingen3 <= 160) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'Get_Rooster.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    const roosterAfbeeldingContainer = document.getElementById('roosterAfbeeldingContainer');
                    const roosterAfbeelding = document.getElementById('roosterAfbeelding');
                    const downloadButton = document.getElementById('downloadRooster');
            
                    if (response.success) {
                        // Toon de afbeelding
                        roosterAfbeeldingContainer.style.display = 'block';
                        roosterAfbeelding.src = response.afbeelding; // Gebruik de exacte afbeelding-URL
                        downloadButton.style.display = 'block';
            
                        // Download-knop voor het PDF-bestand
                        downloadButton.onclick = function (event) {
                            event.preventDefault();
                            const link = document.createElement('a');
                            link.href = response.pdf; // Gebruik het PDF-pad zoals opgegeven door de server
                            link.target = '_blank'; // Open de PDF in een nieuw tabblad
                            link.click();
                        };
                    } else {
                        // Verberg de afbeelding en download-knop als er geen afbeelding is
                        roosterAfbeeldingContainer.style.display = 'none';
                        downloadButton.style.display = 'none';
                    }
                }
            };  
            // Verstuur de gegevens naar PHP met de correcte waarde van aantal leerlingen
            xhr.send(`schooltype=${onderwijsNiveau}&lesmodule=${keuzeModule}&aantalleerlingen=${aantalLeerlingen3}`);
        } else {
            document.getElementById('roosterAfbeeldingContainer').style.display = 'none';
            document.getElementById('downloadRooster').style.display = 'none';
        }
    }
      
    document.getElementById('onderwijsNiveau').addEventListener('change', () => {
        werkKeuzeModulesBij1();
        haalRoosterOp(); // Probeer het rooster op te halen
    });
    document.getElementById('keuzeModule').addEventListener('change', () => {
        werkGeselecteerdeModulesWeergaveBij2();
        haalRoosterOp(); // Probeer -het rooster op te halen
    });
   
    document.getElementById('aantalLeerlingen').addEventListener('input', () => {
        haalRoosterOp(); // Probeer het rooster op te halen
    });

    document.getElementById('aantalLeerlingen').addEventListener('input', function () {
        const aantalLeerlingen = this.value;
        const foutMelding = document.getElementById('aantalLeerlingenFout');
    
        // Controleer of het aantal leerlingen binnen de geldige limiet ligt
        if (aantalLeerlingen === '') {
            foutMelding.style.display = 'none';
            this.setCustomValidity('');
        } else if (aantalLeerlingen < 40 || aantalLeerlingen > 160) {
            foutMelding.style.display = 'block';
            this.setCustomValidity('Het aantal leerlingen moet tussen 40 en 160 liggen.');
        } else {
            // Als het aantal geldig is, verberg de foutmelding en haal het rooster op
            foutMelding.style.display = 'none';
            this.setCustomValidity('');
    
            // Roep hier de functie aan om het rooster op te halen
            haalRoosterOp();
        }
    });
    
    // Initial update based on the default
    werkKeuzeModulesBij1();
    haalRoosterOp();

    let hoverTimeout;

    const aankomsttijdInput = document.getElementById('aankomsttijd');
    const vertrektijdInput = document.getElementById('vertrektijd');

    aankomsttijdInput.addEventListener('mouseenter', () => {
        scheduleHoverMessage4(aankomsttijdInput.dataset.hoverMessage, aankomsttijdInput);
    });
    aankomsttijdInput.addEventListener('mouseleave', cancelHoverMessage5);

    vertrektijdInput.addEventListener('mouseenter', () => {
        scheduleHoverMessage4(vertrektijdInput.dataset.hoverMessage, vertrektijdInput);
    });
    vertrektijdInput.addEventListener('mouseleave', cancelHoverMessage5);

    function scheduleHoverMessage4(message, element) {
        clearTimeout(hoverTimeout);
        hoverTimeout = setTimeout(() => showHoverMessage6(message, element), 200);
    }

    function cancelHoverMessage5() {
        clearTimeout(hoverTimeout);
        hideHoverMessage7();
    }

    function showHoverMessage6(message, element) {
        let hoverMessageElement = document.querySelector('.hover-message');
        if (!hoverMessageElement) {
            hoverMessageElement = document.createElement('div');
            hoverMessageElement.className = 'hover-message';
            document.body.appendChild(hoverMessageElement);
        }
        hoverMessageElement.textContent = message;
        hoverMessageElement.style.display = 'block';
        hoverMessageElement.style.opacity = '1';

        const rect = element.getBoundingClientRect();
        const scrollY = window.scrollY || window.pageYOffset;

        hoverMessageElement.style.left = `${rect.left + window.scrollX}px`;
        hoverMessageElement.style.top = `${rect.top + scrollY - hoverMessageElement.offsetHeight + 10}px`;
    }

    function hideHoverMessage7() {
        const hoverMessageElement = document.querySelector('.hover-message');
        if (hoverMessageElement) {
            hoverMessageElement.style.opacity = '0';
            setTimeout(() => {
                hoverMessageElement.style.display = 'none';
            }, 300);
        }
    }

    const foodInputs = document.querySelectorAll('input[type="checkbox"][name="snack"], input[type="checkbox"][name="lunch"]');
    const foodSummaryDiv = document.getElementById('foodSummary');
    const totalPriceSpan = document.getElementById('totalPrice');
    const bezoekDiv = document.getElementById('bezoek');

    foodInputs.forEach(input => {
        input.addEventListener('change', function () {
            const amountInput = input.closest('.snack-option').querySelector('input[type="number"]');
            if (amountInput) {
                amountInput.disabled = !this.checked;
                if (!this.checked) {
                    amountInput.value = 0;
                }
            }
            updateFoodSummary9();
        });

        const amountInput = input.closest('.snack-option').querySelector('input[type="number"]');
        if (amountInput) {
            amountInput.addEventListener('input', updateFoodSummary9);
        }
    });

    function calculateVisitPrice8() {
        const aantalLeerlingen = parseInt(document.getElementById('aantalLeerlingen').value, 10) || 0;
        const totaalBegeleiders = parseInt(document.getElementById('totaalbegeleiders').value, 10) || 0;
        const prijsPerLeerling = 20;

        const gratisBegeleiders = Math.floor(aantalLeerlingen / 8);
        const teBetalenBegeleiders = Math.max(0, totaalBegeleiders - gratisBegeleiders);

        const bezoekPrijs = (aantalLeerlingen * prijsPerLeerling) + (teBetalenBegeleiders * prijsPerLeerling);
        return bezoekPrijs.toFixed(2);
    }

    function updateFoodSummary9() {
        let totalPrice = 0;
        foodSummaryDiv.innerHTML = '';

        foodInputs.forEach(input => {
            const amountInput = input.closest('.snack-option').querySelector('input[type="number"]');
            if (input.checked && amountInput && amountInput.value > 0) {
                const itemTotal = (input.value * amountInput.value).toFixed(2);
                totalPrice += parseFloat(itemTotal);
                const itemSummary = document.createElement('div');
                itemSummary.className = 'summary-item';
                const itemName = input.labels[0].innerText.split(':')[0]; // Extract the name before the colon
                itemSummary.innerHTML = `<span>${amountInput.value} ${itemName}:</span><span>€${itemTotal}</span>`;
                foodSummaryDiv.appendChild(itemSummary);
            } else if (input.checked && !amountInput) {
                const itemSummary = document.createElement('div');
                itemSummary.className = 'summary-item';
                itemSummary.innerHTML = `<span>${input.labels[0].innerText}</span>`;
                foodSummaryDiv.appendChild(itemSummary);
            }
        });

        const bezoekPrijs = calculateVisitPrice8();
        bezoekDiv.innerHTML = `<span>Bezoekprijs:</span><span>€${bezoekPrijs}</span>`;
        totalPrice += parseFloat(bezoekPrijs);

        totalPriceSpan.textContent = totalPrice.toFixed(2);
    }

    document.getElementById('aantalLeerlingen').addEventListener('input', updateFoodSummary9);
    document.getElementById('totaalbegeleiders').addEventListener('input', updateFoodSummary9);

    updateFoodSummary9(); // Initial call to set the summary

    // Functie om formuliergegevens te verzamelen en versturen
    document.getElementById('onderwijsFormulier').addEventListener('submit', function (event) {
        event.preventDefault(); // Voorkom standaard formulier verzending
        // Verzamel de gegevens en verzend het formulier

        // Functie om te helpen met padding van strings
        function padString(label, value, padLength = 60) {
            const padding = ' '.repeat(padLength - label.length);
            return label + padding + value;
        }

        function getLeftPadding(label, padLength = 40) {
            return ' '.repeat(padLength - label.length);
        }

        const onderwijsNiveauMapping = {
            primairOnderwijs: "Primair Onderwijs",
            voortgezetOnderbouw: "Voortgezet Onderwijs - Onderbouw",
            voortgezetBovenbouw: "Voortgezet Onderwijs - Bovenbouw"
        };

        const onderwijsNiveau = document.getElementById('onderwijsNiveau').value;
        const keuzeModule = document.getElementById('keuzeModule').value;
        const aantalLeerlingen = document.getElementById('aantalLeerlingen').value;
        const contactpersoonvoornaam = document.getElementById('contactpersoonvoornaam').value;
        const contactpersoonachternaam = document.getElementById('contactpersoonachternaam').value;
        const emailadres = document.getElementById('emailadres').value;
        const telefoonnummer = document.getElementById('telefoonnummer').value;
        const totaalbegeleiders = document.getElementById('totaalbegeleiders').value;
        const niveauLeerjaar = document.getElementById('niveauLeerjaar').value;
        const schoolnaam = document.getElementById('schoolnaam').value;
        const adres = document.getElementById('adres').value;
        const postcodeplaats = document.getElementById('postcodeplaats').value;
        const bezoekdatum = new Date(document.getElementById('bezoekdatum').value).toLocaleDateString('nl-NL', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
        const aankomsttijd = document.getElementById('aankomsttijd').value;
        const vertrektijd = document.getElementById('vertrektijd').value;
        const hoekentGeoFort = document.getElementById('hoekentGeoFort').value;
        const vragenOpmerkingen = document.getElementById('vragenOpmerkingen').value;

        const totalPrice2 = parseFloat(document.getElementById('totalPrice').innerText).toFixed(2);
        const bezoekPrice2 = document.getElementById('bezoek').innerText.trim().replace(/\n/g, ' ').replace("Bezoekprijs: ", "");

        // Verzamel de bestelde snacks en lunches individueel
        const snacks = [
            { name: "Remise break", amount: document.getElementById('remiseBreakAantal').value, price: 2.60 },
            { name: "Kazerne break", amount: document.getElementById('kazerneBreakAantal').value, price: 2.60 },
            { name: "Fortgracht break", amount: document.getElementById('fortgrachtBreakAantal').value, price: 2.60 },
            { name: "Waterijsje", amount: document.getElementById('waterijsjeAantal').value, price: 1.00 },
            { name: "Pakje drinken", amount: document.getElementById('pakjeDrinkenAantal').value, price: 1.00 }
        ];

        const lunches = [
            { name: "Remiselunch", amount: document.getElementById('remiseLunchAantal').value, price: 5.20 },
            { name: "Twee belegde broodjes en fruitsap", amount: document.getElementById('tweeBroodjesAantal').value, price: 10.30 }
        ];

        const voorbeeldLabel = ' - Achternaam Contactpersoon:   ';
        const leftPadding = getLeftPadding(voorbeeldLabel);

        // Genereer het overzicht voor de GeoFortdocent
        const overzichtGeoFortMedewerker = `
Hieronder een totaaloverzicht van de aanvraag:
Bezoekgegevens
${padString(' - Voornaam Contactpersoon:', contactpersoonvoornaam)}
${padString(' - Achternaam Contactpersoon:', contactpersoonachternaam)}
${padString(' - E-mailadres:', emailadres)}
${padString(' - Telefoonnummer:', telefoonnummer)}
${padString(' - Totaal begeleiders:', totaalbegeleiders)}
${padString(' - Niveau en Leerjaar:', niveauLeerjaar)}
${padString(' - Schoolnaam:', schoolnaam)}
${padString(' - Bezoekdatum:', bezoekdatum)}
${padString(' - Hoe kent u GeoFort:', hoekentGeoFort)}
${padString(' - Onderwijsniveau:', onderwijsNiveauMapping[onderwijsNiveau])}
${padString(' - Gekozen lesmodule:', keuzeModule)}
${padString(' - Aantal leerlingen:', aantalLeerlingen)}
${padString(' - Vragen en opmerkingen:', vragenOpmerkingen)}\n
Prijs overzicht
${padString(' - Bezoekprijs:', `${bezoekPrice2}`)}
${snacks.some(snack => snack.amount > 0) ? 'Bestelde Snacks\n' : ''}${snacks.filter(snack => snack.amount > 0).map(snack => `${leftPadding}${padString(`- ${snack.amount} ${snack.name}:`, `€${(snack.amount * snack.price).toFixed(2)}`)}`).join('\n')}
${lunches.some(lunch => lunch.amount > 0) ? 'Bestelde Lunches\n' : ''}${lunches.filter(lunch => lunch.amount > 0).map(lunch => `${leftPadding}${padString(`- ${lunch.amount} ${lunch.name}:`, `€${(lunch.amount * lunch.price).toFixed(2)}`)}`).join('\n')}
${padString('Totaalprijs:',`€${totalPrice2}`)}
`;

        // Genereer het overzicht voor de aanvrager
        const overzichtAanvrager = `
Beste ${contactpersoonvoornaam},

Dank voor je aanvraag voor een schoolbezoek aan het GeoFort. 

Je wil graag op ${bezoekdatum} langskomen met ${aantalLeerlingen} leerlingen en ${totaalbegeleiders} begeleiders: we kijken naar je komst uit.

Hieronder een totaaloverzicht van de aanvraag:
Bezoekgegevens
${padString(' - Voornaam Contactpersoon:', contactpersoonvoornaam)}
${padString(' - Achternaam Contactpersoon:', contactpersoonachternaam)}
${padString(' - E-mailadres:', emailadres)}
${padString(' - Telefoonnummer:', telefoonnummer)}
${padString(' - Totaal begeleiders:', totaalbegeleiders)}
${padString(' - Niveau en Leerjaar:', niveauLeerjaar)}
${padString(' - Schoolnaam:', schoolnaam)}
${padString(' - Bezoekdatum:', bezoekdatum)}
${padString(' - Hoe kent u GeoFort:', hoekentGeoFort)}
${padString(' - Onderwijsniveau:', onderwijsNiveauMapping[onderwijsNiveau])}
${padString(' - Gekozen lesmodule:', keuzeModule)}
${padString(' - Aantal leerlingen:', aantalLeerlingen)}
${padString(' - Vragen en opmerkingen:', vragenOpmerkingen)}\n
Prijs overzicht
${padString(' - Bezoekprijs:', `${bezoekPrice2}`)}
${snacks.some(snack => snack.amount > 0) ? 'Bestelde Snacks\n' : ''}${snacks.filter(snack => snack.amount > 0).map(snack => `${leftPadding}${padString(` - ${snack.amount} ${snack.name}:`, `€${(snack.amount * snack.price).toFixed(2)}`)}`).join('\n')}
${lunches.some(lunch => lunch.amount > 0) ? 'Bestelde Lunches\n' : ''}${lunches.filter(lunch => lunch.amount > 0).map(lunch => `${leftPadding}${padString(` - ${lunch.amount} ${lunch.name}:`, `€${(lunch.amount * lunch.price).toFixed(2)}`)}`).join('\n')}
${padString('Totaalprijs:', `€${totalPrice2}`)}

Je aanvraag wordt zo verwerkt en we nemen zo spoedig mogelijk contact met je op.

Met educatieve groet,

Het GeoFort Team.
`;
    const blob = new Blob([overzichtAanvrager], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);

    // Maak een download-link
    const a = document.createElement('a');
    a.href = url;
    a.download = 'aanvraag_overzicht.txt';
    a.click();

    // Revoke the URL after downloading
    URL.revokeObjectURL(url);
    });
});
