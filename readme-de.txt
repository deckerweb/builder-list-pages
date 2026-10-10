=== Builder List Pages ===
Contributors: deckerweb
Tags: admin, pages, page builder, filter, post types
Requires at least: 6.7
Requires PHP: 8.0
Stable tag: 1.1.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Seiten nach Builder filtern und direkt in den normalen WordPress-Inhaltslisten weiterarbeiten.

== Description ==

Deine Seiten. Deine Builder. Alles im Blick.

= Auf einen Blick =

- Builder erkennen: anklickbare Builder-Spalte; mehrere Treffer pro Inhalt sind möglich.
- Schnell filtern: eigene Ansichten für mehrere aktive Builder und „Ohne erkannten Builder“.
- Weiterarbeiten: Builder-Auswahl bleibt bei Suche und normalen Listenfiltern erhalten.
- Gewohnte Navigation: zusätzliche Builder-Untermenüs verwenden die Rechte des jeweiligen Inhaltstyps.
- Wenig Einrichtung: drei optionale Schalter, deutsche Übersetzungen, lokale Anleitung und kompletter Änderungsverlauf.
- DECKERWEB integriert: eingebauter GitHub-Updater V2 und Plugin Library 0.9.0.

= Builder und Erkennung =

Elementor, Bricks, Breakdance, Oxygen 6+, Oxygen Classic, Brizy, Beaver Builder, ZionBuilder, Thrive Architect, Pagelayer und Visual Composer. Visual Composer ist auf den bisherigen Seiten-/Beitragsumfang der kostenlosen Ausgabe begrenzt. Astra- und OceanWP-Inhaltstypen behalten ihre spezielle Menünavigation, sofern diese vorhanden ist.

Erkennung liest bestehende Metadaten aktiver unterstützter Builder für freigegebene Inhaltstypen. „Ohne erkannten Builder“ bedeutet nicht automatisch Gutenberg. Inaktive Builder-Daten und globale Template-Zuweisungen werden nicht analysiert. Existenzbasierte Integrationen behalten ihre bisherige Semantik auch für leere Werte.

Zähler erfassen die gesamte lesbare Gruppe außerhalb des Papierkorbs einschließlich unveröffentlichter Inhalte. Suche und weitere Listenfilter können die sichtbaren Ergebnisse reduzieren. Gruppen können sich überschneiden.

= Einstellungen =

Builder-Spalte, Untermenüs und den Filter ohne erkannten Builder websiteweit ein- oder ausschalten. Die Spalte lässt sich zusätzlich persönlich über Ansicht anpassen ausblenden. Administratoren ändern Einstellungen; Redakteure verwenden Listenfunktionen gemäß ihren normalen Bearbeitungsrechten.

Der lokale Änderungsverlauf öffnet sich in einem per Tastatur bedienbaren Dialog. Schließe ihn mit Escape, dem Schließen-Button oder einem Klick außerhalb des Dialogs; der Fokus kehrt zum Link zurück.

= Updates und Library =

Stabile GitHub-Releases erscheinen über deckerweb Updater 2.1.0 im normalen WordPress-Updatesystem. Ein separates Updater-Plugin ist nicht nötig; automatische Updates bleiben unter deiner Kontrolle.

Die eingebettete deckerweb Library 0.9.0 ergänzt Plugins → Installieren → deckerweb mit einem lokalen freigegebenen Katalog, Serienfiltern und Plugin-Details. Der normale WordPress-Tab bleibt die Vorgabe. Installation, Aktivierung und Updates erfordern eine ausdrückliche Aktion mit passenden Rechten. Der Online-Katalog ist optional und standardmäßig ausgeschaltet.

Bei mehreren deckerweb Plugins übernimmt die aktive kompatible Library-Kopie. Ihre Einstellungen sind von den drei Anzeigeoptionen von Builder List Pages getrennt.

= Neue Werkzeuge und persönliche Ansichten =

Die Ansicht Mehrere Builder erkannt sammelt Inhalte mit mindestens zwei unterschiedlichen Signaturen aktiver Builder. Doppelte Kopien desselben Metadatenfelds zählen einmal.

Öffne Builder-Werkzeuge und persönliche Ansichten oberhalb der normalen Inhaltsliste, um eine Ansicht auszuwählen, einen benannten Favoriten zu speichern, zur kompakten Auswahl zu wechseln, inaktive Builder-Spuren zu prüfen, Diagnosedaten zu kopieren oder eine CSV herunterzuladen. Die Werkzeuge bleiben standardmäßig zugeklappt; die bisherigen Builder-Ansichten bleiben Standard. Bricks-Kennzeichen bieten einen getrennten Bearbeitungslink, wenn die eigenen Bricks-Berechtigungen ihn erlauben.

Inaktive Spuren: Die optionale, rein lesende Ansicht prüft bekannte Metadaten-Signaturen auch bei deaktiviertem Builder. Sie aktiviert keine Builder, verändert keine Inhalte und beweist nicht, welcher Builder das Frontend darstellt. Bei existenzbasierter Erkennung können leere Felder zählen. Aktive Builder, die für einen Inhaltstyp abgeschaltet sind, gelten nicht als inaktiv.

Persönliche Favoriten: Speichere Inhaltstyp, Builder, Status und Suche für dein Benutzerkonto auf dieser Website. Derselbe Name aktualisiert einen Favoriten; bis zu 20 Namen werden unterstützt. Beim Öffnen werden deine Rechte erneut geprüft. Favoriten lassen sich einzeln löschen; Deaktivierung und Deinstallation erhalten sie.

Diagnose: Wähle den lokalen Text aus und kopiere ihn. Er enthält Plugin-, WordPress- und PHP-Version, Namen unterstützter aktiver Builder und zugängliche Inhaltstypen. Website-URLs, Benutzernamen, Zugangsdaten und Seiteninhalte fehlen. Es wird nichts automatisch gesendet.

CSV: Lade Titel, Status, erkannte Builder und Bearbeitungslink für die ausdrücklich angezeigte Auswahl nach Builder/Status/Suche herunter, mit bis zu 5.000 lesbaren und bearbeitbaren Inhalten. Andere Listenfilter werden nicht berücksichtigt; der Hinweis steht vor dem Download. Tabellenformeln werden entschärft. Bearbeitungslinks enthalten die Website-URL; die CSV ist für eine vertrauliche Übergabe gedacht, im Unterschied zur sparsamen Support-Diagnose.

Ein leeres Builder-Ergebnis erklärt Builder, Suche und Status. Suche zurücksetzen oder Builder-Filter aufheben entfernt nur diese Einschränkung und erhält Inhaltstyp und weitere Listenfilter.

Öffne Details zur Erkennung in einer Zeile, um die Namen erkannter Metadatenfelder und einen Hinweis auf leere Werte zu sehen. Metadatenwerte und Builder-Inhalte werden nicht angezeigt.

= Über das Projekt =

Entwickelt und gepflegt von David Decker – DECKERWEB. © 2019–2026. Builder List Pages entstand 2019 und ergänzt die normalen Inhaltslisten. Es liest Metadaten, ohne Seiten umzuwandeln oder Frontend-Assets zu laden.

= Fehler, Sicherheit und Unterstützung =

Melde nachvollziehbare Probleme über [GitHub Issues](https://github.com/deckerweb/builder-list-pages/issues). Sicherheitslücken bitte vertraulich über den [Sicherheitsmeldeweg](https://github.com/deckerweb/builder-list-pages/blob/main/SECURITY.md) melden; keine Exploit-Details oder Zugangsdaten in einem öffentlichen Issue veröffentlichen.

Unterstütze die Entwicklung über [Ko-fi](https://ko-fi.com/deckerweb), [Buy Me a Coffee](https://buymeacoffee.com/daveshine) oder [PayPal](https://paypal.me/deckerweb).

= Lizenz und enthaltene Komponenten =

GPL v2 oder neuer. [Lizenz](https://github.com/deckerweb/builder-list-pages/blob/main/LICENSE). Enthält deckerweb Plugin Library 0.9.0 und deckerweb Updater 2.1.0 von David Decker – DECKERWEB, beide unter GPL-2.0-or-later. Der gemeinsame Changelog-Renderer stammt aus [Advanced Scripts QuickNav](https://github.com/deckerweb/advanced-scripts-quicknav), `includes/deckerweb-changelog-v1.php`, vom selben Autor unter GPL-2.0-or-later. Premium-Code von Buildern oder Admin Cleaner wird nicht mitgeliefert.

Banner und Icon verwenden native Vektorformen. Die Banner enthalten Inter-Schriftpfade, lineare Hintergrundverläufe und Transparenz; keine Rasterbilder, Live-Schriften, SVG-Filter oder externen Ressourcen. Icons verwenden native Formen und Vollfarben. PNGs werden aus denselben SVGs gerendert. Inter ist unter SIL Open Font License 1.1 kostenlos kommerziell nutzbar; die [Schriftlizenz](https://github.com/deckerweb/builder-list-pages/blob/main/assets/Inter-OFL.txt) liegt ohne Fontdateien bei.

== Installation ==

1. Plugin-ZIP über Plugins → Installieren → Plugin hochladen installieren beziehungsweise die alte Plugin-Version ersetzen.
2. Plugin aktivieren und Seiten oder eine andere vom aktiven Builder unterstützte Inhaltsliste öffnen.
3. Einen Builder-Namen über der Tabelle anklicken. Anschließend bei Bedarf die Suche verwenden.
4. Einstellungen bei Bedarf unter Einstellungen → Builder List Pages anpassen.

== Frequently Asked Questions ==

= Muss ich zuerst etwas einrichten? =

Nein. Plugin aktivieren und eine Inhaltsliste öffnen, die ein aktiver unterstützter Builder bearbeiten darf. Ansichten, Builder-Spalte und Untermenüs sind standardmäßig eingeschaltet.

= Welche Builder werden unterstützt? =

Elementor, Bricks, Breakdance, Oxygen ab Version 6, Oxygen Classic, Brizy, Beaver Builder, ZionBuilder, Thrive Architect, Pagelayer und Visual Composer. Bei Visual Composer gilt derzeit der Seiten-/Beitragsumfang der kostenlosen Ausgabe.

= Was bedeutet „Ohne erkannten Builder“? =

Für keinen aktiven unterstützten Builder dieses Inhaltstyps wurde eine passende Datenregel gefunden. Das beweist nicht, dass die Seite Gutenberg verwendet.

= Kann ich die Spalte ausblenden? =

Ja, persönlich über Ansicht anpassen in der Liste oder für die ganze Website unter Einstellungen → Builder List Pages.

= Was ist mit Multisite? =

Die drei Anzeigeoptionen werden getrennt je Website gespeichert. Eine Netzwerkaktivierung lädt das Plugin auf allen Websites, auch auf neu angelegten. Die Library hat eigene Website- und Netzwerkeinstellungen; Website-Administratoren können deren Netzwerkeinstellungen nicht ändern.

= Werden Vorabversionen automatisch installiert? =

Nein. Der Updater bietet ausschließlich stabile GitHub-Releases an. Testpakete werden bei Bedarf manuell installiert. Automatische Updates werden nicht eingeschaltet.

= Was passiert beim Deaktivieren oder Deinstallieren? =

Die Deaktivierung erhält alle Daten. Die Deinstallation entfernt den temporären Update-Cache dieses Plugins, erhält aber die drei Website-Einstellungen, persönlichen Ansichten/Anzeigepräferenzen und alle Builder-Inhalte. Gemeinsame Library-Caches und Aufgaben werden nur entfernt, wenn kein anderer installierter Host sie benötigt, auch kein inaktiver. Library-Einstellungen bleiben erhalten, sofern deren separate Löschoption nicht eingeschaltet ist. Über die Library installierte Plugins werden niemals entfernt.

[Vollständige FAQ nach Themen](https://github.com/deckerweb/builder-list-pages/wiki/FAQ-Deutsch)

== Changelog ==

= Unveröffentlicht · 2026-10-10 =

* Neu: Ergänzt einen Mehrfach-Builder-Filter, optionale Prüfung inaktiver Spuren, persönliche gespeicherte Ansichten, kompakte Benutzerauswahl, sparsame kopierbare Diagnose und einen ausdrücklichen CSV-Export für Übergaben.
* Verbessert: Erklärt leere Ergebnisse mit getrenntem Zurücksetzen von Suche und Builder sowie klareren Kennzeichen-Aktionen; ergänzt normale Bricks-Bearbeitungslinks mit Rechteprüfung.
* Verbessert: Erhält persönliche Favoriten und Anzeigepräferenzen bei Deaktivierung und Deinstallation; begrenzt Exporte auf erlaubte Inhalte und entschärft Tabellenformeln.
* Verbessert: Aktualisiert zweisprachige Banner und Icons mit der freigegebenen blaupetrolfarbenen Vektorgestaltung und lokalen Inter-Schriftpfaden samt Schriftlizenz.

= 1.1.0 · 2026-10-01 =

* Neu: Optionale Builder-Spalte mit anklickbaren Builder-Namen und mehreren Treffern je Inhalt.
* Neu: Eigene Ansichten und Untermenüs für mehrere gleichzeitig aktive Builder.
* Neu: Filter „Ohne erkannten Builder“ für Inhalte außerhalb der erkannten Builder-Gruppen.
* Neu: Kompakte Einstellungen mit deckerweb Header, Footer, lokaler Anleitung und Changelog-Dialog.
* Verbessert: Builder-Auswahl bleibt beim Suchen und Filtern der Inhaltsliste erhalten.
* Verbessert: Zentrale Regeln für Builder-Metadaten; Einstellungen werden einmal je Anfrage aufgelöst.
* Verbessert: Zählt lesbare veröffentlichte und unveröffentlichte Inhalte außerhalb des Papierkorbs mit schlanken ID-Abfragen.
* Behoben: Prüft URL-Parameter und filtert ausschließlich die Hauptabfrage der unterstützten Admin-Liste.
* Behoben: Erhält bestehende Meta-Abfragegruppen und verarbeitet fehlende oder fehlerhafte Builder-Optionen.
* Behoben: Untermenüs verwenden Bearbeitungsrechte des Inhaltstyps statt Theme-Rechte.
* Behoben: Verhindert die irrtümliche Zugriffssperre durch WP Admin Cleaner, wenn dessen ausgeblendete Bricks-Menüseite mit dem Ende einer Builder-Listen-URL kollidiert. Bestehende Listen-Links werden automatisch ergänzt; Suche und Filter bleiben erhalten.
* Sonstiges: Integriert den gemeinsamen deckerweb GitHub-Updater V2 und die eingebettete Plugin Library 0.2.0.
* Sonstiges: Erhöht die PHP-Mindestversion für die Library auf 8.0; WordPress 6.7 bleibt Mindestversion.
* Sonstiges: Ergänzt zweisprachige Readmes, Anleitungen, FAQs, Übersetzungen, Release-Werkzeuge und Regressionstests.

= 1.0.0 · 2025-04-11 =

* Neu: Erste öffentliche Version mit Ansichten und Untermenüs für elf Builder.
