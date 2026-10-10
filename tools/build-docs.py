#!/usr/bin/env python3
"""Render bilingual readmes, FAQ and changelog from one maintained content source."""
from pathlib import Path
import json,re
root=Path(__file__).resolve().parents[1]
d=json.loads((root/'tools/documentation.json').read_text())
def slug(s):return re.sub(r'[^\w]+','-',s.lower(),flags=re.UNICODE).strip('-')
def history(lang,txt=False):
 return '\n\n'.join((f'= {v} =' if txt else f'## {v}')+'\n\n'+'\n'.join((f'* {p}: {t}' if txt else f'- **{p}:** {t}') for p,t in items) for v,items in d['history'][lang])
for lang,name,other in [('en','English','Deutsch'),('de','Deutsch','English')]:
 de=lang=='de';v=d['languages'][lang]; flat=[q for _,qs in v['faq'] for q in qs];v['readme_faq']=[flat[i] for i in v['readme_faq_indices']]; sections=v['sections'];pre=sections[:6];post=sections[6:];faqtitle='FAQ';chtitle='Changelog';fullfaq='Vollständige FAQ nach Themen' if de else 'Complete FAQ by topic'
 intro=v['intro']; intro=re.sub(r'\*\*Version:\*\*[^\n]+',f'**Version:** {d["version"]} · **'+('Voraussetzungen' if de else 'Requires')+':** WordPress 6.7+ / PHP 8.0+ · **'+('Lizenz' if de else 'License')+':** GPL v2 '+('oder neuer' if de else 'or later'),intro)
 ordered=pre+[[faqtitle,'\n\n'.join('### '+q+'\n\n'+a for q,a in v['readme_faq'])+f'\n\n[{fullfaq}](docs/wiki/FAQ-{name}.md)'],[chtitle,history(lang).replace('## ', '### ')+f'\n\n[{chtitle}](docs/wiki/Changelog-{name}.md)']]+post
 toc='## '+('Inhalt' if de else 'Contents')+'\n\n'+'\n'.join(f'- [{title}](#{slug(title)})' for title,_ in ordered)
 md=intro.strip()+'\n\n'+toc+'\n\n'+'\n\n'.join(f'## {t}\n\n{body}' for t,body in ordered)+'\n'
 (root/('README-de.md' if de else 'README.md')).write_text(md)
 header=f'=== Builder List Pages ===\nContributors: deckerweb\nTags: admin, pages, page builder, filter, post types\nRequires at least: 6.7\nRequires PHP: 8.0\nStable tag: {d["version"]}\nLicense: GPL v2 or later\nLicense URI: https://www.gnu.org/licenses/gpl-2.0.html\n\n'+('Seiten nach Builder filtern und direkt in den normalen WordPress-Inhaltslisten weiterarbeiten.' if de else 'Find pages by builder and keep working in familiar WordPress content lists.')+'\n\n'
 desc=pre+post
 txt=header+'== Description ==\n\n'+('Deine Seiten. Deine Builder. Alles im Blick.' if de else 'Your pages. Your builders. One clear overview.')+'\n\n'+'\n\n'.join(f'= {t} =\n\n{body}' for t,body in desc if t not in ('Installation and first filter','Installation und erster Filter'))
 install=next(body for title,body in pre if title.startswith('Installation'))
 txt+='\n\n== Installation ==\n\n'+install+'\n\n== Frequently Asked Questions ==\n\n'+'\n\n'.join('= '+q+' =\n\n'+a for q,a in v['readme_faq'])+f'\n\n[{fullfaq}](https://github.com/deckerweb/builder-list-pages/wiki/FAQ-{name})\n\n== Changelog ==\n\n'+history(lang,True)+'\n'
 txt=re.sub(r'\*\*([^*]+)\*\*',r'\1',txt)
 # WordPress readmes use absolute links outside their own sections.
 txt=re.sub(r'\]\((?!https?:|#)([^)]+)\)',lambda m:'](https://github.com/deckerweb/builder-list-pages/blob/main/'+m.group(1)+')',txt)
 (root/('readme-de.txt' if de else 'readme.txt')).write_text(txt)
 guide='# '+('Anleitung' if de else 'User guide')+f'\n\nBuilder List Pages {d["version"]} · [FAQ](FAQ-{name}.md) · [{other}]({other}.md)\n\n'+'\n\n'.join('## '+t+'\n\n'+body.replace('(assets/Inter-OFL.txt)','(https://github.com/deckerweb/builder-list-pages/blob/main/assets/Inter-OFL.txt)').replace('(SECURITY.md)','(https://github.com/deckerweb/builder-list-pages/blob/main/SECURITY.md)').replace('(LICENSE)','(https://github.com/deckerweb/builder-list-pages/blob/main/LICENSE)') for t,body in sections)+'\n'
 (root/f'docs/wiki/{name}.md').write_text(guide)
 faq='# '+('Fragen nach Themen' if de else 'FAQ by topic')+f'\n\n[{"Anleitung" if de else "Guide"}]({name}.md) · [{other}](FAQ-{other}.md)\n\n'+'\n\n'.join('## '+title+'\n\n'+'\n\n'.join('### '+q+'\n\n'+a for q,a in qs) for title,qs in v['faq'])+'\n'
 (root/f'docs/wiki/FAQ-{name}.md').write_text(faq)
 hist='# Changelog\n\n'+history(lang)+'\n'
 (root/('docs/CHANGELOG-de.md' if de else 'docs/CHANGELOG.md')).write_text(hist)
 (root/f'docs/wiki/Changelog-{name}.md').write_text('# Changelog\n\n'+f'[{other}](Changelog-{other}.md) · [FAQ](FAQ-{name}.md)\n\n'+history(lang)+'\n')
 (root/('docs/changelog-de.txt' if de else 'docs/changelog.txt')).write_text('== Changelog ==\n\n'+history(lang,True)+'\n')
(root/'docs/wiki/Home.md').write_text('# Builder List Pages\n\n![Builder List Pages](https://raw.githubusercontent.com/deckerweb/builder-list-pages/main/assets/banner-1280x640.png)\n\nYour pages. Your builders. One clear overview.\n\n[English](English.md) · [FAQ by topic](FAQ-English.md) · [Changelog](Changelog-English.md)\n\n![Builder List Pages](https://raw.githubusercontent.com/deckerweb/builder-list-pages/main/assets/banner-de-1280x640.png)\n\nDeine Seiten. Deine Builder. Alles im Blick.\n\n[Deutsch](Deutsch.md) · [Fragen nach Themen](FAQ-Deutsch.md) · [Changelog](Changelog-Deutsch.md)\n')
(root/'docs/wiki/_Sidebar.md').write_text('[Builder List Pages](Home)\n\n**English**\n- [User guide](English)\n- [FAQ by topic](FAQ-English)\n- [Changelog](Changelog-English)\n\n**Deutsch**\n- [Anleitung](Deutsch)\n- [Fragen nach Themen](FAQ-Deutsch)\n- [Changelog](Changelog-Deutsch)\n')
(root/'RELEASE-NOTES.md').write_text('# 1.1.0\n\n'+history('en').split('\n\n## 1.0.0')[0]+'\n\n'+history('de').split('\n\n## 1.0.0')[0]+'\n')
print('Generated four readmes, both FAQ and guide languages, history and Wiki navigation.')
