<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoFort Onderwijsaanvraag</title>
    <link rel="stylesheet" href="styles.css">
    <script src="aanvraagscript.js"></script>
</head>
<body>
    <h1>ONDERWIJS AANVRAAG-FORMULIER</h1>
    <form id="onderwijsFormulier" method="post">
        <fieldset>
            <legend>Basis-Gegevens</legend>
            <label for="contactpersoonvoornaam">Voornaam contactpersoon</label >
            <input type="text" id="contactpersoonvoornaam" name="contactpersoonvoornaam" required >

            <label for="contactpersoonachternaam">Achternaam contactpersoon</label>
            <input type="text" id="contactpersoonachternaam" name="contactpersoonachternaam" required >
            
            <label for="emailadres">E-mailadres</label>
            <input type="email" id="emailadres" name="emailadres" required>
            <div id="emailError" class="foutmeldingmail">Vul een geldig e-mailadres in.</div>
            
            <label for="telefoonnummer">Telefoonnummer</label>
            <input type="tel" id="telefoonnummer" name="telefoonnummer" >
            
            <label for="totaalbegeleiders">Aantal begeleiders</label>
            <input type="number" id="totaalbegeleiders" name="totaalbegeleiders" min="1" max="50" step="1" > 
            
            <label for="niveauleerjaar">Niveau en Leerjaar</label>
            <input type="text" id="niveauLeerjaar" name="niveauleerjaar" >
            
            <label for="schoolnaam">Naam school</label>
            <input type="text" id="schoolnaam" name="schoolnaam" >
            
            <label for="adres">Adres</label>
            <input type="text" id="adres" name="adres" >
            
            <label for="postcodeplaats">Postcode en plaats</label>
            <input type="text" id="postcodeplaats" name="postcodeplaats" >
            
            <label for="bezoekdatum">Datum bezoek</label>
            <input type="date" id="bezoekdatum" name="bezoekdatum" >
            
            <label for="aankomsttijd">Wij verwachten u om</label>
            <input type="time" id="aankomsttijd" name="aankomsttijd" value="10:00" readonly required data-hover-message="Standaard aankomsttijd">

            <label for="vertrektijd">Het vertrek is om</label>
            <input type="time" id="vertrektijd" name="vertrektijd" value="15:00" readonly required data-hover-message="Standaard vertrektijd">
            
            <label for="hoekentGeoFort">Hoe kent u GeoFort?</label>
            <input type="text" id="hoekentGeoFort" name="hoekentGeoFort">
        </fieldset>

        <fieldset>
            <legend>Praktische Informatie</legend>
            <div class="informative-text">
                <h4>Kosten en Voorwaarden</h4>
                <p>
                    • Minimaal 40 leerlingen, maximaal 160 leerlingen.<br>
                    • €20 per leerling (incl. BTW), per 8 leerlingen is 1 begeleider gratis. Deze kosten zijn inclusief:
                </p>
                <ul class="secondary-list">
                    <li>BTW</li>
                    <li>De indeling van de dag d.m.v. een rooster</li>
                    <li>Begeleiding bij de meeste lesmodules</li>
                    <li>Een vegetarische snack en plantaardige chocolademelk voor de leerlingen (inbegrepen bij de lesmodule voedselinnovatie)</li>
                    <li>Bij aankomst een kop koffie of thee voor de docenten/begeleiders</li>
                    <li>Een onvergetelijke dag!</li>
                </ul>
                <h4>Tijdschema</h4>
                <p>
                    • Een bezoek kan plaatsvinden tussen 09:45 en 15:00 uur, met een maximum van 5 ronden. Na een welkomswoord start de eerste lesmodule om 10:15 en eindigt de laatste om 14:45.<br>
                    • Museumjaarkaart is niet geldig op onderwijsarrangementen. Cultuurkaart / CJP zijn wel geldig. Vul bij de opmerkingen naam, pashouder en CJP-nummer in.
                </p>
            </div>
        </fieldset>

        <fieldset class="lesprogramma">
            <legend>Lesprogramma</legend>
            <label for="onderwijsNiveau">Selecteer het Schooltype</label>
            <select id="onderwijsNiveau" name="onderwijsNiveau">
                <option value="primairOnderwijs">Primair Onderwijs</option>
                <option value="voortgezetOnderbouw">Voortgezet Onderwijs - Onderbouw</option>
                <option value="voortgezetBovenbouw">Voortgezet Onderwijs - Bovenbouw</option>
            </select>
        
            <div id="keuzeModuleSelectie">
                <label for="keuzeModule">Kies een lesmodule</label>
                <select id="keuzeModule" name="keuzeModule">
                    <!-- Keuzemodules worden hier dynamisch toegevoegd -->
                </select>
            </div>
        
            <label for="aantalLeerlingen">Vul het aantal leerlingen in</label>
            <input type="number" id="aantalLeerlingen" name="aantalLeerlingen" min="40" max="160" step="1" required placeholder="min=40, max=160">
            <div id="aantalLeerlingenFout" class="foutmelding">Vul alstublieft een geldig aantal van 40 tot en met 160 in.</div>
        </fieldset>
        

        <fieldset>
            <legend>Overzicht rooster</legend>
            <div class="informative-text">
                <div id="groepAantalWeergave">
                    <h4>Aantal groepen</h4>
                    <p id="groepAantal">-</p>
                </div>
                <div id="geselecteerdeModulesWeergave">
                    <h4>Standaard lesmodules</h4>
                    <ul id="standaardModulesLijst">
                        <!-- Standard modules will be populated here -->
                    </ul>
                    <h4>Keuze lesmodule</h4>
                    <ul id="gekozenKeuzeModule">
                        <!-- Keuze module will be populated here -->
                    </ul>
                </div>
                <div id="roosterWeergave">
                    <h4>Voorbeeldrooster</h4>
                    <div id="roosterAfbeeldingContainer">
                        <img id="roosterAfbeelding" src="" alt="Voorbeeldrooster">
                        <button id="downloadRooster" style="display:none;">Download rooster</button>
                    </div>
                </div>
            </div>
        </fieldset>
        
        <fieldset>
            <legend>Eten en Drinken</legend>
            <div class="informative-text">
                <h4>STANDAARD INBEGREPEN</h4>
                <ul>
                    <li>Koffie en thee voor begeleiders bij aankomst.</li>
                    <li>Vegetarische snack en plantaardige chocolademelk voor leerlingen tijdens de lesmodule voedselinnovatie</li>
                </ul>
                <h4>OPTITIONEEL BIJ TE BOEKEN</h4>
                <h5>Snacks</h5>
                <ul>
                    <li>remiseBreak, kazerneBreak en fortgrachtBreak</li>
                    <li>waterijsje en pakje drinken</li>
                </ul>
                <h5>Lunch</h5>
                <ul>
                    <li>Remiselunch </li>
                    <li>tweeBroodjesAantal</li>
                </ul>
            </div>
            <h4 class="snacks-heading">Snack keuze menu</h4>
            <span class="subtitle">Vink aan en vul een aantal in</span>
            <div class="snacks-section">
                <div class="snack-option">
                    <label for="remiseBreakCheckbox">Remise break: koek met limonade</label>
                    <span class="subtitle">€2.60</span>
                    <div class="input-group">
                        <input type="checkbox" id="remiseBreakCheckbox" name="snack" value="2.60">
                        <label for="remiseBreakAantal">Aantal:</label>
                        <input type="number" id="remiseBreakAantal" name="remiseBreakAantal" min="0" value="0" disabled>
                    </div>
                </div>
                <div class="snack-option">
                    <label for="kazerneBreakCheckbox">Kazerne break: zakje chips met limonade</label>
                    <span class="subtitle">€2.60</span>
                    <div class="input-group">
                        <input type="checkbox" id="kazerneBreakCheckbox" name="snack" value="2.60">
                        <label for="kazerneBreakAantal">Aantal:</label>
                        <input type="number" id="kazerneBreakAantal" name="kazerneBreakAantal" min="0" value="0" disabled>
                    </div>
                </div>
                <div class="snack-option">
                    <label for="fortgrachtBreakCheckbox">Fortgracht break: fruit met limonade</label>
                    <span class="subtitle">€2.60</span>
                    <div class="input-group">
                        <input type="checkbox" id="fortgrachtBreakCheckbox" name="snack" value="2.60">
                        <label for="fortgrachtBreakAantal">Aantal:</label>
                        <input type="number" id="fortgrachtBreakAantal" name="fortgrachtBreakAantal" min="0" value="0" disabled>
                    </div>
                </div>
                <div class="snack-option">
                    <label for="waterijsjeCheckbox">Waterijsje</label>
                    <span class="subtitle">€1.00</span>
                    <div class="input-group">
                        <input type="checkbox" id="waterijsjeCheckbox" name="snack" value="1.00">
                        <label for="waterijsjeAantal">Aantal:</label>
                        <input type="number" id="waterijsjeAantal" name="waterijsjeAantal" min="0" value="0" disabled>
                    </div>
                </div>
                <div class="snack-option">
                    <label for="pakjeDrinkenCheckbox">Pakje drinken</label>
                    <span class="subtitle">€1.00</span>
                    <div class="input-group">
                        <input type="checkbox" id="pakjeDrinkenCheckbox" name="snack" value="1.00">
                        <label for="pakjeDrinkenAantal">Aantal:</label>
                        <input type="number" id="pakjeDrinkenAantal" name="pakjeDrinkenAantal" min="0" value="0" disabled>
                    </div>
                </div>
            </div>
            <h4 class="lunch-heading">Lunch aanbod </h4>
            <span class="subtitle">Vink aan en vul een aantal in</span>
            <div class="snack-option">
                <label for="remiseLunchCheckbox">Remiselunch: friet, saus, snack en pakje drinken</label>
                <span class="subtitle">€5.20</span>
                <div class="input-group">
                    <input type="checkbox" id="remiseLunchCheckbox" name="lunch" value="5.20">
                    <label for="remiseLunchAantal">Aantal:</label>
                    <input type="number" id="remiseLunchAantal" name="remiseLunchAantal" min="0" value="0" disabled>
                </div>
            </div>
            <div class="snack-option">
                <label for="tweeBroodjesCheckbox">Twee belegde broodjes en fruitsap</label>
                <span class="subtitle">€10.30</span>
                <div class="input-group">
                    <input type="checkbox" id="tweeBroodjesCheckbox" name="lunch" value="10.30">
                    <label for="tweeBroodjesAantal">Aantal:</label>
                    <input type="number" id="tweeBroodjesAantal" name="tweeBroodjesAantal" min="0" value="0" disabled>
                </div>
            </div>
            <div class="snack-option">
                <label for="eigenPicknickCheckbox">Nemen eigen picknick mee</label>
                <input type="checkbox" id="eigenPicknickCheckbox" name="lunch" value="0">
            </div>
        </fieldset>
        <fieldset>
            <legend>Overzicht totaalprijs</legend>
            <div class="informative-text overview-section">
                <div class="price-summary-section">
                    <div class="summary-category">
                        <h3>Bezoek</h3>
                        <div id="bezoek" class="summary-item">
                            <!-- Selected visit price displayed here -->
                        </div>
                    </div>
                    <div class="summary-category">
                        <h3>Eten en drinken</h3>
                        <div id="foodSummary" class="summary-category">
                            <!-- Selected food options will be displayed here -->
                        </div>
                    </div>
                    <div class="total-price">
                        <span>Totaalprijs: €<span id="totalPrice">0.00</span></span>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Vragen en Opmerkingen</legend>
            <label for="vragenOpmerkingen">Vragen en Opmerkingen</label>
            <textarea id="vragenOpmerkingen" name="vragenOpmerkingen" maxlength="600" rows="5"></textarea>
            <div id="tekenTeller">600 tekens over</div>
        </fieldset>
        <button type="submit" id="verzendknop">Verzenden</button>
    </form>

</body>
</html>
