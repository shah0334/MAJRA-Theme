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
            $('#faq-category-image').val(attachment.id);
            $('#faq-category-image-preview').html('<img src="' + attachment.url + '" style="max-width:100px;">');
        });
        mediaUploader.open();
    });

    $('.remove_image_button').click(function(e) {
        e.preventDefault();
        $('#faq-category-image').val('');
        $('#faq-category-image-preview').html('');
    });
});
