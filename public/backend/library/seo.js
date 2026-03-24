(function ($) {
    "use strict"
    var TL = {}

    TL.seoPreview = () => {
        $('input[name=meta_title]').on('keyup', function(){
            let input = $(this)
            let value = input.val()
            $('.meta-title').html(value)
        })

        $('input[name=canonical]').css({
            'padding-left': parseInt($('.baseUrl').outerWidth()) + 10
        })

        $('input[name=canonical]').on('keyup', function(){
            let input = $(this)
            let value = TL.removeUtf8(input.val())
            $('.canonical').html(BASE_URL + value + SUFFIX)
        })

        $('textarea[name=meta_description]').on('keyup', function(){
            let input = $(this)
            let value = input.val()
            $('.meta-description').html(value)
        })
    }

    TL.removeUtf8 = (str= "") => {
        return str
        .toLowerCase()

        // a
        .replace(/[àáạảãâầấậẩẫăằắặẳẵ]/g, "a")
        // e
        .replace(/[èéẹẻẽêềếệểễ]/g, "e")
        // i
        .replace(/[ìíịỉĩ]/g, "i")
        // o
        .replace(/[òóọỏõôồốộổỗơờớợởỡ]/g, "o")
        // u
        .replace(/[ùúụủũưừứựửữ]/g, "u")
        // y
        .replace(/[ỳýỵỷỹ]/g, "y")
        // d
        .replace(/đ/g, "d")

        // ký tự đặc biệt
        .replace(/[^a-z0-9]/g, "-")
        .replace(/-+/g, "-")
        .replace(/^-|-$/g, "");
    }

    $(document).ready(function(){
        TL.seoPreview()
    })

 })(jQuery)
