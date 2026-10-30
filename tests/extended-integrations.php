<?php
/** Verify the implemented integrations in a disposable WordPress installation. */
define('WP_ADMIN',true);require $argv[1];wp_set_current_user(1);
use Deckerweb\BuilderListPages\BlockAdapters as B;
use Deckerweb\BuilderListPages\Registry as R;
use Deckerweb\BuilderListPages\Query as Q;
use Deckerweb\BuilderListPages\LayoutAdapters as L;
/** Assert an integration outcome. @param bool $ok Expected condition. @param string $label Description. @return void */
function check($ok,$label){if(!$ok)throw new Exception($label);echo "PASS $label\n";}
register_post_type('blp_extended',['public'=>true,'show_ui'=>true,'show_in_rest'=>true,'supports'=>['editor','title']]);
$ids=[];
try {
 foreach(['genesis-blocks','generateblocks','stackable','otter','essential-blocks','gutenkit','kubio','siteorigin']as$f)check(B::families()[$f]['active'],'Real plugin active '.$f);
 $cases=['genesis-blocks'=>['genesis-blocks/gb-container','genesis-blocks/gb-post-grid'],'generateblocks'=>['generateblocks/element','generateblocks/container','generateblocks-pro/tabs'],'stackable'=>['stackable/columns','ugb/container'],'otter'=>['themeisle-blocks/advanced-heading','atomic-wind/box'],'essential-blocks'=>['essential-blocks/wrapper','essential-blocks/pro-loop-builder'],'gutenkit'=>['gutenkit/container'],'kubio'=>['kubio/section'],'spectra-blocks'=>['spectra-pro/login'],'kadence-blocks'=>['kadence/marquee','kadence/postgrid']];
 foreach($cases as$f=>$names)foreach($names as$n){$id=wp_insert_post(['post_type'=>'blp_extended','post_status'=>'publish','post_title'=>$n,'post_content'=>'<!-- wp:'.$n.' /-->']);$ids[]=$id;check(isset((new R)->matches($id,'blp_extended')[$f]),'Row '.$n);}
 foreach(['<!-- wp:atomic-blocks/ab-container /-->','<!-- wp:kadence/page-wizard /-->','<!-- wp:gutenberg-templates/spectra-ai /-->','<!-- wp:paragraph --><p>ugb/ uagb/ kubio/</p><!-- /wp:paragraph -->','<!-- wp:paragraph {"customCSS":"color:red"} /-->','<!-- wp:paragraph {"kubio":{}} /-->']as$c)check(!B::inspect($c),'Negative '.substr($c,0,60));
 check(isset(B::inspect('<!-- wp:paragraph {"otterConditions":[{"type":"loggedInUser"}]} /-->')['otter']),'Otter extension');
 check(isset(B::inspect('<!-- wp:paragraph {"kubio":{"id":"abc","styleRef":"def"}} /-->')['kubio']),'Kubio extension');
 $id=wp_insert_post(['post_type'=>'blp_extended','post_status'=>'publish','post_title'=>'SiteOrigin','post_content'=>'']);$ids[]=$id;
 update_post_meta($id,'panels_data',['widgets'=>[],'grids'=>[]]);check(!isset(L::for_post($id)['siteorigin']),'Empty SiteOrigin negative');
 update_post_meta($id,'panels_data',['widgets'=>[],'grids'=>[['cells'=>1]],'grid_cells'=>[['grid'=>0]]]);check(isset((new R)->matches($id,'blp_extended')['siteorigin']),'Structured SiteOrigin matches');
 check(isset(B::inspect('<!-- wp:siteorigin-panels/layout-block {"panelsData":{"grids":[{"cells":1}]}} /-->')['siteorigin']),'SiteOrigin embedded layout');
 $q=new Q(new R);$q->register();
 foreach($cases as$f=>$names)check($q->count('blp_extended',$f)===count($names),'Exact filtered count '.$f);
 check($q->count('blp_extended','siteorigin')===1,'SiteOrigin filter count');
 check(count((new R)->export_labels($ids,'blp_extended'))===count($ids),'CSV labels all fixtures');
 $before=get_num_queries();B::index('blp_extended');B::index('blp_extended');check(get_num_queries()===$before,'Repeated index zero SQL');
 echo "SUCCESS expanded integrations\n";
}finally{foreach($ids as$id)wp_delete_post($id,true);}
