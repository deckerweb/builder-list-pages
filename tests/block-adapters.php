<?php
/** Exercise block families in a disposable installation with the four real free plugins active. */
define('WP_ADMIN',true);define('WP_DISABLE_FATAL_ERROR_HANDLER',true);require $argv[1];wp_set_current_user(1);
use Deckerweb\BuilderListPages\BlockAdapters;
use Deckerweb\BuilderListPages\Registry;
use Deckerweb\BuilderListPages\Query;
use Deckerweb\BuilderListPages\Workflows;
$count=0;
/** Assert a native integration result. @param bool $ok Expected condition. @param string $label Result description. @return void Throws when the condition fails. */
function blp_blocks_verify(bool $ok,string $label):void{global$count;if(!$ok)throw new Exception($label);$count++;echo 'PASS '.$label.PHP_EOL;}
register_post_type('blp_block_test',['public'=>true,'show_ui'=>true,'show_in_rest'=>true,'supports'=>['title','editor','custom-fields']]);update_option('bricks_global_settings',['postTypes'=>['blp_block_test']]);
$created=[];
/** Insert disposable content. @param string $name Neutral fixture title. @param string $content Stored block markup. @param string $type Native content type. @param string $status Publication status. @param int $author Owning test user. @return int Created post ID recorded for cleanup. */
function blp_blocks_fixture(string $name,string $content,string $type='blp_block_test',string$status='publish',int$author=1):int{global$created;$id=wp_insert_post(['post_type'=>$type,'post_status'=>$status,'post_author'=>$author,'post_title'=>'Block adapter '.$name,'post_content'=>wp_slash($content)],true);if(is_wp_error($id))throw new Exception($id->get_error_message());$created[]=$id;return$id;}
try{
 foreach(['kadence-blocks','spectra-legacy','spectra-blocks','greenshift']as$id)blp_blocks_verify(BlockAdapters::families()[$id]['active'],'Real free plugin active: '.$id);
 $a=blp_blocks_fixture('Kadence','<!-- wp:kadence/rowlayout --><div><!-- wp:kadence/advancedheading --><h2>Test</h2><!-- /wp:kadence/advancedheading --></div><!-- /wp:kadence/rowlayout -->');
 $b=blp_blocks_fixture('Legacy','<!-- wp:uagb/container --><div></div><!-- /wp:uagb/container -->');
 $c=blp_blocks_fixture('Spectra','<!-- wp:spectra/container --><div></div><!-- /wp:spectra/container -->');
 $d=blp_blocks_fixture('GreenLight','<!-- wp:greenshift-blocks/element {"tag":"div"} --><div></div><!-- /wp:greenshift-blocks/element -->');
 $e=blp_blocks_fixture('Mixed','<!-- wp:kadence/advancedheading --><h2>Hi</h2><!-- /wp:kadence/advancedheading --><!-- wp:spectra/text --><p>Test</p><!-- /wp:spectra/text -->');update_post_meta($e,'_bricks_editor_mode','bricks');
 $plain=blp_blocks_fixture('Plain','<!-- wp:paragraph --><p>Kadence uagb/ spectra/ greenshift-blocks/</p><!-- /wp:paragraph -->');update_post_meta($plain,'_kad_pagebuilder_layout_flag','disabled');
 $escaped=blp_blocks_fixture('Escaped','<!-- wp:code --><pre>&lt;!-- wp:kadence/rowlayout /--&gt;</pre><!-- /wp:code -->');
 $ai=blp_blocks_fixture('Shared AI','<!-- wp:gutenberg-templates/spectra-ai /-->');
 $extension=blp_blocks_fixture('Extension','<!-- wp:paragraph {"spectraId":"a123","responsiveControls":{"lg":{"paddingTop":"20px"}}} --><p>Test</p><!-- /wp:paragraph -->');
 $idonly=blp_blocks_fixture('Unused Spectra ID','<!-- wp:paragraph {"spectraId":"a123"} --><p>Test</p><!-- /wp:paragraph -->');
 $mask=blp_blocks_fixture('Mask','<!-- wp:image {"spectraMask":{"shape":"circle"}} --><figure></figure><!-- /wp:image -->');
 $pattern=blp_blocks_fixture('Pattern','<!-- wp:uagb/container /-->','wp_block');$ref=blp_blocks_fixture('Synced','<!-- wp:block {"ref":'.$pattern.'} /-->');
 $cycle=blp_blocks_fixture('Cycle','', 'wp_block');wp_update_post(['ID'=>$cycle,'post_content'=>'<!-- wp:block {"ref":'.$cycle.'} /-->']);$cyclepage=blp_blocks_fixture('Cycle page','<!-- wp:block {"ref":'.$cycle.'} /-->');
 $draftpat=blp_blocks_fixture('Draft pattern','<!-- wp:kadence/rowlayout /-->','wp_block','draft');$draftref=blp_blocks_fixture('Draft pattern ref','<!-- wp:block {"ref":'.$draftpat.'} /-->');
 $r=new Registry();$q=new Query($r);$q->register();
 foreach([$a=>'kadence-blocks',$b=>'spectra-legacy',$c=>'spectra-blocks',$d=>'greenshift',$extension=>'spectra-blocks',$mask=>'spectra-blocks',$ref=>'spectra-legacy']as$id=>$family)blp_blocks_verify(isset($r->matches($id,'blp_block_test')[$family]),'Badge matches parsed content: '.$family.' #'.$id);
 blp_blocks_verify(count($r->matches($e,'blp_block_test'))===3,'Hybrid page retains Bricks plus both block families');
 foreach([$plain,$escaped,$ai,$idonly,$cyclepage,$draftref]as$id)blp_blocks_verify(!$r->matches($id,'blp_block_test'),'No false match for negative fixture #'.$id);
 $expected=['kadence-blocks'=>2,'spectra-legacy'=>2,'spectra-blocks'=>4,'greenshift'=>1,'multiple'=>1,'none'=>6];
 foreach($expected as$family=>$number){blp_blocks_verify($q->count('blp_block_test',$family)===$number,'Exact count '.$family.'='.$number);$native=new WP_Query();$q->apply($native,'blp_block_test',$family);$native->query(['post_type'=>'blp_block_test','post_status'=>'publish','posts_per_page'=>-1,'fields'=>'ids','meta_query'=>$native->get('meta_query')]);blp_blocks_verify(count($native->posts)===$number,'Native list agrees with count: '.$family);}
 $filtered=new WP_Query();$q->apply($filtered,'blp_block_test','spectra-blocks');$filtered->query(['post_type'=>'blp_block_test','post_status'=>'publish','s'=>'Extension','fields'=>'ids']);blp_blocks_verify(array_map('intval',$filtered->posts)===[$extension],'Search intersects block view');
 $labels=$r->export_labels([$a,$b,$c,$d,$e,$plain,$ref],'blp_block_test');blp_blocks_verify(count($labels[$e])===3&&in_array('Spectra Legacy',$labels[$ref],true)&&empty($labels[$plain]),'CSV agrees for hybrid, synced pattern and plain content');
 $before=get_num_queries();BlockAdapters::index('blp_block_test');BlockAdapters::index('blp_block_test');blp_blocks_verify(get_num_queries()===$before,'Repeated family scan adds no SQL');
 wp_update_post(['ID'=>$a,'post_content'=>'<!-- wp:paragraph --><p>Removed</p><!-- /wp:paragraph -->']);blp_blocks_verify(!isset(BlockAdapters::index('blp_block_test')[$a]),'Editing content invalidates request-local scan');blp_blocks_verify($q->count('blp_block_test','kadence-blocks')===1,'Saved content also invalidates existing count cache');
 wp_update_post(['ID'=>$pattern,'post_content'=>'<!-- wp:kadence/rowlayout /-->']);blp_blocks_verify(isset(BlockAdapters::index('blp_block_test')[$ref]['kadence-blocks']),'Editing shared pattern invalidates dependent recognition');blp_blocks_verify($q->count('blp_block_test','kadence-blocks')===2,'Saved pattern also invalidates existing count cache');
 $author=wp_insert_user(['user_login'=>'block_author_'.wp_generate_password(6,false),'user_pass'=>wp_generate_password(20),'role'=>'author']);if(is_wp_error($author))throw new Exception('Author fixture failed');
 $own=blp_blocks_fixture('Author owned','<!-- wp:spectra/container /-->','blp_block_test','publish',$author);
 wp_set_current_user($author);$authorRegistry=new Registry();$authorQuery=new Query($authorRegistry);$authorQuery->register();
 blp_blocks_verify($authorQuery->count('blp_block_test','spectra-blocks')===1,'Author count excludes other authors despite shared candidate scan');
 $authorRows=(new Workflows($authorRegistry,$authorQuery))->rows(['post_type'=>'blp_block_test','builder'=>'spectra-blocks','post_status'=>'publish','s'=>'']);blp_blocks_verify(count($authorRows)===1&&$authorRows[0][0]==='Block adapter Author owned','Author CSV and block filter retain own-content permissions');
 wp_set_current_user(1);require_once ABSPATH.'wp-admin/includes/user.php';wp_delete_user($author,1);
 blp_blocks_verify(!BlockAdapters::index('attachment'),'Unsupported content types never scanned');
 echo 'SUCCESS '.$count.' block-adapter checks; WP'.$GLOBALS['wp_version'].' PHP'.PHP_VERSION.PHP_EOL;
}finally{foreach(array_reverse($created)as$id)wp_delete_post($id,true);}
