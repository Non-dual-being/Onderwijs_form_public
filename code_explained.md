# Functie 1: werkKeuzeModulesBij1
## 1.1 Algemeen uitleg
Deze functie werkt de keuzemodules bij op basis van het geselecteerde onderwijsniveau en toont de standaardmodules. Het reset ook het keuzemoduleveld telkens wanneer een ander onderwijsniveau wordt gekozen.

## 1.2 Onderdeel in het geheel
Deze functie wordt aangeroepen wanneer het onderwijsniveau verandert.
Roept de functie werkGeselecteerdeModulesWeergaveBij2() op om de geselecteerde modules te tonen.

## 1.3 Regel voor regel
```jsx
function werkKeuzeModulesBij1() {
    const onderwijsNiveau1 = document.getElementById('onderwijsNiveau').value;
    ...
}

```
>Haalt de waarde op van het geselecteerde onderwijsniveau door het element met het ID onderwijsNiveau te selecteren.

```jsx
    const keuzeModuleSelect1 = document.getElementById('keuzeModule');
```

```jsx
keuzeModuleSelect1.innerHTML = '';
```
>Leegt de inhoud van het dropdown-element om te resetten, zodat het kan worden bijgewerkt met nieuwe keuzemodules.

```jsx
    if (onderwijsNiveau1 in onderwijsModules) {
            onderwijsModules[onderwijsNiveau1].keuze.forEach(module1 => {
            const optie1 = document.createElement('option');
            optie1.value = module1;
            optie1.textContent = module1;
            keuzeModuleSelect1.appendChild(optie1);
        });
}
```

```jsx
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
```
>Controleert of het geselecteerde onderwijsniveau bestaat in het object onderwijsModules, dat alle beschikbare modules per niveau bevat.
>Loopt door de lijst met beschikbare keuzemodules voor het geselecteerde onderwijsniveau, maakt een nieuw option element voor elke module, stelt de value en textContent in, en voegt deze toe aan het keuzemodule-dropdown-element. Optie1 hoeft geen onderscheid te maken tussen de waarden in de keuze array, het is puur voor visuele representatie.

```jsx
function werkKeuzeModulesBij1() {
    ...
    werkGeselecteerdeModulesWeergaveBij2();
}
```
Roept de functie werkGeselecteerdeModulesWeergaveBij2() aan om de standaardmodules en geselecteerde keuzemodule(s) in de interface bij te werken.

# Functie 2: werkGeselecteerdeModulesWeergaveBij2
## 2.1 Algemeen uitleg
Deze functie werkt de weergave van de standaardmodules en de geselecteerde keuzemodule bij in de interface op basis van het gekozen onderwijsniveau en keuzemodule.

## 2.2 Onderdeel in het geheel
Wordt aangeroepen na het selecteren van een onderwijsniveau of keuzemodule.
Werkt samen met werkKeuzeModulesBij1 om de selectie van modules te actualiseren en weer te geven.

## 2.3 Regel voor regel
```jsx
function werkGeselecteerdeModulesWeergaveBij2() {
    const onderwijsNiveau2 = document.getElementById('onderwijsNiveau').value;
    const keuzeModule2 = document.getElementById('keuzeModule').value;
    ...
}
```
>Haalt het geselecteerde onderwijsniveau en de keuzemodule op van de betreffende input-elementen.

```jsx
    const standaardModulesLijst2 = document.getElementById('standaardModulesLijst');
    standaardModulesLijst2.innerHTML = '';

```
>Selecteert het HTML-element met het ID standaardModulesLijst en reset de lijst door de inhoud leeg te maken.

```jsx
    if (onderwijsNiveau2 in onderwijsModules) {
        onderwijsModules[onderwijsNiveau2].standaard.forEach(module2 => {
            const lijstItem2 = document.createElement('li');
            lijstItem2.textContent = module2;
            standaardModulesLijst2.appendChild(lijstItem2);
        });
    }
```

>Controleert of het geselecteerde onderwijsniveau bestaat in het onderwijsModules object, en indien dat het geval is, maakt het een lijst van de standaardmodules door elk item als een li-element aan de standaardModulesLijst toe te voegen.

```jsx
function werkGeselecteerdeModulesWeergaveBij2() { ...
    const gekozenKeuzeModule2 = document.getElementById('gekozenKeuzeModule');
    gekozenKeuzeModule2.textContent = keuzeModule2 || 'Geen keuzevak geselecteerd';
}
```
>Selecteert het HTML-element met het ID gekozenKeuzeModule en werkt de tekst bij om de geselecteerde keuzemodule weer te geven, of toont een standaardbericht als geen keuzemodule is geselecteerd.

# Functie 3: werkGroepAantalBij3()
## 3.1 Algemeen uitleg
Deze functie berekent op basis van het aantal leerlingen hoeveel groepen er nodig zijn en toont dit op de pagina. Dit aantal is belangrijk voor het bepalen van het rooster en het aantal begeleiders.

## 3.2 Onderdeel in het geheel
Deze functie wordt aangeroepen wanneer het aantal leerlingen wordt ingevoerd of gewijzigd.
Het aantal groepen dat nodig is wordt berekend en weergegeven op de pagina.
De functie werkt samen met de geselecteerde keuzemodule en het onderwijsniveau om het juiste roosterbeeld en downloadmogelijkheden te tonen.

## 3.3 Regel voor regel 

```jsx
function werkGroepAantalBij3() {
...
}
```

```jsx
const aantalLeerlingen3 = parseInt(document.getElementById('aantalLeerlingen').value, 10);

```
>aantalLeerlingen3: Haalt de waarde op van het inputveld met het id aantalLeerlingen. Deze waarde wordt omgezet naar een integer (geheel getal) met parseInt(). De 10 staat voor het gebruiken van het decimale talstelsel bij het omzetten.

```jsx
const onderwijsNiveau3 = document.getElementById('onderwijsNiveau').value;
const keuzeModule3 = document.getElementById('keuzeModule').value;
```

```jsx
let groepAantal3 = '';
```

```jsx
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

```

>spreekt voor zich

```jsx
document.getElementById('groepAantal').textContent = groepAantal3;
```

```jsx
const roosterAfbeeldingContainer3 = document.getElementById('roosterAfbeeldingContainer');
const roosterAfbeelding3 = document.getElementById('roosterAfbeelding');
const downloadButton = document.getElementById('downloadRooster');
```

>roosterAfbeeldingContainer3: Verwijst naar de container die de afbeelding van het rooster bevat.
>roosterAfbeelding3: Verwijst naar het daadwerkelijke <img>-element waar het rooster als afbeelding zal worden getoond.
>downloadButton: Verwijst naar de knop die de gebruiker kan gebruiken om het rooster te downloaden.

```jsx
if (onderwijsNiveau3 && keuzeModule3 && groepAantal3 !== '-') {...}
```

Controleert of zowel het onderwijsniveau als de keuzemodule zijn geselecteerd, en of er een geldig aantal groepen is berekend (geen '-')


```jsx
const roosterBestand3 = beschikbareRooster[onderwijsNiveau3]?.[keuzeModule3]?.[groepAantal3];
```

>roosterBestand3: Probeert het juiste roosterbestand op te halen uit het beschikbareRooster object op basis van het onderwijsniveau, de keuzemodule, en het berekende aantal groepen.
>De ?.-operator (optionele chaining) zorgt ervoor dat de code geen fout veroorzaakt als een van deze waarden niet bestaat. Als het roosterbestand niet beschikbaar is, zal roosterBestand3 undefined zijn.

```jsx
if (roosterBestand3) {
    roosterAfbeeldingContainer3.style.display = 'block';
    roosterAfbeelding3.src = `./${roosterBestand3}`;
    downloadButton.style.display = 'block';
    downloadButton.onclick = function (event) {
        event.preventDefault();
        const pdfFileName = roosterBestand3.replace('.png', '.pdf'); // in de lijst staan pngtjes maar in de directorty sstaan pdfjes
        const link = document.createElement('a');
        link.href = `./${pdfFileName}`;
        link.target = '_blank'; // Open in a new tab
        link.click();
    };
} else {
    roosterAfbeeldingContainer3.style.display = 'none';
    downloadButton.style.display = 'none';
}

```

if (roosterBestand3): Als een roosterbestand is gevonden, wordt het volgende uitgevoerd:
Het roosterAfbeeldingContainer3 wordt zichtbaar gemaakt.
Het src-attribuut van roosterAfbeelding3 wordt ingesteld op de juiste afbeelding.
De downloadknop wordt zichtbaar en de onclick-functie wordt ingesteld. Wanneer de gebruiker op de downloadknop klikt, wordt het corresponderende PDF-roosterbestand geopend in een nieuw tabblad.
else: Als er geen roosterbestand beschikbaar is, worden zowel de container als de downloadknop verborgen.


# Event listener 1
```jsx
document.getElementById('onderwijsNiveau').addEventListener('change', () => {
    werkKeuzeModulesBij1();
    werkGroepAantalBij3();
});
```
## E 1.1 Algemene uitleg
Deze event listener zorgt ervoor dat zodra de gebruiker het onderwijsniveau (bijvoorbeeld "primair onderwijs" of "voortgezet onderwijs") verandert, twee functies worden aangeroepen:

## E 1.2 Onderdeel in het geheel 
werkKeuzeModulesBij1(): Werkt het keuzemenu voor de modules bij.
werkGroepAantalBij3(): Berekent het aantal groepen op basis van het aantal leerlingen.
Onderdeel in het geheel
De listener grijpt in op de verandering van het onderwijsniveau.
Werkt samen met de rest van de functies om de juiste keuzemodules en groepen weer te geven.

## regel voor regel
> de eventlistener kijkt naar verandering in het veld onderwijsNiveau
> `() =>` Dit is een arrow functie een verkorte manier om een functie schrijven 
> Bij verandering op deze velden worden de functies aangeroepen de in de body staan

# Event listener 2
```jsx
document.getElementById('keuzeModule').addEventListener('change', () => {
    werkGeselecteerdeModulesWeergaveBij2();
    werkGroepAantalBij3();
});
```
zelfde verhaal als bij 1

# Event listener 3
```jsx
document.getElementById('aantalLeerlingen').addEventListener('input', werkGroepAantalBij3);
```

zelfde maar dan op een inputveld

# Event listener 4
```jsx
document.getElementById('aantalLeerlingen').addEventListener('input', function () {
const aantalLeerlingen = this.value;
const foutMelding = document.getElementById('aantalLeerlingenFout');

if (aantalLeerlingen === '') {
    foutMelding.style.display = 'none';
    this.setCustomValidity('');
} else if (aantalLeerlingen < 40 || aantalLeerlingen > 160) {
    foutMelding.style.display = 'block';
    this.setCustomValidity('Het aantal leerlingen moet tussen 40 en 160 liggen.');
} else {
    foutMelding.style.display = 'none';
    this.setCustomValidity('');
}
});
```

# E4.1 algemeen
Het biedt een foutmelding als aantal leerlingen niet goed is

# E4.2 Ondedeel in het geheel
Het zit op het invoerveld aantal leerlingen

# E4.3 Regel voor rHoe werkt setCustomValidity()?
Functie: setCustomValidity() stelt een aangepaste foutmelding in op een formulierinvoerveld (zoals <input>, <textarea>, etc.). Als een niet-lege foutmelding is ingesteld, zal het formulier niet worden verzonden totdat de fout is opgelost.
Parameter: De functie accepteert één parameter, namelijk een string met de foutmelding. Als de string leeg is (''), wordt de validatie als geslaagd beschouwd, en het invoerveld wordt weer valide.
Effect: Wanneer de functie wordt gebruikt, wordt het invoerveld als "ongeldig" gemarkeerd totdat de fout is opgelost. De browser toont deze foutmelding meestal automatisch bij het verzenden van het formulier, of zodra de gebruiker probeert verder te gaan met invoer.

# Event listener 5
```jsx
document.addEventListener('DOMContentLoaded', () => {
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
});
```

# E 5.1 Algemeen
De eventlistener gaat in als het document is geladen. Als er over de aakomsttijd of vetrekt wordt gehover krijgt de gebruiker een boodschap te zien

# E5.2 Onderdeel in het geheel
Spreek voor zich

# E5.3 Regel voor regel
de dataset haalt de waarde uit de div zelf en gaat dan die content weergeven, de div is nodig om de postitie te bepalen van de message


# functie 4
```jsx
function scheduleHoverMessage4(message, element) {
clearTimeout(hoverTimeout);
hoverTimeout = setTimeout(() => showHoverMessage6(message, element), 200);
}
```
# 4.1 algemeen
De functie zet een timeout in voor de hovermessage en roept de showhovermeessage aan

# 4.3
Dit is om de hovermeessage rustiger te laten verlopen. Cleartimeout leegt een eerder waarde als die nog actief is, en dan is er vertraging van 0.2 seconden


# functie 5
```jsx
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
```

# 5.1 algemeen
Functie om een hover te laten zien

# 5.2 onderdeel in het geel
spreek voor zich

# 5.3 regel voor regel 
> let hoverMessageElement = document.querySelector('.hover-message'); zoek naar een element naar die kasse
> Als het niet gevonden wordt, dan wordt het aangemaakt, en aan de body toegeveogd
> Deze regel gebruikt de methode getBoundingClientRect() om de positie en afmetingen van het element (dat de hover-actie triggert) te verkrijgen.
> window.scrollY is de moderne manier om dit te doen, maar window.pageYOffset is de oudere manier die hier als fallback wordt gebruikt voor compatibiliteit met oudere browsers.
> De rect left is de linkerkant waarover gehoverd wordt. De begint op dezelfde afstand als de div
> bij de rect.top van dif wordt het boven de de div geplaatst (rect.top van div - (hoogte hover + 10px marge))

# 6.1 functie 6
```jsx
function hideHoverMessage7() {
const hoverMessageElement = document.querySelector('.hover-message');
if (hoverMessageElement) {
    hoverMessageElement.style.opacity = '0';
    setTimeout(() => {
        hoverMessageElement.style.display = 'none';
    }, 300);
}
}
```

# 5.3
De message wordt direct op onzichtbaar gezet en na 0.3 seconden helemaal weggehaald

# Eventlistener 6

```jsx
document.addEventListener('DOMContentLoaded', () => {
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
});
```
# 6.1 en 6.2 algemeen en onderdeel in het geheel
Spreekt voor zich , keuze in het eten bijhouden

# 6.3 Regel voor Regel

```jsx
const foodInputs = document.querySelectorAll('input[type="checkbox"][name="snack"], input[type="checkbox"][name="lunch"]');
```

Pakt alle checkboxes met de naam snack en lunch (zie html strutuur)

```jsx
const amountInput = input.closest('.snack-option').querySelector('input[type="number"]');
```

Input.clostest gaat omhoog in DOm structuur en zoekt naar de eerst volgende ouder div met klasse snackoption en daaran gat querySelector omlaag en zoekt het eervolgende kind wat een invoerveld is van type nummer

```jsx
if (amountInput) {
            amountInput.disabled = !this.checked;
            if (!this.checked) {
                amountInput.value = 0;
            }
        }
```
Het zet de waarde op 0 als de checkboxes is uitgevinkt, disabled wordt waar als de checkbox is uitgevinkt en disabled wordt False als aangevinkt


```jsx
   const amountInput = input.closest('.snack-option').querySelector('input[type="number"]');
    if (amountInput) {
        amountInput.addEventListener('input', updateFoodSummary9);
    }
```
Deze luister naar veranderingen in de input velden van snack en lunches en als er eeen verandering optreedt dan wordt de functies updateFoodSummart aangeroepen


```jsx
 const itemName = input.labels[0].innerText.split(':')[0]; // Extract the name before the colon
```
input.labels[0] haalt het eerste label op dat gekoppeld is aan het input-element via het for-attribuut.
Labels worden aan een input gekoppeld door een for-attribuut dat verwijst naar het id van het input-element.
In dit geval is <label for="remiseBreakCheckbox"> gekoppeld aan de checkbox met id="remiseBreakCheckbox".
Het label "Aantal:" is gekoppeld aan het numerieke invoerveld en hoort bij het input-element met id="remiseBreakAantal".
input.labels bevat alleen labels die direct aan dat specifieke input-element zijn gekoppeld.


```jsx
    function calculateVisitPrice8() {
        const aantalLeerlingen = parseInt(document.getElementById('aantalLeerlingen').value, 10) || 0;
        const totaalBegeleiders = parseInt(document.getElementById('totaalbegeleiders').value, 10) || 0;
        const prijsPerLeerling = 20;

        const gratisBegeleiders = Math.floor(aantalLeerlingen / 8);
        const teBetalenBegeleiders = Math.max(0, totaalBegeleiders - gratisBegeleiders);

        const bezoekPrijs = (aantalLeerlingen * prijsPerLeerling) + (teBetalenBegeleiders * prijsPerLeerling);
        return bezoekPrijs.toFixed(2);
    }
```

# 7.1 en 7.2 algemeen en onderdeel in het geheel
Het berekent de bezoekprijs op basis van aantal leerlingen en aantal begeleiders.

# 7.3 Regel voor regel Verkrijgen van het aantal leerlingen

```jsx
const aantalLeerlingen = parseInt(document.getElementById('aantalLeerlingen').value, 10) || 0;
```

- document.getElementById('aantalLeerlingen').value: Haalt de waarde op uit het inputveld met het ID aantalLeerlingen.
- parseInt(..., 10): Converteert de opgehaalde waarde naar een geheel getal (base 10).
- || 0: Dit zorgt ervoor dat als de opgehaalde waarde leeg of niet beschikbaar is, het aantal leerlingen wordt ingesteld op 0. Dit voorkomt dat er een fout optreedt als er geen waarde is ingevoerd.

```jsx
const totaalBegeleiders = parseInt(document.getElementById('totaalbegeleiders').value, 10) || 0;
```
Precies hetzelfde


```jsx
const gratisBegeleiders = Math.floor(aantalLeerlingen / 8);
```

- aantalLeerlingen / 8: Voor elke 8 leerlingen mag er 1 gratis begeleider meekomen.
- Math.floor(...): Ronde het resultaat van de deling naar beneden af naar het dichtstbijzijnde geheel getal. Bijvoorbeeld, als er 25 - - - - leerlingen zijn, krijg je 3 gratis begeleiders (25/8 = 3,125 → 3).

```jsx
const teBetalenBegeleiders = Math.max(0, totaalBegeleiders - gratisBegeleiders);
```

- totaalBegeleiders - gratisBegeleiders: Bereken hoeveel begeleiders er nog overblijven na het aftrekken van de gratis begeleiders.
- Math.max(0, ...): Deze functie zorgt ervoor dat het aantal te betalen begeleiders nooit negatief is. Als het verschil tussen
- totaalbegeleiders en gratis begeleiders negatief zou zijn, wordt het aantal te betalen begeleiders ingesteld op 0.


```jsx
const bezoekPrijs = (aantalLeerlingen * prijsPerLeerling) + (teBetalenBegeleiders * prijsPerLeerling);
```
```jsx
return bezoekPrijs.toFixed(2);
```

bezoekPrijs.toFixed(2): Dit rondt de totale bezoekprijs af op twee decimalen en retourneert het resultaat als een string. Dit zorgt ervoor dat het bedrag netjes wordt weergegeven, bijvoorbeeld als "150.00" in plaats van "150".


# Uitleg van de `updateFoodSummary9` functie

De `updateFoodSummary9` functie verzamelt informatie over de geselecteerde voedingsopties en hun hoeveelheden, berekent de totale prijs van de bestelde items en voegt deze toe aan de bezoekprijs. Het resultaat wordt weergegeven op de pagina.

## 1. Initialiseren van de totaalprijs

```jsx
let totalPrice = 0;
foodSummaryDiv.innerHTML = '';
```

- **totalPrice = 0**: Stelt de variabele `totalPrice` in op 0, dit wordt gebruikt om de totale prijs van de bestelde items bij te houden.
- **foodSummaryDiv.innerHTML = ''**: Leegt de inhoud van het HTML-element `foodSummaryDiv` om ervoor te zorgen dat de lijst met voedingsopties opnieuw kan worden opgebouwd.

## 2. Loopen door de geselecteerde voedselopties

```jsx
foodInputs.forEach(input => {
    const amountInput = input.closest('.snack-option').querySelector('input[type="number"]');
    ...
})
```

- **foodInputs.forEach(...)**: Loopt door alle voedingsopties (snacks en lunches) die als invoer beschikbaar zijn.
- **input.closest('.snack-option').querySelector('input[type="number"]')**: Zoekt naar het dichtstbijzijnde element met de klasse `snack-option` en vervolgens naar het invoerveld van het type nummer dat bij deze optie hoort.

## 3. Controleren of de optie is geselecteerd

```jsx
if (input.checked && amountInput && amountInput.value > 0) {
    const itemTotal = (input.value * amountInput.value).toFixed(2);
    totalPrice += parseFloat(itemTotal);
```

- **input.checked**: Controleert of de voedingsoptie is aangevinkt.
- **amountInput && amountInput.value > 0**: Controleert of er een numerieke waarde is ingevoerd voor deze voedingsoptie.
- **itemTotal = (input.value * amountInput.value).toFixed(2)**: Vermenigvuldigt de waarde van de optie met de ingevoerde hoeveelheid en rondt het resultaat af op twee decimalen.
- **totalPrice += parseFloat(itemTotal)**: Voegt de prijs van het huidige item toe aan de totale prijs.

## 4. Aanmaken van een samenvatting voor het voedingsitem

```jsx
const itemSummary = document.createElement('div');
itemSummary.className = 'summary-item';
const itemName = input.labels[0].innerText.split(':')[0]; // Extract the name before the colon
itemSummary.innerHTML = `<span>${amountInput.value} ${itemName}:</span><span>€${itemTotal}</span>`;
foodSummaryDiv.appendChild(itemSummary);
```

- **document.createElement('div')**: Maakt een nieuw `div`-element voor de samenvatting van de voedingsoptie.
- **itemName = input.labels[0].innerText.split(':')[0]**: Haalt de naam van de optie op uit het label, vóór de dubbele punt.
- **foodSummaryDiv.appendChild(itemSummary)**: Voegt de gemaakte samenvatting toe aan het `foodSummaryDiv`-element.

## 5. Controleren of er een optie zonder aantal is geselecteerd

```jsx
} else if (input.checked && !amountInput) {
    const itemSummary = document.createElement('div');
    itemSummary.className = 'summary-item';
    itemSummary.innerHTML = `<span>${input.labels[0].innerText}</span>`;
    foodSummaryDiv.appendChild(itemSummary);
}
```

- **input.checked && !amountInput**: Controleert of de optie is geselecteerd maar geen invoerveld voor het aantal heeft (bijvoorbeeld bij een vaste optie).
- **Voegt de naam van de optie toe zonder een bedrag.**

## 6. Toevoegen van de bezoekprijs

```jsx
const bezoekPrijs = calculateVisitPrice8();
bezoekDiv.innerHTML = `<span>Bezoekprijs:</span><span>€${bezoekPrijs}</span>`;
totalPrice += parseFloat(bezoekPrijs);
```

- **calculateVisitPrice8()**: Roept de functie aan om de totale prijs voor het bezoek te berekenen op basis van het aantal leerlingen en begeleiders.
- **bezoekDiv.innerHTML**: Vult de bezoekprijs in het juiste HTML-element.
- **totalPrice += parseFloat(bezoekPrijs)**: Voegt de bezoekprijs toe aan de totale prijs.

## 7. Bijwerken van de totaalprijs op de pagina

```jsx
totalPriceSpan.textContent = totalPrice.toFixed(2);
```

- **totalPrice.toFixed(2)**: Rondt de totale prijs af op twee decimalen en stelt dit in als tekst in het `totalPriceSpan`-element.



# Snack en lunches ophalen

# Uitleg van de Code

De volgende code zorgt ervoor dat een lijst met bestelde snacks en lunches dynamisch wordt samengesteld op basis van de ingevoerde hoeveelheden. Hierbij wordt ook de prijs van elke snack en lunch berekend en netjes weergegeven.

```jsx

```

## 1. Snacks en Lunches controleren

```jsx
${snacks.some(snack => snack.amount > 0) ? 'Bestelde Snacks\n' : ''}${snacks.filter(snack => snack.amount > 0).map(snack => `${leftPadding}${padString(` - ${snack.amount} ${snack.name}:`, `€${(snack.amount * snack.price).toFixed(2)}`)}`).join('\n')}
${lunches.some(lunch => lunch.amount > 0) ? 'Bestelde Lunches\n' : ''}${lunches.filter(lunch => lunch.amount > 0).map(lunch => `${leftPadding}${padString(` - ${lunch.amount} ${lunch.name}:`, `€${(lunch.amount * lunch.price).toFixed(2)}`)}`).join('\n')}
```

## 1.1 Snacks en Lunches controleren

```jsx
${snacks.some(snack => snack.amount > 0) ? 'Bestelde Snacks\n' : ''}
```
- **`snacks.some(snack => snack.amount > 0)`**: Controleert of er minstens één snack is waarvan de `amount` (hoeveelheid) groter is dan 0.
- Als dit waar is, dan wordt de string `'Bestelde Snacks\n'` weergegeven. Anders wordt een lege string `''` weergegeven.

## 2. Filteren en tonen van bestelde snacks en lunches

```jsx
${snacks.filter(snack => snack.amount > 0).map(snack => `${leftPadding}
```

- **`filter(snack => snack.amount > 0)`**: Filtert de snacks-array en haalt alleen de snacks op waarvan de hoeveelheid (`amount`) groter is dan 0.
- **`map(snack => ...)`**: Loopt door de gefilterde lijst van snacks en genereert een string voor elke snack met de hoeveelheid, naam en de totale prijs.
- **`leftPadding`**: Een reeks spaties om de uitlijning te verzorgen.

```jsx
${padString(` - ${snack.amount} ${snack.name}:`, `€${(snack.amount * snack.price).toFixed(2)}`)}`).join('\n')}
```

```jsx
function initFlatpickr(unavailableDates, limitedAvailabilityDates, fullyBookedDates, availableDates) {
    flatpickr("#bezoekdatum", {
        locale: "nl",  // Locale Nederlands instellen
        enableTime: false,
        dateFormat: "Y-m-d",
        disable: unavailableDates.map(date => new Date(date)),  // Onbeschikbare datums disablen
        ...
    })
    })
```

function initFlatpickr(...): Dit is de functie die de flatpickr-initialisatie uitvoert. Hierin worden verschillende datums (zoals onbeschikbare en volgeboekte) als argumenten doorgegeven.

flatpickr("#bezoekdatum", {...}): Dit start flatpickr op het element met het id bezoekdatum. Dit is waarschijnlijk het invoerveld waar de gebruiker een bezoekdatum kan selecteren.

locale: "nl": Dit stelt de locale van flatpickr in op Nederlands, zodat de kalender in het Nederlands wordt weergegeven (dagen, maanden, enz.).

enableTime: false: Hiermee wordt aangegeven dat er geen tijdkeuze nodig is. De gebruiker selecteert alleen een datum.
dateFormat: "Y-m-d": Dit bepaalt het formaat van de datum in het invoerveld. Het is ingesteld op het ISO-formaat (jaar-maand-dag).

disable: unavailableDates.map(date => new Date(date)): De onbeschikbare datums worden gemapt naar Date-objecten, zodat flatpickr weet welke datums niet selecteerbaar zijn.

```jsx
        onDayCreate: function (dObj, dStr, fp, dayElem) {
            const dayTimestamp = dayElem.dateObj.setHours(0, 0, 0, 0); // Normale timestamp zonder tijd
            const formattedDate = dayElem.dateObj.toLocaleDateString('nl-NL'); // Locale formatted
            ...
        }
 
```
onDayCreate: function(...): Dit is een callback-functie die wordt aangeroepen wanneer elke dag in de kalender wordt gegenereerd. Dit stelt ons in staat om per dag stijlen toe te passen.

const dayTimestamp = dayElem.dateObj.setHours(0, 0, 0, 0);: Dit zet het Date-object om naar een timestamp zonder uren/minuten/seconden, zodat alleen de datum wordt vergeleken (en niet de tijd).

const formattedDate = dayElem.dateObj.toLocaleDateString('nl-NL');: Dit formatteert de datum op de Nederlandse manier (dd-mm-jjjj), wat handig is voor logging en debuggen.

```jsx
            const unavailableTimestamps = unavailableDates.map(date => new Date(date).setHours(0, 0, 0, 0));
            const fullyBookedTimestamps = fullyBookedDates.map(date => new Date(date).setHours(0, 0, 0, 0));
            const limitedAvailabilityTimestamps = limitedAvailabilityDates.map(date => new Date(date).setHours(0, 0, 0, 0));
            const availableTimestamps = availableDates.map(date => new Date(date).setHours(0, 0, 0, 0));
```
new Date is een ingebouwde javascrip functie die een date omzet in data-time formaat, hier wordt het op een lege tijd gezet omdat alleen de datum van belang is. Map gaat gwn alle data in de array langs


```sql
DROP TABLE IF EXISTS `aanvragen`;
CREATE TABLE IF NOT EXISTS `aanvragen` (
  `id` int NOT NULL AUTO_INCREMENT,
  `schoolnaam` varchar(255) NOT NULL,
  `voornaam_contactpersoon` varchar(255) NOT NULL,
  `achternaam_contactpersoon` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefoon` varchar(20) DEFAULT NULL,
  `aantal_leerlingen` int DEFAULT NULL,
  `schooltype` enum('Primair Onderwijs','Voortgezet Onderwijs - Onderbouw','Voortgezet Onderwijs - Bovenbouw') NOT NULL,
  `keuze_module` enum('Minecraft-Klimaatspeurtocht','Earth-Watch','Minecraft-Windenergiespeurtocht','Stop-de-Klimaat-Klok','Crisismanagement') NOT NULL,
  `status` enum('In optie','Definitief','Afgewezen') NOT NULL DEFAULT 'In optie',
  `bezoekdatum` date NOT NULL,
  `opmerkingen` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

```

```php
    // E-mail validatie
    if (empty($_POST['emailadres']) || !filter_var($_POST['emailadres'], FILTER_VALIDATE_EMAIL)) {
        $errors['emailadres'] = "Ongeldig e-mailadres.";
    } else {
        $email = sanitize_input($_POST['emailadres'], 100);
    }
```


```md
filter_var()
De functie filter_var() wordt in PHP vaak gebruikt om data te valideren of te filteren. Het neemt twee verplichte argumenten:

De te valideren of te filteren waarde (hier: $_POST['emailadres']).
Het type validatie of filter dat je wilt toepassen (hier: FILTER_VALIDATE_EMAIL).
In jouw geval valideert de functie of de ingevoerde waarde een geldig e-mailadres is.

Voordelen van filter_var() boven preg_match() voor e-mailvalidatie:

Standaard ingebouwde validatie: FILTER_VALIDATE_EMAIL volgt de specificaties voor een geldig e-mailadres volgens de RFC 5321/5322 standaarden. Het bevat daarom ingebouwde ondersteuning voor de meeste edge-cases.
Eenvoudiger te gebruiken: In plaats van een complexe reguliere expressie met preg_match(), maakt filter_var() de validatie veel simpeler en leesbaarder.
Betrouwbaarheid: Reguliere expressies zijn moeilijk om volledig correct te krijgen als het gaat om e-mailvalidatie, vooral als je rekening houdt met alle mogelijke gevallen van e-mailformaten (bijv. plus-adressering, subdomeinen, etc.).
filter_var($_POST['emailadres'], FILTER_VALIDATE_EMAIL)
$_POST['emailadres']: Dit is de input die een gebruiker via een formulier heeft ingevuld.
FILTER_VALIDATE_EMAIL: Dit vertelt de functie om te controleren of de input een geldig e-mailadres is.
Als het ingevoerde e-mailadres geldig is, retourneert filter_var() de waarde zelf (het e-mailadres). Als het ongeldig is, retourneert het false.
```


```php
$datumObject = DateTime::createFromFormat('d F Y', $ontvangenDatumEngels);
```

1. DateTime class in PHP
De DateTime class in PHP is een zeer krachtige en flexibele manier om met datum- en tijdgegevens te werken. Deze class biedt vele methoden om datums en tijden te maken, te manipuleren en weer te geven.

2. DateTime::createFromFormat()
De methode createFromFormat() wordt gebruikt om een DateTime object te creëren vanuit een string die een datum of tijd voorstelt, met een specifiek formaat.

De gebruikelijke manier om een datum te creëren in PHP is via new DateTime(), maar als de datumstring in een niet-standaardformaat is (dus niet een formaat dat PHP automatisch begrijpt), kun je met createFromFormat() expliciet aangeven hoe de datumstring is opgebouwd.

3. De structuur van createFromFormat()
De methode heeft twee argumenten:

Het formaat van de datum: Dit geeft aan hoe de datum is opgebouwd in de string. In dit geval: 'd F Y'.
d: Dag van de maand, twee cijfers met voorloopnullen (01 tot 31).
F: Volledige maandnaam, bijvoorbeeld "January", "February", etc.
Y: Volledig jaartal, vier cijfers (bijvoorbeeld "2024").
De datumstring zelf: Dit is de string die je wilt omzetten naar een DateTime object. In dit geval wordt dit weergegeven door de variabele $ontvangenDatumEngels. Deze string zou bijvoorbeeld kunnen zijn: "25 September 2024".

4. Context en gebruik
Als we dit in de context plaatsen, stel je voor dat je een datum ontvangt in een tekstueel formaat zoals "25 September 2024" (dit is typisch Engels omdat de maand volledig in het Engels geschreven wordt). Deze string is niet direct bruikbaar in veel situaties waarin je een datum als een PHP DateTime object nodig hebt. Met de createFromFormat() methode kun je deze string eenvoudig omzetten naar een DateTime object zodat je er verder mee kunt werken (bijvoorbeeld om datumberekeningen uit te voeren, het formaat te veranderen, enz.).

```php
$ontvangenDatumEngels = '25 September 2024';
$datumObject = DateTime::createFromFormat('d F Y', $ontvangenDatumEngels);

// $datumObject is nu een DateTime object en je kunt er verder mee werken:
echo $datumObject->format('Y-m-d');  // Geeft: 2024-09-25
```

