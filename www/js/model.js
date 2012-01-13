(function (window, document, $) {
    $(function () {
        $('#thumbnailsContainer').jScrollPane({
            verticalDragMinHeight:20,
            verticalDragMaxHeight:20
        });

        $('#thumbnails .profile-image a').click(function (e) {
            e.preventDefault();
            var src = $(this).data('src');
            if (src.length > 0) {
                $('#image img')
                    .attr('src', src)
                    .parent().attr('href', $(this).attr('href'));
            }
        });

        $("a[rel^='prettyPhoto']").prettyPhoto({
            theme:'facebook',
            social_tools:''
        });
    });
}(this, this.document, this.jQuery));
