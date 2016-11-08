
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
      url: '/admin/ajax/products/',
      type: "POST",
      // dataType: 'json',
      data: $.param(data),
      // success: function(response){
      //
      //       if (data.action == "add_templates") {
      //
      //           if (response.info) {
      //               handleAlert('.info_messages',response.info,'success');
      //               // $("body").animate({scrollTop:100}, '500');
      //           }
      //           if (response.error) {
      //               handleAlert('.info_messages',response.error,'denger');
      //           }
      //       }
      //   }

    });
  }

    return {
        //main function to initiate the module
        init: function () {
            handleForm();
        }
    };

}();
