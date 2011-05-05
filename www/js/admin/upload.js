(function($, window, document) {

	$(function() {
		var DELETE_URL = '/admin/model-image/delete';

		var buildUploadRow = doT.template(document.getElementById('uploadRow').innerHTML);
		var buildDownloadRow = doT.template(document.getElementById('progressRow').innerHTML);

		$('#modelImage').fileUploadUI({
			uploadTable: $('#files'),
			buildUploadRow: function (files, index, handler) {
				return $(buildUploadRow(files[index]));
			},
			buildDownloadRow: function (file, handler) {
				if (file.error) {
					showMessage(file.error, 'error');
					return;
				}
				return $(buildDownloadRow(file));
			}
		});

		$('#files').delegate('button.delete', 'click', function(e) {
			e.preventDefault();
			var button = this;
			var data = {
				id : $(this).data('id')
			};
			$.getJSON(DELETE_URL, data, function(r) {
				if (r.error) {
					showMessage(r.error, 'error');
					return false;
				}
				$(button).parents('tr:first').remove();
			});
		}, 'json');
	});

})(this.jQuery, this, this.document);