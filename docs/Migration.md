# Schritte TYPO3 Core Update
- [ ] Livedatenbank einspielen (ddev import-db --file=sql/*.sql)
- [ ] TYPO3 austauschen -> auf Version 10
- [ ] Dateien kopieren
  - [ ] dist/typo3conf/*
- [ ] Install Tool aufrufen (https://kist-escherich-com-v12.ddev.site/typo3/install.php)
  - [ ] Dump-Autoload
  - [ ] Cache Clear
  - [ ] Environment/Directory Status
- [ ] TYPO3 austauschen -> auf Version 11
- [ ] DDEV neu starten
- [ ] Migration Wizard aus v11 durchführen
- [ ] TYPO3 austauschen -> auf Version 12
- [ ] PHP Version in DDEV auf Version 8.2 anpassen
- [ ] DDEV neu starten

# Offene Punkte
- [ ] Datenbankfelder aus diesen Extensions übertragen
  - [ ] Xo -> Foundation
  - [ ] Xna -> Site
  - [ ] Akkordeon -> Akkordeon
- [ ] CeDownloads:
  - [ ] Sonderfall wkhtmltopdf Felder berücksichtigen
  - [ ] wkhtmltopdf fixen
- [ ] XNA Anpassungen
  - [ ] Layout
  - [ ] Übernahme SitesConfig

# Offene Extensions
- [ ] Chart -> wird in EnityProduct integriert
- [ ] Contact -> wird KistContact
- [ ] Entity
- [ ] EntityProduct
- [ ] CleverReach
- [ ] Powermail
- [x] Container
- [x] ContainerColumns
- [ ] Flux
- [ ] Html2Pdf
- [ ] KeSearch
- [ ] Masi
- [ ] News
- [ ] NewsExtended -> KistNews
- [ ] Scriptmerger
- [x] Teaser