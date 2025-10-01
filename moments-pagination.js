jQuery(document).ready(function ($) {
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();

        let page = $(this).data('page');
        let folder = $(this).data('folder');
        let imagesPerPage = $(this).data('images-per-page');

        let container = $('.moments-gallery').parent();

        $.ajax({
            type: 'POST',
            url: ajaxpagination.ajaxurl,
            data: {
                action: 'load_images_page',
                nonce: ajaxpagination.nonce,
                page: page,
                folder: folder,
                images_per_page: imagesPerPage
            },
            beforeSend: function () {
                // Show spinner
                container.html(
                    '<div class="gf-loading"><span class="spinner"></span> Loading...</div>'
                );
            },
            success: function (response) {
                // Replace gallery + pagination
                container.html(response);
            },
            error: function () {
                container.html('<p style="color:red;">Error loading images.</p>');
            }
        });
    });
});
