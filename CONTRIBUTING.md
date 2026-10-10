# Maintenance / Pflege

## English

Keep the native list workflow, permissions, settings and builder metadata intact. Patch versions contain compatible fixes, minor versions contain compatible features, and major versions identify incompatible changes. Higher WordPress/PHP minimums require a deliberate release decision. Use the existing `vX.Y.Z` GitHub tag convention and reviewed plugin ZIP; publishing is a separate action.

`tools/documentation.json` is the shared content source for the four readmes, guides, FAQ and release history. `tools/translations.json` maintains English keys with informal and formal German values. Regenerate documents with `python3 tools/build-docs.py` and languages with `python3 tools/build-languages.py` (GNU gettext `msgfmt` required). Install the documentation renderer dependency in `tools/` with `npm install`, then run `npm run docs` there. Build the plugin and source ZIPs with `python3 tools/build-release.py --output /path/to/packages`; add `--label review-YYYY-MM-DD` for a test package without changing the plugin version. Existing snippet artifacts are historical; the package builder creates no new snippets.

Run the PHP scripts in `tests/` against a disposable WordPress installation with this plugin active: `php tests/integration.php /path/to/wp-load.php`, `php tests/maintenance.php /path/to/wp-load.php` and `php tests/update-safety.php /path/to/wp-load.php`. These tests create and remove fixture data and change test settings; never run them on a customer site. Shared component releases are copied from their approved original packages. Preserve the Library manifest and file hashes; component changes belong to their own maintenance workflow.

## Deutsch

Erhalte die normalen Inhaltslisten, Rechte, Einstellungen und Builder-Metadaten. Patch-Versionen enthalten kompatible Korrekturen, Minor-Versionen kompatible Funktionen und Major-Versionen kennzeichnen inkompatible Änderungen. Höhere WordPress-/PHP-Mindestversionen brauchen eine bewusste Release-Entscheidung. Verwende die bestehenden GitHub-Tags `vX.Y.Z` und das geprüfte Plugin-ZIP; die Veröffentlichung ist eine separate Aktion.

`tools/documentation.json` ist die gemeinsame Inhaltsquelle für die vier Readmes, Anleitungen, FAQ und Veröffentlichungshistorie. `tools/translations.json` enthält englische Schlüssel und deutsche Du-/Sie-Texte. Erzeuge Dokumente mit `python3 tools/build-docs.py` und Sprachdateien mit `python3 tools/build-languages.py` (GNU-gettext-Werkzeug `msgfmt` erforderlich). Installiere im Ordner `tools/` mit `npm install` die Abhängigkeit des Dokumentationsrenderers und führe dort `npm run docs` aus. Erzeuge Plugin- und Quell-ZIP mit `python3 tools/build-release.py --output /pfad/zu/paketen`; `--label review-JJJJ-MM-TT` erstellt ein Testpaket ohne Änderung der Pluginversion. Vorhandene Snippet-Dateien sind historische Artefakte; der Paketgenerator erzeugt keine neuen Snippets.

Führe die PHP-Skripte in `tests/` nur in einer separaten WordPress-Testinstallation mit aktivem Plugin aus: `php tests/integration.php /pfad/zu/wp-load.php`, `php tests/maintenance.php /pfad/zu/wp-load.php` und `php tests/update-safety.php /pfad/zu/wp-load.php`. Sie erzeugen und entfernen Testdaten und ändern Testeinstellungen; niemals auf einer Kundensite ausführen. Gemeinsame Komponenten werden aus ihren freigegebenen Originalpaketen übernommen. Erhalte das Library-Manifest und seine Datei-Prüfsummen; Komponentenänderungen gehören in deren eigenen Pflegeablauf.

## Terms / Begriffe

| English | Deutsch (Du) | Deutsch (Sie) |
| --- | --- | --- |
| Builder | Builder | Builder |
| No recognized builder | Ohne erkannten Builder | Ohne erkannten Builder |
| Settings | Einstellungen | Einstellungen |
| Readable items | Für dich lesbare Inhalte | Für Sie lesbare Inhalte |
| Screen Options | Ansicht anpassen | Ansicht anpassen |
| Changelog | Änderungsverlauf | Änderungsverlauf |
| Plugin series | Plugin-Serien | Plugin-Serien |
