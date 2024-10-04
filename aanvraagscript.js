document.addEventListener('DOMContentLoaded', function () {
    const onderwijsModules = {
        primairOnderwijs: {
            standaard: ["Klimaat-Experience", "Klimparcours", "Voedsel-Innovatie", "Dynamische-Globe"],
            keuze: ["Minecraft-Klimaatspeurtocht", "Earth-Watch","Stop-de-Klimaat-Klok"]
        },
        voortgezetOnderbouw: {
            standaard: ["Klimaat-Experience", "Voedsel-Innovatie", "Dynamische-Globe", "Earth-Watch"],
            keuze: ["Minecraft-Windenergiespeurtocht", "Stop-de-Klimaat-Klok","Minecraft-Programmeren"]
        },
        voortgezetBovenbouw: {
            standaard: ["Klimaat-Experience", "Voedsel-Innovatie", "Dynamische-Globe", "Earth-Watch", "Stop-de-Klimaat-Klok"],
            keuze: ["Crisismanagement"]
        }
    };

    function valideerInvoer(veld, foutElement, validatieFunctie) {
        let waarde;
    
        // Als de validatiefunctie 'verzendknopFoutMelding' is, haal de waarde uit het foutElement in plaats van het veld
        if (validatieFunctie.name === "verzendknopFoutMelding") {
            waarde = foutElement.textContent.trim(); // Haal de waarde uit het foutElement
            console.log("Gebruik foutElement waarde:", waarde);
            console.log("Foutelemetndiv", foutElement);
            console.log("Foutelemetndiv", veld);
        } else {
            waarde = veld.value.trim(); // Haal de waarde uit het invoerveld zoals gewoonlijk
        }
        
        console.log("Waarde van validatie:", waarde);
        let foutmelding = validatieFunctie(waarde); // Roep de specifieke validatiefunctie aan
        
        if (foutmelding) {
            if (validatieFunctie.name === "verzendknopFoutMelding") {
                veld.focus();
                setTimeout(() => {
                    toonFoutmelding(foutElement, foutmelding, veld);
                },600);
            } else{
                toonFoutmelding(foutElement, foutmelding, veld);
            }
            // Toon de foutmelding in foutElement
            
            // Speciale weergave voor specifieke foutmeldingen
            if (foutmelding === "Dit veld mag niet leeg blijven.") {
                veld.style.border = ''; // Geen rand bij lege velden
                veld.style.backgroundColor = ''; // Geen achtergrondkleur bij lege velden
            } else if (foutmelding === "Voer een geldig getal in tussen 0 en 200.") {
                veld.style.border = ''; 
                veld.style.backgroundColor = '';         
            } else if (foutmelding === "Aanvraag succesvol ontvangen!") {
                veld.style.border = ''; 
                veld.style.backgroundColor = '';
                foutElement.style.left = '35%';
                foutElement.style.width = '30%'; // Zorg voor iets extra ruimte
                foutElement.style.fontSize = '18px';  // Maak de tekst groter
                foutElement.style.backgroundColor = '#1c2541'; // Donkerblauw voor een professionele uitstraling
                foutElement.style.color = '#ffffff'; // Witte tekst voor contrast
                foutElement.style.padding = '15px'; // Extra padding voor ruimtelijkheid
                foutElement.style.borderRadius = '8px'; // Subtiel afgeronde hoeken
                foutElement.style.border = '4px solid white'; // Witte rand
                foutElement.style.boxShadow = '0 4px 10px rgba(0, 0, 0, 0.2)'; // Lichte schaduw
                foutElement.style.position = 'absolute';
                foutElement.style.zIndex = '2000';
                foutElement.style.opacity = '1';
                foutElement.style.display = 'flex'; // Flexbox voor centrering
                foutElement.style.alignItems = 'center'; // Verticaal centreren
                foutElement.style.justifyContent = 'center'; // Horizontaal centreren
                foutElement.style.transition = 'opacity 0.6s ease, transform 0.3s ease';
                foutElement.style.transform = 'scale(1)'; // Animatie bij tonen
            } else {
                // Voor andere foutmeldingen, toon wel visuele feedback
                veld.style.border = '1px solid #ff9900'; // Lichte oranje rand
                veld.style.backgroundColor = '#fff7e6'; // Pastel oranje achtergrond
            }
     
            
            return false; // Keer terug als er een foutmelding is
        } else {
            hideFoutmelding(foutElement); // Verberg de foutmelding als er geen is
            veld.style.border = ''; // Reset de rand naar de standaardwaarde
            veld.style.backgroundColor = ''; // Reset achtergrondkleur
            return true; // Geen foutmelding
        }
    }
    

    function valideerVoornaam(waarde) {
        const maxLength = 50;
        const onlyLetters = /^[A-Za-z\s.]*$/;

        if (waarde.length === 0) {
            return "Dit veld mag niet leeg blijven."; 
        }
        
        if (waarde.length > maxLength) {
            return `De voornaam mag maximaal ${maxLength} tekens bevatten.`;
        } else if (!onlyLetters.test(waarde)) {
            return "De voornaam mag alleen letters bevatten.";
        }
        return ""; // Geen foutmelding
    }

    function valideerAchternaam(waarde) {
        const maxLength = 50;
        const onlyLetters = /^[A-Za-z\s]*$/;

        if (waarde.length === 0) {
            return "Dit veld mag niet leeg blijven."; 
        }
        
    
        if (waarde.length > maxLength) {
            return `De achternaam mag maximaal ${maxLength} tekens bevatten.`;
        } else if (!onlyLetters.test(waarde)) {
            return "De achternaam mag alleen letters en spaties bevatten.";
        }
        return ""; // Geen foutmelding
    }

    function valideerEmail(waarde) {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
        const maxLength = 100; // Maximale lengte van het e-mailadres

        if (waarde.length === 0) {
            return "Dit veld mag niet leeg blijven."; 
        }
        
    
        if (waarde.length > maxLength) {
            return `Het e-mailadres mag maximaal ${maxLength} tekens bevatten.`;
        } else if (!emailRegex.test(waarde)) {
            return "Voer een geldig e-mailadres in.";
        }
    
        return ""; // Geen foutmelding
    }

    function valideerTelefoonnummer(waarde) {
        const telefoonRegex = /^(\+31|0)[1-9][0-9]{8}$|^(\+31|0)[1-9][0-9]{1,3}-[0-9]{6,7}$/;
        const maxLength = 15; // Maximale lengte voor het telefoonnummer
    
        if (waarde.length === 0) {
            return "Dit veld mag niet leeg blijven."; 
        }
            
        // Controleer de maximale lengte
        if (waarde.length > maxLength) {
            return `Het telefoonnummer mag maximaal ${maxLength} tekens bevatten.`;
        }
    
        // Valideer het telefoonnummer met de regex
        if (!telefoonRegex.test(waarde)) {
            return "Voer een geldig Nederlands telefoonnummer in.";
        }
    
        return ""; // Geen foutmelding
    }

    function valideerAantalBegeleiders(waarde) {
 
        // Controleer of de invoer een geldig getal is tussen 1 en 50.
        const aantal = parseInt(waarde, 10);
        if (isNaN(aantal) || aantal < 1 || aantal > 50 ) {
            return "Voer een getal in tussen 1 en 50.";
        }
    
        return ""; // Geen foutmelding
    }

    function valideerNiveauEnLeerjaar(waarde) {
        const maxLength = 50;
        const niveauRegex = /^[A-Za-z0-9\s.]+$/;

        if (waarde.length === 0) {
            return "Dit veld mag niet leeg blijven."; 
        }
        
        // Controleer de maximale lengte
        if (waarde.length > maxLength) {
            return `In dit veld mag je maximaal ${maxLength} tekens gebruiken.`;
        }
    
        // Valideer het telefoonnummer met de regex
        if (!niveauRegex.test(waarde)) {
            return "In dit veld kun je alleen letters en cijfers gebruiken";
        }
    
        return ""; // Geen foutmelding

    }

// Functie om naam van school te valideren
    function valideerNaamSchool(waarde) {
        const naamSchoolRegex = /^[A-Za-z0-9\s.]+$/;
        const maxLength = 80; // Laten we een limiet instellen voor de lengte van de naam van de school

        if (waarde.length === 0) {
            return "Dit veld mag niet leeg blijven."; 
        }
        
        
        // Controleer of de naam te lang is
        if (waarde.length > maxLength) {
            return `De naam van de school mag maximaal ${maxLength} tekens bevatten.`;
        }

        // Controleer of de naam alleen toegestane karakters bevat
        if (!naamSchoolRegex.test(waarde)) {
            return "De naam van de school mag alleen letters, cijfers, spaties en punten bevatten.";
        }

        return ""; // Geen foutmelding
}

function valideerAdres(waarde) {
    const maxLength = 100;  // Adressen kunnen wat langer zijn
    const adresRegex = /^[A-Za-z0-9\s.,'-]+$/;

       if (waarde.length === 0) {
            return "Dit veld mag niet leeg blijven."; 
        }
        
    if (waarde.length > maxLength) {
        return `Het adres mag maximaal ${maxLength} tekens bevatten.`;
    } else if (!adresRegex.test(waarde)) {
        return "Het adres mag alleen letters, cijfers, spaties en de volgende tekens bevatten: ., '-";
    }
    return ""; // Geen foutmelding
}

function valideerPostcode(waarde) {
    const postcodeRegex = /^[1-9][0-9]{3}\s?[A-Za-z]{2}$/; // Nederlandse postcode-formaat

    if (waarde.length === 0) {
        return "Dit veld mag niet leeg blijven.";
    }

    if (!postcodeRegex.test(waarde)) {
        return "De ingevoerde postcode is niet geldig. Het formaat moet 1234 AB zijn.";
    }

    return ""; // Geen foutmelding
}

function valideerPlaats(waarde) {
    const maxLengthPlaats = 100;
    const plaatsRegex = /^[A-Za-z\s'-]+$/; // Alleen letters, spaties, apostrof en streepjes toegestaan

    if (waarde.length === 0) {
        return "Dit veld mag niet leeg blijven.";
    }

    if (waarde.length > maxLengthPlaats) {
        return `De plaatsnaam mag maximaal ${maxLengthPlaats} tekens bevatten.`;
    }

    if (!plaatsRegex.test(waarde)) {
        return "De plaatsnaam mag alleen letters, spaties, apostrof en streepjes bevatten.";
    }

    return ""; // Geen foutmelding
}

function valideerHoeKentGeoFort(waarde) {
    const regex = /^[A-Za-z0-9\s.]*$/; // Letters, cijfers, spaties, en punten toegestaan

    // Als het veld leeg is, geen foutmelding geven
    if (waarde.length === 0) {
        return ""; // Geen foutmelding, want het veld mag leeg zijn
    }

    // Controleer of de invoer voldoet aan de regex
    if (!regex.test(waarde)) {
        return "Dit veld mag alleen letters, cijfers, spaties en punten bevatten.";
    }

    return ""; // Geen foutmelding
}

function valideerOnderwijsNiveau(waarde) {
    const geldigeNiveaus = Object.keys(onderwijsModules); // Verkrijg de geldige onderwijsniveaus uit de modules
    if (!waarde) {
        return "Selecteer een onderwijsniveau."; // Geen waarde ingevuld
    }
    if (!geldigeNiveaus.includes(waarde)) {
        return "Ongeldig onderwijsniveau geselecteerd."; // Niet in de lijst met geldige niveaus
    }
    return ""; // Geen foutmelding
}

// Stap 3: Functie voor validatie van de keuze module
function valideerKeuzeModule(waarde) {


    // Verzamel alle keuze modules uit het onderwijsModules object
    const alleKeuzeModules = [
        ...onderwijsModules.primairOnderwijs.keuze,
        ...onderwijsModules.voortgezetOnderbouw.keuze,
        ...onderwijsModules.voortgezetBovenbouw.keuze
    ];

    // Stap 5: Gebruik `some()` om te controleren of de gekozen module geldig is
    const isGeldig = alleKeuzeModules.some(module => module === waarde);

    return isGeldig ? "" : "De gekozen lesmodule is niet geldig."; // Retourneer lege string als alles geldig is, anders de foutmelding
}


function valideerAantalLeerlingen(waarde) {
    // Controleer of de invoer leeg is
    if (waarde.trim() === "") {
        return "Het aantal leerlingen moet worden doorgegeven."; // Als het veld leeg is
    }

    // Controleer of de invoer alleen cijfers bevat
    const cijferRegex = /^[0-9]+$/;
    if (!cijferRegex.test(waarde)) {
        return "Voer een geldig getal in."; // Als de invoer geen geldig nummer is
    }

    // Converteer de waarde naar een getal en valideer het bereik
    const aantal = parseInt(waarde, 10);
    if (aantal < 40 || aantal > 160) {
        return "Het aantal leerlingen moet tussen 40 en 160 liggen.";
    }

    return ""; // Geen foutmelding
}

let isTyping = false; // Vlag om te detecteren of de gebruiker aan het typen is


function valideerInput(amountInput) {
    const cijferRegex = /^[0-9]+$/; // Regex om alleen cijfers toe te staan
    console.log("amountINput zelf in de valideercheck: ", amountInput)
    console.log("amountINput.value zelf in de valideercheck: ", amountInput.value)
    let waarde = amountInput.value.trim();
   
    // Als de invoer leeg is, zet de waarde automatisch op 0 en geen foutmelding
    if (waarde === '') {
        amountInput.value = 0;
        return ""; // Geen foutmelding
    }

    // Trim leidende nullen (behalve als de waarde gewoon '0' is)
    if (waarde.startsWith('0') && waarde.length > 1) {
        waarde = waarde.replace(/^0+/, ''); // Verwijder leidende nullen
        amountInput.value = waarde; // Update het invoerveld
    }

    // Controleer of de invoer alleen cijfers bevat
    if (!cijferRegex.test(waarde)) {
        amountInput.value = 0; // Zet de waarde naar 0 als de invoer ongeldig is
        return "Voer een geldig getal in tussen 0 en 200."; // Ongeldige invoer
    }

    const aantal = parseInt(waarde, 10);

    // Controleer of het aantal tussen 0 en 200 ligt
    if (aantal < 0 || aantal > 200) {
        amountInput.value = 0; // Zet de waarde naar 0 als het buiten het bereik valt
        return "Voer een geldig getal in tussen 0 en 200."; // Ongeldig bereik
    }


    return ""; // Geen foutmelding
}

function valideerVragenenOpmerkingen(waarde) {
    const maxLength = 600;  
    const vragenenOpmerkingenRegex = /^[A-Za-z0-9\s.,:]+$/;

    if (waarde.length === 0){
        return "";
    }
    
    if (waarde.length > maxLength) {
        return `Dit veld mag max ${maxLength} tekens bevatten.`;
    } else if (!vragenenOpmerkingenRegex.test(waarde)) {
        return "Speciale tekens zoals } of / kunnen niet gebruikt worden";
    }
    return ""; // Geen foutmelding
}

function verzendknopMelding(waarde) {
   return waarde;
}

function verzendknopFoutMelding(waarde) {
    return waarde;
 }
 


    // Algemene functie om foutmeldingen te tonen met display
    // Functie om foutmeldingen te tonen met inline styling
    function toonFoutmelding(foutElement, foutmelding, element, duur = 4000) {
        foutElement.textContent = foutmelding;
        foutElement.style.width = 'auto'; // Zorg voor iets extra ruimte
        foutElement.style.fontSize = '14px';  // Maak de tekst iets groter
        foutElement.style.backgroundColor = '#081540';
        foutElement.style.color = 'white';
        foutElement.style.padding = '10px';
        foutElement.style.borderRadius = '7px';
        foutElement.style.border = '1px solid white';
        foutElement.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
        foutElement.style.position = 'absolute';
        foutElement.style.zIndex = '2000';
        foutElement.style.opacity = '1';
        foutElement.style.display = 'block';
        foutElement.style.transition = 'opacity 0.6s ease';
    
        // Dynamische positionering ten opzichte van het invoerveld
        const rect = element.getBoundingClientRect();
        const scrollY = window.scrollY || window.pageYOffset;
        foutElement.style.left = `${rect.left + window.scrollX}px`;
        foutElement.style.top = `${rect.top + scrollY - foutElement.offsetHeight - 5}px`;
    
        setTimeout(() => {
            hideFoutmelding(foutElement);
        }, duur);
    }
    
    // Verberg foutmelding en herstel de styling van het invoerveld
    function hideFoutmelding(foutElement) {
        foutElement.style.opacity = '0'; // Start fade-out effect
        setTimeout(() => {
            foutElement.style.display = 'none'; // Verberg volledig
            foutElement.textContent = ""; // Wis de foutmelding
        }, 600);
    }
    
    document.getElementById('contactpersoonvoornaam').addEventListener('blur', function() {
        const voornaamVeld = document.getElementById('contactpersoonvoornaam');
        const voornaamFoutElement = document.getElementById('voornaamFout');
        
        valideerInvoer(voornaamVeld, voornaamFoutElement, valideerVoornaam);
    });

    document.getElementById('contactpersoonachternaam').addEventListener('blur', function() {
        const achternaamVeld = document.getElementById('contactpersoonachternaam');
        const achternaamFoutElement = document.getElementById('achternaamFout');
        
        valideerInvoer(achternaamVeld, achternaamFoutElement, valideerAchternaam);
    });

    document.getElementById('emailadres').addEventListener('blur', function() {
        const emailVeld = document.getElementById('emailadres');
        const emailFoutElement = document.getElementById('emailFout');
    
        valideerInvoer(emailVeld, emailFoutElement, valideerEmail);
    });

    
    document.getElementById('schoolTelefoonnummer').addEventListener('blur', function() {
        const telefoonVeld = document.getElementById('schoolTelefoonnummer');
        const telefoonFoutElement = document.getElementById('schoolTelefoonnummerFout');
    
        valideerInvoer(telefoonVeld, telefoonFoutElement, valideerTelefoonnummer);
    });

    document.getElementById('contactTelefoonnummer').addEventListener('blur', function() {
        const telefoonVeld = document.getElementById('contactTelefoonnummer');
        const telefoonFoutElement = document.getElementById('contactTelefoonnummerFout');
    
        valideerInvoer(telefoonVeld, telefoonFoutElement, valideerTelefoonnummer);
    });
    
    
    document.getElementById('totaalbegeleiders').addEventListener('blur', function() {
        const begeleidersVeld = document.getElementById('totaalbegeleiders');
        const begeleidersFoutElement = document.getElementById('aantalBegeleidersFout');
        
        // Roep de validatiefunctie aan
        valideerInvoer(begeleidersVeld, begeleidersFoutElement, valideerAantalBegeleiders);
    });

    document.getElementById('niveauleerjaar').addEventListener('blur', function() {
        const niveauLeerVeld = document.getElementById('niveauleerjaar');
        const niveauLeerFoutElement = document.getElementById('niveauLeerjaarFout')
        
        // Roep de validatiefunctie aan
        valideerInvoer(niveauLeerVeld, niveauLeerFoutElement, valideerNiveauEnLeerjaar);
    });

    document.getElementById('schoolnaam').addEventListener('blur', function() {
        const schoolVeld = document.getElementById('schoolnaam');
        const schoolFoutElement = document.getElementById('naamSchoolFout');
        
        valideerInvoer(schoolVeld, schoolFoutElement, valideerNaamSchool);
    });

    document.getElementById('adres').addEventListener('blur', function() {
        const adresVeld = document.getElementById('adres');
        const adresFoutElement = document.getElementById('adresFout');
    
        valideerInvoer(adresVeld, adresFoutElement, valideerAdres);
    });


    document.getElementById('postcode').addEventListener('blur', function() {
        const postcodeVeld = document.getElementById('postcode');
        const postcodeFoutElement = document.getElementById('postcodeFout');
        
        valideerInvoer(postcodeVeld, postcodeFoutElement, valideerPostcode);
    });
    
    document.getElementById('plaats').addEventListener('blur', function() {
        const plaatsVeld = document.getElementById('plaats');
        const plaatsFoutElement = document.getElementById('plaatsFout');
        
        valideerInvoer(plaatsVeld, plaatsFoutElement, valideerPlaats);
    });

    document.getElementById('hoekentGeoFort').addEventListener('blur', function() {
        const hoekentGeoFortVeld = document.getElementById('hoekentGeoFort');
        const hoekentGeoFortFoutElement = document.getElementById('hoekentGeoFortFout');
        
        valideerInvoer(hoekentGeoFortVeld, hoekentGeoFortFoutElement, valideerHoeKentGeoFort);
    });

    document.getElementById('vragenOpmerkingen').addEventListener('blur', function() {
        const adresVeld = document.getElementById('vragenOpmerkingen');
        const adresFoutElement = document.getElementById('vragenOpmerkingenFout');
    
        valideerInvoer(adresVeld, adresFoutElement, valideerVragenenOpmerkingen);
    });
    

    function werkKeuzeModulesBij1() {
        const onderwijsNiveau1 = document.getElementById('onderwijsNiveau').value || 'primairOnderwijs';
        const keuzeModuleSelect1 = document.getElementById('keuzeModule');

        keuzeModuleSelect1.innerHTML = '';

        if (onderwijsNiveau1 in onderwijsModules) {
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
        const onderwijsNiveau2 = document.getElementById('onderwijsNiveau').value || 'primairOnderwijs';
        const keuzeModule2 = document.getElementById('keuzeModule').value;

        const standaardModulesLijst2 = document.getElementById('standaardModulesLijst');
        standaardModulesLijst2.innerHTML = '';
        if (onderwijsNiveau2 in onderwijsModules) {
            onderwijsModules[onderwijsNiveau2].standaard.forEach(module2 => {
                const lijstItem2 = document.createElement('li');
                lijstItem2.textContent = module2;
                standaardModulesLijst2.appendChild(lijstItem2);
            });
        }

        const gekozenKeuzeModule2 = document.getElementById('gekozenKeuzeModule');
        gekozenKeuzeModule2.textContent = keuzeModule2 || 'Geen keuzevak geselecteerd';
    }

    function initialiseerFormulier() {
        document.getElementById('onderwijsNiveau').value = 'primairOnderwijs'; // Stel het standaard onderwijsniveau in
        werkKeuzeModulesBij1();  // Vul de keuzemodules en standaardmodules in
        werkGeselecteerdeModulesWeergaveBij2();  // Update de weergave van modules
    }

    // Voeg event listeners toe om modules bij te werken wanneer het onderwijsniveau verandert
    document.getElementById('onderwijsNiveau').addEventListener('change', () => {
        werkKeuzeModulesBij1();
    });

    // Voeg event listener toe om geselecteerde modules bij te werken wanneer de keuzemodule verandert
    document.getElementById('keuzeModule').addEventListener('change', () => {
        werkGeselecteerdeModulesWeergaveBij2();
    });

    // Roep de initialisatiefunctie aan bij het laden van de pagina
    initialiseerFormulier();

   // Selecteer alle toggles en inhoudsblokken
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
                toggle.querySelector("span").textContent = "Verberg informatie";
            } else {
                // Voeg hier de check toe voor bezoektijdenInfo
                if (targetId === "begeleidersInfo") {
                    toggle.querySelector("span").textContent = "Meer informatie over aantal begeleiders";
                } else if (targetId === "telefoonInfo") {
                    toggle.querySelector("span").textContent = "Meer informatie over telefoonnummers";
                } else if (targetId === "bezoektijdenInfo") {
                    toggle.querySelector("span").textContent = "Meer informatie over bezoektijden";
                }
            }
        });
    });



    function haalRoosterOp() {
        const onderwijsNiveau = document.getElementById('onderwijsNiveau').value;
        const keuzeModule = document.getElementById('keuzeModule').value;
        const aantalLeerlingen3 = parseInt(document.getElementById('aantalLeerlingen').value, 10);
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
                        roosterAfbeeldingContainer.style.display = 'block';
                        roosterAfbeelding.src = response.afbeelding;
                        console.log("afbeelding src van sql: ", response.afbeelding);
                        downloadButton.style.display = 'block';

                        downloadButton.onclick = function (event) {
                            event.preventDefault();
                            const link = document.createElement('a');
                            link.href = response.pdf;
                            link.target = '_blank';
                            link.click();
                        };
                    } else {
                        roosterAfbeeldingContainer.style.display = 'none';
                        downloadButton.style.display = 'none';
                        console.log(response.message);
                    }
                }
            };
            xhr.send(`schooltype=${onderwijsNiveau}&lesmodule=${keuzeModule}&aantalleerlingen=${aantalLeerlingen3}`);
        } else {
            document.getElementById('roosterAfbeeldingContainer').style.display = 'none';
            document.getElementById('downloadRooster').style.display = 'none';
        }
    }

    document.getElementById('onderwijsNiveau').addEventListener('change', () => {
        werkKeuzeModulesBij1();
        haalRoosterOp();
    });

    document.getElementById('keuzeModule').addEventListener('change', () => {
        werkGeselecteerdeModulesWeergaveBij2();
        haalRoosterOp();
    });

    document.getElementById('aantalLeerlingen').addEventListener('input', () => {
        haalRoosterOp();
    });

    function valideerAantalLeerlingenInvoer() {
        const aantalLeerlingenVeld = document.getElementById('aantalLeerlingen');
        const aantalLeerlingen = aantalLeerlingenVeld.value;
        const foutMelding = document.getElementById('aantalLeerlingenFout');
    
        // Roep de validatiefunctie aan
        const validatieFout = valideerAantalLeerlingen(aantalLeerlingen);
    
        if (validatieFout) {
            foutMelding.textContent = validatieFout;
            foutMelding.style.display = 'block'; // Toon de foutmelding
            aantalLeerlingenVeld.setCustomValidity(validatieFout); // Stel de custom validatie in
    
            // Stijl aanpassen bij fout
            aantalLeerlingenVeld.style.border = '1px solid #ff9900'; // Lichte oranje tint
            aantalLeerlingenVeld.style.backgroundColor = '#fff7e6'; // Pastel oranje achtergrond
        } else {
            foutMelding.style.display = 'none'; // Verberg de foutmelding
            aantalLeerlingenVeld.setCustomValidity(''); // Reset de custom validatie
    
            // Reset de stijl als de invoer correct is
            aantalLeerlingenVeld.style.border = ''; // Terug naar standaardrand
            aantalLeerlingenVeld.style.backgroundColor = ''; // Terug naar standaard achtergrondkleur
    
            // Roep de rooster-ophaal functie aan wanneer de invoer correct is
            haalRoosterOp();
        }
    }
    
    // Voeg de eventlistener toe voor zowel 'input' als 'blur'
    const aantalLeerlingenVeld = document.getElementById('aantalLeerlingen');
    aantalLeerlingenVeld.addEventListener('blur', valideerAantalLeerlingenInvoer);
    aantalLeerlingenVeld.addEventListener('input', valideerAantalLeerlingenInvoer);
  


    
    

    
    // Voeg event listeners voor hover-meldingen toe
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

    function scheduleHoverMessage4(message, element, className = 'hover-message') {
        clearTimeout(hoverTimeout);
        hoverTimeout = setTimeout(() => showHoverMessage6(message, element, className), 200);
    }
    
    function cancelHoverMessage5() {
        clearTimeout(hoverTimeout);
        hideHoverMessage7();
    }

    let hoverTimeout_agenda; // Variabele voor het bijhouden van de timeout voor de agenda
    let hoverMessageElement_agenda; // Variabele voor de huidige hover-message van de agenda

    function showHoverMessage6(message, element, className = 'hover-message') {
        let hoverMessageElement;
    
        // Voor agenda-specifieke hover-messages
        if (className === 'hover-message-agenda') {
            // Verberg de huidige agenda hover-message als die nog actief is
            
    
            const calendarContainer = document.querySelector('.flatpickr-calendar');
            hoverMessageElement_agenda = calendarContainer.querySelector(`.${className}`);
    
            // Als het element nog niet bestaat, maak het aan
            if (!hoverMessageElement_agenda) {
                hoverMessageElement_agenda = document.createElement('div');
                hoverMessageElement_agenda.className = className;
                calendarContainer.appendChild(hoverMessageElement_agenda);
            }
    
            // Stel de agenda hover-message in
            hoverMessageElement_agenda.textContent = message;
            hoverMessageElement_agenda.style.backgroundColor = 'rgba(8, 21, 64, 0.9)';
            hoverMessageElement_agenda.style.color = 'white';
            hoverMessageElement_agenda.style.padding = '10px';
            hoverMessageElement_agenda.style.borderRadius = '5px';
            hoverMessageElement_agenda.style.border = '2px solid white';
            hoverMessageElement_agenda.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
            hoverMessageElement_agenda.style.fontSize = '14px';
            hoverMessageElement_agenda.style.display = 'block';
            hoverMessageElement_agenda.style.opacity = '1';
            hoverMessageElement_agenda.style.transition = 'opacity 0.6s ease';
    
    
            // Clear bestaande timeout en stel een nieuwe in
            clearTimeout(hoverTimeout_agenda);
        hoverTimeout_agenda = setTimeout(() => {
            hoverMessageElement_agenda.style.opacity = '0'; // Start de fade-out
            setTimeout(() => {
                hoverMessageElement_agenda.style.display = 'none'; // Verberg volledig na de fade-out
            }, 600); // Na de overgang wordt de display op 'none' gezet
        }, 6000);  // Verberg na 6 seconden
    
        } else {
            // Voor andere hover-messages (niet agenda)
            // Maak een nieuw hover-message element aan als het nog niet bestaat
            hoverMessageElement = element.querySelector(`.${className}`);
            if (!hoverMessageElement) {
                hoverMessageElement = document.createElement('div');
                hoverMessageElement.className = className;
                document.body.appendChild(hoverMessageElement);
            }
    
            // Stel de inhoud en stijl in (CSS verzorgt de styling verder)
            hoverMessageElement.textContent = message;
            hoverMessageElement.style.display = 'block';
            hoverMessageElement.style.opacity = '1';

    
            // Positionering voor niet-agenda hover-messages
            const rect = element.getBoundingClientRect();
            const scrollY = window.scrollY || window.pageYOffset;
            hoverMessageElement.style.left = `${rect.left + window.scrollX}px`;
            hoverMessageElement.style.top = `${rect.top + scrollY - hoverMessageElement.offsetHeight + 15}px`;
     
        }
        
    }
    

    
    
    function hideHoverMessage7() {
        const hoverMessageElement = document.querySelector('.hover-message');
        const hoverMessageElement_agenda = document.querySelector('.hover-message-agenda');
    
        if (hoverMessageElement) {
            hoverMessageElement.style.opacity = '0';
    
            setTimeout(() => {
                hoverMessageElement.style.display = 'none';
                hoverMessageElement.remove(); // Verwijder het element uit de DOM
            }, 300);
        }
    
        if (hoverMessageElement_agenda) {
            hoverMessageElement_agenda.style.opacity = '0';
    
            setTimeout(() => {
                hoverMessageElement_agenda.style.display = 'none';
                hoverMessageElement_agenda.remove(); // Verwijder agenda hover bericht uit de DOM
            }, 400);
        }
    }
    
    
    
    const foodInputs = document.querySelectorAll('input[type="checkbox"][name="snack"], input[type="checkbox"][name="lunch"]');
    const foodSummaryDiv = document.getElementById('foodSummary');
    const totalPriceSpan = document.getElementById('totalPrice');
    const bezoekDiv = document.getElementById('bezoek');

    foodInputs.forEach(input => {
        const snackOption = input.closest('.snack-option');
        const amountInput = snackOption ? snackOption.querySelector('input[type="number"]') : null;
    
        if (amountInput) {
            // Start detectie van input-activiteit
            amountInput.addEventListener('input', function () {
                const foutElement = document.getElementById(`${amountInput.id}Fout`);
    
                // Valideer de invoer tijdens het typen
                valideerInvoer(amountInput, foutElement, () => {
                    return valideerInput(amountInput); // Input validatie, deze syntax is een callback functie, die valideerinput pas inzet nadat de divs zijn ontvangen
                });
            });
        }
    });
    
    foodInputs.forEach(input => {
        input.addEventListener('change', function () {
            const amountInput = input.closest('.snack-option').querySelector('input[type="number"]');
            if (amountInput) {
                amountInput.disabled = !this.checked;
                if (!this.checked) {
                    amountInput.value = 0;
                    amountInput.disabled = true;  // Maak het invoerveld disabled

                    // Reset inline styling (verwijder fout-styling)
                    amountInput.style.border = '';  // Reset de border
                    amountInput.style.backgroundColor = '';  // Reset de achtergrondkleur
                    
                    // Optioneel: Voeg een class of inline-styling toe voor disabled status
                    amountInput.classList.add('disabled');  // Voeg disabled-stijl toe
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
        return [bezoekPrijs.toFixed(2),aantalLeerlingen,teBetalenBegeleiders];
    }
    function updateFoodSummary9() {
        let totalPrice = 0;
        let foodPrice = 0;
        
        const btwPercentage_bezoek = 21; // Btw percentage''
        const btwPercentage_food = 9;
        const totalPriceSpan = document.getElementById('totalPrice');
        const foodSummaryDiv = document.getElementById('foodSummary');
        const bezoekDiv = document.getElementById('bezoek');
        
        foodSummaryDiv.innerHTML = '';
        bezoekDiv.innerHTML = '';
    
        // Haal de bezoekprijs, aantal leerlingen en begeleiders op
        const bezoekPrice = parseFloat(calculateVisitPrice8()[0]);
        const aantalLeerlingen = calculateVisitPrice8()[1];
        const teBetalenBegeleiders = calculateVisitPrice8()[2];
        const prijsLeerlingen = aantalLeerlingen * 20;
    
        // Voeg de samenvatting van de leerlingen toe
        if (aantalLeerlingen > 0){
            const leerlingSummary = document.createElement('div');
            leerlingSummary.className = 'summary-item';
            leerlingSummary.innerHTML = `<span>${aantalLeerlingen} leerlingen:</span><span>€${prijsLeerlingen.toFixed(2)}</span>`;
            bezoekDiv.appendChild(leerlingSummary);
        }
    
        // Voeg de samenvatting van te betalen begeleiders toe, als ze er zijn
        if (teBetalenBegeleiders > 0) {
            const begeleidersPrijs = teBetalenBegeleiders * 20; // Aannemen dat elke begeleider €20 kost
            const begeleiderSummary = document.createElement('div');
            begeleiderSummary.className = 'summary-item';
            begeleiderSummary.innerHTML = `<span>${teBetalenBegeleiders} te betalen begeleiders:</span><span>€${begeleidersPrijs.toFixed(2)}</span>`;
            bezoekDiv.appendChild(begeleiderSummary);
        }
    
        const bezoekTotalSummary = document.createElement('div');
        bezoekTotalSummary.className = 'summary-item';
        bezoekTotalSummary.innerHTML = `<span style="font-size: 1.2em;"><strong>Totale bezoekprijs:</strong></span><span style="font-size: 1.2em;"><strong>€${bezoekPrice.toFixed(2)}</strong></span>`;
        bezoekDiv.appendChild(bezoekTotalSummary);

          // Bereken de prijs exclusief en inclusief btw
        const totalExclBtw_bezoek = bezoekPrice / (1 + btwPercentage_bezoek / 100);
        
          // Toon de prijs exclusief btws
        const exclBtwSummary_bezoek = document.createElement('div');
        exclBtwSummary_bezoek.className = 'summary-item';
        exclBtwSummary_bezoek.innerHTML = `<span style="font-size: 0.9em;">Totale bezoekprijs excl. btw (21%):</span><span style="font-size: 0.9em;">€${totalExclBtw_bezoek.toFixed(2)}</span>`;
        bezoekDiv.appendChild(exclBtwSummary_bezoek);
    
        // Voeg de samenvatting van het eten toe
        foodInputs.forEach(input => {
            const amountInput = input.closest('.snack-option').querySelector('input[type="number"]');
            if (input.checked && amountInput && amountInput.value > 0) {
                const itemTotal = (input.value * amountInput.value).toFixed(2);
                foodPrice += parseFloat(itemTotal);
                totalPrice += parseFloat(itemTotal);
                const itemSummary = document.createElement('div');
                itemSummary.className = 'summary-item';
                const itemName = input.labels[0].innerText.split(':')[0];
                itemSummary.innerHTML = `<span>${amountInput.value} ${itemName}:</span><span>€${itemTotal}</span>`;
                foodSummaryDiv.appendChild(itemSummary);
            }
        });
    
        // Voeg totale prijs eten toe
        const foodTotalSummary = document.createElement('div');
        foodTotalSummary.className = 'summary-item';
        foodTotalSummary.innerHTML = `<span style="font-size: 1.2em;"><strong>Totale bestelprijs:</strong></span><span style="font-size: 1.2em;"><strong>€${foodPrice.toFixed(2)}</strong></span>`;
        foodSummaryDiv.appendChild(foodTotalSummary);
    
        
    
        // Bereken de prijs exclusief en inclusief btw
        const totalExclBtwfood = foodPrice / (1 + btwPercentage_food / 100);

        // Toon de prijs exclusief btw
        const exclBtwSummary_food = document.createElement('div');
        exclBtwSummary_food.className = 'summary-item';
        exclBtwSummary_food.innerHTML = `<span style="font-size: 0.9em;">Totale bestelprijs excl. btw (9%):</span><span style="font-size: 0.9em;">€${totalExclBtwfood.toFixed(2)}</span>`;
        foodSummaryDiv.appendChild(exclBtwSummary_food);

        totalPrice += bezoekPrice;
        const totalExlBtw = parseFloat(totalExclBtw_bezoek + totalExclBtwfood);
        totalPriceSpan.textContent = `${totalPrice.toFixed(2)}`;

        const exlBTWTotalDiv = document.createElement('div');
        exlBTWTotalDiv.className = 'summary-item';
        exlBTWTotalDiv.innerHTML = `<span style="font-size: 0.6em;"><strong>Totale prijs exl. BTW:</strong></span><span style="font-size: 0.6em;"><strong> €${totalExlBtw.toFixed(2)}</strong></span>`;
        totalPriceSpan.appendChild(exlBTWTotalDiv);

       
    }
    
    document.getElementById('aantalLeerlingen').addEventListener('input', updateFoodSummary9);
    document.getElementById('totaalbegeleiders').addEventListener('input', updateFoodSummary9);

    updateFoodSummary9();

    function initFlatpickr(agendaData) {
        const currentDate = new Date();
        const endOfYearDate = new Date(currentDate.getFullYear(), 11, 31);  // 31 december van dit jaar
    
        // Array met schoolvakanties
        const schoolVacations = [
            { start: '2024-02-19', end: '2024-02-23' },  // Voorjaarsvakantie
            { start: '2024-04-29', end: '2024-05-03' },  // Meivakantie
            { start: '2024-07-15', end: '2024-08-23' },  // Zomervakantie
            { start: '2024-10-21', end: '2024-10-25' },  // Herfstvakantie
            { start: '2024-12-23', end: '2024-12-31' }   // Kerstvakantie
        ];
    
        // Functie om te checken of een datum in de vakantieperiode valt
        function isVacation(date) {
            return schoolVacations.some(vacation => {
                const start = new Date(vacation.start);
                const end = new Date(vacation.end);
                return date >= start && date <= end;
            });
        }
    
        const disabledDates = [];
        flatpickr.localize(flatpickr.l10ns.nl);
    
        flatpickr("#bezoekdatum", {
            locale: "nl",  // Locale Nederlands instellen
            enableTime: false,
            dateFormat: "d F Y",  // Dag, maand, jaar
            disable: [
                function (date) {
                    const isWeekend = (date.getDay() === 0 || date.getDay() === 6);
                    const isSchoolVacation = isVacation(date);
                    const isPastDate = date < currentDate;  // Datum uit het verleden uitschakelen
                    const isNextYear = date > endOfYearDate;  // Datum na dit jaar uitschakelen
        
                    const isDisabled = isWeekend || isSchoolVacation || isPastDate || isNextYear;
        
                    if (isDisabled) {
                        disabledDates.push(date.setHours(0, 0, 0, 0));  // Voeg disabled datums toe
                    }
        
                    return isDisabled;
                }
            ],
            appendTo: document.body,
        
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                const dayTimestamp = dayElem.dateObj.setHours(0, 0, 0, 0); // Normale timestamp zonder tijd
                
                resetDayStyles(dayElem);
        
                // Zoek naar een match met agendaData op basis van de datum
                const dateMatch = agendaData.find(item => new Date(item.datum).setHours(0, 0, 0, 0) === dayTimestamp);
                
                if (dateMatch) {
                    if (dateMatch.status === 'onbeschikbaar') {
                        applyStyling(dayElem, 'gray', 'white', 'Onbeschikbaar');
                    } else if (dateMatch.status === 'volgeboekt') {
                        applyStyling(dayElem, 'red', 'white', 'Volgeboekt', '#081540');
                        dayElem.classList.add('flatpickr-disabled');
                    } else if (dateMatch.status === 'beperkt beschikbaar') {
                        const beschikbarePlaatsen = 160 - parseInt(dateMatch.totale_leerlingen, 10);
                        applyStyling(dayElem, '#081540', 'white', `Beperkt te boeken voor ${beschikbarePlaatsen} leerlingen`, 'green');
                    }
                } else {
                    const isFutureDate = dayElem.dateObj >= currentDate && dayElem.dateObj <= endOfYearDate;
                    if (isFutureDate && !disabledDates.includes(dayTimestamp)) {
                        applyStyling(dayElem, 'green', 'white', 'Te boeken voor max 160 leerlingen', 'white');
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
            }
        });
        
    }
    
    
    // Herstel dag stijlen
    function resetDayStyles(dayElem) {
        dayElem.style.backgroundColor = '';  
        dayElem.style.color = '';            
        dayElem.title = '';                  
    }
    
    // Pas stijlen toe op de dagen
    function applyStyling(dayElem, backgroundColor, textColor, title, border = null, className = 'hover-message-agenda') {
        dayElem.style.backgroundColor = backgroundColor;
        dayElem.style.color = textColor;
        dayElem.title = "";  // Leegmaken van het standaard title-attribuut
    
        // Voeg een rand toe indien border is meegegeven
        if (border) {
            dayElem.style.border = `3px solid ${border}`;
            dayElem.style.borderRadius = '30px';
        }
    
        // Voeg de hover event listeners toe
        dayElem.addEventListener('mouseenter', () => {
                
            scheduleHoverMessage4(title, dayElem, className);  // Hoverfunctie voor de tooltip
        });

    
    
       
        
    }
    
    
    
    
    
    // Laad de agenda data van de server
   // Laad de agenda data van de server
function loadCalendarData() {
    fetch('get_aanvragen_data.php')
        .then(response => response.json())
        .then(data => {
            // Controleer of de ontvangen data niet leeg is
            if (data && data.length > 0) {
                initFlatpickr(data);  // Data wordt gebruikt als er beschikbaar is
            } else {
                initFlatpickr([]);  // Laad Flatpickr met een lege dataset
            }
        })
        .catch(error => {
            console.error('Error fetching calendar data:', error);
            initFlatpickr([]);  // Als er een fout is, laad een lege Flatpickr-agenda
        });
}

loadCalendarData();  // Kalenderdata wordt geladen bij het laden van de pagina

document.getElementById('onderwijsFormulier').addEventListener('submit', function (event) {
    event.preventDefault();  // Voorkom standaard formulierverzending

    // Definieer alle verplichte velden die niet leeg mogen zijn
    const verplichteVelden = [
        { veld: document.getElementById('contactpersoonvoornaam'), foutElement: document.getElementById('voornaamFout'), validatieFunctie: valideerVoornaam },
        { veld: document.getElementById('contactpersoonachternaam'), foutElement: document.getElementById('achternaamFout'), validatieFunctie: valideerAchternaam },
        { veld: document.getElementById('emailadres'), foutElement: document.getElementById('emailFout'), validatieFunctie: valideerEmail },
        { veld: document.getElementById('schoolTelefoonnummer'), foutElement: document.getElementById('schoolTelefoonnummerFout'), validatieFunctie: valideerTelefoonnummer },
        { veld: document.getElementById('contactTelefoonnummer'), foutElement: document.getElementById('contactTelefoonnummerFout'), validatieFunctie: valideerTelefoonnummer },
        { veld: document.getElementById('totaalbegeleiders'), foutElement: document.getElementById('aantalBegeleidersFout'), validatieFunctie: valideerAantalBegeleiders },
        { veld: document.getElementById('niveauleerjaar'), foutElement: document.getElementById('niveauLeerjaarFout'), validatieFunctie: valideerNiveauEnLeerjaar },
        { veld: document.getElementById('schoolnaam'), foutElement: document.getElementById('naamSchoolFout'), validatieFunctie: valideerNaamSchool },
        { veld: document.getElementById('adres'), foutElement: document.getElementById('adresFout'), validatieFunctie: valideerAdres },
        { veld: document.getElementById('postcode'), foutElement: document.getElementById('postcodeFout'), validatieFunctie: valideerPostcode },
        { veld: document.getElementById('plaats'), foutElement: document.getElementById('plaatsFout'), validatieFunctie: valideerPlaats },
        { veld: document.getElementById('aantalLeerlingen'), foutElement: document.getElementById('aantalLeerlingenFout'), validatieFunctie: valideerAantalLeerlingen },
        { veld: document.getElementById('onderwijsNiveau'), foutElement: document.getElementById('onderwijsNiveauFout'), validatieFunctie: valideerOnderwijsNiveau },
        { veld: document.getElementById('keuzeModule'), foutElement: document.getElementById('keuzeModuleFout'), validatieFunctie: valideerKeuzeModule }
    ];

    let isFormulierGeldig = true;

    // Valideer verplichte velden
    for (const veldInfo of verplichteVelden) {
        const isGeldig = valideerInvoer(veldInfo.veld, veldInfo.foutElement, veldInfo.validatieFunctie);

        // Als er een fout is, focus op het eerste veld dat niet geldig is en stop met de controle
        if (!isGeldig) {
            veldInfo.veld.focus();  // Verplaats de focus naar het foutieve veld
            isFormulierGeldig = false;
            break;  // Stop met het valideren van andere velden
        }
    }

    // Stop de verdere verwerking als het formulier niet geldig is
    if (!isFormulierGeldig) {
        return false;
    }
    // Voeg de validatie toe voor de datum met de flatpickr
    const datumVeld = document.getElementById('bezoekdatum');
    const datumFoutElement = document.getElementById('datumFout');
    const datumPicker = datumVeld._flatpickr;
    const geselecteerdeDatum = datumPicker.selectedDates[0];

    if (!geselecteerdeDatum || isNaN(geselecteerdeDatum)) {
        toonFoutmelding(datumFoutElement, "Selecteer een geldige datum.", datumVeld);
        datumVeld.focus();
        isFormulierGeldig = false;
    } else {
        const isDisabledDatum = datumPicker.config.disable.some(disableRule => {
            if (typeof disableRule === 'function') {
                return disableRule(geselecteerdeDatum);
            }
            if (disableRule instanceof Date) {
                return disableRule.getTime() === geselecteerdeDatum.getTime();
            }
            return false;
        });
        if (isDisabledDatum) {
            datumVeld.focus();
            isFormulierGeldig = false;
        } else {
            hideFoutmelding(datumFoutElement);
        }
    }

    // Valideer optionele velden
    const optioneleVelden = [
        { veld: document.getElementById('hoekentGeoFort'), foutElement: document.getElementById('hoekentGeoFortFout'), validatieFunctie: valideerHoeKentGeoFort },
        { veld: document.getElementById('vragenOpmerkingen'), foutElement: document.getElementById('vragenOpmerkingenFout'), validatieFunctie: valideerVragenenOpmerkingen }
    ];

    for (const veldInfo of optioneleVelden) {
        const waarde = veldInfo.veld.value.trim();
        if (waarde !== "") {
            const isGeldig = valideerInvoer(veldInfo.veld, veldInfo.foutElement, veldInfo.validatieFunctie);
            if (!isGeldig) {
                veldInfo.veld.focus();
                isFormulierGeldig = false;
                break;
            }
        }
    }

    const foodInputs = document.querySelectorAll('input[type="checkbox"][name="snack"], input[type="checkbox"][name="lunch"]');


foodInputs.forEach(input => {
    const snackOption = input.closest('.snack-option');
    const amountInput = snackOption ? snackOption.querySelector('input[type="number"]') : null;

   
    if (input.checked && amountInput) {
        const foutElement = document.getElementById(`${amountInput.id}Fout`);
        const isValid = valideerInvoer(amountInput, foutElement, () => valideerInput(amountInput));
        if (!isValid) {
            amountInput.focus();
            isFormulierGeldig = false;
            return false;
        }
    } else if (!input.checked && amountInput) {
        amountInput.value = 0;
        amountInput.disabled = true;
    }
});


const formData = new FormData(this);

foodInputs.forEach(input => {
    const snackOption = input.closest('.snack-option');
    const amountInput = snackOption ? snackOption.querySelector('input[type="number"]') : null;

    // Controleer of de checkbox is aangevinkt en de 'name' niet gelijk is aan 'snack' of 'lunch'
    if (amountInput && input.checked && amountInput.name !== 'snack' && amountInput.name !== 'lunch') {
        formData.append(amountInput.name, amountInput.value);  // Voeg het aantal toe aan formData
    } else if (amountInput) {
        formData.append(amountInput.name, 0);  // Zet de waarde op 0 als het niet is aangevinkt
    } else if (input.id === 'eigenPicknickCheckbox') {
        formData.append('eigenPicknick', input.checked ? 1 : 0);  // Voeg eigenPicknick waarde toe
    }
});

formData.delete('snack');
formData.delete('lunch');

const xhr = new XMLHttpRequest();
xhr.open('POST', 'validatie.php', true);  // Verwijs naar je PHP-bestand
xhr.onload = function () {
    if (xhr.status === 200) {
        // Verwerk de JSON-response van PHP
        const response = JSON.parse(xhr.responseText);
        console.log("Response van server:", response); // Log de volledige response

        if (response.success) {
            // Als het formulier succesvol is verwerkt, toon succesbericht
            const verzendknopDiv = document.getElementById("verzendknop");
            const verzendMeldingdiv = document.getElementById("verzendknopMeldingdiv");
            verzendknopDiv.value = response.message;
            valideerInvoer(verzendknopDiv, verzendMeldingdiv, verzendknopMelding);
        } else {
            // Als er fouten zijn, koppel deze terug aan de juiste velden en functies
            console.log("Fouten ontvangen:", response.errors); // Log de ontvangen fouten

            for (let veld in response.errors) {
                const foutElement = document.getElementById(`${veld}Fout`);
                const veldElement = document.getElementById(veld);


                if (foutElement && veldElement) {
                    // Voeg de foutmelding toe aan het foutElement
                    foutElement.textContent = response.errors[veld];  // Stel de nieuwe waarde in vanuit de server
                    console.log(`Fout gevonden voor veld: ${veld}, fout: ${response.errors[veld]}`); // Log de fout en het veld
                    
                    // Roep de juiste validatiefunctie aan op basis van het veld
                    switch (veld) {
                        case 'contactpersoonvoornaam':
                            console.log("Uitvoeren validatie voor voornaam");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'contactpersoonachternaam':
                            console.log("Uitvoeren validatie voor achternaam");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'emailadres':
                            console.log("Uitvoeren validatie voor e-mailadres");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'schoolTelefoonnummer':
                            console.log("Uitvoeren validatie voor telefoonnummer");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'contactTelefoonnummer':
                            console.log("Uitvoeren validatie voor telefoonnummer");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'totaalbegeleiders':
                            console.log("Uitvoeren validatie voor aantal begeleiders");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'niveauleerjaar':
                            console.log("Uitvoeren validatie voor niveau en leerjaar");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'schoolnaam':
                            console.log("Uitvoeren validatie voor schoolnaam");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'adres':
                            console.log("Uitvoeren validatie voor adres");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'postcode':
                            console.log("Uitvoeren validatie voor postcode");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'plaats':
                            console.log("Uitvoeren validatie voor plaats");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'aantalLeerlingen':
                            console.log("Uitvoeren validatie voor aantal leerlingen");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'bezoekdatum':
                            console.log("Uitvoeren validatie voor bezoekdatum");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'remiseBreakAantal':
                            console.log("Uitvoeren validatie voor Remise break aantal");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'kazerneBreakAantal':
                            console.log("Uitvoeren validatie voor Kazerne break aantal");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'fortgrachtBreakAantal':
                            console.log("Uitvoeren validatie voor Fortgracht break aantal");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'waterijsjeAantal':
                            console.log("Uitvoeren validatie voor Waterijsje aantal");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'pakjeDrinkenAantal':
                            console.log("Uitvoeren validatie voor Pakje drinken aantal");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'remiseLunchAantal':
                            console.log("Uitvoeren validatie voor Remise lunch aantal");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        case 'eigenPicknickCheckbox':
                            console.log("Uitvoeren validatie voor Eigen picknick checkbox");
                            valideerInvoer(veldElement, foutElement, verzendknopFoutMelding);
                            break;
                        default:
                            const verzendknopDiv = document.getElementById("verzendknop");
                            const verzendMeldingdiv = document.getElementById("verzendknopMeldingdiv");
                            verzendknopDiv.value = response.message;
                            valideerInvoer(verzendknopDiv, verzendMeldingdiv, verzendknopFoutMelding);
                            break;
                    }
                    
                }
            }
        }
    } 
};

xhr.send(formData);  // Verstuur de formulierdata naar PHP  
    
});

});
