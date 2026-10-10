#!/usr/bin/env python3
"""Compile the host domain, retaining host wording and bundled component messages."""
from pathlib import Path
import re,json,subprocess
root=Path(__file__).resolve().parents[1]; translations=json.loads((root/'tools/translations.json').read_text());messages={}
for f in [root/'builder-list-pages.php',*root.glob('includes/class-blp-*.php'),root/'includes/updater-translations.php']:
 text=f.read_text()
 for m in re.finditer(r"\b(?:__|esc_html__|esc_attr__)\(\s*'((?:\\.|[^'\\])*)'\s*,\s*'builder-list-pages'",text):
  key=m.group(1).replace("\\'","'").replace('\\\\','\\');messages.setdefault(key,[]).append(f'{f.relative_to(root)}:{text[:m.start()].count(chr(10))+1}')
for key in translations:messages.setdefault(key,['includes/deckerweb-plugin-library / shared updater'])
assert not set(messages)-set(translations),set(messages)-set(translations)
quote=lambda x:json.dumps(x,ensure_ascii=False)
for locale in ['de_DE','de_DE_formal']:
 header=f'Project-Id-Version: Builder List Pages 1.1.0\nReport-Msgid-Bugs-To: https://github.com/deckerweb/builder-list-pages/issues\nPOT-Creation-Date: 2026-10-10 12:00+0200\nPO-Revision-Date: 2026-10-10 12:00+0200\nLast-Translator: David Decker – DECKERWEB\nLanguage-Team: German\nLanguage: {locale}\nMIME-Version: 1.0\nContent-Type: text/plain; charset=UTF-8\nContent-Transfer-Encoding: 8bit\nPlural-Forms: nplurals=2; plural=(n != 1);\n'
 po='msgid ""\nmsgstr '+quote(header)+'\n\n'
 for msg,refs in sorted(messages.items()):
  trans=translations[msg][locale];assert trans,msg
  po+='#: '+' '.join(refs)+'\n'+('#, php-format\n' if re.search(r'%[\d$]*[sd]',msg) else '')+'msgid '+quote(msg)+'\nmsgstr '+quote(trans)+'\n\n'
 p=root/f'languages/builder-list-pages-{locale}.po';p.write_text(po.rstrip()+"\n");subprocess.run(['msgfmt','--check','-o',str(p.with_suffix('.mo')),str(p)],check=True)
 pot='msgid ""\nmsgstr '+quote('Project-Id-Version: Builder List Pages 1.1.0\nContent-Type: text/plain; charset=UTF-8\n')+'\n\n'
 for msg,refs in sorted(messages.items()):pot+='#: '+' '.join(refs)+'\nmsgid '+quote(msg)+'\nmsgstr ""\n\n'
 (root/'languages/builder-list-pages.pot').write_text(pot.rstrip()+"\n")
print(f'Compiled {len(messages)} nonempty translations in each German locale.')
