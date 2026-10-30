# Changelog

[English](Changelog-English.md) · [FAQ](FAQ-Deutsch.md)

## 1.3.0 · 2026-10-30

- **Neu:** Erkennt zusätzliche klassische und blockbasierte Builder einschließlich ihrer unterstützten Premium-Blöcke. Einschließlich Genesis Blocks.
- **Neu:** Erkennt direkte Etch-Inhalte und zeigt installierte GreenShift-Addons mit ihrem jeweiligen Aktiv-Status an.
- **Neu:** Erkennt direkt aktivierte CrocoBuilder-Inhalte und direkte Builderius-Seiten-Templates in Free und Pro.
- **Neu:** Zeigt installierte Integrationen mit Status, Free-/Pro-Ausgaben und direkten Links zu gefilterten Inhalten.
- **Neu:** Ergänzt Inhaltstyp-Auswahl pro Website, lokale Informationen im Website-Zustand und Sprungziele in der verfügbaren WordPress-Befehlspalette.
- **Verbessert:** Neue Builder-Übersicht mit getrennten Tabellen und kurzen Hinweisen zu Einschränkungen.
- **Verbessert:** Zeigt Inhaltstypen mit Treffern zuerst, bevorzugt Seiten und Beiträge innerhalb der Gruppen und hebt Core-Inhaltstypen mit eigenen dezenten Farben hervor. Erklärt klassische Builder und Block-Erweiterungen verständlich.
- **Verbessert:** Aktualisiert die eingebettete deckerweb Library auf 1.2.0 mit persönlichen Statusfiltern, passenden Trefferzahlen und verständlicheren Download-Diagnosen.
- **Behoben:** Erkennt Zion Builder Pro mit enthaltenem Basis-Builder und berücksichtigt die Inhaltstypen von Beaver Builder zuverlässiger.

## 1.2.2 · 2026-10-22

- **Verbessert:** Trennt Updater-Caches nach Implementierungsversion.
- **Behoben:** Aktualisiert den eingebetteten deckerweb Updater auf 2.2.1, damit dieses Plugin auch bei einer zuerst geladenen älteren Updater-Kopie seinen eigenen Updateweg behält.

## 1.2.1 · 2026-10-21

- **Verbessert:** Aktualisiert die eingebettete deckerweb Library auf 1.1.0 und den Updater auf 2.2.0, einschließlich Host-Übersetzungen und geprüfter katalogbasierter Updates.
- **Behoben:** Prüft Updatepakete bei nativen Sammelupdates ebenso wie bei Einzelupdates.
- **Behoben:** Hält geschlossene Änderungsverläufe auch bei fremden Dialog-Styles verborgen und erhält native Schließ- und Dokumentationslink-Fallbacks.
- **Sonstiges:** Benötigt PHP 8.1; WordPress 6.7 bleibt unterstützt. Einstellungen, Favoriten und Builder-Inhalte bleiben erhalten.

## 1.2.0 · 2026-10-10

- **Neu:** Ergänzt einen Mehrfach-Builder-Filter, optionale Prüfung inaktiver Spuren, persönliche gespeicherte Ansichten, kompakte Benutzerauswahl, sparsame kopierbare Diagnose und einen ausdrücklichen CSV-Export für Übergaben.
- **Verbessert:** Erklärt leere Ergebnisse mit getrenntem Zurücksetzen von Suche und Builder sowie klareren Kennzeichen-Aktionen; ergänzt normale Bricks-Bearbeitungslinks mit Rechteprüfung.
- **Verbessert:** Erhält persönliche Favoriten und Anzeigepräferenzen bei Deaktivierung und Deinstallation; begrenzt Exporte auf erlaubte Inhalte und entschärft Tabellenformeln.
- **Verbessert:** Aktualisiert zweisprachige Banner und Icons mit der freigegebenen blaupetrolfarbenen Vektorgestaltung und lokalen Inter-Schriftpfaden samt Schriftlizenz.
- **Verbessert:** Integriert deckerweb Library 0.9.0 und Updater 2.1.0 mit vollständigen deutschen Du-/Sie-Übersetzungen im Host und synchroner lokaler Dokumentation.
- **Verbessert:** Zeigt das Plugin-Icon einmal im Einstellungsheader; Version, Dokumentation und Autorenangaben bleiben im Footer.
- **Behoben:** Ordnet normale Admin-Hinweise unter dem vollständigen Einstellungsheader ein und präzisiert die Beschriftungen für Spalte und Admin-Menüs der Inhaltstypen.
- **Sonstiges:** Erhält bestehende Website-Einstellungen, Builder-Inhalte und Mindestanforderungen; liefert die lokale Inter-Schriftlizenz mit.

## 1.1.0 · 2026-10-01

- **Neu:** Optionale Builder-Spalte mit anklickbaren Builder-Namen und mehreren Treffern je Inhalt.
- **Neu:** Eigene Ansichten und Untermenüs für mehrere gleichzeitig aktive Builder.
- **Neu:** Filter „Ohne erkannten Builder“ für Inhalte außerhalb der erkannten Builder-Gruppen.
- **Neu:** Kompakte Einstellungen mit deckerweb Header, Footer, lokaler Anleitung und Changelog-Dialog.
- **Verbessert:** Builder-Auswahl bleibt beim Suchen und Filtern der Inhaltsliste erhalten.
- **Verbessert:** Zentrale Regeln für Builder-Metadaten; Einstellungen werden einmal je Anfrage aufgelöst.
- **Verbessert:** Zählt lesbare veröffentlichte und unveröffentlichte Inhalte außerhalb des Papierkorbs mit schlanken ID-Abfragen.
- **Behoben:** Prüft URL-Parameter und filtert ausschließlich die Hauptabfrage der unterstützten Admin-Liste.
- **Behoben:** Erhält bestehende Meta-Abfragegruppen und verarbeitet fehlende oder fehlerhafte Builder-Optionen.
- **Behoben:** Untermenüs verwenden Bearbeitungsrechte des Inhaltstyps statt Theme-Rechte.
- **Behoben:** Verhindert die irrtümliche Zugriffssperre durch WP Admin Cleaner, wenn dessen ausgeblendete Bricks-Menüseite mit dem Ende einer Builder-Listen-URL kollidiert. Bestehende Listen-Links werden automatisch ergänzt; Suche und Filter bleiben erhalten.
- **Sonstiges:** Integriert den gemeinsamen deckerweb GitHub-Updater V2 und die eingebettete Plugin Library 0.2.0.
- **Sonstiges:** Erhöht die PHP-Mindestversion für die Library auf 8.0; WordPress 6.7 bleibt Mindestversion.
- **Sonstiges:** Ergänzt zweisprachige Readmes, Anleitungen, FAQs, Übersetzungen, Release-Werkzeuge und Regressionstests.

## 1.0.0 · 2025-04-11

- **Neu:** Erste öffentliche Version mit Ansichten und Untermenüs für elf Builder.
