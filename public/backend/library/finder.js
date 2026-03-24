(function ($) {
    "use strict"
    var TL = {}

    TL.setupCkEditor = () => {
        if($('.ckeditor')){
            $('.ckeditor').each(function(){
                let editor = $(this)
                let elementId = editor.attr('id')
                let elementHeight = editor.attr('data-height')
                TL.ckeditor4(elementId, elementHeight)
            })
        }
    }

    TL.uploadAlbum = () => {
        $(document).on('click', '.upload-picture', function(e){
            TL.browserServerAlbum()
            e.preventDefault()
        })
    }

    TL.multipleUploadimageCkeditor = () => {
        $(document).on('click', '.multipleUploadImageCkeditor', function(e){
            let object = $(this)
            let target = object.attr('data-target')
            TL.browserServerCkeditor(object, 'Images', target)

            e.preventDefault()
        })
    }

    TL.ckeditor4 = (elementId, elementHeight) => {
        if(typeof(elementHeight) == 'undefined'){
            elementHeight = 500
        }
        CKEDITOR.replace( elementId, {
            height: elementHeight,
            removeButtons: '',
            entities: true,
            allowedContent: true,
            toolbarGroups: [
                { name: 'clipboard', groups: [ 'clipboard', 'undo']},
                { name: 'editing', groups: ['find', 'selection', 'spellchecker']},
                { name: 'links'},
                { name: 'insert'},
                { name: 'forms'},
                { name: 'tools'},
                { name: 'document', groups: ['mode', 'document', 'doctools']},
                { name: 'colors'},
                { name: 'others'},
                '/',
                { name: 'basicstyles', groups: ['basicstyles', 'cleanup', 'colors', 'styles', 'indent']},
                { name: 'paragraph', groups: ['list', '', 'blocks', 'align', 'bidi']},
            ],
            removeButtons: 'Save,NewPage,Pdf,Preview,Print,Find,Replace,CreateDiv,SelectAll,Symbol,Block,Button,Language',
            removePlugins: "exportpdf"
        })
    }

    TL.uploadImageToInput = () => {
            $('.upload-image').click(function(){
                let input = $(this)
                let type = input.attr('data-type')
            TL.setupCkFinder2(input, type)
        })
    }

    TL.uploadImageAvatar = () => {
        $('.image-target').click(function(){
            let input = $(this)
            let type = 'Images'
            TL.browseServerAvatar(input, type)
        })
    }

    TL.setupCkFinder2 = (object, type) => {
        if (typeof(type) == 'undefined'){
            type = 'Images'
        }
        var finder = new CKFinder()
        finder.resourceType = type
        finder.selectActionFunction = function(fileUrl, data){
            object.val(fileUrl)
        }
        finder.popup()
    }

    TL.browseServerAvatar = (object, type) => {
        if (typeof(type) == 'undefined'){
            type = 'Images'
        }
        var finder = new CKFinder()
        finder.resourceType = type
        finder.selectActionFunction = function(fileUrl, data){
            object.find('img').attr('src', fileUrl)
            object.siblings('input').val(fileUrl)
        }
        finder.popup()
    }

    TL.browserServerCkeditor = (object, type, target) => {
        if (typeof(type) == 'undefined'){
            type = 'Images'
        }
        var finder = new CKFinder()

        finder.resourceType = type
        finder.selectActionFunction = function(fileUrl, data, allFiles){
            let html = ''
            for(var i = 0; i < allFiles.length; i++){
                var image = allFiles[i].url

                html += '<div class="image-content img-cover"><figure>'
                    html += '<img src="'+image+'" alt="'+image+'">'
                    html += '<figcaption>Nhap mo ta image</figcaption>'
                html += '</figure></div>'

            }
            CKEDITOR.instances[target].insertHtml(html)
        }
        finder.popup()
    }
    TL.browserServerAlbum = () => {
        var type = 'Images'
        var finder = new CKFinder()

        finder.resourceType = type
        finder.selectActionFunction = function(fileUrl, data, allFiles){
            let html = ''
            for(var i = 0; i < allFiles.length; i++){
                var image = allFiles[i].url

                html += '<li class="ui-state-default">'
                    html += '<div class="thumb">'
                        html += '<span class="span image img-scaledown">'
                            html += '<img src="'+image+'" alt="'+image+'">'
                            html += '<input type="hidden" name="album[]" value="'+image+'">'
                        html += '</span>'
                        html += '<button class="delete-image"><i class="fa fa-trash"></i></button>'
                    html += '</div>'
                html += '</li>'

            }
            $('.click-to-upload').addClass('hidden')
            $('#sortable').append(html)
            $('.upload-list').removeClass('hidden')
        }
        finder.popup()
    }

    TL.deletePicture = () => {
        $(document).on('click', '.delete-image', function () {
            let _this = $(this)
            _this.parents('.ui-state-default').remove()
            if($('.ui-state-default').length == 0 ){
                $('.click-to-upload').removeClass('hidden')
                $('.upload-list').addClass('hidden')
            }
         })
    }

    $(document).ready(function(){
        TL.uploadImageToInput()
        TL.setupCkEditor()
        TL.uploadImageAvatar()
        TL.multipleUploadimageCkeditor()
        TL.uploadAlbum()
        TL.deletePicture()
    })

 })(jQuery)
