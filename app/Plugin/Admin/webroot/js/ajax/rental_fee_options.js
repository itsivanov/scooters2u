
jQuery(document).ready(function(){
    Rental.init();

});

var Rental = function () {

    var handleForm = function(){

        $('.del_but').on('click', function () {
            var id = $(this).attr('id');
            var data = {
                id: id,
                action: "delete"
            }

            handleAjax(data);
        });
    }


    var handleAjax = function( data ) {

        $.ajax({
            url: '/admin/ajax/rent_fee/',
            type: "POST",
            // dataType: 'json',
            data: $.param(data)
        });
    }

    return {
        //main function to initiate the module
        init: function () {
            handleForm();
        }
    };

}();
