<?php

// Get plugin path for skins
global $lsPluginPath;

// Basic
$width = layerslider_check_unit($slides['properties']['width']);
$height = layerslider_check_unit($slides['properties']['height']);
$responsive = isset($slides['properties']['responsive']) ? 'true' : 'false';
$responsiveunder = !empty($slides['properties']['responsiveunder']) ? $slides['properties']['responsiveunder'] : '0';
$sublayercontainer = !empty($slides['properties']['sublayercontainer']) ? $slides['properties']['sublayercontainer'] : '0';

// Slideshow
$autostart = (isset($slides['properties']['autostart']) && $slides['properties']['autostart'] != 'false') ? 'true' : 'false';
$pauseonhover = (isset($slides['properties']['pauseonhover']) && $slides['properties']['pauseonhover'] != 'false') ? 'true' : 'false';
$firstlayer = is_numeric($slides['properties']['firstlayer']) ? $slides['properties']['firstlayer'] : '\'random\'';
$animatefirstlayer = (isset($slides['properties']['animatefirstlayer']) && $slides['properties']['animatefirstlayer'] != 'false') ? 'true' : 'false';
$randomslideshow = (isset($slides['properties']['randomslideshow']) && $slides['properties']['randomslideshow'] != 'false') ? 'true' : 'false';
$twowayslideshow = (isset($slides['properties']['twowayslideshow']) && $slides['properties']['twowayslideshow'] != 'false') ? 'true' : 'false';
$loops = !empty($slides['properties']['loops']) ? $slides['properties']['loops'] : 0;
$forceloopnum = ( isset($slides['properties']['forceloopnum']) && $slides['properties']['forceloopnum'] != 'false') ? 'true' : 'false';
$autoplayvideos = ( isset($slides['properties']['autoplayvideos']) && $slides['properties']['autoplayvideos'] != 'false') ? 'true' : 'false';
$autoPauseSlideshow = !empty($slides['properties']['autopauseslideshow']) ? $slides['properties']['autopauseslideshow'] : 'auto';

if($autoPauseSlideshow == 'auto') {
    $autoPauseSlideshow = '\'auto\'';

} else if($autoPauseSlideshow == 'enabled') {
    $autoPauseSlideshow = 'true';

} else if($autoPauseSlideshow == 'disabled') {
    $autoPauseSlideshow = 'false';
}

$youtubepreview = !empty($slides['properties']['youtubepreview']) ? $slides['properties']['youtubepreview'] : 'maxresdefault.jpg';
$keybnav = (isset($slides['properties']['keybnav']) && $slides['properties']['keybnav'] != 'false') ? 'true' : 'false';
$touchnav = (isset($slides['properties']['touchnav']) && $slides['properties']['touchnav'] != 'false') ? 'true' : 'false';

// Appearance
$skin = $slides['properties']['skin'];
$skinpath = $GLOBALS['lsPluginPath'].'skins/';
$backgroundcolor = $slides['properties']['backgroundcolor'];
$backgroundimage = $slides['properties']['backgroundimage'];

// Navigation
$navprevnext = (isset($slides['properties']['navprevnext']) && $slides['properties']['navprevnext'] != 'false') ? 'true' : 'false';
$navstartstop = (isset($slides['properties']['navstartstop']) && $slides['properties']['navstartstop'] != 'false') ? 'true' : 'false';
$navbuttons = (isset($slides['properties']['navbuttons']) && $slides['properties']['navbuttons'] != 'false') ? 'true' : 'false';
$hoverprevnext = (isset($slides['properties']['hoverprevnext']) && $slides['properties']['hoverprevnext'] != 'false') ? 'true' : 'false';
$hoverbottomnav = (isset($slides['properties']['hoverbottomnav']) && $slides['properties']['hoverbottomnav'] != 'false') ? 'true' : 'false';
$bartimer = (isset($slides['properties']['bartimer']) && $slides['properties']['bartimer'] != 'false') ? 'true' : 'false';
$circletimer = (isset($slides['properties']['circletimer']) && $slides['properties']['circletimer'] != 'false') ? 'true' : 'false';
$thumb_nav = !empty($slides['properties']['thumb_nav']) ? $slides['properties']['thumb_nav'] : 'hover';
$thumb_width = !empty($slides['properties']['thumb_width']) ? $slides['properties']['thumb_width'] : '100';
$thumb_height = !empty($slides['properties']['thumb_height']) ? $slides['properties']['thumb_height'] : '60';
$thumb_container_width = !empty($slides['properties']['thumb_container_width']) ? $slides['properties']['thumb_container_width'] : '60%';
$thumb_active_opacity = !empty($slides['properties']['thumb_active_opacity']) ? $slides['properties']['thumb_active_opacity'] : '35';
$thumb_inactive_opacity = !empty($slides['properties']['thumb_inactive_opacity']) ? $slides['properties']['thumb_inactive_opacity'] : '100';

// Misc
$imgpreload = (isset($slides['properties']['imgpreload']) && $slides['properties']['imgpreload'] != 'false') ? 'true' : 'false';

// YourLogo
$yourlogo = !empty($slides['properties']['yourlogo']) ? '\''.$slides['properties']['yourlogo'].'\'' : 'false';
$yourlogostyle = !empty($slides['properties']['yourlogostyle']) ? $slides['properties']['yourlogostyle'] : 'position: absolute; left: 10px; top: 10px; z-index: 99;';
$yourlogolink = !empty($slides['properties']['yourlogolink']) ? '\''.$slides['properties']['yourlogolink'].'\'' : 'false';
$yourlogotarget = !empty($slides['properties']['yourlogotarget']) ? $slides['properties']['yourlogotarget'] : '_self';

// Callbacks
$cbinit = !empty($slides['properties']['cbinit']) ? stripslashes($slides['properties']['cbinit']) : 'function() {}';
$cbstart = !empty($slides['properties']['cbstart']) ? stripslashes($slides['properties']['cbstart']) : 'function() {}';
$cbstop = !empty($slides['properties']['cbstop']) ? stripslashes($slides['properties']['cbstop']) : 'function() {}';
$cbpause = !empty($slides['properties']['cbpause']) ? stripslashes($slides['properties']['cbpause']) : 'function() {}';
$cbanimstart = !empty($slides['properties']['cbanimstart']) ? stripslashes($slides['properties']['cbanimstart']) : 'function() {}';
$cbanimstop = !empty($slides['properties']['cbanimstop']) ? stripslashes($slides['properties']['cbanimstop']) : 'function() {}';
$cbprev = !empty($slides['properties']['cbprev']) ? stripslashes($slides['properties']['cbprev']) : 'function() {}';
$cbnext = !empty($slides['properties']['cbnext']) ? stripslashes($slides['properties']['cbnext']) : 'function() {}';

// Demo page
//$skin = !empty($_GET['skin']) ? $_GET['skin'] : $skin;
//$thumb_nav = !empty($_GET['nav']) ? $_GET['nav'] : $thumb_nav;

if(is_array($slides)) {

    $data .= '<script type="text/javascript">';
    $data .= 'var lsjQuery = jQuery;';
    $data .= '</script>';

        $data .= '<script type="text/javascript" src="'.$GLOBALS['lsPluginPath'].'js/layerslider.kreaturamedia.jquery.js?ver='.$GLOBALS['lsPluginVersion'].'"></script>' . NL;
        $data .= '<script type="text/javascript" src="'.$GLOBALS['lsPluginPath'].'js/jquery-easing-1.3.js?ver=1.3.0"></script>' . NL;
        $data .= '<script type="text/javascript" src="'.$GLOBALS['lsPluginPath'].'js/jquerytransit.js?ver=0.9.9"></script>' . NL;
        
    $data .= '<script type="text/javascript">' . NL;

            $data .= 'lsjQuery(document).ready(function() {
                if(typeof lsjQuery.fn.layerSlider == "undefined") { lsShowNotice(\'layerslider_'.$id.'\',\'jquery\'); }
                    else if(typeof lsjQuery.transit == "undefined" || typeof lsjQuery.transit.modifiedForLayerSlider == "undefined") { lsShowNotice(\'layerslider_'.$id.'\', \'transit\'); }
                        else {
                            lsjQuery("#layerslider_'.$id.'").layerSlider({
                                width : \''.$width .'\',
                                height : \''.$height.'\',
                                responsive : '.$responsive.',
                                responsiveUnder : '.$responsiveunder.',
                                sublayerContainer : '.$sublayercontainer.',
                                autoStart : '.$autostart.',
                                pauseOnHover : '.$pauseonhover.',
                                firstLayer : '.$firstlayer.',
                                animateFirstLayer : '.$animatefirstlayer.',
                                randomSlideshow : '.$randomslideshow.',
                                twoWaySlideshow : '.$twowayslideshow.',
                                loops : '.$loops.',
                                forceLoopNum : '.$forceloopnum.',
                                autoPlayVideos : '.$autoplayvideos.',
                                autoPauseSlideshow : '.$autoPauseSlideshow.',
                                youtubePreview : \''.$youtubepreview.'\',
                                keybNav : '.$keybnav.',
                                touchNav : '.$touchnav.',
                                skin : \''.$skin.'\',
                                skinsPath : \''.$skinpath.'\',' . NL;
                                if(!empty($backgroundcolor)) :
                                    $data .= 'globalBGColor : \''.$backgroundcolor.'\',' . NL;
                                endif;
                                if(!empty($backgroundimage)) :
                                    $data .= 'globalBGImage : \''.$backgroundimage.'\',' . NL;
                                endif;
                                $data .= 'navPrevNext : '.$navprevnext.',
                                navStartStop : '.$navstartstop .',
                                navButtons : '.$navbuttons.',
                                hoverPrevNext : '.$hoverprevnext.',
                                hoverBottomNav : '.$hoverbottomnav.',
                                showBarTimer : '.$bartimer.',
                                showCircleTimer : '.$circletimer.',
                                thumbnailNavigation : \''.$thumb_nav.'\',
                                tnWidth : '.$thumb_width.',
                                tnHeight : '.$thumb_height.',
                                tnContainerWidth : \''.$thumb_container_width.'\',
                                tnActiveOpacity : '.$thumb_active_opacity.',
                                tnInactiveOpacity : '.$thumb_inactive_opacity.',
                                imgPreload : '.$imgpreload.',
                        		yourLogo : '.$yourlogo.',
                                yourLogoStyle : \''.$yourlogostyle.'\',
                                yourLogoLink : '.$yourlogolink.',
                                yourLogoTarget : \''.$yourlogotarget.'\',
                                cbInit : '.$cbinit.',
                                cbStart : '.$cbstart.',
                                cbStop : '.$cbstop.',
                                cbPause : '.$cbpause.',
                                cbAnimStart : '.$cbanimstart.',
                                cbAnimStop : '.$cbanimstop.',
                                cbPrev : '.$cbprev.',
                                cbNext : '.$cbnext.'
                            });
                        }
            });
        </script>';
}