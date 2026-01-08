jQuery(document).ready(function ($) {
    // Media Library for Gallery Images
    let mediaFrame;
    const galleryList = $('#gallery-images-list');

    $('#select-images').on('click', function (e) {
        e.preventDefault();

        if (mediaFrame) {
            mediaFrame.open();
            return;
        }

        mediaFrame = wp.media({
            title: 'Select Images',
            button: {
                text: 'Add to Gallery',
            },
            multiple: true,
        });

        mediaFrame.on('select', function () {
            const attachments = mediaFrame.state().get('selection').toArray();

            attachments.forEach(function (attachment) {
                const imageId = attachment.id;
                const imageUrl = attachment.attributes.url;

                const listItem = `
                    <li>
                        <img src="${imageUrl}" width="100" />
                        <input type="hidden" name="gallery_images[]" value="${imageId}" />
                        <button type="button" class="remove-image">Remove</button>
                    </li>
                `;

                galleryList.append(listItem);
            });
        });

        mediaFrame.open();
    });

    galleryList.on('click', '.remove-image', function () {
        $(this).closest('li').remove();
    });
});
