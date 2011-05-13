(function (window, document, $) {
    $(function() {
        $('#thumbnailsContainer').jScrollPane({
			verticalDragMinHeight:20,
			verticalDragMaxHeight:20
		});
    });
}(this, this.document, this.jQuery));