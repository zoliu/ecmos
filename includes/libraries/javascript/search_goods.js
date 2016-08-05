$(function(){
    /* 显示全部分类 */
    $("#show_category").click(function(){
        $("ul[ectype='ul_category'] li").show();
        $(this).hide();
    });

    /* 显示全部品牌 */
    $("#show_brand").click(function(){
        $("ul[ectype='ul_brand'] li").show();
        $(this).hide();
    });

    /* 自定义价格区间 */
    $("#set_price_interval").click(function(){
        $("ul[ectype='ul_price'] li").show();
        $(this).hide();
    });

    /* 显示全部地区 */
    $("#show_region").click(function(){
        $("ul[ectype='ul_region'] li").show();
        $(this).hide();
    });

    /* 筛选事件 */
    $("ul[ectype='ul_category'] a").click(function(){
        replaceParam('cate_id', this.id);
        return false;
    });
    $("ul[ectype='ul_brand'] a").click(function(){
        replaceParam('brand', this.id);
        return false;
    });
    $("ul[ectype='ul_price'] a").click(function(){
        replaceParam('price', this.title);
        return false;
    });
    $("#search_by_price").click(function(){
        replaceParam('price', $(this).siblings("input:first").val() + '-' + $(this).siblings("input:last").val());
        return false;
    });
    $("ul[ectype='ul_region'] a").click(function(){
        replaceParam('region_id', this.id);
        return false;
    });
    $("li[ectype='li_filter'] img").click(function(){
        dropParam(this.title);
        return false;
    });
    $("[ectype='order_by']").change(function(){
        replaceParam('order', this.value);
        return false;
    });

    /* 下拉过滤器 */
    $("li[ectype='dropdown_filter_title'] a").click(function(){
        var jq_li = $(this).parents("li[ectype='dropdown_filter_title']");
        var status = jq_li.find("img").attr("src") == upimg ? 'off' : 'on';
        switch_filter(jq_li.attr("ecvalue"), status)
    });

    /* 显示方式 */
    $("[ectype='display_mode']").click(function(){
        $("[ectype='current_display_mode']").attr('class', $(this).attr('ecvalue'));
        $.setCookie('goodsDisplayMode', $(this).attr('ecvalue'));
    });
    
    //360cd.cn  seema
    //商品分类
    $("*[ectype='ul_cate'] a").click(function(){
        replaceParam('cate_id', this.id);
        return false;
    });
    //品牌
    $("*[ectype='ul_brand'] a").click(function(){
        replaceParam('brand', this.id);
        return false;
    });
    //价格
    $("*[ectype='ul_price'] a").click(function(){
        replaceParam('price', this.id);
        return false;
    });
    $("#search_by_price").click(function(){
        replaceParam('price', $(this).siblings("input:first").val() + '-' + $(this).siblings("input:last").val());
        return false;
    });
    //地区
    $("*[ectype='ul_region'] a").click(function(){
        replaceParam('region_id', this.id);
        return false;
    });
    //删除
    $(".selected-attr a").click(function(){
        dropParam(this.id);
        return false;
    });
    
    //价格
    $('.price-area .con-btn').click(function(){
        start_price = parseFloat(number_format($(this).parent().find('input[name="start_price"]').val(),0));
        end_price   = parseFloat(number_format($(this).parent().find('input[name="end_price"]').val(),0));
        
        if(start_price>=end_price){
            end_price = Number(start_price) + 200;
        }
        replaceParam('price', start_price+'-'+end_price);
        return false;
    });

    //360cd.cn  seema
});

/** 打开/关闭过滤器
 *  参数 filter 过滤器   brand | price | region
 *  参数 status 目标状态 on | off
 */
function switch_filter(filter, status)
{
    $("li[ectype='dropdown_filter_title']").attr('class', 'normal');
    $("li[ectype='dropdown_filter_title'] img").attr('src', downimg);
    $("div[ectype='dropdown_filter_content']").hide();

    if (status == 'on')
    {
        $("li[ectype='dropdown_filter_title'][ecvalue='" + filter + "']").attr('class', 'active');
        $("li[ectype='dropdown_filter_title'][ecvalue='" + filter + "'] img").attr('src', upimg);
        $("div[ectype='dropdown_filter_content'][ecvalue='" + filter + "']").show();
    }
}

/* 替换参数 */
function replaceParam(key, value)
{
    var params = location.search.substr(1).split('&');
    var found  = false;
    for (var i = 0; i < params.length; i++)
    {
        param = params[i];
        arr   = param.split('=');
        pKey  = arr[0];
        if (pKey == 'page')
        {
            params[i] = 'page=1';
        }
        if (pKey == key)
        {
            params[i] = key + '=' + value;
            found = true;
        }
    }
    if (!found)
    {
        value = transform_char(value);
        params.push(key + '=' + value);
    }
    location.assign(SITE_URL + '/index.php?' + params.join('&'));
}

/* 删除参数 */
function dropParam(key)
{
    var params = location.search.substr(1).split('&');
    for (var i = 0; i < params.length; i++)
    {
        param = params[i];
        arr   = param.split('=');
        pKey  = arr[0];
        if (pKey == 'page')
        {
            params[i] = 'page=1';
        }
        if (pKey == key)
        {
            params.splice(i, 1);
        }
    }
    location.assign(SITE_URL + '/index.php?' + params.join('&'));
}
