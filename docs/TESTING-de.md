# Testplan · Builder List Pages 1.1.0-rc2

## Installation

1. Testkopie mit WordPress ab 6.7 und PHP ab 8.0 verwenden.
2. `builder-list-pages-1.1.0-rc2.zip` als Plugin hochladen. Vorhandene Version ersetzen.
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
Variante A ist als finales Design gewählt; B und C bleiben im Designpaket verfügbar.

## Kompatibilitätskorrektur in rc2

Mit WordPress 7.1.2, Bricks 2.4.2 und WP Admin Cleaner 2.0.1 geprüft; 44 Integrationstests bestanden.

- Admin Cleaner aktiv lassen und „Bricks → Erste Schritte“ ausblenden.
- `wp-admin/edit.php?post_type=page&builder=bricks` öffnen: Die Liste lädt und ergänzt automatisch `&blp_view=1`.
- Suche, Sortierung und Datumsfilter prüfen: Die Bricks-Auswahl bleibt erhalten.
- Die ausgeblendete Bricks-Menüseite bleibt gesperrt.
