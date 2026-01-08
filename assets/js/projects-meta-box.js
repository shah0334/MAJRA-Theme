jQuery(document).ready(function ($) {
    let frame;

    $('#add-partners-logo').click(function (e) {
        e.preventDefault();
        if (frame) {
            frame.open();
            return;
        }

        frame = wp.media({
            title: 'Select Partner Logos',
            button: { text: 'Add Logos' },
            multiple: true
        });

        frame.on('select', function () {
            const attachments = frame.state().get('selection').toJSON();
            attachments.forEach(function (attachment) {
                $('#partners-logo-list').append(`
                    <li>
                        <img src="${attachment.url}" style="max-width: 100px;" class="d-block"/>
                        <input type="hidden" name="partners_logo[]" value="${attachment.id}"/>
                        <button type="button" class="remove-logo button d-block" style="margin-top:0.5rem;width: 100px;">Remove</button>
                    </li>
                `);
            });
        });

        frame.open();
    });

    let kpiImageFrame;
    $('#add-kpi-image').click(function (e) {
        e.preventDefault();
        if (kpiImageFrame) {
            kpiImageFrame.open();
            return;
        }

        kpiImageFrame = wp.media({
            title: 'Select KPI Image',
            button: { text: 'Add Image' },
            multiple: false
        });

        kpiImageFrame.on('select', function () {
            const attachments = kpiImageFrame.state().get('selection').toJSON();
            attachments.forEach(function (attachment) {
                $('#kpi-image-list').html(`
                    <li>
                        <img src="${attachment.url}" style="max-width: 100px;" class="d-block"/>
                        <input type="hidden" name="kpi_image" value="${attachment.id}"/>
                        <button type="button" class="remove-kpi-image button d-block" style="margin-top:0.5rem;width: 100px;">Remove</button>
                    </li>
                `);
            });
        });

        kpiImageFrame.open();
    });

    $(document).on('click', '.remove-logo', function () {
        $(this).closest('li').remove();
    });

    $(document).on('click', '.remove-kpi-image', function () {
        $(this).closest('li').remove();
    });
});
