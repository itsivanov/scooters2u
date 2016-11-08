jQuery(document).ready(function(){
    Fields.init();
});

var Fields = function () {



    var addField = function (){
        $('button.btn_add_rental_field').on('click', function(e){
            e.preventDefault();

            var id = $('#RentalFeeId').val(),
                countItem =  $('.fields').length + 1;
            console.log($('.fields ').length);

            var newSection =
                '<div class="section fields">' +
                '<div class="delete-faq"><i class="fa fa-times fa-2x remove-special" aria-hidden="true"></i></div>' +
                '<input type="hidden" name="RentalFeeOption['+countItem+'][rental_fee_id]" value="'+ id +'" />'+
                '<input type="text" name="RentalFeeOption['+countItem+'][value]"  placeholder="new field" "/>'+
                '</div>';

            $(this).before(newSection);
        });
    }

    var deleteField = function (){
        $('body').on('click', '.delete-faq', function(){
            $(this).parent('.section').remove();
        });
    }


    return {
        //main function to initiate the module
        init: function () {
            addField();
            deleteField();
        }

    };
}();