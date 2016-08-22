$(function () {

    /* 店铺详情 移动弹出 BEGIN*/
    $(".shop-info").hover(function () {
        $(".cursor").addClass("transform");
        $(this).find(".invisible").css({
            display: "block"
        }).stop(true, false).animate({
            opacity: "1"
        }, 300, 'easeInOutExpo');
    }, function () {
        $(".cursor").removeClass("transform");
        $(this).find(".invisible").css({
            display: "none"
        }).stop(true, false).animate({
            opacity: "0"
        }, 500, 'easeInOutExpo');
    });
    /* 店铺详情 移动弹出 END*/

    /* 菜单评价切换 BEGIN*/
    $(".menu-tab").click(function () {
        $(this).addClass("on").siblings().removeClass("on");
        $(".menu-panel").show().next().hide();
    });
    $(".evaluation-tab").click(function () {
        $(this).addClass("on").siblings().removeClass("on");
        $(".menu-panel").hide().next().show();
    });
    /* 菜单评价切换 END*/

    /* 产品图片移动特效 BEGIN*/
    $(".img").hover(function () {
        $(this).find(".i-shop").show();
    }, function () {
        $(this).find(".i-shop").hide();
    });
    /* 产品图片移动特效 END*/

    $(".goods").last().css("margin-bottom", "0");
    $(".commentList li").last().css("border-bottom", "none");
    
    
    $(".without-list").each(function () {
        $(this).find("li").last().css("border-bottom", "none");
    })
    $(".without-list li").hover(function () {
        $(this).addClass("on");
    }, function () {
        $(this).removeClass("on");
    });
    
    /*购物车移动显示 BEGIN*/
//    $(".count-panel").mouseenter(function() {
//        $(this).find(".i-plus,.i-add").show();
//    })
//    $(".count-panel").mouseleave(function() {
//        $(this).find(".i-plus,.i-add").hide();
//    })
    /*购物车移动显示 END*/

/* 左侧分类特效  BEGIN */
    var demo_goodsTop = [];
    var demo_itemTop = [];
    for (var i = 0; i < $(".goods").length; i++) {
        demo_goodsTop.push($(".goods").eq(i).offset().top + $(".goods").eq(i).height());
    }
    $(".item").each(function (index) {
        $(this).click(function () {
            var index = $(this).index();
            var top = $(".goods").eq(index).offset().top;
            $(this).siblings().removeClass("on");
            var scrollTop = document.documentElement.scrollTop + document.body.scrollTop;
            $(document.documentElement).animate({
                scrollTop: top
            }, 'easeInOutExpo');
            $(document.body).animate({
                scrollTop: top
            }, 'easeInOutExpo');

            setTimeout(function () {
                $(this).siblings().removeClass("on");
            }, 200)

        })
    });
    function g2_whereisGoods(j) {
        var demoPos = 0;
        if (j == demo_goodsTop[0]) {
            return 0
        }

        for (var i = 0; i < demo_goodsTop.length; i++) {
            if (j < demo_goodsTop[i]) {
                demoPos = i;
                break;
            }
        }
        return demoPos;
    }
    $(window).scroll(function () {
        var ii = g2_whereisGoods($(window).scrollTop());
        $(".menu-list .item").removeClass("on");
        $(".menu-list .item").eq(ii).addClass("on");
    })
/* 左侧分类特效  END */



    

});




//$(document).on("click", ".i-fav-not", function () {
//    var store_id = $("#store_id").val();
//    var ele = $(this);
//    $.get(SITE_URL + '/index.php?app=yunjie.default', {'act': 'add_collect_store', 'store_id': store_id, 'check': 0}, function (data) {
//        if (data == "1" || data == "-2") {
//            ele.removeClass("i-fav-not");
//            ele.addClass("i-fav");
//        } else if (data == "-1") {
//            if (confirm("您尚未登录，是否先去登录？")) {
//                window.location.href = SITE_URL + "/index.php?app=member.member&act=login";
//            }
//            return false;
//        } else {
//            alert("收藏失败");
//            return false;
//        }
//    });
//});



function clearCartLoukou() {
    if (confirm("确认清除购物车？")) {
        clearcart();
        $(".i-num").html(0);
        $(".i-num").hide();
    } else {
        return false;
    }
}



/* 左侧栏 与 右侧栏 滚动位置 BEGIN*/
var sVV = 0;
var max2Width = $(window).width();
var body2Height = $("body").height();
$(window).scroll(function () {
    sVV = $(window).scrollTop();
    function new_do_1() {
        if (sVV > body2Height - $(".right-panel").height()) {
            return;
        }
        if ($(window).scrollTop() > 225) {
            $(".right-panel").css({marginTop: sVV - $(".shop-panel").offset().top});
        } else {
            $(".right-panel").css({marginTop: 0});
        }
        if ($(window).scrollTop() > 500) {
            $(".menu-list").css({marginTop: sVV - $(".menu-sort").offset().top});
        } else {
            $(".menu-list").css({marginTop: 0});
        }
    }
    function new_do_2() {
        if ($(window).scrollTop() > 225) {
            $(".right-panel").css({position: "fixed", top: '10px'});
        } else {
            $(".right-panel").css({position: "static"});
        }
        if ($(window).scrollTop() > 500) {
            $(".menu-list").css({position: "fixed", top: '0'});
        } else {
            $(".menu-list").css({position: "absolute", top: '0'});
        }
    }
    if (max2Width > 1150) {
        new_do_2();
    } else {
        new_do_1();
    }
})
/* 左侧栏 与 右侧栏 滚动位置 END*/




