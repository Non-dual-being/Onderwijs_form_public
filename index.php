<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoFort Onderwijsaanvraag</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="aanvraagscript.js"></script>
</head>
<body>
    <h1>ONDERWIJS AANVRAAG-FORMULIER</h1>
    <form id="onderwijsFormulier" method="post" novalidate>
        <fieldset>

            <label for="schoolnaam">Naam school</label>
            <input type="text" id="schoolnaam" name="schoolnaam" required>
            <div id="naamSchoolFout" class="foute-invoermelding"></div>
            
            <label for="adres">Adres</label>
            <input type="text" id="adres" name="adres" required>
            <div id="adresFout" class="foute-invoermelding"></div>
            
            <label for="postcode">Postcode</label>
            <input type="text" id="postcode" name="postcode" required>
            <div id="postcodeFout" class="foute-invoermelding"></div>

            <label for="plaats">Plaats</label>
            <input type="text" id="plaats" name="plaats" required>
            <div id="plaatsFout" class="foute-invoermelding"></div>

            <label for="niveauleerjaar">Niveau en Leerjaar</label>
            <input type="text" id="niveauleerjaar" name="niveauleerjaar" required>
            <div id="niveauleerjaarFout" class="foute-invoermelding"></div>

            <div class="meer-informatie-container">
            <a href="#" class="meerInformatieToggle" data-target="niveauleerjaarInfo"><span>Meer informatie over het niveau en het leerjaar.</span></a>
                <div id="niveauleerjaarInfo" class="meerInformatieContent">
                    <p><strong>Omschrijving niveau en leerjaar: </strong> Met VMBO 3, HAVO 2 of groep 8 worden het niveau en het leerjaar correct omschreven.</p>
                </div>
            </div>

            <label for="schoolTelefoonnummer">Telefoonnummer school</label>
            <input type="tel" id="schoolTelefoonnummer" name="schoolTelefoonnummer" required>
            <div id="schoolTelefoonnummerFout" class="foute-invoermelding"></div>

            <div class="meer-informatie-container">
            <a href="#" class="meerInformatieToggle" data-target="telefoonInfo"><span>Meer informatie over telefoonnummers</span></a>
                <div id="telefoonInfo" class="meerInformatieContent">
                    <p><strong>Telefoonnummer van de school:</strong> Dit nummer wordt gebruikt voor alle communicatie met de school zelf.</p>
                    <p><strong>Telefoonnummer contactpersoon:</strong> Dit nummer is voor de contactpersoon die tijdens het schoolbezoek bereikbaar is.</p>
                </div>
            </div>

            <legend>Basis-Gegevens</legend>
            <label for="contactpersoonvoornaam">Voornaam contactpersoon</label>
            <input type="text" id="contactpersoonvoornaam" name="contactpersoonvoornaam" required>
            <div id="voornaamFout" class="foute-invoermelding"></div>

            <label for="contactpersoonachternaam">Achternaam contactpersoon</label>
            <input type="text" id="contactpersoonachternaam" name="contactpersoonachternaam" required >
            <div id="achternaamFout" class="foute-invoermelding"></div>
            
            <label for="emailadres">E-mailadres</label>
            <input type="email" id="emailadres" name="emailadres" required>
            <div id="emailFout" class="foute-invoermelding"></div>
            

            <label for="contactTelefoonnummer">Telefoonnummer contactpersoon</label>
            <input type="tel" id="contactTelefoonnummer" name="contactTelefoonnummer" required>
            <div id="contactTelefoonnummerFout" class="foute-invoermelding"></div>

            
            <label for="bezoekdatum">Datum bezoek</label>
            <input type="date" id="bezoekdatum" name="bezoekdatum" required>
            <div id="bezoekdatumFout" class="foute-invoermelding"></div>
            
            <label for="Aankomsttijd">Standaard Aankomsttijd</label>
            <div id ="aankomsttijd" class="overview-section-tijden">
                <p><strong> 09:45</strong></p>
            </div>


            <label for="vertrektijd"> Standaard Vertrektijd</label>
            <div id ="vertrektijd" class="overview-section-tijden">
                <p><strong>15:00</strong></p>
            </div>

           

            <div class="meer-informatie-container">
            <a href="#" class="meerInformatieToggle" data-target="bezoektijdenInfo"><span>Meer informatie over bezoektijden</span></a>
            <div id="bezoektijdenInfo" class="meerInformatieContent">
                <p>GeoFort hanteert <strong class="highlighted-text">standaard</strong> bezoektijden, in overleg kan bij uitzondering het bezoek afwijken van de standaardtijden.</p>
            </div>

            
            <label for="hoekentGeoFort">Hoe kent u GeoFort?</label>
            <input type="text" id="hoekentGeoFort" name="hoekentGeoFort">
            <div id="hoekentGeoFortFout" class="foute-invoermelding"></div>
        </fieldset>

        <fieldset class="fieldset-informative">
            <legend class="legend-informative">Praktische Informatie</legend>
            <div class="informative-text">
                <h4>Kosten en Voorwaarden</h4>
                <p>
                    • Minimaal 40 leerlingen, maximaal 160 leerlingen.<br>
                    • €20,- per leerling, per 8 leerlingen is 1 begeleider gratis. <br>
                    • Deze kosten zijn inclusief:
                  
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
                    • Museumjaarkaart is niet geldig op onderwijsarrangementen. Cultuurkaart / CJP zijn wel geldig. Vul bij de opmerkingen naam, pashouder en CJP-nummer in.<br>
                    • In geval van allergieën, graag zelf lunch meenemen.
                </p>
            </div>
        </fieldset>

        <fieldset class="lesprogramma">
            <legend>Lesprogramma</legend>
            <label for="onderwijsNiveau">Selecteer het Schooltype</label>
            <select id="onderwijsNiveau" name="onderwijsNiveau" required>
                <option value="primairOnderwijs" selected>Primair Onderwijs</option>
                <option value="voortgezetOnderbouw">Voortgezet Onderwijs - Onderbouw</option>
                <option value="voortgezetBovenbouw">Voortgezet Onderwijs - Bovenbouw</option>
            </select>
            <div id="onderwijsNiveauFout" class="foute-invoermelding"></div>
            <div class="meer-informatie-container">
            <a href="#" class="meerInformatieToggle" data-target="onderwijsNiveauInfo"><span>Klik om de standaard modules van Primair Onderwijs te bekijken</span></a>
                <div id="onderwijsNiveauInfo" class="meerInformatieContent">
                    <p><lu id="weergaveStandaardModules"><!--standaardmodules worden hier dynamisch weergegeven--></lu></p>
                </div>
            </div>
        
            <div id="keuzeModuleSelectie">
                <label for="keuzeModule">Kies een lesmodule</label>
                <select id="keuzeModule" name="keuzeModule" required>
                    <!-- Keuzemodules worden hier dynamisch toegevoegd -->
                </select>
                <div id="keuzeModuleFout" class="foute-invoermelding"></div>
            </div>
        
            <label for="aantalLeerlingen">Vul het aantal leerlingen in</label>
            <input type="number" id="aantalLeerlingen" name="aantalLeerlingen" min="40" max="160" step="1" required placeholder="min=40, max=160">
            <div id="aantalLeerlingenFout" class="foutmelding"></div>

            <div class="meer-informatie-container">
            <a href="#" class="meerInformatieToggle" data-target="meerInformatieAantalLeerlingen"><span>Meer informatie over het aantal leerlingen</span></a>
                <div id="meerInformatieAantalLeerlingen" class="meerInformatieContent">
                    <p> Standaard hanteert GeoFort voor een schoolbezoek een minimaal aantal van 40 leerlingen en een maximum van 160.</p>
                    <p> GeoFort brengt minimaal de prijs voor 40 leerlingen in rekening, het bezoek kan wel met minder leerlingen plaatsvinden.</p>
                    <p> Onderaan het aanvraag formulier bij de sectie <strong class="highlighted-text">vragen en opmerkingen </strong> kunt u aangeven als u met minder leerlingen wil komen </p>
                </div>
            </div>

            <label for="totaalbegeleiders">Aantal begeleiders</label>
            <input type="number" id="totaalbegeleiders" name="totaalbegeleiders" min="1" max="50" step="1" inputmode="numeric" pattern="[0-9]*" required>
            <div id="aantalBegeleidersFout" class="foute-invoermelding"></div>

            <div class="meer-informatie-container">
                <a href="#" class="meerInformatieToggle" data-target="begeleidersInfo"><span>Meer informatie over aantal begeleiders</span></a>
                <div id="begeleidersInfo" class="meerInformatieContent">
                    <p>
                        GeoFort verwacht minimaal <strong>één begeleider per groep leerlingen</strong>. Voor elke <strong>8 leerlingen</strong> is <strong>één begeleider</strong> gratis inbegrepen. Voor aanvullende begeleiders wordt er <strong>€20</strong> per begeleider in rekening gebracht.
                    </p>
                </div>
            </div>
        </fieldset>
        

        <fieldset class="fieldset-informative" >
            <legend>Overzicht rooster</legend>
            <div class="informative-text-rooster">
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
                        <li id="gekozenKeuzeModule2"></li>
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
        
        <fieldset class="fieldset-informative">
            <legend >Eten en Drinken informatie</legend>
            <div class="informative-text">
                <h4>STANDAARD INBEGREPEN</h4>
                <ul>
                    <li>Koffie en thee voor begeleiders bij aankomst.</li>
                    <li>Vegetarische snack en plantaardige chocolademelk voor leerlingen tijdens de lesmodule voedselinnovatie</li>
                </ul>
                <h4>OPTIONEEL BIJ TE BOEKEN</h4>
                <h5>Snacks</h5>
                <ul>
                    <li>Remise break, Kazerne break en Fortgracht break</li>
                    <li>Waterijsje en glaasje limonade</li>
                </ul>
                <h5>Lunch</h5>
                <ul>
                <li>Remiselunch: tarwebolletjes met vegetarisch beleg <small>(leerlingen en begeleiders)</small></li>
                <li>Er is optie om een eigen lunch mee te nemen</li>
                </ul>
                <h4>EXTRA INFORMATIE</h4>
                <ul>
                    <li>Het eten en drinken kunt u alleen vooraf bestellen</li>
                    <li>Het restaurant is tijdens het schoolbezoek dicht</li>
                </ul>
            </div>
        </fieldset>
        
        <fieldset>
        <legend >Eten en Drinken keuzemenu</legend>
            <h4 class="snacks-heading">Snack keuze menu</h4>
    <span class="subtitle">Vink aan en vul een aantal in</span>
    <div class="snacks-section">
        <div class="snack-option">
            <label for="remiseBreakCheckbox">Remise break: ontbijtkoek met limonade</label>
            <span class="subtitle">€2.60</span>
            <div class="input-group">
                <input type="checkbox" id="remiseBreakCheckbox" name="snack" value="2.60">
                <label for="remiseBreakAantal">Aantal:</label>
                <input type="number" id="remiseBreakAantal" name="remiseBreakAantal" min="0" value="0" disabled>
                <div id="remiseBreakAantalFout" class="foute-invoermelding"></div> <!-- Foutdiv toegevoegd -->
            </div>
        </div>
        <div class="snack-option">
            <label for="kazerneBreakCheckbox">Kazerne break: zakje chips met limonade</label>
            <span class="subtitle">€2.60</span>
            <div class="input-group">
                <input type="checkbox" id="kazerneBreakCheckbox" name="snack" value="2.60">
                <label for="kazerneBreakAantal">Aantal:</label>
                <input type="number" id="kazerneBreakAantal" name="kazerneBreakAantal" min="0" value="0" disabled>
                <div id="kazerneBreakAantalFout" class="foute-invoermelding"></div> <!-- Foutdiv toegevoegd -->
            </div>
        </div>
        <div class="snack-option">
            <label for="fortgrachtBreakCheckbox">Fortgracht break: fruit met limonade</label>
            <span class="subtitle">€2.60</span>
            <div class="input-group">
                <input type="checkbox" id="fortgrachtBreakCheckbox" name="snack" value="2.60">
                <label for="fortgrachtBreakAantal">Aantal:</label>
                <input type="number" id="fortgrachtBreakAantal" name="fortgrachtBreakAantal" min="0" value="0" disabled>
                <div id="fortgrachtBreakAantalFout" class="foute-invoermelding"></div> <!-- Foutdiv toegevoegd -->
            </div>
        </div>
        <div class="snack-option">
            <label for="waterijsjeCheckbox">Waterijsje</label>
            <span class="subtitle">€1.00</span>
            <div class="input-group">
                <input type="checkbox" id="waterijsjeCheckbox" name="snack" value="1.00">
                <label for="waterijsjeAantal">Aantal:</label>
                <input type="number" id="waterijsjeAantal" name="waterijsjeAantal" min="0" value="0" disabled>
                <div id="waterijsjeAantalFout" class="foute-invoermeldingg"></div> <!-- Foutdiv toegevoegd -->
            </div>
        </div>
        <div class="snack-option">
            <label for="pakjeDrinkenCheckbox">limonade drink</label>
            <span class="subtitle">€1.00</span>
            <div class="input-group">
                <input type="checkbox" id="pakjeDrinkenCheckbox" name="snack" value="1.00">
                <label for="pakjeDrinkenAantal">Aantal:</label>
                <input type="number" id="pakjeDrinkenAantal" name="pakjeDrinkenAantal" min="0" value="0" disabled>
                <div id="pakjeDrinkenAantalFout" class="foute-invoermelding"></div> <!-- Foutdiv toegevoegd -->
            </div>
        </div>
    </div>

    <h4 class="lunch-heading">Lunch aanbod</h4>
    <span class="subtitle">Vink aan en vul een aantal in</span>
    <div class="snack-option">
        <label for="remiseLunchCheckbox">Remiselunch: tarwebolletjes met vegetarisch beleg</label>
        <span class="subtitle">€5.20</span>
        <div class="input-group">
            <input type="checkbox" id="remiseLunchCheckbox" name="lunch" value="5.20">
            <label for="remiseLunchAantal">Aantal:</label>
            <input type="number" id="remiseLunchAantal" name="remiseLunchAantal" min="0" value="0" disabled>
            <div id="remiseLunchAantalFout" class="foute-invoermelding"></div> <!-- Foutdiv toegevoegd -->
        </div>
    </div>
    <div class="snack-option">
        <label for="eigenPicknickCheckbox">Nemen eigen picknick mee</label>
        <input type="checkbox" id="eigenPicknickCheckbox" name="lunch" value="0">
    </div>
</fieldset>

        </fieldset>
        <fieldset class="fieldset-informative">
            <legend class="legend-informative">Overzicht totaalprijs</legend>
            <div class="informative-text">
                <div class="price-summary-section">
                    <div>
                        <h3>Bezoek</h3>
                        <div id="bezoek" class="summary-category">
                            <!-- Selected visit price displayed here -->
                        </div>
                    </div>
                    <div>
                        <h3>Eten en drinken</h3>
                        <div id="foodSummary" class="summary-category">
                            <!-- Selected food options will be displayed here -->
                        </div>
                    </div>
                    <div class="total-price">
                        <span>Totale prijs: €<span id="totalPrice">0.00</span></span>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Vragen en Opmerkingen</legend>
            <label for="vragenOpmerkingen">Vragen en Opmerkingen</label>
            <textarea id="vragenOpmerkingen" name="vragenOpmerkingen" maxlength="600" rows="5"></textarea>
            <div id="vragenOpmerkingenFout" class="foute-invoermelding"></div>
            <div id="tekenTeller">600 tekens over</div>
            <div id="contact">
            <p>Neem contact op via: <a href="mailto:onderwijs@geofort.nl">onderwijs@geofort.nl</a></p>
            </div>

        </fieldset>
        <button type="submit" id="verzendknop">Verzenden</button>
        <div id="verzendknopMeldingdiv" class="foute-invoermelding"></div> <!-- Foutdiv toegevoegd -->
    </form>
    <footer class="main-footer">
    <div class="footer-logo-container">
        <p id="copy_logo">&copy; 2024 GeoFort</p>
        <img src="images/geofort_logo.png" alt="GeoFort Logo" class="footer-logo">
    </div>
</footer>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/nl.js"></script>
</body>
</html>

