//Адреса и различные константы
var IMAGE_DELETE_URL = '/admin/products/delete-image/';
IMAGE_ADD_URL = '/admin/products/add-image';

$(function () {
    var product_id;

    var imageForm = $('#productImage'),
        defaultOptions = {
            beforeSubmit:onBeforeRequest, // pre-submit callback
            type:'post', // 'get' or 'post', override for form's 'method' attribute
            dataType:'json', // 'xml', 'script', or 'json' (expected server response type)
            data:{
                product_id:getProductId(),
                format:'json'
            },
            error:function () {
                onAfterRequest();
                imageForm.resetForm();
                showMessage('Error uploading File. Please try again', 'error');
            }
        };

    $('#product-images-block').delegate('button.delete', 'click', function (e) {
        imageForm.ajaxSubmit($.extend(defaultOptions, {
            success:showDeleteResult,
            url:IMAGE_DELETE_URL,
            data:$.extend(defaultOptions.data, {
                id:$(this).attr('image_id')
            })
        }));
        e.preventDefault();
    });

    imageForm.find('input[type=file]').change(function () {
        imageForm.ajaxSubmit($.extend(defaultOptions, {
            success:showResponse,
            url:IMAGE_ADD_URL,
            iframe:true
        }));
    });

    // pre-submit callback
    function onBeforeRequest() {
        $('#add-profile-image-loading').show();
        $('#add-profile-image, #productImage').hide();
        return true;
    }

    function onAfterRequest() {
        $('#add-profile-image-loading').hide();
        $('#add-profile-image, #productImage').show();
        if ($('#product-images-block input:radio:checked').length == 0) {
            $('#product-images-block input:radio:first').attr('checked', true);
        }
        return true;
    }

    // post-submit callback
    function showResponse(data) {
        onAfterRequest();

        if (data.error) {
            showMessage(data.error, 'error');
            imageForm.resetForm();
            return;
        }

        var length = $('#product-images-block img').length,
            checked = ( length == 0 ) ? 'checked' : '',
            imageFilename = data['image']['name'],
            imageFilenameParts = imageFilename.split('.'),
            imageData = {
                imageFolder:data['image']['path'],
                name:imageFilenameParts.slice(0, -1).join('.'),
                ext:imageFilenameParts.slice(-1).shift(),
                image_id:data['image']['id'],
                checked:checked
            };
        $('#product-images-block').append($.trim(tmpl('imageTpl', imageData)));
        imageForm.resetForm();
    }

    function showDeleteResult(data) {
        if (data['result']['status'] == 'success') {
            $('#' + data['result']['id']).parents('div.product-image-wrapper:first').remove();
        }
        onAfterRequest();
        imageForm.resetForm();
    }

    $('.content-narrow').resize(
        function () {
            var addButton = $('#add-profile-image button'), abPos = addButton.position();
            imageForm.css('top', abPos.top);
        }).resize();

    if ($('#product-images-block input:radio:checked').length == 0) {
        $('#product-images-block input:radio:first').attr('checked', true);
    }

    function getProductId() {
        if (!product_id) {
            product_id = parseInt($('input:hidden#id').val());
        }
        return product_id || 0;
    }
});
