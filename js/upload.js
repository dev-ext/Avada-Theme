jQuery(document).ready(function($){
    window.formfield = '';

    $('.upload_button').live('click', function() {
        window.formfield = $('.upload_field',$(this).parent());
        tb_show('', 'media-upload.php?type=image&TB_iframe=true');
        return false;
    });

    window.original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function(html) {
        if (window.formfield) {
            imgurl = $('img',html).attr('src');
            window.formfield.val(imgurl);
            tb_remove();
        }
        else {
            window.original_send_to_editor(html);
        }
        window.formfield = '';
        window.imagefield = false;
    }
});