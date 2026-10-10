# Security / Sicherheit

## English

Report vulnerabilities in Builder List Pages or its embedded deckerweb Library privately through [GitHub Security Advisories](https://github.com/deckerweb/builder-list-pages/security/advisories/new). Include affected versions, reproduction steps and the potential impact. Do not post credentials, private content or exploit details in public issues. David Decker – DECKERWEB maintains this plugin and coordinates security fixes. Use the latest stable version; older versions are not maintained separately.

There is no usage telemetry. The updater contacts `api.github.com` for public release information and GitHub for update downloads. Requests include the WordPress version and site URL through the standard HTTP user agent; the receiving server can see the IP address. The Library ships a local catalog. Its optional online catalog contacts the approved GitHub catalog endpoint only when enabled. An explicit installation or update downloads the chosen package from its approved source. Page content and builder metadata are not uploaded.

## Deutsch

Melde Sicherheitslücken in Builder List Pages oder seiner eingebetteten deckerweb Library vertraulich über [GitHub Security Advisories](https://github.com/deckerweb/builder-list-pages/security/advisories/new). Nenne betroffene Versionen, Schritte zur Reproduktion und mögliche Auswirkungen. Veröffentliche keine Zugangsdaten, vertraulichen Inhalte oder Exploit-Details in öffentlichen Issues. David Decker – DECKERWEB pflegt das Plugin und koordiniert Sicherheitskorrekturen. Verwende die aktuelle stabile Version; ältere Versionen werden nicht getrennt gepflegt.

Es gibt keine Nutzungstelemetrie. Der Updater ruft `api.github.com` für öffentliche Release-Informationen und GitHub für Update-Downloads auf. Anfragen enthalten über den üblichen HTTP-User-Agent die WordPress-Version und Website-URL; der empfangende Server sieht die IP-Adresse. Die Library enthält einen lokalen Katalog. Ihr optionaler Online-Katalog kontaktiert den freigegebenen GitHub-Katalog-Endpunkt nur bei eingeschalteter Option. Eine ausdrücklich ausgelöste Installation oder ein Update lädt das gewählte Paket aus der freigegebenen Quelle. Seiteninhalte und Builder-Metadaten werden nicht übertragen.

Personal views remain local to the authenticated user and current site. CSV export requires a valid nonce and content permissions, neutralizes formula prefixes and exports at most 5,000 readable/editable items. Its edit URLs identify the site; hand it only to intended recipients. Minimal support diagnostics exclude those URLs and all user/content data.

Persönliche Ansichten bleiben beim angemeldeten Benutzer und der aktuellen Website. Der CSV-Export prüft Nonce und Inhaltsrechte, entschärft Formel-Präfixe und enthält höchstens 5.000 lesbare/bearbeitbare Inhalte. Seine Bearbeitungslinks identifizieren die Website; gib ihn nur an vorgesehene Empfänger weiter. Die sparsame Support-Diagnose enthält diese URLs und Benutzer-/Inhaltsdaten nicht.
