(function($, window, document) {

	$(function() {
		$('#file_upload').fileUploadUI({
			uploadTable: $('#files'),
			buildUploadRow: function (files, index, handler) {
				return $('<tr><td>' + files[index].name + '<\/td>' +
						'<td class="file_upload_progress"><div><\/div><\/td>' +
						'<td class="file_upload_cancel">' +
						'<button class="ui-state-default ui-corner-all" title="Cancel">' +
						'<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
						'<\/button><\/td><\/tr>');
			},
			buildDownloadRow: function (file, handler) {
				return $('<tr><td>' + file.name + '<\/td><\/tr>');
			}
		});
	});

})(this.jQuery, this, this.document);