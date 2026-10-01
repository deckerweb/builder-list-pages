# Testplan · Builder List Pages 1.1.0-rc1

## Installation

1. Testkopie mit WordPress ab 6.7 und PHP ab 8.0 verwenden.
2. `builder-list-pages-1.1.0-rc1.zip` als Plugin hochladen. Vorhandene Version ersetzen.
3. Alte Snippet-Version deaktivieren, falls sie verwendet wurde.
4. Cache leeren und die Seitenliste neu öffnen.

## Prüfen

- Ein aktiver Builder: Ansicht, Untermenü und Builder-Spalte stimmen überein.
- Zwei aktive Builder: beide erscheinen; eine Seite mit zwei Metadatentreffern zeigt beide.
- Builder wählen, suchen, Datumsfilter verwenden und paginieren: Auswahl bleibt erhalten.
- „Ohne erkannten Builder“ zeigt keine Seite mit einem passenden aktiven Builder-Marker.
- Entwürfe, ausstehende und private Inhalte sind im passenden Gruppenzähler; Papierkorb nicht.
- Redakteur: Builder-Untermenüs ohne Theme-Bearbeitungsrecht verfügbar.
- Autor: keine fremden unveröffentlichten Inhalte im Zähler.
- Vorhandener Meta-Filter eines anderen Plugins bleibt wirksam.
- Einstellungen: drei Schalter speichern; Spalte zusätzlich über Ansicht anpassen ausblenden.
- Footer: lokale deutsche/englische Anleitung, FAQ und Changelog-Dialog öffnen; Escape schließt.
- Library: deckerweb-Tab einmal vorhanden, kein doppelter Hinweis mit einem zweiten deckerweb-Host.
- Updater: Icons und Banner vorhanden; keine Rückstufung auf 1.0.0; RCs bleiben manuelle Updates.

## Grenzen

Automatische Prüfungen verwenden Builder-Metadaten und Konstanten als Fixtures.
Echte Builder-Versionen, MySQL, PHP 8.0, Multisite und ClassicPress sind noch
nicht umfassend validiert. Inaktive Builder und globale Templates werden nicht erkannt.
Variante A ist vorläufig im Paket; B und C sind alternative Designvorschläge.
