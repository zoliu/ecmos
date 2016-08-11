$(function(){

	$("img.lazyload").lazyLoad();
	
	$(".backtop").hide();
    $(window).scroll(function() {
        if ($(window).scrollTop() > 320) {
            $(".backtop").show();
         } else {
            $(".backtop").hide();
         }
     });
	
	$('.mall-nav .allcategory').hover(function(){
		$(this).find('.allcategory-list').show();
	},function(){
		$(this).find('.allcategory-list').hide();
	});
   
	$('.has-eject-box').hover(function(){
		$(this).addClass('active');
	},function(){
		$(this).removeClass('active');
	})
   
		
   	$(".top-search li").click(function(){
	   $(".top-search li").each(function(){
		   $(this).removeClass("current");
	   });
	   $(this).addClass("current");
	   $(".top-search-box input[name='act']").val(this.id);
	   
	   if($.trim($(".top-search-box input[name='keyword']").val())==""){
		   $(".top-search-box input[name='keyword']").attr("class","");
		   $(".top-search-box input[name='keyword']").addClass(this.id+"_bj kw_bj keyword");
	   }
   }); 
   
   $(".top-search-box input[name='keyword']").focus(function(){
	   $(this).attr("class","keyword");
   }).blur(function(){
	   if($.trim($(this).val())=="") {
		   $(this).attr("class",$(this).parent().find("input[name='act']").val()+"_bj kw_bj keyword");
	   }
   });
   
   $('.login-register .form .input').focus(function(){
		$(this).removeClass('hover');
		$(this).addClass('focus');
	});
	$('.login-register .form .input').keydown(function(){
		$(this).siblings('.error').hide();
	});
	$('.login-register .form .input').hover(function(){
		$(this).removeClass('hover');
		$(this).addClass('hover');
	},function(){
		$(this).removeClass('hover');
	});
	$('.login-register .form .input').blur(function(){
		$(this).removeClass('hover');
		$(this).removeClass('focus');
	});	
	
	$('.J_GlobalImageAdsBotton').click(function(){
		$(this).hide();
		$(this).parent().slideUp();
	});
	$('.tabOne').mouseover(function(){
		var liIndex = $(this).parent('.tabList').children('.tabOne').index(this);
		var liWidth = $(this).width();
		$(this).addClass('active').siblings().removeClass('active');
		$(this).parent('.tabList').parent('.tabSwitcher').find('.tabContent').eq(liIndex).show().siblings().hide();
		$(this).parent('.tabList').next('.arrow').stop(false,true).animate({'left' : liIndex * liWidth + 'px'},500);
	});
	$('.J_tab li').mouseover(function(){
		$(this).addClass('on').siblings('li').removeClass('on');
		var index = $(this).index();
		$(this).parent().siblings('.tab-content').find('>li:eq('+index+')').show().siblings().hide();
	});
	
	$(window).bind("scroll",function(){ 
		var fnav_height = $(".J_FloorNav").height();
		var floor_left = ($(window).width()-1200)/2-35;
		var floor_top = ($(window).height()-fnav_height)/2;
		$(".J_FloorNav").css({'left':floor_left,'top':floor_top});
		if($(window).scrollTop()>1000){
			$(".J_FloorNav").fadeIn();
		}else{
			$(".J_FloorNav").fadeOut();
		}
		$('.floor').parent().each(function(index, element) {
			//alert($(this).offset().top);1354
            if($(window).scrollTop()>$(this).offset().top-$(window).height()/2 && $(window).scrollTop()<$(this).offset().top+$(this).outerHeight()-$(window).height()/2){
				$('.J_FloorNav a[navid='+$(this).attr("id")+']').addClass("current");
				$(this).find('.floor').find('.title').find('i').addClass("current");
			}else{
				$('.J_FloorNav a[navid='+$(this).attr("id")+']').removeClass("current");	
				$(this).find('.floor').find('.title').find('i').removeClass("current");			
			}
        });
	});
	$(".J_FloorNav a").click(function(){
		var pos = $("#"+$(this).attr("navid")).offset().top;
		$('html,body').animate({'scrollTop':pos},500);
		$(this).addClass("current");
	})
})

function poshytip_message(obj,className,showOn,alignTo,alignX,offsetX,offsetY)
{
	if(obj==undefined) return;
	if(className==undefined) className = 'tip-yellowsimple';
	if(showOn==undefined) showOn = 'focus';
	if(alignTo==undefined) alignTo = 'target';
	if(alignX==undefined) alignX = 'inner-left';
	if(offsetX==undefined) offsetX = 0;
	if(offsetY==undefined) offsetY = 5;
		
	obj.poshytip({
		className: className,
		showOn: showOn,
		alignTo: alignTo,
		alignX: alignX,
		offsetX: offsetX,
		offsetY: offsetY
	});
}
function countdown(theDaysBox, theHoursBox, theMinsBox, theSecsBox)
{
		var refreshId = setInterval(function() {
			var currentSeconds = theSecsBox.text();
	  		var currentMins    = theMinsBox.text();
	  		var currentHours   = theHoursBox.text();
	  		var currentDays    = theDaysBox.text();
	  
	  		// hide day
	  		if(currentDays == 0) {
	  			theDaysBox.next('em').hide();
	  			theDaysBox.hide();
	 		}
	  
	  		if(currentSeconds == 0 && currentMins == 0 && currentHours == 0 && currentDays == 0) {
	  			// if everything rusn out our timer is done!!
	  			// do some exciting code in here when your countdown timer finishes
	  	
	  		} else if(currentSeconds == 0 && currentMins == 0 && currentHours == 0) {
	  			// if the seconds and minutes and hours run out we subtract 1 day
	  			theDaysBox.html(currentDays-1);
	  			theHoursBox.html("23");
	  			theMinsBox.html("59");
	  			theSecsBox.html("59");
	  		} else if(currentSeconds == 0 && currentMins == 0) {
	  			// if the seconds and minutes run out we need to subtract 1 hour
	  			theHoursBox.html(currentHours-1);
	  			theMinsBox.html("59");
	  			theSecsBox.html("59");
	  		} else if(currentSeconds == 0) {
	  			// if the seconds run out we need to subtract 1 minute
	  			theMinsBox.html(currentMins-1);
	  			theSecsBox.html("59");
	  		} else {
      			theSecsBox.html(currentSeconds-1);
      		}
   		}, 1000);
}