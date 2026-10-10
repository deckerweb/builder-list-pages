const fs=require('fs');
const path=require('path');
(async()=>{
const {marked}=await import(require.resolve('marked'));
const version=JSON.parse(fs.readFileSync(path.resolve(__dirname,'documentation.json'),'utf8')).version;
const dir=path.resolve(__dirname,'../docs/wiki');
for(const name of fs.readdirSync(dir).filter(x=>x.endsWith('.md')&&!x.startsWith('_'))){
 let text=fs.readFileSync(dir+'/'+name,'utf8').replace(/\]\((?!https?:|\.\.\/)([^)]+)\.md([#][^)]*)?\)/g,']($1.html$2)');
 text=text.replaceAll('https://raw.githubusercontent.com/deckerweb/builder-list-pages/main/assets/', '../../assets/').replaceAll('https://github.com/deckerweb/builder-list-pages/blob/main/SECURITY.md','../../SECURITY.md').replaceAll('https://github.com/deckerweb/builder-list-pages/blob/main/LICENSE','../../LICENSE').replaceAll('https://github.com/deckerweb/builder-list-pages/blob/main/assets/Inter-OFL.txt','../../assets/Inter-OFL.txt');
 let content=marked.parse(text).replace(/<h([123])>(.*?)<\/h\1>/g,(all,n,title)=>`<h${n} id="${title.replace(/<[^>]*>/g,'').toLowerCase().replace(/[^\p{L}\p{N}]+/gu,'-').replace(/^-|-$/g,'')}">${title}</h${n}>`);
 const de=/Deutsch/.test(name);const title=de?'Builder List Pages · Dokumentation':'Builder List Pages · Documentation';
 const style='body{margin:0;background:#f3f6f7;color:#223641;font:16px/1.7 system-ui,sans-serif}main{max-width:940px;margin:36px auto;padding:32px 42px;background:white;border:1px solid #d4dee0;border-radius:12px}nav{display:flex;flex-wrap:wrap;gap:14px;padding-bottom:18px;border-bottom:1px solid #d4dee0}a{color:#17616a;text-underline-offset:3px}h1{font-size:32px}h2{margin-top:38px;padding-top:14px;border-top:1px solid #e2e8ea}h3{margin-top:28px}img{max-width:100%}code{background:#eef4f5;padding:2px 5px}pre{white-space:pre-wrap;overflow-wrap:anywhere}table{border-collapse:collapse}td,th{border:1px solid #d4dee0;padding:8px}@media(max-width:640px){main{margin:12px;padding:20px}}';
 fs.writeFileSync(path.resolve(dir,'../../assets/documentation.css'),style+'\n');
 const page=`<!doctype html><html lang="${de?'de':'en'}"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>${title}</title><link rel="stylesheet" href="../../assets/documentation.css"></head><body><main><nav><a href="Home.html">Builder List Pages</a><a href="English.html">English</a><a href="Deutsch.html">Deutsch</a><a href="FAQ-${de?'Deutsch':'English'}.html">FAQ</a><a href="Changelog-${de?'Deutsch':'English'}.html">Changelog</a></nav>${content}<footer><p>© 2019–2026 David Decker – DECKERWEB · ${version}</p></footer></main></body></html>`;
 fs.writeFileSync(dir+'/'+name.replace('.md','.html'),page);
}
console.log('Rendered seven local HTML documentation pages.');
})();
