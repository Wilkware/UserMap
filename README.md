# 🗺️ Benutzerkarte (User Map)

[![Home](https://img.shields.io/badge/Home-wilkware.de-0b1830.svg?style=flat-square)](https://wilkware.de/module/benutzerkarte/)
[![Version](https://img.shields.io/badge/Symcon-PHP--Modul-red.svg?style=flat-square)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Product](https://img.shields.io/badge/Symcon%20Version-8.1-blue.svg?style=flat-square)](https://www.symcon.de/produkt/)
[![Version](https://img.shields.io/badge/Modul%20Version-2.0.20260731-orange.svg?style=flat-square)](https://github.com/Wilkware/UserMap)
[![License](https://img.shields.io/badge/License-CC%20BY--NC--SA%204.0-green.svg?style=flat-square)](https://creativecommons.org/licenses/by-nc-sa/4.0/)
[![Actions](https://img.shields.io/github/actions/workflow/status/wilkware/UserMap/ci.yml?branch=main&label=CI&style=flat-square)](https://github.com/Wilkware/UserMap/actions)

Das Modul bietet die Möglichkeit, jedem Symcon-Benutzer direkt von der Konsole aus seinen eigenen Standortmarker auf eine interaktive Karte (Symcon User Map) hinzuzufügen.  

![Module-Visu](imgs/user-map.png) 

## Inhaltverzeichnis

1. [Funktionsumfang](#user-content-1-funktionsumfang)
2. [Voraussetzungen](#user-content-2-voraussetzungen)
3. [Installation](#user-content-3-installation)
4. [Einrichtung](#user-content-4-einrichtung)
5. [Statusvariablen](#user-content-5-statusvariablen)
6. [Darstellungen](#user-content-6-darstellungen)
7. [Visualisierung](#user-content-7-visualisierung)
8. [Befehlsreferenz](#user-content-8-befehlsreferenz)
9. [Versionshistorie](#user-content-9-versionshistorie)

### 1. Funktionsumfang

Die Idee für dieses Modul stammt aus dem ehemaligen Symcon-Forum sowie aus dem HomeMatic-Forum. Dort wurde eine ähnliche Lösung bereits auf einfache und pragmatische Weise mit Google Maps umgesetzt. Der Nachteil dieser Variante war jedoch, dass die Standortdaten über Nachrichten zwischen den Systemen ausgetauscht und verwaltet werden mussten.  
Dieser Ansatz kombiniert verschiedene Technologien, um eine moderne, einfache und selbst verwaltbare Lösung für die Verwaltung eigener Standorte bereitzustellen. Ziel ist es, eine flexible Standortdarstellung zu ermöglichen, ohne auf externe Verwaltungsdienste oder komplizierte manuelle Prozesse angewiesen zu sein.

* Auslieferung als schlanker One-Pager über CDN (Netlify) für die externe Website
* Integrierte Unterstützung für die Kachelvisualisierung (Tile-Visu) in Symcon
* Registrieren, Aktualisieren und Löschen der Standortdaten über eine einfache REST-API
* Redaktionelle Möglichkeit, bei Fehlern oder Problemen schnell einzugreifen
* Verwaltung des eigenen Standorts sowie privater Links über das Symcon Modul

### 2. Voraussetzungen

* Symcon ab Version 8.1

### 3. Installation

* Über den Modul Store das Modul _Benutzerkarte_ installieren.
* Alternativ über das Modul Control folgende URL hinzufügen.  
`https://github.com/Wilkware/UserMap` oder `git://github.com/Wilkware/UserMap.git`

### 4. Einrichtung

* Unter 'Instanz hinzufügen' ist das _Benutzerkarte_-Modul unter dem Hersteller '(Geräte)' aufgeführt.

__Konfigurationsseite__:

Einstellungsbereich:

> 🙋 Benutzerdaten ...

Name                               | Beschreibung
---------------------------------- | -----------------------------------------------------------------
Benutzername (Forum)               | Eigener Benutzername (Nickname/Spitzname) wie im Forum! Bitte nichts Neues erfinden!!!
Standort (Breitengrad, Längengrad) | Dein gewünschter Standort. Über den KOPIEREN Button können die im System hinterlegten Koordinaten übernommen werden.
Links (nicht verpflichend)         | Wer will kann mehrere Links zu seiner Person mitgeben (Website, Github ...)

_Aktionsbereich:_

> 🗝️ Verwalten Sie Ihren Eintrag über die Schaltflächen ...

Aktion                  | Beschreibung
----------------------- | ---------------------------------
REGISTRIEREN            | Den eigenen Standort freigeben bzw. registrieren
AKTUALISIEREN           | Update der daten, z.B. neuer Standort oder Links. ÄNDERUNG DES NAMENS IST NICHT ERLAUBT!
LÖSCHEN                 | Standort wieder zurücknehmen bzw. öffentlich Löschen!

### 5. Statusvariablen

Es werden keine Statusvariablen angelegt.

### 6. Darstellungen

Es werden keine Darstellungen oder Profile benötigt.

### 7. Visualisierung

Man kann gesamte Modul (HTML-SDK Support) direkt in der Visualisierung verlinken.

### 8. Befehlsreferenz

Das Modul stellt keine direkten Funktionsaufrufe zur Verfügung.

### 9. Versionshistorie

v2.0.20260731

* _NEU_: Einführung von namespaced traits
* _FIX_: Unnötige Bibliotheken entfernt

v2.0.20260713

* _NEU_: Support für TileVisu (Kachel-Visualisierung)
* _NEU_: Kompatibilität auf IPS 8.1 vereinheitlicht
* _NEU_: Umstellung auf Strict-Modus (IPSModuleStrict)
* _NEU_: Modulversion wird in Quellcodesektion angezeigt
* _FIX_: Modulkonfiguration überarbeitet und vereinheitlicht
* _FIX_: Interne Bibliotheken überarbeitet
* _FIX_: Internes Deployment überarbeitet

v1.0.20231206

* _NEU_: Initialversion

## Entwickler

Seit nunmehr über 10 Jahren fasziniert mich das Thema Haussteuerung. In den letzten Jahren betätige ich mich auch intensiv in der Symcon Community und steuere dort verschiedenste Skript und Module bei. Ihr findet mich dort unter dem Namen @pitti ;-)

[![GitHub](https://img.shields.io/badge/GitHub-@wilkware-181717.svg?style=for-the-badge&logo=github)](https://wilkware.github.io/)

## Spenden

Die Software ist für die nicht kommerzielle Nutzung kostenlos, über eine Spende bei Gefallen des Moduls würde ich mich freuen.

[![PayPal](https://img.shields.io/badge/PayPal-spenden-00457C.svg?style=for-the-badge&logo=paypal)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8816166)

## Lizenz

Namensnennung - Nicht-kommerziell - Weitergabe unter gleichen Bedingungen 4.0 International

[![Licence](https://img.shields.io/badge/License-CC_BY--NC--SA_4.0-EF9421.svg?style=for-the-badge&logo=creativecommons)](https://creativecommons.org/licenses/by-nc-sa/4.0/)
