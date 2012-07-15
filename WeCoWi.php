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
$dir = dirname(__FILE__);
$wgExtensionMessagesFiles['WeCoWi'] = $dir . '/WeCoWi.i18n.magic.php';

$wgAutoloadClasses['WeCoWiHooks'] = "$dir/WeCoWi.hooks.php";

$wgHooks['ParserGetVariableValueSwitch'][] = 'WeCoWiHooks::myVars'; 
$wgHooks['SkinBuildSidebar'][] = 'WeCoWiHooks::SocialSidebar';
$wgHooks['BeforePageDisplay'][] = 'WeCoWiHooks::SocialSidebarScripts';
$wgHooks['MagicWordwgVariableIDs'][] = 'WeCoWiHooks::wfMyDeclareVarIds';