$(function() {
    //配置变量
    var config = {
        showNum : 1,    //设置滚动的显示个数
        autoScroll : true,    //是否自动滚动，默认为 false
        autoScrollInterval : 5000    //自动滚动间隔，默认为 3000 毫秒，autoScroll = true 时才有效
    }
    
    var scrollUlWidth = $('.scroll_ul li').outerWidth(true),    //单个 li 的宽度
        scrollUlLeft = 0,    //.scroll_ul 初使化时的 left 值为 0
        prevAllow = true,    //为了防止连续点击上一页按钮
        nextAllow = true;    //为了防止连续点击下一页按钮
        
        
    //计算父容量限宽
    $('.scroll_list').width(config.showNum * scrollUlWidth);

    //点击上一页
    $('#prev').click(function() {
        if (prevAllow) {
            prevAllow = false;
            scrollUlLeft = scrollUlLeft - scrollUlWidth;
            $('.scroll_ul').css('left', scrollUlLeft);
            //复制最后一个 li 并插入到 ul 的最前面
            $('.scroll_ul li:last').clone().prependTo('.scroll_ul');
            //删除最后一个 li
            $('.scroll_ul li:last').remove();
            $('.scroll_ul').animate({
                left : scrollUlLeft + scrollUlWidth
            }, 300, function() {
                scrollUlLeft = parseInt($('.scroll_ul').css('left'), 10);
                prevAllow = true;
            })
        }
    });
    
    //点击下一页
    $('#next').click(function() {
        if (nextAllow) {
            nextAllow = false;
            $('.scroll_ul').animate({
                left : scrollUlLeft - scrollUlWidth
            }, 300, function() {
                scrollUlLeft = parseInt($('.scroll_ul').css('left'), 10);
                scrollUlLeft = scrollUlLeft + scrollUlWidth;
                $('.scroll_ul').css('left', scrollUlLeft);
                //复制第一个 li 并插入到 ul 的最后面
                $('.scroll_ul li:first').clone().appendTo('.scroll_ul');
                //删除第一个 li
                $('.scroll_ul li:first').remove();
                nextAllow = true;
            })
        }
    });
   
    //自动滚动
    if (config.autoScroll) {
        setInterval(function() {
            $('#next').trigger('click');
        }, config.autoScrollInterval)
    }
})