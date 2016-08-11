$(function(){
	$('.J_GcategoryLi').click(function(){
		$(this).parent().find('.category-child').toggle();
		$(this).toggleClass('gcategory-show-child');
	});
	
	$(".float-back-top").hide();
	 $(document).on('scroll', function(event) {
		if ($(window).scrollTop() > 420) {
            $(".float-back-top").show();
         } else {
            $(".float-back-top").hide();
         }
  	});
	
	//页面底部导航 点击箭头，显示隐藏导航
   	$('.global-nav__operate-wrap').on('click',function(){
		$('.global-nav').toggleClass('global-nav-current');
   	});
	$('.options .more').click(function(){
		$(this).parents('.attrv').find('li.hidden').toggle();
		if($(this).hasClass('unfold') == true)
		{
			$(this).find('span').html('查看更多');
			$(this).removeClass('unfold');
		}
		else
		{
			$(this).find('span').html('收起更多');
			$(this).addClass('unfold');
		}
	})
});
function pageBack()
{
	window.history.back();
}
function PsmobanShowMenu()
{
	var derection = arguments[0] ? arguments[0] : 'right';	
	if(parseInt($(".J_page").css(derection))<=0){
		if(derection == 'right')
		{
			$(".J_page").animate({right:275 , left:-275},"fast").css({"display":"block" , "height":"100%","overflow":"hidden",'position':'absolute'});
			$(".J_menus").animate({right:0},"fast");
		}
		else
		{
			$(".J_page").animate({right:-275 , left:275},"fast").css({"display":"block" , "height":"100%","overflow":"hidden",'position':'absolute'});
			$(".J_menus").animate({left:0},"fast");
		}
		$('.J_masker').show();
		$('html').addClass('open-hide-layer');
	}
	else
	{
		if(derection == 'right')
		{
			$(".J_menus").animate({right: -275 },"fast");
		}
		else
		{
			$(".J_menus").animate({left: -275 },"fast");
		}
		$(".J_page").animate({right:0 , left : 0 },"fast").css({"display":"block" ,"overflow":"hidden","position": "static"});
		$('.J_masker').hide();
		$('html').removeClass('open-hide-layer');
	}
}