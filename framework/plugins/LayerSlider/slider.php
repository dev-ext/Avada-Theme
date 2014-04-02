<?php

if(is_array($slides)) {

    if(isset($slides['properties']['forceresponsive']) && $slides['properties']['forceresponsive'] != 'false') {
        $data .= '<div class="ls-wp-fullwidth-container">';
            $data .= '<div class="ls-wp-fullwidth-helper">';
    }

    // Layer props
    $sliderStyle = isset($slides['properties']['sliderstyle']) ? $slides['properties']['sliderstyle'] : '';

                $data .= '<div id="layerslider_'.$id.'" class="ls-wp-container" style="width: '.layerslider_check_unit($slides['properties']['width']).'; height: '.layerslider_check_unit($slides['properties']['height']).'; margin: 0px auto; '.$sliderStyle.'">';
                    if(is_array($slides['layers'])) {
                        foreach($slides['layers'] as $layerkey => $layer) {
//print_r($layer);
                        // Skip layer?
                        if(isset($layer['properties']['skip'])) {
                            continue;
                        }

                        // ID
                        if(!empty($layer['properties']['id'])) {
                            $layerID = 'id="'.$layer['properties']['id'].'"';
                        } else {
                            $layerID = '';
                        }

                        // Deeplink
                        if(!empty($layer['properties']['deeplink'])) {
                            $deeplink = 'deeplink: '.$layer['properties']['deeplink'].'; ';
                        } else {
                            $deeplink = '';
                        }

                        // Time shift
                        if(isset($layer['properties']['new_transitions'])) {
                            $ts = !empty($layer['properties']['timeshift']) ? $layer['properties']['timeshift'] : '0';
                            $timeshift = 'timeshift: '.$ts.'; ';
                        } else {
                            $timeshift = '';
                        }

                        // Default values for transitions
                        $transition2d = '';
                        $transition3d = '';
                        $customtransition2d = '';
                        $customtransition3d = '';

                        // Transitions
                        if(isset($layer['properties']['new_transitions'])) {

                            // Built-in transitions
                            if(
                                empty($layer['properties']['2d_transitions']) &&
                                empty($layer['properties']['3d_transitions']) &&
                                empty($layer['properties']['custom_2d_transitions']) &&
                                empty($layer['properties']['custom_3d_transitions'])
                            ) {
                                $transition2d = 'transition2d: all; ';
                            } else {

                                if(!empty($layer['properties']['2d_transitions'])) $transition2d = 'transition2d: '.$layer['properties']['2d_transitions'].'; ';
                                if(!empty($layer['properties']['3d_transitions'])) $transition3d = 'transition3d: '.$layer['properties']['3d_transitions'].'; ';
                                if(!empty($layer['properties']['custom_2d_transitions'])) $customtransition2d = 'customtransition2d: '.$layer['properties']['custom_2d_transitions'].'; ';
                                if(!empty($layer['properties']['custom_3d_transitions'])) $customtransition3d = 'customtransition3d: '.$layer['properties']['custom_3d_transitions'].'; ';
                            }
                         }

                        $data .= '<div class="ls-layer" '.$layerID.' style="'.$deeplink.'slidedirection: '.$layer['properties']['slidedirection'].'; slidedelay: '.$layer['properties']['slidedelay'].'; durationin: '.$layer['properties']['durationin'].'; durationout: '.$layer['properties']['durationout'].'; easingin: '.$layer['properties']['easingin'].'; easingout: '.$layer['properties']['easingout'].'; delayin: '.$layer['properties']['delayin'].'; delayout: '.$layer['properties']['delayout'].'; '.$timeshift.''.$transition2d.''.$transition3d.''.$customtransition2d.''.$customtransition3d.'">';

                            // Layer background
                            if(!empty($layer['properties']['background'])) {
                                $data .= '<img src="'.$layer['properties']['background'].'" class="ls-bg" alt="Slide background">';
                            }

                            // Layer thumbnail
                            if(!empty($slides['properties']['thumb_nav']) && $slides['properties']['thumb_nav'] != 'disabled') {
                                if(!empty($layer['properties']['thumbnail'])) {
                                    $data .= '<img src="'.$layer['properties']['thumbnail'].'" class="ls-tn" alt="Slide thumbnail">';
                                }
                            }

                            if(isset($layer['sublayers']) && is_array($layer['sublayers'])) {
                                foreach($layer['sublayers'] as $sublayer) {

                                    // Skip sublayer?
                                    if(isset($sublayer['skip'])) {
                                        continue;
                                    }

                                    // SlideDirection
                                    if(!empty($sublayer['slidedirection']) && $sublayer['slidedirection'] != 'auto') {
                                        $slidedirection = 'slidedirection : '.$sublayer['slidedirection'].';';
                                    } else {
                                        $slidedirection = '';
                                    }

                                    // SlideOutDirection
                                    if(!empty($sublayer['slideoutdirection']) && $sublayer['slideoutdirection'] != 'auto') {
                                        $slideoutdirection = 'slideoutdirection : '.$sublayer['slideoutdirection'].';';
                                    } else {
                                        $slideoutdirection = '';
                                    }

                                    // ID
                                    if(!empty($sublayer['id'])) {
                                        $sublayerID = 'id="'.$sublayer['id'].'"';
                                    } else {
                                        $sublayerID = '';
                                    }

                                    // LinkTo
                                    $linkTo = '';
                                    if(!empty($sublayer['url'])) {

                                        if(preg_match('/^\#[0-9]/', $sublayer['url']) > 0 ) {
                                            $linkTo = ' ls-linkto-'.substr($sublayer['url'], 1).'';
                                        } else {
                                            $linkTo = '';
                                        }
                                    }

                                    // Title
                                    if(!empty($sublayer['title'])) {
                                        $sublayerTitle = 'title="'.$sublayer['title'].'"';

                                    } else {
                                        $sublayerTitle = '';
                                    }

                                    // Alt
                                    if(!empty($sublayer['alt'])) {
                                        $sublayerAlt = 'alt="'.$sublayer['alt'].'"';
                                    } else {
                                        $sublayerAlt = '';
                                    }

                                    // Rel
                                    if(!empty($sublayer['rel'])) {
                                        $sublayerRel = 'rel="'.$sublayer['rel'].'"';
                                    } else {
                                        $sublayerRel = '';
                                    }

                                    // WordWrap
                                    if(!isset($sublayer['wordwrap'])) {
                                        $sublayerWordWrap = ' white-space: nowrap;';
                                    } else {
                                        $sublayerWordWrap = '';
                                    }

                                    // Custom style
                                    if(!empty($sublayer['style'])) {
                                        $sublayerStyle = preg_replace('/\s\s+/', ' ', stripslashes($sublayer['style']));
                                    } else {
                                        $sublayerStyle = '';
                                    }

                                    // Custom classes
                                    if(!empty($sublayer['class'])) {
                                        $sublayerClass = ' '.$sublayer['class'].'';
                                    } else {
                                        $sublayerClass = '';
                                    }

                                    // Show until
                                    if(empty($sublayer['showuntil'])) {
                                        $sublayer['showuntil'] = '0';
                                    }

                                    // Build style settings if any
                                    if(!empty($sublayer['styles'])) {

                                        // String to hold custom style settings
                                        $customStyles = '';

                                        // Get custom style settings
                                        $styles = json_decode(stripslashes($sublayer['styles']), true);

                                        // Build custom style string
                                        foreach($styles as $key => $val) {
                                            if(is_numeric($val)) {
                                                $customStyles .= ''.$key.': '.layerslider_check_unit($val).'; ';
                                            } else {
                                                $customStyles .= ''.$key.': '.$val.'; ';
                                            }
                                        }
                                    } else {
                                        $customStyles = '';
                                    }

                                    // Rotate
                                    $sublayer['rotatein'] = empty($sublayer['rotatein']) ? '' : 'rotatein : '.$sublayer['rotatein'].'; ';
                                    $sublayer['rotateout'] = empty($sublayer['rotateout']) ? '' : 'rotateout : '.$sublayer['rotateout'].'; ';

                                    // Scale
                                    $sublayer['scalein'] = (!isset($sublayer['scalein']) || $sublayer['scalein'] == '1.0') ? '' : 'scalein : '.$sublayer['scalein'].'; ';
                                    $sublayer['scaleout'] = (!isset($sublayer['scaleout']) || $sublayer['scaleout'] == '1.0') ? '' : 'scaleout : '.$sublayer['scaleout'].'; ';

                                    if(!empty($sublayer['url']) && preg_match('/^\#[0-9]/', $sublayer['url']) == 0) {
                                        $data .= '<a href="'.$sublayer['url'].'" target="'.$sublayer['target'].'" '.$sublayerID.' '.$sublayerRel.' class="ls-s'.$sublayer['level'].'" '.$sublayerTitle.' style="position: absolute; top: '.layerslider_check_unit($sublayer['top']).'; left:'.layerslider_check_unit($sublayer['left']).'; display: block; '.$slidedirection.' '.$slideoutdirection.' durationin : '.$sublayer['durationin'].'; durationout : '.$sublayer['durationout'].'; easingin : '.$sublayer['easingin'].'; easingout : '.$sublayer['easingout'].'; delayin : '.$sublayer['delayin'].'; delayout : '.$sublayer['delayout'].'; '.$sublayer['rotatein'].''.$sublayer['rotateout'].''.$sublayer['scalein'].''.$sublayer['scaleout'].'showuntil : '.$sublayer['showuntil'].'">';

                                            if(empty($sublayer['type']) || $sublayer['type'] == 'img') {
                                                if(!empty($sublayer['image'])) {
                                                    $data .= '<img src="'.$sublayer['image'].'" '.$sublayerAlt.' style="'.$sublayerStyle.''.$customStyles.'">';
                                                }
                                            } else {
                                                $data .= '<'.$sublayer['type'].' class="'.$sublayerClass.'" style="'.$sublayerStyle.' '.$customStyles.''.$sublayerWordWrap.'"> '.do_shortcode(__(stripslashes($sublayer['html']))).' </'.$sublayer['type'].'>';
                                            }
                                        $data .= '</a>';
                                    } else {
                                        if(empty($sublayer['type']) || $sublayer['type'] == 'img') {
                                            if(!empty($sublayer['image'])) {
                                                $data .= '<img class="ls-s'.$sublayer['level'].''.$linkTo.''.$sublayerClass.'" '.$sublayerID.' src="'.$sublayer['image'].'" '.$sublayerAlt.' style="position: absolute; top: '.layerslider_check_unit($sublayer['top']).'; left: '.layerslider_check_unit($sublayer['left']).'; '.$slidedirection.' '.$slideoutdirection.'  durationin : '.$sublayer['durationin'].'; durationout : '.$sublayer['durationout'].'; easingin : '.$sublayer['easingin'].'; easingout : '.$sublayer['easingout'].'; delayin : '.$sublayer['delayin'].'; delayout : '.$sublayer['delayout'].'; '.$sublayer['rotatein'].''.$sublayer['rotateout'].''.$sublayer['scalein'].''.$sublayer['scaleout'].'showuntil : '.$sublayer['showuntil'].'; '.$sublayerStyle.''.$customStyles.'">';
                                            }
                                        } else {
                                            $data .= '<'.$sublayer['type'].' '.$sublayerID.' class="ls-s'.$sublayer['level'].''.$linkTo.''.$sublayerClass.'" style="position: absolute; top:'.layerslider_check_unit($sublayer['top']).'; left: '.layerslider_check_unit($sublayer['left']).'; '.$slidedirection.' '.$slideoutdirection.' durationin : '.$sublayer['durationin'].'; durationout : '.$sublayer['durationout'].'; easingin : '.$sublayer['easingin'].'; easingout : '.$sublayer['easingout'].'; delayin : '.$sublayer['delayin'].'; delayout : '.$sublayer['delayout'].'; '.$sublayer['rotatein'].''.$sublayer['rotateout'].''.$sublayer['scalein'].''.$sublayer['scaleout'].'showuntil : '.$sublayer['showuntil'].'; '.$sublayerStyle.' '.$customStyles.''.$sublayerWordWrap.'"> '.do_shortcode(__(stripslashes($sublayer['html']))).' </'.$sublayer['type'].'>';
                                        }
                                    }
                                }
                            }


                            // Link this slide
                            if(!empty($layer['properties']['layer_link'])) {
                                $data .= '<a href="'.$layer['properties']['layer_link'].'" target="'.$layer['properties']['layer_link_target'].'" class="ls-link"></a>';
                            }
                        $data .= '</div>';
                        }
                    }
                $data .= '</div>';
    if(isset($slides['properties']['forceresponsive']) && $slides['properties']['forceresponsive'] != 'false') {
            $data .= '</div>';
        $data .= '</div>';
    }
}
?>