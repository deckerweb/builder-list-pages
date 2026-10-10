# Anleitung

Builder List Pages 1.1.0 · [FAQ](FAQ-Deutsch.md) · [English](English.md)

## Auf einen Blick

- **Builder erkennen:** anklickbare Builder-Spalte; mehrere Treffer pro Inhalt sind möglich.
- **Schnell filtern:** eigene Ansichten für mehrere aktive Builder und „Ohne erkannten Builder“.
- **Weiterarbeiten:** Builder-Auswahl bleibt bei Suche und normalen Listenfiltern erhalten.
- **Gewohnte Navigation:** zusätzliche Builder-Untermenüs verwenden die Rechte des jeweiligen Inhaltstyps.
- **Wenig Einrichtung:** drei optionale Schalter, deutsche Übersetzungen, lokale Anleitung und kompletter Änderungsverlauf.
- **DECKERWEB integriert:** eingebauter GitHub-Updater V2 und Plugin Library 0.9.0.

## Installation und erster Filter

1. Plugin-ZIP über **Plugins → Installieren → Plugin hochladen** installieren beziehungsweise die alte Plugin-Version ersetzen.
2. Plugin aktivieren und **Seiten** oder eine andere vom aktiven Builder unterstützte Inhaltsliste öffnen.
3. Einen Builder-Namen über der Tabelle anklicken. Anschließend bei Bedarf die Suche verwenden.
4. Einstellungen bei Bedarf unter **Einstellungen → Builder List Pages** anpassen.

## Builder und Erkennung

Elementor, Bricks, Breakdance, Oxygen 6+, Oxygen Classic, Brizy, Beaver Builder, ZionBuilder, Thrive Architect, Pagelayer und Visual Composer. Visual Composer ist auf den bisherigen Seiten-/Beitragsumfang der kostenlosen Ausgabe begrenzt. Astra- und OceanWP-Inhaltstypen behalten ihre spezielle Menünavigation, sofern diese vorhanden ist.

Erkennung liest bestehende Metadaten aktiver unterstützter Builder für freigegebene Inhaltstypen. „Ohne erkannten Builder“ bedeutet nicht automatisch Gutenberg. Inaktive Builder-Daten und globale Template-Zuweisungen werden nicht analysiert. Existenzbasierte Integrationen behalten ihre bisherige Semantik auch für leere Werte.

Zähler erfassen die gesamte lesbare Gruppe außerhalb des Papierkorbs einschließlich unveröffentlichter Inhalte. Suche und weitere Listenfilter können die sichtbaren Ergebnisse reduzieren. Gruppen können sich überschneiden.

## Einstellungen

Builder-Spalte, Untermenüs und den Filter ohne erkannten Builder websiteweit ein- oder ausschalten. Die Spalte lässt sich zusätzlich persönlich über **Ansicht anpassen** ausblenden. Administratoren ändern Einstellungen; Redakteure verwenden Listenfunktionen gemäß ihren normalen Bearbeitungsrechten.

Der lokale Änderungsverlauf öffnet sich in einem per Tastatur bedienbaren Dialog. Schließe ihn mit Escape, dem Schließen-Button oder einem Klick außerhalb des Dialogs; der Fokus kehrt zum Link zurück.

## Updates und Library

Stabile GitHub-Releases erscheinen über deckerweb Updater 2.1.0 im normalen WordPress-Updatesystem. Ein separates Updater-Plugin ist nicht nötig; automatische Updates bleiben unter deiner Kontrolle.

Die eingebettete deckerweb Library 0.9.0 ergänzt **Plugins → Installieren → deckerweb** mit einem lokalen freigegebenen Katalog, Serienfiltern und Plugin-Details. Der normale WordPress-Tab bleibt die Vorgabe. Installation, Aktivierung und Updates erfordern eine ausdrückliche Aktion mit passenden Rechten. Der Online-Katalog ist optional und standardmäßig ausgeschaltet.

Bei mehreren deckerweb Plugins übernimmt die aktive kompatible Library-Kopie. Ihre Einstellungen sind von den drei Anzeigeoptionen von Builder List Pages getrennt.

## Über das Projekt

Entwickelt und gepflegt von David Decker – DECKERWEB. © 2019–2026. Builder List Pages entstand 2019 und ergänzt die normalen Inhaltslisten. Es liest Metadaten, ohne Seiten umzuwandeln oder Frontend-Assets zu laden.

## Fehler, Sicherheit und Unterstützung

Melde nachvollziehbare Probleme über [GitHub Issues](https://github.com/deckerweb/builder-list-pages/issues). Sicherheitslücken bitte vertraulich über den [Sicherheitsmeldeweg](https://github.com/deckerweb/builder-list-pages/blob/main/SECURITY.md) melden; keine Exploit-Details oder Zugangsdaten in einem öffentlichen Issue veröffentlichen.

Unterstütze die Entwicklung über [Ko-fi](https://ko-fi.com/deckerweb), [Buy Me a Coffee](https://buymeacoffee.com/daveshine) oder [PayPal](https://paypal.me/deckerweb).

## Lizenz und enthaltene Komponenten

GPL v2 oder neuer. [Lizenz](https://github.com/deckerweb/builder-list-pages/blob/main/LICENSE). Enthält deckerweb Plugin Library 0.9.0 und deckerweb Updater 2.1.0 von David Decker – DECKERWEB, beide unter GPL-2.0-or-later. Der gemeinsame Changelog-Renderer stammt aus [Advanced Scripts QuickNav](https://github.com/deckerweb/advanced-scripts-quicknav), `includes/deckerweb-changelog-v1.php`, vom selben Autor unter GPL-2.0-or-later. Premium-Code von Buildern oder Admin Cleaner wird nicht mitgeliefert.

Die bestehenden Banner und das Icon bestehen aus nativen SVG-Formen mit Volltonfarben und Transparenz. Banner enthalten lebenden Systemschrift-Text, ohne eingebettete Schrift- oder Rasterdateien. PNG-Dateien sind lokale Ableitungen der freigegebenen Grafik.
