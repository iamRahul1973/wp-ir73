jQuery(function ($) {

    /* --------------------------------------------------
       | MEDIA UPLOADER
       -------------------------------------------------- */

    var CustomPluginMediaUploader = {

        construct: function () {
            // Run initButton when the media button is clicked.
            $('body').find('.ir73-media-button').each(function (index) {
                CustomPluginMediaUploader.initButton($(this));
                console.log('fuck');
            });
        },

        initButton: function (_that) {
            _that.click(function (e) {
                // Instantiates the variable that holds the media library frame.
                var metaImageFrame;

                // Get the btn
                var btn = e.target;

                // Check if it's the upload button
                if (!btn || !$(btn).attr('data-ir73-media-uploader-target')) return;

                // Get the field target
                var field = $(btn).siblings('input:hidden');

                // Prevents the default action from occuring.
                e.preventDefault();

                // Sets up the media library frame
                metaImageFrame = wp.media.frames.metaImageFrame = wp.media({
                    title: 'Chose a File',
                    button: { text: 'Use this file' },
                });

                // Runs when an image is selected.
                metaImageFrame.on('select', function () {

                    // Grabs the attachment selection and creates a JSON representation of the model.
                    var media_attachment = metaImageFrame.state().get('selection').first().toJSON();

                    // Sends the attachment URL to our custom image input field.
                    $(field).val(media_attachment.url);

                });

                // Opens the media library frame.
                metaImageFrame.open();
            });
        }

    };

    CustomPluginMediaUploader.construct();

    /* --------------------------------------------------
       | ADD NEW REPEATOR ROW
       -------------------------------------------------- */

    $('[data-action="add_ir73_repeator_item"]').on('click', function () {

        console.log('Hello');

        let templateId = $(this).data('template');
        let template = $('#' + templateId).html();

        let wrapperId = $(this).data('wrapper');
        let wrapper = $('#' + wrapperId);

        Mustache.parse(template);
        let rendered = Mustache.render(template);
        wrapper.append(rendered);

        // Disable Add More Button
        // Will be re-enabled in the media selected callback
        // $(this).attr('disabled', true);

        CustomPluginMediaUploader.construct();

    });

    /* --------------------------------------------------
       | DELETE REPEATER ROW
       -------------------------------------------------- */

    $('a[data-role="delete-row"]').on('click', function (e) {
        e.preventDefault();
        $(this).parents('div.row').remove();
    });

    /* --------------------------------------------------
       | REMOVE SECONDARY THUMBNAIL ITEM
       -------------------------------------------------- */

    $(document).on('click', 'a[data-action="remove"]', function (e) {
        e.preventDefault();
        $(this).parent('p').parent('div').remove();
    });

    /* --------------------------------------------------
       | JQUERY SORTABLE
       -------------------------------------------------- */

    $('[data-action="sortable"]').sortable();
    $('[data-action="sortable"]').disableSelection();

});

// Don’t cry because it’s over, smile because it happened.
