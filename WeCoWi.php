<?php

if( !defined( 'MEDIAWIKI' ) ) die( "Not an entry point." );

$wgExtensionCredits['other'][] = array (
'path'=> __FILE__ ,
'name'=>'Web Community Wiki Mod',
'url'=>'https://github.com/DaSchTour/wecowi',
'description'=>'Spezielle Modifikationen der MediaWiki-Software und ihrer Erweiterungen zur besseren Nutzbarkeit.',
'author'=>'[http://www.dasch-tour.de DaSch]',
'version'=>'0.8.5',
);
/** EXTENSION */
$dir = dirname(__FILE__);
$wgExtensionMessagesFiles['WeCoWi'] = $dir . '/WeCoWi.i18n.magic.php';

$wgAutoloadClasses['WeCoWiHooks'] = "$dir/WeCoWi.hooks.php";

$wgHooks['ParserGetVariableValueSwitch'][] = 'WeCoWiHooks::myVars'; 
$wgHooks['MagicWordwgVariableIDs'][] = 'WeCoWiHooks::wfMyDeclareVarIds';
if ($wgWeCoWiSocialSidebar) {
	$wgHooks['SkinBuildSidebar'][] = 'WeCoWiHooks::SocialSidebar';
	$wgHooks['BeforePageDisplay'][] = 'WeCoWiHooks::SocialSidebarScripts';;
}
