# 1.1.0

## Unreleased · 2026-10-10

- **New:** Adds a multiple-builder filter, optional inactive-trace audit, personal saved views, compact user display choice, minimal copyable diagnostics and an explicit CSV handover export.
- **Improved:** Explains empty results with independent search/builder recovery and clearer badge actions; adds permission-checked native Bricks editor links.
- **Improved:** Preserves personal favorites and preferences across deactivation and uninstall; limits exports to permitted items and neutralizes spreadsheet formulas.
- **Improved:** Updates bilingual banners and icons using the approved blue-petrol vector artwork and local Inter font outlines with the font license.
- **Fixed:** Places native admin notices below the complete settings header and clarifies the column and post-type admin menu labels.

## 1.1.0 · 2026-10-01

- **New:** Optional Builder column with clickable builder names and overlapping matches.
- **New:** Independent views and submenus for multiple active builders.
- **New:** “No recognized builder” filter for content outside the detected builder groups.
- **New:** Compact settings page with deckerweb header, footer, local documentation and changelog dialog.
- **Improved:** Retains the builder selection when searching and filtering the content list.
- **Improved:** Centralizes builder metadata rules and resolves settings once per request.
- **Improved:** Counts readable published and unpublished content outside trash using ID-only queries.
- **Fixed:** Validates URL parameters and targets only the main supported admin list.
- **Fixed:** Preserves existing meta query groups and handles missing or malformed builder options.
- **Fixed:** Uses post-type editing permissions for builder submenus instead of theme permissions.
- **Fixed:** Prevents WP Admin Cleaner from incorrectly blocking builder lists when a hidden Bricks menu slug matches the end of their URL. Existing list links are normalized automatically while preserving search and filters.
- **Misc:** Integrates the shared deckerweb GitHub Updater V2 and embedded Plugin Library 0.2.0.
- **Misc:** Raises minimum PHP to 8.0 for the embedded Library; WordPress 6.7 remains the minimum.
- **Misc:** Adds bilingual readmes, guides, FAQs, translations, release tooling and regression checks.

## Unveröffentlicht · 2026-10-10

- **Neu:** Ergänzt einen Mehrfach-Builder-Filter, optionale Prüfung inaktiver Spuren, persönliche gespeicherte Ansichten, kompakte Benutzerauswahl, sparsame kopierbare Diagnose und einen ausdrücklichen CSV-Export für Übergaben.
- **Verbessert:** Erklärt leere Ergebnisse mit getrenntem Zurücksetzen von Suche und Builder sowie klareren Kennzeichen-Aktionen; ergänzt normale Bricks-Bearbeitungslinks mit Rechteprüfung.
- **Verbessert:** Erhält persönliche Favoriten und Anzeigepräferenzen bei Deaktivierung und Deinstallation; begrenzt Exporte auf erlaubte Inhalte und entschärft Tabellenformeln.
- **Verbessert:** Aktualisiert zweisprachige Banner und Icons mit der freigegebenen blaupetrolfarbenen Vektorgestaltung und lokalen Inter-Schriftpfaden samt Schriftlizenz.
- **Behoben:** Ordnet normale Admin-Hinweise unter dem vollständigen Einstellungsheader ein und präzisiert die Beschriftungen für Spalte und Admin-Menüs der Inhaltstypen.

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
