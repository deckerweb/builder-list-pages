# Anleitung

Builder List Pages 1.1.0-rc1 · [FAQ](FAQ-Deutsch.md) · [English](English.md)

## Auf einen Blick

- **Builder erkennen:** anklickbare Builder-Spalte; mehrere Treffer pro Inhalt sind möglich.
- **Schnell filtern:** eigene Ansichten für mehrere aktive Builder und „Ohne erkannten Builder“.
- **Weiterarbeiten:** Builder-Auswahl bleibt bei Suche und normalen Listenfiltern erhalten.
- **Gewohnte Navigation:** zusätzliche Builder-Untermenüs verwenden die Rechte des jeweiligen Inhaltstyps.
- **Wenig Einrichtung:** drei optionale Schalter, deutsche Übersetzungen, lokale Anleitung und kompletter Änderungsverlauf.
- **DECKERWEB integriert:** eingebauter GitHub-Updater V2 und Plugin Library 0.2.0.

## Installation und erster Filter

1. Test-ZIP über **Plugins → Installieren → Plugin hochladen** installieren beziehungsweise die alte Plugin-Version ersetzen.
2. Plugin aktivieren und **Seiten** oder eine andere vom aktiven Builder unterstützte Inhaltsliste öffnen.
3. Einen Builder-Namen über der Tabelle anklicken. Anschließend bei Bedarf die Suche verwenden.
4. Einstellungen bei Bedarf unter **Einstellungen → Builder List Pages** anpassen.

Entweder Plugin oder den separat erzeugten Snippet-Export verwenden. Die neue modulare Hauptdatei lässt sich nicht allein als Snippet importieren. Der Export enthält die Listenfunktionen mit Standardschaltern, jedoch keine Einstellungen, Assets, Library oder Updates.

## Builder und Erkennung

Elementor, Bricks, Breakdance, Oxygen 6+, Oxygen Classic, Brizy, Beaver Builder, ZionBuilder, Thrive Architect, Pagelayer und Visual Composer. Visual Composer ist auf den bisherigen Seiten-/Beitragsumfang der kostenlosen Ausgabe begrenzt. Astra- und OceanWP-Inhaltstypen behalten ihre spezielle Menünavigation, sofern diese vorhanden ist.

Erkennung liest bestehende Metadaten aktiver unterstützter Builder für freigegebene Inhaltstypen. „Ohne erkannten Builder“ bedeutet nicht automatisch Gutenberg. Inaktive Builder-Daten und globale Template-Zuweisungen werden nicht analysiert. Existenzbasierte Integrationen behalten ihre bisherige Semantik auch für leere Werte.

Zähler erfassen die gesamte lesbare Gruppe außerhalb des Papierkorbs einschließlich unveröffentlichter Inhalte. Suche und weitere Listenfilter können die sichtbaren Ergebnisse reduzieren. Gruppen können sich überschneiden.

## Einstellungen

Builder-Spalte, Untermenüs und den Filter ohne erkannten Builder websiteweit ein- oder ausschalten. Die Spalte lässt sich zusätzlich persönlich über **Ansicht anpassen** ausblenden. Administratoren ändern Einstellungen; Redakteure verwenden Listenfunktionen gemäß ihren normalen Bearbeitungsrechten.

## Updates und Library

Stabile GitHub-Releases erscheinen über den deckerweb Updater im regulären WordPress-Updatesystem. Vorabversionen werden manuell installiert. Der Updater schaltet keine automatischen Updates ein.

Die eingebettete Library ergänzt **Plugins → Installieren → deckerweb**. Sie installiert nichts automatisch und hat eigene Sichtbarkeits- und Online-Katalogeinstellungen. Ihr lokaler Katalog enthält freigegebene Releases mit Prüfsummen; dieses RC ersetzt dort nicht die vorhandene stabile Version 1.0.0.

