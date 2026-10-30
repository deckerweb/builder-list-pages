#!/usr/bin/env python3
"""Render the compact bilingual builder matrix from one maintained source."""
from pathlib import Path
import json
root=Path(__file__).resolve().parents[1]
data=json.loads((root/'tools/support-matrix.json').read_text())
for lang,name,other in [('de','Deutsch','English'),('en','English','Deutsch')]:
 de=lang=='de'
 def table(rows):
  return '| '+('Werkzeug | Unterstützt | Kurzinfo' if de else 'Tool | Supported | Note')+' |\n|---|---|---|\n'+'\n'.join('| '+name+' | '+('Ja' if de else 'Yes')+' | '+((german if de else english) or '—')+' |' for name,german,english in rows)
 text=('# Unterstützte Builder' if de else '# Supported builders')+f'\n\nBuilder List Pages {data["version"]} · [{other}](Builders-{other}.md)\n\n'
 text+=('Finde dein Werkzeug in der passenden Tabelle. Bei blockbasierten Werkzeugen werden die verwendeten Blöcke erkannt, auch bei gemischten Seiten.' if de else 'Find your tool in the matching table. Block-based tools are recognized from the blocks used, including mixed pages.')+'\n\n'
 for key,title in [('classic','Klassische Page Builder' if de else 'Classic page builders'),('blocks','Blockbasierte Builder' if de else 'Block-based builders')]:text+='## '+title+'\n\n'+table(data[key])+'\n\n'
 text+=('Das passende Plugin beziehungsweise Theme muss für aktive Filter verfügbar sein. Reine WordPress-Blöcke zählen nicht als zusätzlicher Builder. Globale Header, Footer und automatische Templates werden nicht pauschal einer Seite zugerechnet.' if de else 'The corresponding plugin or theme must be available for active filters. WordPress core blocks do not count as another builder. Global headers, footers and automatic templates are not automatically attributed to a page.')+f'\n\n[{"Sonderfälle und Grenzen" if de else "Special cases and limits"}](Recognition-{name}.md) · [{"Anleitung" if de else "Guide"}]({name}.md)\n'
 (root/f'docs/wiki/Builders-{name}.md').write_text(text)
