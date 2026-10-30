# Fragen nach Themen

[Anleitung](Deutsch.md) · [English](FAQ-English.md)

## Erste Schritte

### Muss ich zuerst etwas einrichten?

Nein. Plugin aktivieren und eine Inhaltsliste öffnen, die ein aktiver unterstützter Builder bearbeiten darf. Ansichten, Builder-Spalte und Untermenüs sind standardmäßig eingeschaltet.

### Welche Voraussetzungen gelten?

WordPress 6.7+ und PHP 8.1+. Geprüft auf WordPress 7.1.4 mit PHP 8.1 und 8.4 sowie auf WordPress 6.7 mit PHP 8.1. Die Integrationsprüfungen für CrocoBuilder und Builderius bestanden außerdem mit PHP 8.2 und 8.4. Die Tests verwenden isolierte SQLite-Installationen; vollständige MySQL-/MariaDB-, FTP-/SSH-Tests und jede Kombination aus Builder und Version sind nicht abgedeckt.

### Ist das Plugin kostenlos?

Ja, unter GPL v2 oder neuer. Einige unterstützte Builder benötigen eine eigene kostenpflichtige Lizenz.

### Welche Builder werden unterstützt?

Die [Builder-Übersicht](Builders-Deutsch.md) enthält getrennte Tabellen für klassische und blockbasierte Builder, einschließlich Free-/Pro-Hinweisen und kurzen Bedingungen.

### Warum sehe ich keine Builder-Ansicht?

Der Builder muss aktiv sein, den Inhaltstyp erlauben und erkennbare Einstellungen bereitstellen. Dein Benutzer braucht außerdem Bearbeitungsrechte für diesen Typ. Fehlende, ungültige und versteckte Inhaltstypen werden ausgeschlossen.

### Funktioniert es mit eigenen Inhaltstypen?

Ja, wenn ein aktiver Builder sie erlaubt und sie eine Admin-Oberfläche haben. Medienanhänge sind ausgeschlossen.

## Erkennung und Filter

### Was sagt die Builder-Spalte aus?

Sie zeigt passende Metadaten aktiver unterstützter Builder, die für den Inhaltstyp freigegeben sind. Sie analysiert weder die komplette Frontend-Darstellung noch Template-Zuweisungen.

### Kann eine Seite zwei Builder anzeigen?

Ja. Jeder passende Builder erscheint. Nach einer Migration können alte Metadaten verbleiben. Builder-Gruppen können sich überschneiden; ihre Zähler lassen sich nicht einfach addieren.

### Was bedeutet „Ohne erkannten Builder“?

Für keinen aktiven unterstützten Builder dieses Inhaltstyps wurde eine passende Datenregel gefunden. Das beweist nicht, dass die Seite Gutenberg verwendet.

### Werden inaktive Builder erkannt?

Die Standardansichten erkennen nur aktive Builder. Öffne Builder-Werkzeuge und persönliche Ansichten → Inaktive Builder-Spuren prüfen für eine ausdrückliche rein lesende Prüfung bekannter inaktiver Signaturen. Sie beweist nicht die Darstellung im Frontend.

### Erkennt das Plugin Builder-Templates auf normalen Seiten?

Nein. Die Erkennung basiert auf den eigenen Metadaten der Seite, nicht auf globalen Templates oder Theme-Builder-Bedingungen.

### Warum erscheint eine leere Seite unter Oxygen oder Breakdance?

Diese Integrationen behalten die bisherige Regel zur Existenz eines Metaschlüssels bei. Ein leerer gespeicherter Wert zählt als vorhanden; das ist keine Inhaltsprüfung.

### Wie kombiniere ich Builder und Suche?

Builder auswählen und anschließend die Suche oder normale Listenfilter verwenden. Die Builder-ID bleibt im Listenformular erhalten. Alle oder eine andere Standardansicht setzt die Builder-Auswahl zurück.

### Werden Entwürfe und private Seiten mitgezählt?

Die Zähler berücksichtigen lesbare veröffentlichte, geplante, als Entwurf, ausstehend oder privat gespeicherte Inhalte gemäß den registrierten Admin-Status. Papierkorb und automatische Entwürfe sind ausgeschlossen. Wer fremde Inhalte nicht bearbeiten darf, erhält Zähler für eigene Inhalte.

### Warum ist der Zähler größer als die Suchergebnisliste?

Er beschreibt die gesamte lesbare Builder-Gruppe außerhalb des Papierkorbs. Suche, Datum, Autor und andere Listenfilter können die gerade sichtbaren Zeilen reduzieren.

### Funktionieren Filter anderer Plugins weiterhin?

Bestehende Meta-Abfragegruppen bleiben erhalten und werden mit AND mit dem Builder kombiniert. Der Filter ohne Builder ergänzt einen separaten Ausschluss. Abfrage-Hooks anderer Plugins können Ergebnisse weiterhin beeinflussen.

### Welche Block-Sammlungen kann ich finden?

Kadence Blocks, Spectra Legacy, Spectra Blocks, Greenshift / GreenLight, GenerateBlocks, Genesis Blocks, Stackable, Otter Blocks, Essential Blocks, GutenKit und Kubio. Erkannte Nutzung bedeutet nicht, dass die gesamte Seite mit einem einzigen Werkzeug erstellt wurde. [Sonderfälle und Grenzen](Recognition-Deutsch.md).

## Einstellungen und Rechte

### Wer kann Einstellungen ändern?

Benutzer mit manage_options, normalerweise Administratoren. Inhaltsfilter verwenden die Bearbeitungsrechte des jeweiligen Inhaltstyps.

### Können Redakteure die Untermenüs verwenden?

Ja, wenn sie den jeweiligen Inhaltstyp bearbeiten dürfen. edit_theme_options ist nicht erforderlich.

### Kann ich die Spalte ausblenden?

Ja, persönlich über Ansicht anpassen in der Liste oder für die ganze Website unter Einstellungen → Builder List Pages.

### Kann ich die zusätzlichen Untermenüs ausblenden?

Ja, für die ganze Website unter Einstellungen → Builder List Pages. Builder-Ansichten über der Liste bleiben erhalten.

### Gelten Einstellungen pro Benutzer oder Website?

Die drei Plugin-Schalter gelten für die aktuelle Website. Ansicht anpassen steuert die Spaltensichtbarkeit zusätzlich für jeden Benutzer separat.

### Was ist mit Multisite?

Die drei Anzeigeoptionen werden getrennt je Website gespeichert. Eine Netzwerkaktivierung lädt das Plugin auf allen Websites, auch auf neu angelegten. Die Library hat eigene Website- und Netzwerkeinstellungen; Website-Administratoren können deren Netzwerkeinstellungen nicht ändern.

## Updates, Library und Hilfe

### Wie funktionieren Updates?

Der mitgelieferte deckerweb GitHub-Updater V2 prüft öffentliche stabile Releases und integriert sie in die normalen WordPress-Updateseiten. Ein zusätzliches Updater-Plugin ist nicht nötig. Automatische Updates werden nicht eingeschaltet.

### Werden Vorabversionen automatisch installiert?

Nein. Der Updater bietet ausschließlich stabile GitHub-Releases an. Testpakete werden bei Bedarf manuell installiert. Automatische Updates werden nicht eingeschaltet.

### Was ist die deckerweb Library?

Ein eingebetteter Entdeckungs- und Installationskatalog unter Plugins → Installieren → deckerweb. Der normale WordPress-Tab bleibt die Standardansicht. Sichtbarkeit und Online-Katalog haben eigene Library-Einstellungen.

### Lädt die Library automatisch Plugins herunter?

Nein. Installation benötigt eine ausdrückliche Aktion, Rechte- und Nonce-Prüfungen. Der mitgelieferte Katalog liegt lokal; optionale Online-Aktualisierung muss separat aktiviert werden.

### Überschreibt die Library neuere installierte Plugins?

Nein. Eine Neuinstallation ersetzt kein vorhandenes Plugin-Verzeichnis. Updates erfolgen über eine eigene Aktion mit Versions- und Anforderungsprüfung; ein Downgrade wird nicht angeboten.

### Gibt es Nutzungs-Tracking?

Builder List Pages enthält keine Nutzungs-Telemetrie. GitHub-Updateprüfungen kontaktieren GitHub; optionale Kataloganfragen den freigegebenen Endpunkt. Server sehen die anfragende IP-Adresse. Installieren lädt das gewählte Release.

### Kann ich es als Code Snippet verwenden?

Verwende das installierbare Plugin-ZIP. Ältere Releases enthielten ein separates Snippet mit kleinerem Funktionsumfang. Aktiviere ein altes Snippet nicht parallel zum Plugin; die Plugin-Hauptdatei ist kein eigenständiges Snippet.

### Ändert oder bereinigt das Plugin Seiteninhalte?

Nein. Es liest Builder-Metadaten und filtert Admin-Listen. Es gibt keine Konvertierung, Builder-Bereinigung oder Frontend-Assets.

### Wird ClassicPress unterstützt?

Das aktuelle Plugin, seine Update-URI-Anbindung und die eingebettete Library wurden nicht mit ClassicPress getestet. Frühere Kompatibilitätsangaben begründen keine Unterstützung dieses Arbeitsstands.

### Was soll ich bei falscher Erkennung melden?

Plugin- und Builder-Version, Inhaltstyp, Benutzerrolle, gewählten Filter und erwartetes Ergebnis angeben. Eine Testkopie verwenden und keine privaten Seiteninhalte oder Zugangsdaten teilen.

### Warum sperrt WP Admin Cleaner die Bricks-Seitenliste?

WP Admin Cleaner 2.0.1 vergleicht ausgeblendete Menüeinträge mit dem Ende der URL. Ist „Bricks → Erste Schritte“ ausgeblendet, trifft der Menüname `bricks` auch auf `edit.php?post_type=page&builder=bricks` zu. Seit 1.1.0-rc2 ergänzt Builder List Pages automatisch `&blp_view=1` am Ende seiner Listen-URLs. Suche und Filter bleiben erhalten, und die ausgeblendete Bricks-Menüseite bleibt gesperrt. Admin Cleaner kann aktiv bleiben.

### Was passiert beim Deaktivieren oder Deinstallieren?

Die Deaktivierung erhält alle Daten. Die Deinstallation entfernt den temporären Update-Cache dieses Plugins, erhält aber die drei Website-Einstellungen, persönlichen Ansichten/Anzeigepräferenzen und alle Builder-Inhalte. Gemeinsame Library-Caches und Aufgaben werden nur entfernt, wenn kein anderer installierter Host sie benötigt, auch kein inaktiver. Library-Einstellungen bleiben erhalten, sofern deren separate Löschoption nicht eingeschaltet ist. Über die Library installierte Plugins werden niemals entfernt.

## Neue Werkzeuge und persönliche Ansichten

### Wo werden persönliche Ansichten gespeichert?

In deinen Benutzer-Metadaten mit einem getrennten Schlüssel je Website. Andere Benutzer erhalten deine Favoriten nicht. Deaktivierung und Deinstallation erhalten sie; lösche einen Favoriten im Werkzeugbereich.

### Was beweist die Prüfung inaktiver Spuren?

Nur, dass eine bekannte Metadaten-Signatur vorhanden ist. Sie kann historisch oder leer sein und beweist nicht die Darstellung im Frontend. Die Prüfung verändert keine Inhalte.

### Was enthält die CSV?

Titel, Status, Builder-Namen und normaler Bearbeitungslink für die Auswahl nach Builder/Status/Suche, begrenzt auf 5.000 lesbare und bearbeitbare Inhalte. Andere Listenfilter fehlen; Tabellenformeln werden entschärft.

## Inhaltstypen und WordPress-Werkzeuge

### Warum erscheinen manche Inhaltstypen nicht mehr?

Die Vorauswahl lässt bekannte technische Datensätze und Systemtypen weg. Wähle unter Einstellungen → Builder List Pages → Inhaltstypen anpassen bei einem geeigneten eigenen Typ Einbeziehen. Berechtigungen und Einschränkungen des Builders gelten weiterhin; Core-Systemdatensätze bleiben ausgeschlossen.

### Löscht Ausblenden Inhalte oder ändert es meinen Builder?

Nein. Es nimmt den Inhaltstyp auf dieser Website aus den Integrationsfunktionen dieses Plugins. Inhalte, Builder-Einstellungen und gespeicherte Favoriten bleiben erhalten. Beim Öffnen eines Favoriten gilt die aktuelle Auswahl. Automatisch entfernt eine Ausnahme.

### Was versteht dieses Plugin unter einem Builder?

Als „Builder“ bezeichnet dieses Plugin Werkzeuge, mit denen du Inhalte und Layouts in WordPress gestaltest. Dazu gehören klassische Page Builder wie Bricks, Elementor oder Oxygen ebenso wie Erweiterungen des WordPress-Blockeditors, beispielsweise Kadence Blocks oder Spectra.

Bei Block-Erweiterungen genügt es, dass ein Inhalt einzelne Blöcke oder unterstützte Gestaltungsfunktionen des Werkzeugs verwendet. Die gesamte Seite muss damit nicht erstellt worden sein. Deshalb können bei einem Inhalt auch mehrere Builder erscheinen.

Ein aktives Theme oder eine globale Vorlage allein bedeutet dagegen nicht, dass der einzelne Inhalt mit diesem Builder bearbeitet wurde.

### Warum fehlen die Befehle?

Befehle werden nur dort ergänzt, wo WordPress seine eigene Palette lädt, und nur für zugängliche Builder-/Inhaltstyp-Kombinationen. Auf älteren WordPress-Oberflächen führen die vorhandenen Menülinks oder die Einstellungsübersicht zum Ziel.
