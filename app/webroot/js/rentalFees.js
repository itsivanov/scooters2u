$(document).ready(function () {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "500",
        "hideDuration": "500",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }


    Rental.init();
});

var Rental = function () {
    var handleForm = function () {
        // no keypress in calendar for 4 propositions
        for(var i=1;i<=4; i++){
            if( document.getElementById('rental_calendar_'+i) !== null){
                document.getElementById('rental_calendar_'+i).onkeypress =function (e) {
                    return false;
                }
            }
        }

        $(document).scroll(function(){
            console.log(11);
        });
        //change calendar
        $( ".rentalDatepicker" ).datepicker({
            defaultDate: new Date(),
            numberOfMonths: 1,
            minDate: 0,
            onClose: function( selectedDate ) {
                // if(selectedDate != ''){
                //     var plusOneDay = new Date(selectedDate);
                //     plusOneDay.setDate(plusOneDay.getDate());
                //     $( ".cal2" ).datepicker("option", "minDate", plusOneDay);
                //
                //     var D = new Date(selectedDate);
                //     D.setMonth(D.getMonth() + 1);
                //     // $( ".cal2" ).datepicker("option", "maxDate", D);
                // }
            }
        }).on("input change", function () {
            // // $(this).valid();
            // var parsedCurrentDate = $(this).val().split('/').reverse().join('/');
            // var parsedNextDate = $('.cal2').val().split('/').reverse().join('/');
            //
            // if( Date.parse(parsedCurrentDate) > Date.parse(parsedNextDate) || !parsedNextDate){
            //     $( ".cal2" ).datepicker("setDate", $(this).val());
            // }
            // var data = calculatorData();
            // handleAjax(data);
        }).datepicker('widget').wrap('<div class="ll-skin-lugo"/>');


        $(document).on('click', '.btnGet', function (e) {
            e.preventDefault();
            var id      = $(this).attr('id'),
                time    = $('input[name="rental_time_'+id+'"]').val(),
                price   = $('input[name="price_'+id+'"]').val(),
                date    = $('input[name="rental_calendar_from_'+id+'"]').val();

            var someDate = new Date(date);
            // var duration = time; //In Days
            someDate.setTime(someDate.getTime() +  (time * 24 * 60 * 60 * 1000));
            var new_date = (someDate.getMonth() + 1) + '/'+ someDate.getDate() + '/'+ someDate.getFullYear();
            $('#calendar_to_'+id ).val(new_date);

            // if(time != 0 && time != 1){
            //     price = price * time;
            // }

            var data = {
                time : time,
                price : price,
                from : date,
                to : new_date
            };
            
            if(!date){
                toastr.error('Enter calendar');

            }else{
                    ajaxHandle(data);
            }
        });

    };
    
    var ajaxHandle = function (data) {

        $.ajax({
            url: '/ajax/rentalFee/',
            type: "POST",
            dataType: 'json',
            data: $.param(data),
            success: function(result){
                if(result){

                    window.location = "/get-started/order/payment";
                }
            }
        });
    };



    return {
        init: function () {
            handleForm();
        }
    }
}();