(function (window, document, $) {
	$.fn.listchecks = function() {
		return this.each(function() {
			var $table = $(this).parents('table:first');
			$table.delegate('input.select-all', 'change', function(e) {
				var isChecked = this.checked;
				$table.find('td input:checkbox').attr('checked', isChecked);
			});
		});
	}
}(this, this.document, this.jQuery));