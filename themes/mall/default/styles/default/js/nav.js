$(function(){
    var navs = $("#nav a");
    var cur_nav = $("#nav a.link").get(0);
    navs.mouseover(function(){
        var _self = this;
        navs.each(function(i){
            $(this).attr('class', this == _self ? 'link' : 'hover');
        });
    });
    navs.mouseout(function(){
        navs.each(function(i){
            $(this).attr('class', this == cur_nav ? 'link' : 'hover');
        });
    });
})