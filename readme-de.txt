=== Builder List Pages ===
Contributors: deckerweb
Tags: admin, pages, page builder, filter, post types
Requires at least: 6.7
Requires PHP: 8.0
Stable tag: 1.1.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html



Deine Seiten. Deine Builder. Alles im Blick. Erkenne Builder in deinen Inhaltslisten, filtere Elementor-, Bricks- und andere Builder-Seiten und arbeite im gewohnten WordPress-Admin weiter. Hilfreich bei gemischten Websites, Übergaben und Builder-Wechseln.

Version: 1.1.0 · Voraussetzungen: WordPress 6.7+ / PHP 8.0+ · Lizenz: GPL v2 oder neuer

[English](README.md) · [Anleitung](docs/wiki/Deutsch.md) · [Ausführliche FAQ](docs/wiki/FAQ-Deutsch.md) · [GitHub Releases](https://github.com/deckerweb/builder-list-pages/releases)

== Inhalt ==

- [Auf einen Blick](#auf-einen-blick)
- [Installation und erster Filter](#installation-und-erster-filter)
- [Builder und Erkennung](#builder-und-erkennung)
- [Einstellungen](#einstellungen)
- [Updates und Library](#updates-und-library)
- [FAQ](#faq)
- [Changelog](#changelog)
- [Über das Projekt](#über-das-projekt)

== Auf einen Blick ==

- Builder erkennen: anklickbare Builder-Spalte; mehrere Treffer pro Inhalt sind möglich.
- Schnell filtern: eigene Ansichten für mehrere aktive Builder und „Ohne erkannten Builder“.
- Weiterarbeiten: Builder-Auswahl bleibt bei Suche und normalen Listenfiltern erhalten.
- Gewohnte Navigation: zusätzliche Builder-Untermenüs verwenden die Rechte des jeweiligen Inhaltstyps.
- Wenig Einrichtung: drei optionale Schalter, deutsche Übersetzungen, lokale Anleitung und kompletter Änderungsverlauf.
- DECKERWEB integriert: eingebauter GitHub-Updater V2 und Plugin Library 0.2.0.

== Installation und erster Filter ==

1. Test-ZIP über Plugins → Installieren → Plugin hochladen installieren beziehungsweise die alte Plugin-Version ersetzen.
2. Plugin aktivieren und Seiten oder eine andere vom aktiven Builder unterstützte Inhaltsliste öffnen.
3. Einen Builder-Namen über der Tabelle anklicken. Anschließend bei Bedarf die Suche verwenden.
4. Einstellungen bei Bedarf unter Einstellungen → Builder List Pages anpassen.

Entweder Plugin oder den separat erzeugten Snippet-Export verwenden. Die neue modulare Hauptdatei lässt sich nicht allein als Snippet importieren. Der Export enthält die Listenfunktionen mit Standardschaltern, jedoch keine Einstellungen, Assets, Library oder Updates.

== Builder und Erkennung ==

Elementor, Bricks, Breakdance, Oxygen 6+, Oxygen Classic, Brizy, Beaver Builder, ZionBuilder, Thrive Architect, Pagelayer und Visual Composer. Visual Composer ist auf den bisherigen Seiten-/Beitragsumfang der kostenlosen Ausgabe begrenzt. Astra- und OceanWP-Inhaltstypen behalten ihre spezielle Menünavigation, sofern diese vorhanden ist.

Erkennung liest bestehende Metadaten aktiver unterstützter Builder für freigegebene Inhaltstypen. „Ohne erkannten Builder“ bedeutet nicht automatisch Gutenberg. Inaktive Builder-Daten und globale Template-Zuweisungen werden nicht analysiert. Existenzbasierte Integrationen behalten ihre bisherige Semantik auch für leere Werte.

Zähler erfassen die gesamte lesbare Gruppe außerhalb des Papierkorbs einschließlich unveröffentlichter Inhalte. Suche und weitere Listenfilter können die sichtbaren Ergebnisse reduzieren. Gruppen können sich überschneiden.

== Einstellungen ==

Builder-Spalte, Untermenüs und den Filter ohne erkannten Builder websiteweit ein- oder ausschalten. Die Spalte lässt sich zusätzlich persönlich über Ansicht anpassen ausblenden. Administratoren ändern Einstellungen; Redakteure verwenden Listenfunktionen gemäß ihren normalen Bearbeitungsrechten.

== Updates und Library ==

Stabile GitHub-Releases erscheinen über den deckerweb Updater im regulären WordPress-Updatesystem. Vorabversionen werden manuell installiert. Der Updater schaltet keine automatischen Updates ein.

Die eingebettete Library ergänzt Plugins → Installieren → deckerweb. Sie installiert nichts automatisch und hat eigene Sichtbarkeits- und Online-Katalogeinstellungen. Ihr lokaler Katalog enthält freigegebene Releases mit Prüfsummen; dieses RC ersetzt dort nicht die vorhandene stabile Version 1.0.0.

== FAQ ==

Muss ich zuerst etwas einrichten? Nein. Plugin aktivieren und eine Inhaltsliste öffnen, die ein aktiver unterstützter Builder bearbeiten darf. Ansichten, Builder-Spalte und Untermenüs sind standardmäßig eingeschaltet.

Warum sehe ich keine Builder-Ansicht? Der Builder muss aktiv sein, den Inhaltstyp erlauben und erkennbare Einstellungen bereitstellen. Dein Benutzer braucht außerdem Bearbeitungsrechte für diesen Typ. Fehlende, ungültige und versteckte Inhaltstypen werden ausgeschlossen.

Was bedeutet „Ohne erkannten Builder“? Für keinen aktiven unterstützten Builder dieses Inhaltstyps wurde eine passende Datenregel gefunden. Das beweist nicht, dass die Seite Gutenberg verwendet.

Kann eine Seite zwei Builder anzeigen? Ja. Jeder passende Builder erscheint. Nach einer Migration können alte Metadaten verbleiben. Builder-Gruppen können sich überschneiden; ihre Zähler lassen sich nicht einfach addieren.

Kann ich die Spalte ausblenden? Ja, persönlich über Ansicht anpassen in der Liste oder für die ganze Website unter Einstellungen → Builder List Pages.

Wie funktionieren Updates? Der mitgelieferte deckerweb GitHub-Updater V2 prüft öffentliche stabile Releases und integriert sie in die normalen WordPress-Updateseiten. Ein zusätzliches Updater-Plugin ist nicht nötig. Automatische Updates werden nicht eingeschaltet.

Ändert oder bereinigt das Plugin Seiteninhalte? Nein. Es liest Builder-Metadaten und filtert Admin-Listen. Es gibt keine Konvertierung, Builder-Bereinigung oder Frontend-Assets.

[Weitere Antworten nach Themen](docs/wiki/FAQ-Deutsch.md)

== Changelog ==

= 1.1.0 · 2026-10-01 =

- **Sonstiges:** Veröffentlicht 1.1.0 als stabile Version nach erfolgreichem Kundentest der Admin-Cleaner-Korrektur. Enthält die Funktionen und Korrekturen aus rc1 und rc2.

= 1.1.0-rc2 · 2026-10-01 =

- **Behoben:** Verhindert die irrtümliche Zugriffssperre durch WP Admin Cleaner, wenn dessen ausgeblendete Bricks-Menüseite mit dem Ende einer Builder-Listen-URL kollidiert. Bestehende Listen-Links werden automatisch ergänzt; Suche und Filter bleiben erhalten.
- **Sonstiges:** Bestätigt Designvariante A als finales Icon und Banner.

= 1.1.0-rc1 · 2026-10-01 =

- Neu: Optionale Builder-Spalte mit anklickbaren Builder-Namen und mehreren Treffern je Inhalt.
- Neu: Eigene Ansichten und Untermenüs für mehrere gleichzeitig aktive Builder.
- Neu: Filter „Ohne erkannten Builder“ für Inhalte außerhalb der erkannten Builder-Gruppen.
- Neu: Kompakte Einstellungen mit deckerweb Header, Footer, lokaler Anleitung und Changelog-Dialog.
- Verbessert: Builder-Auswahl bleibt beim Suchen und Filtern der Inhaltsliste erhalten.
- Verbessert: Zentrale Regeln für Builder-Metadaten; Einstellungen werden einmal je Anfrage aufgelöst.
- Verbessert: Zählt lesbare veröffentlichte und unveröffentlichte Inhalte außerhalb des Papierkorbs mit schlanken ID-Abfragen.
- Behoben: Prüft URL-Parameter und filtert ausschließlich die Hauptabfrage der unterstützten Admin-Liste.
- Behoben: Erhält bestehende Meta-Abfragegruppen und verarbeitet fehlende oder fehlerhafte Builder-Optionen.
- Behoben: Untermenüs verwenden Bearbeitungsrechte des Inhaltstyps statt Theme-Rechte.
- Sonstiges: Integriert den gemeinsamen deckerweb GitHub-Updater V2 und die eingebettete Plugin Library 0.2.0.
- Sonstiges: Erhöht die PHP-Mindestversion für die Library auf 8.0; WordPress 6.7 bleibt Mindestversion.
- Sonstiges: Ergänzt zweisprachige Readmes, Anleitungen, FAQs, Übersetzungen, Release-Werkzeuge und Regressionstests.
- Sonstiges: Überarbeitet Icon und englische/deutsche Banner; drei Designalternativen liegen im Quellpaket.

= 1.0.0 · 2025-04-11 =

- Neu: Erste öffentliche Version.

[Vollständiger Änderungsverlauf](docs/CHANGELOG-de.md)

== Über das Projekt ==

Entwickelt von David Decker – DECKERWEB. © 2019–2026. [Projekt unterstützen](https://ko-fi.com/deckerweb).

Dieses Plugin liest Builder-Daten; es verändert keine Inhalte und lädt keine Frontend-Assets. Echte Builder, weitere PHP-/WordPress-Versionen, Multisite und ClassicPress sind für dieses RC noch nicht umfassend geprüft.
