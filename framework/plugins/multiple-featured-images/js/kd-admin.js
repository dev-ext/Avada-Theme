function kdMuFeaImgSetBoxContent( content, featuredImageID, post_type ) {
    //jQuery( '.inside', '#kd_' + featuredImageID ).html( content );
    jQuery( '#' + featuredImageID + '_' + post_type + ' .inside' ).html( content );
}
function kdMuFeaImgSetMetaValue( id, featuredImageID, post_type ) {
    var field = jQuery('input[value=kd_' + featuredImageID + '_' + post_type + '_id]', '#list-table');
    if ( field.size() > 0 ) {
        jQuery('#meta\\[' + field.attr('id').match(/[0-9]+/) + '\\]\\[value\\]').text( id );
    }    
}

function kdMuFeaImgRemove ( featuredImageID, post_type, nonce ) {
    jQuery.post( ajaxurl, {
        action: 'set-MuFeaImg-' + featuredImageID + '-' + post_type,
        post_id: jQuery('#post_ID').val(),
        thumbnail_id: -1,
        _ajax_nonce: nonce,
        cookie: encodeURIComponent(document.cookie)
    }, function( str ) {
        if( str == '0' ) {
            alert( setPostThumbnailL10n.error );
        }
        else {
            kdMuFeaImgSetBoxContent( str, featuredImageID, post_type );
        }   
    });
}

function kdMuFeaImgSet( id, featuredImageID, post_type, nonce ) {
    var $link = jQuery( 'a#' + featuredImageID + '-featuredimage' );
    
    $link.text( setPostThumbnailL10n.saving );
    
    jQuery.post( ajaxurl, {
        action: 'set-MuFeaImg-' + featuredImageID + '-' + post_type,
        post_id: post_id,
        thumbnail_id: id,
        _ajax_nonce: nonce,
        cookie: encodeURIComponent(document.cookie)
    }, function( str ) {
        if( str == '0' ) {
            alert( setPostThumbnailL10n.error );
        }
        else {
            var win = window.dialogArguments || opener || parent || top;
            
            $link.show().text( setPostThumbnailL10n.done );

            $link.fadeOut( 'slow', function() {
                jQuery('tr.MuFeaImg-' + featuredImageID + '-' + post_type ).hide();
            });
            
            win.kdMuFeaImgSetBoxContent( str, featuredImageID, post_type );
            win.kdMuFeaImgSetMetaValue( id, featuredImageID, post_type );
        }
    });
}