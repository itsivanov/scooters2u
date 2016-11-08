
$(function() {

    LResize();
    $(window).resize(function(){LResize(); Processgraph(); });
    $(window).scroll(function (){ scrollmenu(); });


    	// icon  gray Hover
	$('.iconBox.gray').hover(function(){
		  var name=$(this).find('img').attr('alt');
		  $(this).find('img').animate({ opacity: 0.5 }, 0, function(){
			    $(this).attr('src','images/icon/color_18/'+name+'.png').animate({ opacity: 1 }, 700);
		 });
	},function(){
		  var name=$(this).find('img').attr('alt');
		  $(this).find('img').attr('src','images/icon/gray_18/'+name+'.png');
	 })

	// Animation icon  Logout
	$('div.logout').hover(function(){
		  var name=$(this).find('img').attr('alt');
		  $(this).find('img').animate({ opacity: 0.4 }, 200, function(){
			    $(this).attr('src','/admin/images/'+name+'.png').animate({ opacity: 1 }, 500);
		 });
	},function(){
		  var name=$(this).find('img').attr('name');
		  $(this).find('img').animate({ opacity: 0.5 }, 200, function(){
			    $(this).attr('src','/admin/images/'+name+'.png').animate({ opacity: 1 }, 500);
		 });
	 })

	// Animation icon  setting
	$('div.setting').hover(function(){
		$(this).find('img').addClass('gearhover');
	},function(){
		$(this).find('img').removeClass('gearhover');
	 })

	// shoutcutBox   Hover
	$('.shoutcutBox').hover(function(){
		  $(this).animate({ left: '+=15'}, 200);
	},function(){
		$(this).animate({ left: '0'}, 200);
	 })

	// shoutcutBox   Hover
	$("#shortcut li").hover(function() {
		  var e = this;
		$(e).find("a").stop().animate({ marginTop: "-7px" }, 200, function() {
		  $(e).find("a").animate({ marginTop: "-5px" }, 200);
		});
	  },function(){
		  var e = this;
		$(e).find("a").stop().animate({ marginTop: "2px" }, 200, function() {
			  $(e).find("a").animate({ marginTop: "0px" }, 200);
		});
	  });

    $("select").not("select.chzn-select,select[multiple]").selectBox();

});


var mybrowser=navigator.userAgent;
if(mybrowser.indexOf('MSIE')>0){$(function() {
           $('.formEl_b fieldset').css('padding-top', '0');
            $('div.section label small').css('font-size', '10px');
            $('div.section  div .select_box').css({'margin-left':'-5px'});
            $('.iPhoneCheckContainer label').css({'padding-top':'6px'});
            $('.uibutton').css({'padding-top':'6px'});
            $('.uibutton.icon:before').css({'top':'1px'});
            $('.dataTables_wrapper .dataTables_length ').css({'margin-bottom':'10px'});
    });
}
if(mybrowser.indexOf('Firefox')>0){ $(function() {
           $('.formEl_b fieldset  legend').css('margin-bottom', '0px');
           $('table .custom-checkbox label').css('left', '3px');
      });
}
if(mybrowser.indexOf('Presto')>0){
    $('select').css('padding-top', '8px');
}
if(mybrowser.indexOf('Chrome')>0){$(function() {
             $('div.tab_content  ul.uibutton-group').css('margin-top', '-40px');
              $('div.section  div .select_box').css({'margin-top':'0px','margin-left':'-2px'});
              $('select').css('padding', '6px');
              $('table .custom-checkbox label').css('left', '3px');
    });
}
if(mybrowser.indexOf('Safari')>0){}


function Processgraph(){
var 	bar = $('.bar'), bw = bar.width(), percent = bar.find('.percent'), circle = bar.find('.circle'), ps =  percent.find('span'),
	cs = circle.find('span'), name = 'rotate';
		var t = $('#pct'), val = t.val();
		if(val){
			val = t.val().replace("%", "");

		if (val >=0 && val <= 100){
			var w = 100-val, pw = (bw*w)/100,
				pa = {  	width: w+'%' },
				cw = (bw-pw), ca = {	"left": cw }
			ps.animate(pa);
			cs.text(val+'%');
			circle.animate(ca, function(){
				circle.removeClass(name)
			}).addClass(name);
		} else {
			alert('range: 0 - 100');
			t.val('');
		}
	}
}


function imgRow(){
      var maxrow=$('.albumpics').width();
      if(maxrow){
              maxItem= Math.floor(maxrow/160);
              maxW=maxItem*160;
              mL=(maxrow-maxW)/2;
              $('.albumpics ul').css({
                      'width'	:	maxW	,
                      'marginLeft':mL
       })
  }}

function scrollmenu(){
      if($(window).scrollTop()>=1){
        $("#header ").css("z-index", "102");
    }else{
        $("#header ").css("z-index", "47");
   }
}

function LResize(){
  imgRow();
  scrollmenu();
	$("#shadowhead").show();
		if($(window).width()<=480) {
					$(' .albumImagePreview').show();
					$('.screen-msg').hide();
					$('.albumsList').hide();
		}
		if($(window).width()<=768){
			$('body').addClass('nobg');
			$('#content').css({ marginLeft: "70px" });	
			$('#main_menu').removeClass('main_menu').addClass('iconmenu');
					$('#main_menu li').each(function() {	  
							var title=$(this).find('b').text();
							$(this).find('a').attr('title',title);		
					});
					$('#main_menu li a').find('b').hide();	
					$('#main_menu li ').find('ul').hide();
		}else{
			$('body').removeClass('nobg').addClass('dashborad');
			$('#content').css({ marginLeft: "240px" });	
			$('#main_menu').removeClass('iconmenu ').addClass('main_menu');
			$('#main_menu li a').find('b').show();	
			}
		if($(window).width()>1024) {
				//	$('#main_menu').removeClass('iconmenu ').addClass('main_menu');
				//	$('#main_menu li a').find('b').show();	
		}
}

function Delete(title, name, url, callback) {
    if (typeof title == undefined) title = 'Delete?';
    $.confirm({
        'title': title, 'message': " <strong>YOU WANT TO DELETE </strong><br /><font color=red>' " + name + " ' </font> ", 'buttons': {'Yes': {'class': 'special',
            'action': function () {
                loading('Checking');
                $('#preloader').html('Deleting...');
                $.ajax({
                    url: url,
                    type: "POST",
                    success: function(data){
                        unloading();
                        if(!data.error) {
                            if (typeof callback == "function") callback(data);
                            //showSuccess('Success', 5000);
                        } else {
                            showError(data.err_desc, 5000);
                        }
                    },
                    dataType: 'json'
                });
            }}, 'No': {'class': ''}}});
}

function loading(name,overlay) {
    $('body').append('<div id="overlay"></div><div id="preloader">'+name+'..</div>');
            if(overlay==1){
              $('#overlay').css('opacity',0.4).fadeIn(400,function(){  $('#preloader').fadeIn(400);	});
              return  false;
       }
    $('#preloader').fadeIn();
}
function unloading() {
    $('#preloader').fadeOut(400,function(){ $('#overlay').fadeOut(); $.fancybox.close(); }).remove();
}

