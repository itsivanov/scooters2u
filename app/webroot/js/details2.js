jQuery(document).ready(function(){
    Order.init();
});

var Order = function () {

    var handleForm = function () {

        $('body').on('change', '#numberScooters', function(){
            var data = calculatorData();
            console.log(data);
            handleAjaxOrder(data);
        });

        $( ".cal_order_1" ).datepicker({dateFormat: 'yy-m-d'}).on("input change", function () {
            var data = calculatorData();
            handleAjaxOrder(data);
        });

        $( ".cal_order_2" ).datepicker({dateFormat: 'yy-m-d'}).on("input change", function () {
            var data = calculatorData();
            handleAjaxOrder(data);
        });

        $('body').on('click', '.btn-delete', function(event){
            event.preventDefault();
            var data = [];
            var id = $(this).attr('id');

            data = {
                del_id: id
            };

            handleAjaxOrder(data);
            window.location.reload();
        });

        $('body').on('keyup', '#discount', function() {
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
                // $('body #hidden_ajax').html( response.hidden + response.days);
                // console.log(data.for_total_price.length);
                if(response.for_total_price){
                    for (var i=0;i< response.for_total_price.length; i++){
                        $('body #result_total_acc_'+ i).html(response.for_total_price[i]);
                    }
                }

                $('body #sub_total').html( response.sub_total );
                $('body #super_total').html( response.super_total );

                if(response.discount){
                    $('body #show_discount').html( response.discount );
                }
            }
        });
    }

    var calculatorData = function () {

        var numScooters = $('.numberScooters').val(),
            day1 = $('.cal_order_1').val(),
            day2 = $('.cal_order_2').val(),
            summa = $('.per_a_day').val(),
            countItem =  $('.bluud').length,
            discount = $('#discount').val();

        var acc_new_price = [];
        for(var i = 1; i<= countItem; i++){
            acc_new_price.push($('#a_price_'+ i).val());
        }

        var data = {
            num : numScooters,
            cal1: day1,
            cal2: day2,
            sum:summa,
            for_total_price: acc_new_price,
            discount : discount
        };
        return data;
    };

    return {
        init: function () {
            handleForm();
        }
    }
}();