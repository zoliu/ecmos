//页头搜索选项卡
shopsearch = {};
shopsearch.search = function(t) {
	$('#search_goods').removeClass('cur');
	$('#search_shop').removeClass('cur');
	if(t == 'search_goods') {
		$('#search_goods').addClass('cur');
		//$('#search_key').addClass('s');
		$('#search_type').val('goods');
	} else {
		$('#search_type').val('shop');
		$('#search_shop').addClass('cur');
	}
}
shopsearch.getkeyword = function() {
	var keyword=$('#search_key').val();
	if(keyword.length<1||keyword==''){
		keyword = "";
	} else {
		keyword = keyword.replace('-','');
	}
	return encodeURIComponent(keyword);
}
shopsearch.submit = function(){
	var keyword = shopsearch.getkeyword();
	var url = 'index.php?act=index&keyword='+keyword+'&app=search&Submit=搜索';
	if($('#search_type').val() == 'shop') {
		url = 'index.php?act=store&keyword='+keyword+'&app=search&Submit=搜索';
	}
	window.location.href = url;
	return false;
}

function search_keys_focus(){
	if(!$('#search_key').val()){
		//alert($('#search_goods').css('background-image') != 'none');
		if($('#search_goods').css('background-image') != 'none'){
			$('#search_key').removeClass('search_goods');
		}
		if($('#search_shop').css('background-image') != 'none'){
			$('#search_key').removeClass('search_shop');
		}
	}
}


$(document).ready(function(){
	//搜索框放大镜 start
	$('#search_shop').click(function(){
		shopsearch.search('search_shop');
		if(!$('#search_key').val()){
			$('#search_key').removeClass('search_goods');
			$('#search_key').addClass('search_shop');
		}
	});
	$('#search_goods').click(function(){
		shopsearch.search('search_goods');
		if(!$('#search_key').val()){
			$('#search_key').removeClass('search_shop');
			$('#search_key').addClass('search_goods');
		}
	});
	$('#search_key').focus(function(){
		if(!$('#search_key').val()){
			if($('#search_goods').css('background-image') != 'none'){
				$('#search_key').removeClass('search_goods');
			}
			if($('#search_shop').css('background-image') != 'none'){
				$('#search_key').removeClass('search_shop');
			}
		}
	});
	$('#search_key').blur(function(){
		if(!$('#search_key').val()){
			if($('#search_goods').css('background-image') != 'none'){
				$(this).removeClass('search_shop');
				$(this).addClass('search_goods');
			}
			if($('#search_shop').css('background-image') != 'none'){
				$(this).removeClass('search_goods');
				$(this).addClass('search_shop');
			}
		}
	});
	$('#search_key').focus();
});
//首页banner 大图轮播
function changeflash(obj)
{
  var obj=obj;
  var oLi=obj.find(".slider li");
  var len=oLi.length;
  var index=0;
  var adTimer;
  oLi.each(function(){
    $(this).css("display","none")
  })
  //添加数字
  var ANum = " <ul class='num'>";
  for(var i=0; i < len; i++) {
    ANum += "<li><span>"+i+"</span></li>";
  }
  ANum += "</ul>";
  obj.append(ANum);
  var oNum=obj.find(".num li");

  //数字点击
  oNum.click(function(event) {
    index =oNum.index(this);
    showImg(index);
  }).eq(0).click();

  //定时
  obj.hover(function(){
       clearInterval(adTimer);
     },function(){
       adTimer = setInterval(function(){
          showImg(index)
          index++;
        if(index==len){index=0;}
        } , 3000);
   }).trigger("mouseleave");

  //显示banner大图
  function showImg(index)
  {
    if(oLi.eq(index).is(":visible"))
    {
    }
    else
    {
      var OliBg= oLi.eq(index).children().attr("bgcolor");
      oLi.css("display","none");
      oLi.eq(index).stop(true,false).fadeIn("normal");
      oLi.eq(index).css("background-color",OliBg);
      oNum.removeClass("cur").eq(index).addClass("cur");
    }
  }

}




//菜单显示隐藏
function xianshi(obj1){
    var obj=obj1;
    obj.hover(function(){
        $(this).children("h3").addClass("hover").siblings().show();
    },function(){
        $(this).children("h3").removeClass("hover").siblings().hide();
    })
}
//二级菜单显示隐藏
function S_leverFn(obj1,obj2){
   var obj=obj1;
   var objc=obj2;
   obj.find("ul .c-item").each(function(index, element){
    var items=objc.find(".sc-item");
    $(this).hover(function(){
        items.hide()
        $(this).addClass("hover").siblings().removeClass("hover");     
       var top=$(this).position().top;
       obj2.show().animate({top:top+"px"},35);
       items.eq(index).show();   
    })
   })

}