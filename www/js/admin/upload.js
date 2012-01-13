(function ($, window, document) {

    function checkMainImage() {
        if ($('input.checkable:checked').length == 0) {
            $('input.checkable:first').attr('checked', 'checked');
        }
    }

    $(function () {
        var DELETE_URL = '/admin/model-image/delete';

        var buildUploadRow = doT.template(document.getElementById('uploadRow').innerHTML);
        var buildDownloadRow = doT.template(document.getElementById('progressRow').innerHTML);

        $('#modelImage').fileUploadUI({
            uploadTable:$('#files'),
            buildUploadRow:function (files, index, handler) {
                return $(buildUploadRow(files[index]));
            },
            buildDownloadRow:function (file, handler) {
                if (file.error) {
                    showMessage(file.error, 'error');
                    return;
                }
                return $(buildDownloadRow(file));
            },
            onCompleteAll:function (files) {
                checkMainImage();
            }
        });

        $('#files').delegate('button.delete', 'click', function (e) {
            e.preventDefault();
            var button = this;
            var data = {
                id:$(this).data('id')
            };
            var model_id = $('#model #id').val() * 1;
            if (model_id) {
                data.model_id = model_id;
            }
            $.getJSON(DELETE_URL, data, function (r) {
                if (r.error) {
                    showMessage(r.error, 'error');
                    return false;
                }
                $(button).parents('tr:first').remove();
            });
        }, 'json');

        $('.upload_form button.submit').click(function (e) {
            e.preventDefault();
            $('form:not(.file_upload)').submit();
        });

        checkMainImage();
    });

})(this.jQuery, this, this.document);
