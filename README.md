# Benutzerkarte (User Map)

[![Version](https://img.shields.io/badge/Symcon-PHP--Modul-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Product](https://img.shields.io/badge/Symcon%20Version-6.4%20%3E-blue.svg)](https://www.symcon.de/produkt/)
[![Version](https://img.shields.io/badge/Modul%20Version-1.0.20231206-orange.svg)](https://github.com/Wilkware/UserMap)
[![License](https://img.shields.io/badge/License-CC%20BY--NC--SA%204.0-green.svg)](https://creativecommons.org/licenses/by-nc-sa/4.0/)
[![Actions](https://github.com/Wilkware/UserMap/workflows/Check%20Style/badge.svg)](https://github.com/Wilkware/UserMap/actions)

Das Modul bietet die Möglichkeit, jedem Symcon-Benutzer direkt von der Konsole aus seinen eigenen Standortmarker auf einer globalen Karte (Symcon User Map Website) hinzuzufügen.  

## Inhaltverzeichnis

1. [Funktionsumfang](#user-content-1-funktionsumfang)
2. [Voraussetzungen](#user-content-2-voraussetzungen)
3. [Installation](#user-content-3-installation)
4. [Einrichten der Instanzen in IP-Symcon](#user-content-4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#user-content-5-statusvariablen-und-profile)
6. [WebFront](#user-content-6-webfront)
7. [PHP-Befehlsreferenz](#user-content-7-php-befehlsreferenz)
8. [Versionshistorie](#user-content-8-versionshistorie)

### 1. Funktionsumfang

Die Idee für das Modul stammt aus dem alten Symcon Forum bzw. aus dem HomeMatic Forum. Dort hat man es einfach und pragmatisch mit Google Maps umgesetzt. Der Nachteil ist das man es immer mit Nachrichten hin und her verwaltet hat.  
Diese Ansatz kombiniert verschiedene Technologien um ein einfaches und selbst managebares Verfahren zum Verwalten des eigenen Standorts zu bieten.  

* Auslieferung über eine sehr schlanken One-Pager via CDN (netlify)
* Registrieren, Aktualisieren und Löschen über eine einfache REST API
* Redaktinelle Möglichkeit bei Fehlern schnell einzugreifen
* Verwaltung des eigenen Standorts und privater Links via Symcon Modul

### 2. Voraussetzungen

* IP-Symcon ab Version 6.4

### 3. Installation

* Über den Modul Store das Modul _User Map_ installieren.
* Alternativ Über das Modul-Control folgende URL hinzufügen.  
`https://github.com/Wilkware/UserMap` oder `git://github.com/Wilkware/UserMap.git`

### 4. Einrichten der Instanzen in IP-Symcon

* Unter 'Instanz hinzufügen' ist das _User Map_-Modul (Alias: _Benutzerkarte_) unter dem Hersteller '(Geräte)' aufgeführt.

__Konfigurationsseite__:

Einstellungsbereich:

> Benutzerdaten ...

Name                               | Beschreibung
---------------------------------- | -----------------------------------------------------------------
Benutzername (Forum)               | Eigener Benutzername (Nickname/Spitzname) wie im Forum! Bitte nichts Neues erfinden!!!
Standort (Breitengrad, Längengrad) | Dein gewünschter Standort. Über den KOPIEREN Button können die im System hinterlegten Koordinaten übernommen werden.
Links (nicht verpflichend)         | Wer will kann mehrere Links zu seiner Person mitgeben (Website, Github ...)

_Aktionsbereich:_

Aktion                  | Beschreibung
----------------------- | ---------------------------------
REGISTRIEREN            | Den eigenen Standort freigeben bzw. registrieren
AKTUALISIEREN           | Update der daten, z.B. neuer Standort oder Links. ÄNDERUNG DES NAMENS IST NICHT ERLAUBT!
LÖSCHEN                 | Standort wieder zurücknehmen bzw. öffentlich Löschen!

### 5. Statusvariablen und Profile

Es werden keine zusätzlichen Statusvariablen oder Profile benötigt.

### 6. WebFront

Es ist keine weitere Steuerung oder gesonderte Darstellung integriert.

### 7. PHP-Befehlsreferenz

Das Modul stellt keine direkten Funktionsaufrufe zur Verfügung.

### 8. Versionshistorie

v1.0.20231206

* _NEU_: Initialversion

## Entwickler

Seit nunmehr über 10 Jahren fasziniert mich das Thema Haussteuerung. In den letzten Jahren betätige ich mich auch intensiv in der IP-Symcon Community und steuere dort verschiedenste Skript und Module bei. Ihr findet mich dort unter dem Namen @pitti ;-)

[![GitHub](https://img.shields.io/badge/GitHub-@wilkware-181717.svg?style=for-the-badge&logo=github)](https://wilkware.github.io/)

## Spenden

Die Software ist für die nicht kommerzielle Nutzung kostenlos, über eine Spende bei Gefallen des Moduls würde ich mich freuen.

[![PayPal](https://img.shields.io/badge/PayPal-spenden-00457C.svg?style=for-the-badge&logo=paypal)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8816166)

## Lizenz

Namensnennung - Nicht-kommerziell - Weitergabe unter gleichen Bedingungen 4.0 International

[![Licence](https://img.shields.io/badge/License-CC_BY--NC--SA_4.0-EF9421.svg?style=for-the-badge&logo=creativecommons)](https://creativecommons.org/licenses/by-nc-sa/4.0/)
