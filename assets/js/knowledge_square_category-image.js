jQuery(document).ready(function ($) {
    var mediaUploader;

    $('.upload_image_button').on('click', function (e) {
        e.preventDefault();

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Select Images',
            button: {
                text: 'Choose Image',
            },
            multiple: false,
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#ks-category-image').val(attachment.id);
            $('#ks-category-image-preview').html('<img src="' + attachment.url + '" style="max-width:100px;">');
        });
        mediaUploader.open();
    });

    $('.remove_image_button').click(function(e) {
        e.preventDefault();
        $('#ks-category-image').val('');
        $('#ks-category-image-preview').html('');
    });
});
