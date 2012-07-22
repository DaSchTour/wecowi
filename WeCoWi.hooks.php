<?php 
class WeCoWiHooks {
	
	public function myVars (&$parser, &$cache, &$magicWordId, &$ret) {
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
	
	public function wfMyDeclareVarIds( &$customVariableIds ) {
        // $customVariableIds is where MediaWiki wants to store its list of custom
        // variable IDs. We oblige by adding ours:
        $customVariableIds[] = 'MAG_CURRENTUSER';
		$customVariableIds[] = 'MAG_LOGO';
		$customVariableIds[] = 'MAG_CURRENTUSERREALNAME';
        // must do this or you will silence every MagicWordwgVariableIds hook
        // registered after this!
        return true;
	}
	
	public function SocialSidebar ($skin, &$bar ) {
		$out = '';
        $out .= '<ul><li id="twitter"><span class="social">';
		$out .= '<a href="https://twitter.com/share" class="twitter-share-button" data-via="WeCoWi" data-lang="de">Twittern</a>';	
        $out .= '</span></li><li id="google"><span class="social"><g:plusone size="small"></g:plusone></span></li>';
        $out .= '<li id="facebook"><span class="social"><fb:like send="false" layout="button_count" width="60" show_faces="true"></fb:like></span></li></ul>';
        $bar['Social Networks'] = $out;
		return true;
	}
	public function SocialSidebarScripts ( OutputPage &$out, Skin &$skin ) {
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
	public function PiwikSetup( $skin, &$text = '' ) {
		$text .= WeCoWiHooks::AddPiwik( $skin->getTitle() );
		return true;
	}
	function AddPiwik ($title) {
	global $wgPiwikIDSite, $wgPiwikURL, $wgPiwikIgnoreSysops, $wgPiwikIgnoreBots, $wgUser, $wgScriptPath, $wgPiwikCustomJS, $wgPiwikActionName, $wgPiwikUsePageTitle;
	if ( !$wgUser->isAllowed( 'bot' ) || !$wgPiwikIgnoreBots ) {
		if ( !$wgUser->isAllowed( 'protect' ) || !$wgPiwikIgnoreSysops ) {
			if ( !empty( $wgPiwikIDSite ) AND !empty( $wgPiwikURL ) ) {
				if ( $wgPiwikUsePageTitle ) {
					$wgPiwikPageTitle = $title->getPrefixedText();

					$wgPiwikFinalActionName = $wgPiwikActionName;
					$wgPiwikFinalActionName .= $wgPiwikPageTitle;
				} else {
					$wgPiwikFinalActionName = $wgPiwikActionName;
				}
				// Stop xss since page title's can have " and stuff in them.
				$wgPiwikFinalActionName = Xml::encodeJsVar( $wgPiwikFinalActionName );
				$funcOutput = <<<PIWIK
<!-- Piwik -->
<script type="text/javascript">
/* <![CDATA[ */
var pkBaseURL = (("https:" == document.location.protocol) ? "https://{$wgPiwikURL}" : "http://{$wgPiwikURL}");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
/* ]]> */
</script>
<script type="text/javascript">
/* <![CDATA[ */
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", {$wgPiwikIDSite});
piwikTracker.setDocumentTitle({$wgPiwikFinalActionName});
piwikTracker.setIgnoreClasses("image");
{$wgPiwikCustomJS}
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
/* ]]> */
</script><noscript><p><img src="http://{$wgPiwikURL}piwik.php?idsite={$wgPiwikIDSite}" style="border:0" alt=""/></p></noscript>
<!-- /Piwik -->
PIWIK;
			} else {
				$funcOutput = "\n<!-- You need to set the settings for Piwik -->";
			}
		} else {
			$funcOutput = "\n<!-- Piwik tracking is disabled for users with 'protect' rights (i.e., sysops) -->";
		}
	} else {
		$funcOutput = "\n<!-- Piwik tracking is disabled for bots -->";
	}

	return $funcOutput;	
	}
}
