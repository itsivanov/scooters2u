jQuery(document).ready(function(){

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


    Details.init();
    AppAccesory.init();
    Order.init();
    Payment.init();
});

var Details = function () {

    var handleForm = function(){
        $('#numberScootersDetails').selectBox();


        if(document.getElementById('cal1') !== null || document.getElementById('cal2') !== null){
            var cal1   = document.getElementById('cal1'),
                cal2    = document.getElementById('cal2');
            cal1.onkeypress = function (e) {
                return false;
            };
            cal2.onkeypress = function (e) {
                return false;
            };
        }

        $( ".cal1" ).datepicker({
            defaultDate: new Date(),
            numberOfMonths: 1,
            minDate: 0,
            onClose: function( selectedDate ) {
                if(selectedDate != ''){
                    var plusOneDay = new Date(selectedDate);
                    plusOneDay.setDate(plusOneDay.getDate());
                    $( ".cal2" ).datepicker("option", "minDate", plusOneDay);

                }
            }
        }).on("input change", function () {
                 // $(this).valid();
                var parsedCurrentDate = $(this).val().split('/').reverse().join('/');
                var parsedNextDate = $('.cal2').val().split('/').reverse().join('/');

                if( Date.parse(parsedCurrentDate) > Date.parse(parsedNextDate) || !parsedNextDate){
                    $( ".cal2" ).datepicker("setDate", $(this).val());
                }
                var data = calculatorData();
                handleAjax(data);
        }).datepicker('widget').wrap('<div class="ll-skin-lugo"/>');

        $( ".cal2" ).datepicker({
            defaultDate: new Date(),
            numberOfMonths: 1,
            minDate: 0,
            // maxDate: 30,
            onClose: function( selectedDate ) {
                // $( "#from_date" ).datepicker( "option", "maxDate", selectedDate );
            }
        }).on("input change", function () {
                var parsedCurrentDate = $('.cal1').val().split('/').reverse().join('/');
                var parsedNextDate = $(this).val().split('/').reverse().join('/');

                if( Date.parse(parsedCurrentDate) > Date.parse(parsedNextDate) || !parsedCurrentDate){
                    $( ".cal1" ).datepicker("setDate", $(this).val());
                }

                var data = calculatorData();
                handleAjax(data);

        });


        $('body').on('change', '#numberScootersDetails', function(){
            var data = calculatorData();
            handleAjax(data);
            $(this).trigger('refresh');
        });

        $('#formDetails').on('change', '.access_item', function(){
            var data = calculatorData();
            handleAjax(data);
        });

        $('#formDetails').on('click', '.hid_block span',function () {
            var id = $(this).attr('data-id');
            $(this).parents('.acces_item').addClass("show_select");
            $('#ac_'+ id ).change();

        });

        $('#formDetails').on('click','.fa-close', function() {
            var id = $(this).attr('id');
            $('.item_'+ id).removeClass('show_select');
            $('.hid_' +id +' span').show();
            $('.item_size_'+id).remove();
            $('#ac_'+ id ).val(0);
            // $('#ac_'+ id ).children("select option[value=0]").attr('selected', 'true');
            var data = calculatorData();
            handleAjax(data);
        });

    }


    var calculatorData = function () {
        var arrAccess = [];

        for (i = 1;i<= $('.access_item').length +1 ;i++){
            var n = {id : $("#ac_"+ i).attr('data-id') ,price : $('.access_price_'+i).text(), number : $("#ac_"+ i).val()};
            arrAccess.push(n);
        }

        var access_id = $('.access_item').attr('id'),
            product_id = $('input[name="id"]').val(),
            numScooters = $('#numberScootersDetails').val(),
            price = $('.span_price').attr('data-price'),
            price_ready = parseInt(price.replace(/\.(?=.*\.)|[^\d\.-]/g,"" )),
            day1 = $('.cal1').val(),
            day2 = $('.cal2').val();

        var data = {
            num : numScooters,
            price : price_ready,
            cal1: day1,
            cal2: day2,
            accessories:arrAccess,
            product_id:product_id
        };

        return data;

    }


  var handleAjax = function( data ) {

    $.ajax({
      url: '/ajax/details/',
      type: "POST",
      dataType: 'json',
      data: $.param(data),
      success: function(response){
              $('body #result_sum').html(  response.sum );
              $('body #hidden_ajax').html( response.hidden + response.days);

              $('.span_price').attr('data-price',response.price )
              $('body .span_price').html('$' + response.price);
        }
    });
  }

    return {
        //main function to initiate the module
        init: function () {
            handleForm();
        }

    };
}();

var AppAccesory = function () {

    var AppendBlock = function () {
        $('#formDetails').on('change', '.access_item', function(){

            var url_img = $(this).attr('data-img'),
            id_img = $(this).attr('data-id'),
            value = $(this).val(),
            countItem =  $('.bluud').length;
            $('.item_size_'+ id_img).remove();
            for(var i=0; i< value; i++){
                $('.select_size').append(
                    '<script>$("select.styled").selectBox();</script>'+
                    '<li class="acces_item item_size_'+ id_img +' bluud show_select acc_sten">'+
                        '<div class="item_wrapper">'+
                            '<div class="img_acces">'+
                                '<img src=' + url_img + ' alt="">'+
                            '</div>'+
                            '<div class="hid_block">'+
                                '<div class="name_wr">'+
                                // '<span>Size</span>'+
                            '</div>'+
                            '<div class="select_wr">'+
                                '<input type="hidden" name="size['+countItem+'][id_accees]" value="'+ id_img +'">'+
                                '<select class="styled floatLabel test_select" name="size['+countItem+'][size]" >'+
                                    '<option>L</option>'+
                                    '<option>M</option>'+
                                    '<option>XL</option>'+
                                '</select>'+
                        '</div>'+
                    '</li>'
                );
                countItem++;
            }
        });
    };

    return {
        init: function () {
            AppendBlock();
        }
    }
}();

var Order = function () {


    var handleForm = function () {
        var data_t = calculatorData();
        handleAjaxOrder(data_t);

        if($('.scooter_box').length ==0){ $('.shoping-cart-content').remove();$('.no_item').css('display', 'block');}
        $(document).on('click', '.icon-plus', function () {
            $('.mobile_cart .content_cart').toggle("slow");
        });

        $(document).on('click', '.icon-plus-access', function () {
            var id = $(this).attr('data-id');
            $('.mob_access_'+ id).toggle('slow');
        });

        if(document.getElementById('mob_cal_order_1') !== null || document.getElementById('mob_cal_order_1') !== null){
            var cal1   = document.getElementById('mob_cal_order_1'),
                cal2    = document.getElementById('mob_cal_order_2');
            cal1.onkeypress = function (e) {
                return false;
            };
            cal2.onkeypress = function (e) {
                return false;
            };
        }


        $('body').on('change', '.numberScooters', function(){
            var data = calculatorData();
            handleAjaxOrder(data);
        });

        if(document.getElementById('cal_order_1') !== null || document.getElementById('cal_order_1') !== null){
            var cal1   = document.getElementById('cal_order_1'),
                cal2    = document.getElementById('cal_order_2');
            cal1.onkeypress = function (e) {
                return false;
            };
            cal2.onkeypress = function (e) {
                return false;
            };
        }


        $( ".cal_order_1" ).datepicker({
            defaultDate: new Date(),
            numberOfMonths: 1,
            minDate: 0,
            onClose: function( selectedDate ) {
                if(selectedDate != ''){
                    var plusOneDay = new Date(selectedDate);
                    plusOneDay.setDate(plusOneDay.getDate());
                    $( ".cal_order_2" ).datepicker("option", "minDate", plusOneDay);

                    // var D = new Date(selectedDate);
                    // D.setMonth(D.getMonth() + 1);
                    // $( ".cal2" ).datepicker("option", "maxDate", D);
                }
            }
        }).on("input change", function () {

            var parsedCurrentDate = $(this).val().split('/').reverse().join('/');
            var parsedNextDate = $('.cal_order_2').val().split('/').reverse().join('/');

            if( Date.parse(parsedCurrentDate) > Date.parse(parsedNextDate) || !parsedNextDate){
                $( ".cal_order_2" ).datepicker("setDate", $(this).val());
            }


            var data = calculatorData();
            handleAjaxOrder(data);

        }).datepicker('widget').wrap('<div class="ll-skin-lugo"/>');

        $( ".cal_order_2" ).datepicker({
            defaultDate: new Date(),
            numberOfMonths: 1,
            minDate: 0,
            // maxDate: 30,
            onClose: function( selectedDate ) {
                // $( "#from_date" ).datepicker( "option", "maxDate", selectedDate );
            }
        }).on("input change", function () {
            var parsedCurrentDate = $('.cal_order_1').val().split('/').reverse().join('/');
            var parsedNextDate = $(this).val().split('/').reverse().join('/');

            if( Date.parse(parsedCurrentDate) > Date.parse(parsedNextDate) || !parsedCurrentDate){
                $( ".cal_order_1" ).datepicker("setDate", $(this).val());
            }
            var data = calculatorData();
            handleAjaxOrder(data);
        });

        $('body').on('click', '.main_del', function () {

            handleAjaxOrder({clear:1});
            $('.shoping-cart-content').remove();
            $('.mobile_shopping_cart').remove();
            $('.no_item').css('display', 'block')
        })

        $('body').on('click', '.btn-delete', function(event){
            event.preventDefault();
            var data = [];
            var id = $(this).attr('data-del');
            var id_2 = $(this).attr('id');
            $('.for_del_'+ id_2).remove();

            $('.bluud').each(function(i) {
                $('.scrin').removeClass('result_total_acc_' + i);
            });

            $('.scrin').each(function(i) {

                $(this).addClass('result_total_acc_' + i);

            });


            var data = {};
            data = calculatorData();

            data['del_id'] = id;
            handleAjaxOrder(data);
        });

        $('body').on('keyup', '#discount', function() {
            var val = $('#discount').val();
            if (val.length == 0){
                $('#show_discount').html('00.00$');
            }

            var data = calculatorData();
            handleAjaxOrder(data);
        });
        $('body').on('keyup', '#mob_discount', function() {
            var data = calculatorData();
            handleAjaxOrder(data);
        });

        $('body').on('click', '.go_pay', function(){
            // e.preventDefault();
            // window.location.href = '/   ';
            window.location = "/get-started/order/payment";
        });


    };

    var handleAjaxOrder = function( data ) {

        $.ajax({
            url: '/ajax/details_order_2/',
            type: "POST",
            dataType: 'json',
            data: $.param(data),
            success: function(response){

                $('body #result_total_price').html( response.total_price );
                $('body #mob_total_price').html( response.total_price );
                // $('body #hidden_ajax').html( response.hidden + response.days);

                if(response.for_total_price){
                    for (var i = 0;i< response.for_total_price.length * 4; i++){
                        if(response.for_total_price[i]){
                            $('body .result_total_acc_'+ i).html(response.for_total_price[i]);
                        }
                    }
                }

                $('body #sub_total').html( response.sub_total );
                $('body .super_total').html( response.super_total );
                $('body #mob_super_total').html( response.super_total );

                if(response.discount){
                    $('body #show_discount').html( response.discount );
                }


                //per a day
                $('body #per_a_day_view').html(response.price);
                $('body .per_a_day').val(response.price);
            }
        });
    }

    var calculatorData = function () {

        if(screen.width > 500) {
            var numScooters = $('.numberScooters').val(),
                product_id  = $('input[name="product_id"]').val(),
                day1        = $('#cal_order_1').val(),
                day2        = $('#cal_order_2').val(),
                summa       = $('.per_a_day').val(),
                countItem   = $('.bluud').length,
                discount    = $('#discount').val();

        }else{
            var numScooters = $('#mobNumberScooters').val(),
                day1        = $('#mob_cal_order_1').val(),
                day2        = $('#mob_cal_order_2').val(),
                summa       = $('.per_a_day').val(),
                countItem   = $('.bluud').length,
                discount    = $('#mob_discount').val();
        }


        var acc_new_price = [];
        for(var i = 0; i<=countItem * 5; i++){
            if ($('#a_price_'+ i).val()){
                acc_new_price.push($('#a_price_'+ i).val());
            }
        }

        var data = {
            num : numScooters,
            cal1: day1,
            cal2: day2,
            sum:summa,
            for_total_price: acc_new_price,
            discount : discount,
            product_id:product_id
        };
        return data;
    };

    return {
        init: function () {
            handleForm();
        }
    }
}();


var Payment = function () {

    var handleForm = function () {

        $(document).on('click', '.btn_order', function (e) {
            e.preventDefault();

            if($('#pay_form_1').valid({onsubmit: false}) == true){

                var data  = {
                    order_product_name :$('input[name="order[product_name]"]').val(),
                    order_sum :$('input[name="order[sum]"]').val(),
                    order_delivery_time :$('input[name="order[delivery_time]"]').val(),
                    full_name :$('input[name="rental[full_name]"]').val(),
                    email :$('input[name="rental[email]"]').val(),
                    phone :$('input[name="rental[phone]"]').val(),
                    address :$('input[name="rental[address]"]').val(),
                    address2 :$('input[name="rental[address2]"]').val(),
                    city : $('input[name="rental[city]"]').val(),
                    zip_code :$('input[name="rental[zip_code]"]').val(),
                    billingAddressAddress : $('input[name="billingAddress[address]"]').val(),
                    billingAddressCity :$('input[name="billingAddress[city]"]').val(),
                    state :$('input[name="billingAddress[state]"]').val(),
                    billingZip_code :$('input[name="billingAddress[zip_code]"]').val(),
                    country :$('input[name="billingAddress[country]').val(),
                    first_name :$('input[name="billingInfo[first_name]').val(),
                    last_name :$('input[name="billingInfo[last_name]').val(),
                    security_code :$('input[name="billingInfo[security_code]').val(),
                    card_number : $('input[name="billingInfo[card_number]"]').val(),
                    expiration_date : $('input[name="billingInfo[expiration_date]"]').val(),
                    year : $('select[name="billingInfo[year]"]').val(),
                    i_agree : $('input[name="billingInfo[i_agree]"]').val(),
                    delivery_time: $('select[name="order[delivery_time]"]').val()
                }

                handleAjaxPay(data);
            }

        });

        var handleAjaxPay = function( data ) {

            $.ajax({
                url: '/ajax/pay/',
                type: "POST",
                dataType: 'json',
                data: $.param(data),
                success: function(response){

                    if(response.error){
                        toastr.error(response.error);
                    }else{
                        // toastr.success(response.success);
                        window.location = "/get-started";
                }
                }
            });
        }

    };



    return {
        init: function () {
            handleForm();
        }
    }
}();