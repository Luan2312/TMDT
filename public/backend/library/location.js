(function($){
    "use strict"
    var TL = {}

    TL.getLocation = () => {
        $(document).on('change', '.location', function () {
            let _this = $(this)
            let option = {
                'data': {
                    'location_id': _this.val()
                },
                'target': _this.attr('data-target')
            }
            TL.sendDataTogetLocation(option)

         })
    }

    TL.sendDataTogetLocation = (option) => {
        $.ajax({
                url: 'ajax/location/getLocation',
                type: 'GET',
                data: option,
                dataType: 'json',
                success: function (data) {
                    $('.' + option.target).html(data.html)

                    if(district_id != '' && option.target == 'districts'){
                        $('.districts').val(district_id).trigger('change')
                    }
                    if(ward_id != '' && option.target == 'wards'){
                        $('.wards').val(ward_id).trigger('change')
                    }
                 },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log('Error: ' + textStatus + ' ' + errorThrown)
                }
            })
    }

    TL.loadCity = () => {
        if(province_id != ''){
            $(".province").val(province_id).trigger('change')
        }
    }

    $(document).ready(function () {
        TL.getLocation()
        TL.loadCity()
     })
})(jQuery)
