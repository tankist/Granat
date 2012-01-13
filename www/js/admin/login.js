$(function () {
    // initialize scrollable
    var isKey = false, api = $(".form_container")
        .scrollable({
            size:1,
            clickable:false,
            globalNav:true,
            api:true,
            onBeforeSeek:function (e, i, time) {
                if (isKey && i != 1 && i != 2) {
                    return false;
                }
            }
        });
    api.seekTo(1);
    isKey = true;

    function go(p) {
        isKey = false
        api.seekTo(p);
        isKey = true;
        return false;
    }

    function showLogin() {
        $('.messages.error').removeClass('error');
        return go(1);
    }

    function showForgot() {
        $('.messages.error').removeClass('error').find('p.error').empty().end();
        return go(2);
    }

    function extract(o) {
        var _o = [];
        $.each(o, function (key, value) {
            _o.push(value);
        });
        return _o;
    }

    $('a.forgot_password').click(showForgot);
    $('a.back_to_login').click(showLogin);

    $('form').ajaxForm(function (data, status, xhr, form) {
        var messages = [];
        if (!jQuery.isEmptyObject(data.error)) {
            for (var i in data.error) {
                if (data.error.hasOwnProperty(i)) {
                    messages.push(extract(data.error[i]));
                }
            }
            var p = 0;
            if (form.is('#loginForm')) {
                p = 0;
            }
            if (form.is('#forgotForm')) {
                p = 3;
            }
            $('.messages').addClass('error').find('p.error').html(messages.join('<br />'));
            go(p);
        }
        else {
            if (form.is('#loginForm')) {
                var referer = form.find('#referer').val();
                if (referer.length > 0) {
                    document.location.href = referer;
                }
            }
        }
    });
});
