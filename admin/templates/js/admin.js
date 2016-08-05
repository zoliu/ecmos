$(function(){
    /* 全选 */
    $('.checkall').click(function(){
        $('.checkitem').attr('checked', this.checked)
    });

    /* 批量操作按钮 */
    if($('#batchAction').length == 1){
        $('.batchButton').click(function(){
            /* 是否有选择 */
            if($('.checkitem:checked').length == 0){    //没有选择
                return false;
            }
            /* 运行presubmit */
            if($(this).attr('presubmit')){
                if(!eval($(this).attr('presubmit'))){
                    return false;
                }
            }
            /* 获取选中的项 */
            var items = '';
            $('.checkitem:checked').each(function(){
                items += this.value + ',';
            });
            items = items.substr(0, (items.length - 1));
            /* 将选中的项通过GET方式提交给指定的URI */
            var uri = $(this).attr('uri');
            window.location = uri + '&' + $(this).attr('name') + '=' + items;
        });
    }

    /* 缩小大图片 */
    $('.makesmall').each(function(){
        if(this.complete){
            makesmall(this, $(this).attr('max_width'), $(this).attr('max_height'));
        }else{
            $(this).load(function(){
                makesmall(this, $(this).attr('max_width'), $(this).attr('max_height'));
            });
        }
    });
});
function drop_confirm(msg, url){
    if(confirm(msg)){
        if(url == undefined){
            return true;
        }
        window.location = url;
    }else{
        if(url == undefined){
            return false;
        }
    }
}
function makesmall(obj,w,h){
    srcImage=obj;
    var srcW=srcImage.width;
    var srcH=srcImage.height;
    if (srcW==0)
    {
        obj.src=srcImage.src;
        srcImage.src=obj.src;
        var srcW=srcImage.width;
        var srcH=srcImage.height;
    }
    if (srcH==0)
    {
        obj.src=srcImage.src;
        srcImage.src=obj.src;
        var srcW=srcImage.width;
        var srcH=srcImage.height;
    }

    if(srcW>srcH){
        if(srcW>w){
            obj.width=newW=w;
            obj.height=newH=(w/srcW)*srcH;
        }else{
            obj.width=newW=srcW;
            obj.height=newH=srcH;
        }
    }else{
        if(srcH>h){
            obj.height=newH=h;
            obj.width=newW=(h/srcH)*srcW;
        }else{
            obj.width=newW=srcW;
            obj.height=newH=srcH;
        }
    }
    if(newW>w){
        obj.width=w;
        obj.height=newH*(w/newW);
    }else if(newH>h){
        obj.height=h;
        obj.width=newW*(h/newH);
    }
}
