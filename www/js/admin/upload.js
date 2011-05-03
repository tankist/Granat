(function($, window, document) {

	$(function() {
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
	});

})(this.jQuery, this, this.document);