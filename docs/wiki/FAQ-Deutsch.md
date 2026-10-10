# Fragen nach Themen

[Anleitung](Deutsch.md) · [English](FAQ-English.md)

## Erste Schritte

### Muss ich zuerst etwas einrichten?

Nein. Plugin aktivieren und eine Inhaltsliste öffnen, die ein aktiver unterstützter Builder bearbeiten darf. Ansichten, Builder-Spalte und Untermenüs sind standardmäßig eingeschaltet.

### Welche Voraussetzungen gelten?

WordPress ab 6.7 und PHP ab 8.0. Geprüft mit WordPress 6.7 und 7.1.2 sowie PHP 8.4.5 in separaten SQLite-Installationen, einschließlich Bricks 2.4.2 und WP Admin Cleaner 2.0.1. Andere Builder, Datenbanksysteme und Versionen benötigen weitere Tests.

### Ist das Plugin kostenlos?

Ja, unter GPL v2 oder neuer. Einige unterstützte Builder benötigen eine eigene kostenpflichtige Lizenz.

### Welche Builder werden unterstützt?

Elementor, Bricks, Breakdance, Oxygen ab Version 6, Oxygen Classic, Brizy, Beaver Builder, ZionBuilder, Thrive Architect, Pagelayer und Visual Composer. Bei Visual Composer gilt derzeit der Seiten-/Beitragsumfang der kostenlosen Ausgabe.

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

In Version 1.1.0 noch nicht. Ihre verbliebenen Daten bilden keine erkannte Gruppe. Eine Bestandsaufnahme inaktiver Builder ist ein späteres Feature.

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

Die Deaktivierung erhält alle Daten. Die Deinstallation entfernt den temporären Update-Cache dieses Plugins, erhält aber die drei Website-Einstellungen und alle Builder-Inhalte. Gemeinsame Library-Caches und Aufgaben werden nur entfernt, wenn kein anderer installierter Host sie benötigt, auch kein inaktiver. Library-Einstellungen bleiben erhalten, sofern deren separate Löschoption nicht eingeschaltet ist. Über die Library installierte Plugins werden niemals entfernt.
