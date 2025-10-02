jQuery(document).ready(function ($) {
    $(document).on('click', '.pagination a.prev-page, .pagination a.next-page', function (e) {
        e.preventDefault();

        var button = $(this);
        var page = button.data('page');
        var folder = button.data('folder');
        var imagesPerPage = button.data('images-per-page');
        var wrapper = button.closest('.gallery-wrapper');

        //console.log("Clicked page:", page, "folder:", folder, "images:", imagesPerPage);

        wrapper.find('.moments-gallery').html('<div class="loading">Loading...</div>');

        $.ajax({
            url: ajaxpagination.ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'load_images_page',
                page: page,
                folder: folder,
                images_per_page: imagesPerPage,
                nonce: ajaxpagination.nonce,
            },
            success: function (response) {
                console.log(response.data);
                if (response.success) {
                    wrapper.replaceWith(response.data.html); // ✅ replace wrapper content
                } else {
                    wrapper.find('.moments-gallery').html('<p>Error: ' + response.data + '</p>');
                }
            },
            error: function () {
                wrapper.find('.moments-gallery').html('<p>AJAX error loading images.</p>');
            },
        });
    });
});
