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
        $out .= '</span></li><li id="flattr"><span class="social">';
        $out .= '<a class="FlattrButton" style="display:none;" rev="flattr;button:compact;" href="http://www.wecowi.de"></a><noscript><a href="http://flattr.com/thing/734946/Web-Community-Wiki" target="_blank"><img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a></noscript>';
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
		$out->addScript("<script type='text/javascript'>
/* <![CDATA[ */
    (function() {
        var s = document.createElement('script'), t = document.getElementsByTagName('script')[0];
        s.type = 'text/javascript';
        s.async = true;
        s.src = 'http://api.flattr.com/js/0.6/load.js?mode=auto';
        t.parentNode.insertBefore(s, t);
    })();
/* ]]> */</script>");
		$out->addScript( '<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang:de} </script>');
		return true;
	}
}