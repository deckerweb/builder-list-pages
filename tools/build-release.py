#!/usr/bin/env python3
"""Build reproducible direct-distribution packages without external dependencies."""
from pathlib import Path
import argparse,hashlib,json,re,zipfile
parser=argparse.ArgumentParser();parser.add_argument('--output',required=True);args=parser.parse_args()
root=Path(__file__).resolve().parents[1];out=Path(args.output).resolve();out.mkdir(parents=True,exist_ok=True)
main=(root/'builder-list-pages.php').read_text();version=re.search(r'\* Version: (\S+)',main).group(1)
required=['includes/class-blp-registry.php','includes/class-blp-query.php','includes/class-blp-admin.php','includes/class-blp-settings.php','includes/class-blp-github-updates.php','includes/deckerweb-github-release-updater-v2.php','includes/deckerweb-plugin-library/bootstrap.php','includes/deckerweb-plugin-library/catalog.json','assets/icon.svg','assets/admin.css','assets/documentation.js','assets/banner-1544x500.png','assets/banner-de-1544x500.png','languages/builder-list-pages-de_DE.mo','docs/wiki/English.html','docs/wiki/Deutsch.html','docs/changelog.txt','docs/changelog-de.txt']
for name in required:
    assert (root/name).is_file(),f'Missing runtime file: {name}'
exclude={'.git','.github','.nova','tests','tools'}
def files(runtime):
    for p in sorted(root.rglob('*')):
        if not p.is_file():continue
        rel=p.relative_to(root)
        if any(part in exclude for part in rel.parts):continue
        if p.name in {'.DS_Store','.gitattributes','.gitignore','_config.yml'}:continue
        if runtime and ('alternatives' in rel.parts or p.suffix=='.scss' or p.name in {'DEVELOPMENT.md','ROADMAP.md'}):continue
        yield p,rel

def archive(destination,items):
    with zipfile.ZipFile(destination,'w',zipfile.ZIP_DEFLATED,compresslevel=9) as z:
        for name,data in items:
            info=zipfile.ZipInfo(name,date_time=(2026,10,1,12,0,0));info.compress_type=zipfile.ZIP_DEFLATED;info.external_attr=0o100644<<16;z.writestr(info,data)
plugin=out/f'builder-list-pages-{version}.zip'
archive(plugin,((f'builder-list-pages/{rel.as_posix()}',p.read_bytes()) for p,rel in files(True)))
source=out/f'builder-list-pages-{version}-source.zip'
source_items=list((f'builder-list-pages/{rel.as_posix()}',p.read_bytes()) for p,rel in files(False))
for folder in ('tests','tools','.github'):
    for p in sorted((root/folder).rglob('*')):
        if p.is_file():source_items.append((f'builder-list-pages/{p.relative_to(root).as_posix()}',p.read_bytes()))
archive(source,source_items)
# A snippet is a list-only build, not the plugin bootstrap pasted into a manager.
parts=[]
for name in ('registry','query','admin'):
    s=(root/f'includes/class-blp-{name}.php').read_text()
    s=s.replace('<?php','',1).replace('namespace Deckerweb\\BuilderListPages;','',1).replace("defined( 'ABSPATH' ) || exit;",'',1)
    parts.append(s)
stub="final class Settings { public static function enabled( string $key ): bool { return true; } }\n"
body="namespace Deckerweb\\BuilderListPages;\nif ( ! defined( 'ABSPATH' ) ) { return; }\nif ( ! defined( 'BLP_VERSION' ) && ! class_exists( 'DDW_Builder_List_Pages', false ) ) {\n"
body+=f"define( 'BLP_VERSION', '{version}' );\ndefine( 'BLP_SNIPPET', true );\ndefine( 'BLP_PLUGIN_FILE', __FILE__ );\n"+stub+'\n'.join(parts)
body+="\n$registry = new Registry();\n$query = new Query( $registry );\n( new Admin( $registry, $query, new Settings() ) )->register();\n}\n"
snippet=out/f'ddw-builder-list-pages-{version}.php';snippet.write_text('<?php\n/** Builder List Pages list-only snippet. Use instead of the plugin, never together. */\n'+body)
export=out/'ddw-builder-list-pages.code-snippets.json';export.write_text(json.dumps({'generator':'Code Snippets v3.6.8','date_created':'2026-10-01 12:00','snippets':[{'name':'DDW Builder List Pages','desc':f'List-only Builder List Pages {version}. No settings, updater or Library.','code':body,'tags':['deckerweb','builder-list-pages'],'scope':'global','active':False,'priority':10}]},ensure_ascii=False,indent=2)+'\n')
art=out/f'builder-list-pages-{version}-designs.zip';archive(art,((rel.name,p.read_bytes()) for p,rel in files(False) if 'alternatives' in rel.parts))
paths=[plugin,source,snippet,export,art]
(out/'SHA256SUMS.txt').write_text(''.join(f'{hashlib.sha256(p.read_bytes()).hexdigest()}  {p.name}\n' for p in paths))
for p in paths:print(f'{p.name}: {p.stat().st_size:,} bytes')
