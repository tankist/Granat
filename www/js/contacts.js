(function (window, document, $) {
    $(function () {
        $(".mapLink").click(function (e) {
            e.preventDefault();
            $(this).parent().siblings('.mapContainer').slideToggle(function () {
                $.each(window.maps, function (index, map) {
                    map.redraw();
                })
            });
        })
    });
}(this, this.document, this.jQuery));
