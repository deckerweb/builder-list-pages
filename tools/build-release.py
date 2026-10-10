#!/usr/bin/env python3
"""Build deterministic plugin and source archives; publication remains a separate action."""
from pathlib import Path
import argparse,hashlib,re,zipfile
p=argparse.ArgumentParser();p.add_argument('--output',required=True);p.add_argument('--label',default='');args=p.parse_args()
root=Path(__file__).resolve().parents[1];out=Path(args.output).resolve();out.mkdir(parents=True,exist_ok=True)
version=re.search(r'\* Version: (\S+)',(root/'builder-list-pages.php').read_text()).group(1)
assert not args.label or re.fullmatch(r'[a-z0-9-]+',args.label),'Invalid package label'
suffix=args.label or version
required=['builder-list-pages.php','uninstall.php','includes/deckerweb-plugin-library/lifecycle.php','includes/deckerweb-plugin-library/compatibility.json','includes/deckerweb-github-release-updater-v2.php','includes/updater-translations.php','includes/deckerweb-changelog-v1.php','assets/icon.svg','assets/Inter-OFL.txt','assets/admin.css','assets/documentation.js','assets/documentation.css','languages/builder-list-pages-de_DE.mo','languages/builder-list-pages-de_DE_formal.mo','docs/wiki/English.html','docs/wiki/Deutsch.html','docs/changelog.txt','docs/changelog-de.txt','SECURITY.md']
for name in required:assert (root/name).is_file(),f'Missing runtime file: {name}'
rootfiles={'builder-list-pages.php','uninstall.php','index.php','LICENSE','SECURITY.md','README.md','README-de.md','readme.txt','readme-de.txt'}
def files(runtime):
 for f in sorted(root.rglob('*')):
  if not f.is_file():continue
  rel=f.relative_to(root)
  if any(part in {'.git','.nova','__pycache__','node_modules'} for part in rel.parts) or f.name in {'.DS_Store','.gitattributes','.gitignore'}:continue
  assert not any(word in str(rel).lower() for word in ['roadmap','specifikation','sub-spec','internal-archive']),f'Private document: {rel}'
  if runtime:
   allowed=(str(rel) in rootfiles or rel.parts[0] in {'includes','languages'} or (rel.parts[0]=='assets' and 'alternatives' not in rel.parts and (f.suffix in {'.svg','.png','.css','.js'} or f.name == 'Inter-OFL.txt')) or (str(rel) in {'docs/changelog.txt','docs/changelog-de.txt'}) or (rel.parts[:2]==('docs','wiki') and f.suffix=='.html'))
   if not allowed:continue
  yield f,rel

def archive(path,items):
 with zipfile.ZipFile(path,'w',zipfile.ZIP_DEFLATED,compresslevel=9) as z:
  for f,rel in items:
   info=zipfile.ZipInfo('builder-list-pages/'+rel.as_posix(),date_time=(2026,10,10,12,0,0));info.compress_type=zipfile.ZIP_DEFLATED;info.external_attr=0o100644<<16;z.writestr(info,f.read_bytes())
paths=[]
for kind,runtime in [('',True),('-source',False)]:
 dest=out/f'builder-list-pages-{suffix}{kind}.zip';archive(dest,files(runtime));paths.append(dest);print(f'{dest.name}: {dest.stat().st_size:,} bytes')
(out/f'SHA256SUMS-{suffix}.txt').write_text(''.join(f'{hashlib.sha256(f.read_bytes()).hexdigest()}  {f.name}\n' for f in paths))
