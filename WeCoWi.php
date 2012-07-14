<?php

if( !defined( 'MEDIAWIKI' ) ) die( "Not an entry point." );

$wgExtensionCredits['other'][] = array (
'path'=> __FILE__ ,
'name'=>'Web Community Wiki Mod',
'url'=>'https://sourceforge.net/apps/trac/wecowi/wiki/WeCoWi-Mod',
'description'=>'Spezielle Modifikationen der MediaWiki-Software und ihrer Erweiterungen zur besseren Nutzbarkeit. Integriert sind [http://www.mediawiki.org/wiki/Extension:NoTitle Extension:NoTitle] und [http://www.mediawiki.org/wiki/Extension:MagicNoCache Extension:MagicNoCache].',
'author'=>'[http://www.dasch-tour.de DaSch]',
'version'=>'0.7',
);
/** EXTENSION */

$wgExtensionMessagesFiles['WeCoWi'] = dirname(__FILE__) . '/WeCoWi.i18n.magic.php';

$wgHooks['ParserGetVariableValueSwitch'][] = 'myVars'; 
$wgHooks['SkinBuildSidebar'][] = 'SocialSidebar';
$wgHooks['BeforePageDisplay'][] = 'SocialSidebarScripts';


function myVars (&$parser, &$cache, &$magicWordId, &$ret) {
	switch ($magicWordId) {
        case 'MAG_CURRENTUSER':
			$parser->disableCache(); # Mark this content as uncacheable
			$ret = $GLOBALS['wgUser']->mName;
			break;
		case 'MAG_LOGO':
			$ret = $GLOBALS['wgLogo'];
			break;
		case 'MAG_CURRENTUSERREALNAME':
			$parser->disableCache(); # Mark this content as uncacheable
            $ret = $GLOBALS['wgUser']->mRealName;
			break;
    }
	return true;
}
$wgHooks['MagicWordwgVariableIDs'][] = 'wfMyDeclareVarIds';
function wfMyDeclareVarIds( &$customVariableIds ) {
        // $customVariableIds is where MediaWiki wants to store its list of custom
        // variable IDs. We oblige by adding ours:
        $customVariableIds[] = 'MAG_CURRENTUSER';
		$customVariableIds[] = 'MAG_LOGO';
		$customVariableIds[] = 'MAG_CURRENTUSERREALNAME';
        // must do this or you will silence every MagicWordwgVariableIds hook
        // registered after this!
        return true;
}

function SocialSidebar ($skin, &$bar ) {
		$out = '';
        $out .= '<ul><li id="twitter"><span class="social">';
		$out .= '<a href="https://twitter.com/share" class="twitter-share-button" data-via="WeCoWi" data-lang="de">Twittern</a>';	
        $out .= '</span></li><li id="google"><span class="social"><g:plusone size="small"></g:plusone></span></li>';
        $out .= '<li id="facebook"><span class="social"><fb:like send="false" layout="button_count" width="60" show_faces="true"></fb:like></span></li></ul>';
        $bar['Social Networks'] = $out;
		return true;
}
function SocialSidebarScripts ( OutputPage &$out, Skin &$skin ) {
		$out->addScript( '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>' );	
		$out->addScript('<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) {return;}
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, "script", "facebook-jssdk"));</script>');
		$out->addScript( '<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang:de} </script>');
		return true;
}