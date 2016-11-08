jQuery(document).ready(function(){
  Fields.init();
});

var Fields = function () {


    var switchingSections = function(){
        $('#faq_section').children('span').on('click', function(){

            var id = this.id;

            $('.block_answer').hide();
            $('#' + block + id).show();
        });

    }

    var addFaq = function (){
        $('button.btn_add_rental_field').on('click', function(e){
            e.preventDefault();

            var id = $(this).attr("data-id"),
                num = $(this).attr("data-num"),
                countItem =  $('.fields').length + 1;
            console.log($('.fields ').length);
            id = id + 1;
            var newSection =
                '<div class="section fields">' +
                    '<div class="delete-faq"><i class="fa fa-times fa-2x remove-special" aria-hidden="true"></i></div>' +
                    '<input type="hidden" name="productRentals['+countItem+'][product_id]"/>'+
                    '<input type="text" name="productRentals['+countItem+'][number]" class="section_second_position" placeholder="Number" required />'+
                    ' <input type="text" name="productRentals['+countItem+'][title]" class="section_second_position" placeholder="Title" required />'+
                    ' <input type="text" name="productRentals['+countItem+'][value]" class="section_second_position" placeholder="Price" required />'+

                '</div>';

            $(this).before(newSection);
        });
    }

    var deleteFaq = function (){
        $('body').on('click', '.delete-faq', function(){
            $(this).parent('.section').remove();
        });
    }


    return {
        //main function to initiate the module
        init: function () {
            switchingSections();
            addFaq();
            deleteFaq();
        }

    };
}();