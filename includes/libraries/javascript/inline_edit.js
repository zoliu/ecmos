$(function(){
    var url = window.location.search;
    var params  = url.substr(1).split('&');
    var app = '';
    //找出排序的列和排序的方式及app控制器
    var sort  = '';
    var order = '';
    for(var j=0; j < params.length; j++)
    {
        var param = params[j];
        var arr   = param.split('=');
        if(arr[0] == 'app')
        {
            app = arr[1];
        }
        if(arr[0] == 'sort')
        {
            sort = arr[1];
        }
        if(arr[0] == 'order')
        {
            order = arr[1];
        }
    }
    $('span[ectype="order_by"]').each(function(){
        if($(this).attr('fieldname') == sort)
        {
            if(order == 'asc')
            {
            $(this).removeClass();
            $(this).addClass("sort_asc");
            }
        else if (order == 'desc')
            {
            $(this).removeClass();
            $(this).addClass("sort_desc");
            }
        }
    });
//给需要修改的位置添加修给行为
$('span[ectype="inline_edit"]').click(function(){
    var s_value  = $(this).text();
    var s_name   = $(this).attr('fieldname');
    var s_id     = $(this).attr('fieldid');
    var req      = $(this).attr('required');
    var type     = $(this).attr('datatype');
    var max      = $(this).attr('maxvalue');
    $('<input type="text">').css({border:'1px solid #ccc',width:'80%',height:'20px'})
                        .attr({value:s_value,size:5})
                        .appendTo($(this).parent())
                        .focus()
                        .select()
                        .keyup(function(event){
                        if(event.keyCode == 13)
                        {
                            if(req)
                            {
                                if(!required($(this).attr('value'),s_value,$(this)))
                                {
                                    return;
                                }
                            }
                            if(type)
                            {
                                if(!check_type(type,$(this).attr('value'),s_value,$(this)))
                                {
                                    return;
                                }
                            }
                            if(max)
                            {
                                if(!check_max($(this).attr('value'),s_value,max,$(this)))
                                {
                                    return;
                                }
                            }
                            $(this).prev('span').show().text($(this).attr("value"));
                            $.get('index.php?app='+app+'&act=ajax_col&ajax=1',{id:s_id,column:s_name,value:$(this).attr('value')},function(data){
                                if(data === 'false')
                                {
                                    alert(lang.name_exist);
                                    $('span[fieldname="'+s_name+'"][fieldid="'+s_id+'"]').text(s_value);
                                    return;
                                }
                            });
                            $(this).remove();
                        }
                    })
                        .blur(function(){
                        if(req)
                        {
                            if(!required($(this).attr('value'),s_value,$(this)))
                            {
                                return;
                            }
                        }
                        if(type)
                        {
                            if(!check_type(type,$(this).attr('value'),s_value,$(this)))
                            {
                                return;
                            }
                        }
                        if(max)
                        {
                            if(!check_max($(this).attr('value'),s_value,max,$(this)))
                            {
                                return;
                            }
                        }
                        $(this).prev('span').show().text($(this).attr('value'));
                        $.get('index.php?app='+app+'&act=ajax_col&ajax=1',{id:s_id,column:s_name,value:$(this).attr('value')},function(data){
                            if(data === 'false')
                                {
                                    alert(lang.name_exist);
                                    $('span[fieldname="'+s_name+'"][fieldid="'+s_id+'"]').text(s_value);
                                    return;
                                }
                        });
                        $(this).remove();
                    });
    $(this).hide();
});
//给需要修改的图片添加异步修改行为
$('img[ectype="inline_edit"]').click(function(){
                var i_id    = $(this).attr('fieldid');
                var i_name  = $(this).attr('fieldname');
                var i_src   = $(this).attr('src');
                var i_val   = ($(this).attr('fieldvalue'))== 0 ? 1 : 0;
                $.get('index.php?app='+app+'&act=ajax_col&ajax=1',{id:i_id,column:i_name,value:i_val},function(data){
                if(data === 'true')
                    {
                        if(i_src.indexOf('positive')>-1)
                        {
                            if(i_src.indexOf('disabled')>-1)
                            {
                                $('img[fieldid="'+i_id+'"][fieldname="'+i_name+'"]').attr({'src':i_src.replace('disabled','enabled'),'fieldvalue':i_val});
                            }
                            else
                            {
                                $('img[fieldid="'+i_id+'"][fieldname="'+i_name+'"]').attr({'src':i_src.replace('enabled','disabled'),'fieldvalue':i_val});
                            }
                        }
                        else if(i_src.indexOf('negative')>-1)
                        {
                            if(i_src.indexOf('enabled')>-1)
                            {
                                $('img[fieldid="'+i_id+'"][fieldname="'+i_name+'"]').attr({'src':i_src.replace('enabled','disabled'),'fieldvalue':i_val});
                            }
                            else
                            {
                                $('img[fieldid="'+i_id+'"][fieldname="'+i_name+'"]').attr({'src':i_src.replace('disabled','enabled'),'fieldvalue':i_val});
                            }
                        }
                    }
                });
});
    //给每个可编辑的小图片的父元素添加可编辑标题
    $('img[ectype="inline_edit"]').parent().attr('title',lang.editable);
    //给列表有排序行为的列添加鼠标手型效果
    $('span[ectype="order_by"]').hover(function(){$(this).css({cursor:'pointer'});},function(){});
    //给列表的每一列添加排序行为
    $('span[ectype="order_by"]').click(function(){
    var s_name = $(this).attr('fieldname');
    var found   = false;
    for(var i = 0;i < params.length;i++)
    {
        var param = params[i];
        var arr   = param.split('=');
        if('page' == arr[0])
        {
            params[i] = 'page=1';
        }
        else if('sort' == arr[0])
        {
            params[i] = 'sort'+'='+ s_name;
            found = true;
        }
        else if('order' == arr[0])
        {
            params[i] = 'order'+'='+(arr[1] =='asc' ? 'desc' : 'asc');
        }
    }
    if(!found)
    {
            params.push('sort'+'='+ s_name);
            params.push('order=asc');
    }
    if(location.pathname.indexOf('/admin/')>-1)
    {
            location.assign(SITE_URL + '/admin/index.php?' + params.join('&'));
            return;
    }
    location.assign(SITE_URL + '/index.php?' + params.join('&'));
    });
});
    //检查提交内容的必须项
    function required(str,s_value,jqobj)
    {
        if(str == '')
        {
            jqobj.prev('span').show().text(s_value);
            jqobj.remove();
            alert(lang.not_empty);
            return 0;
        }
    return 1;
    }
    //检查提交内容的类型是否合法
    function check_type(type, value, s_value, jqobj)
    {
        if(type == 'number')
        {
            if(isNaN(value))
            {
            jqobj.prev('span').show().text(s_value);
            jqobj.remove();
            alert(lang.only_number);
            return 0;
            }
        }
        if(type == 'int')
        {
            var regu = /^-{0,1}[0-9]{1,}$/;
            if(!regu.test(value))
            {
                jqobj.prev('span').show().text(s_value);
                jqobj.remove();
                alert(lang.only_int);
                return 0;
            }
        }
        if(type == 'pint')
        {
            var regu = /^[0-9]+$/;
            if(!regu.test(value))
            {
                jqobj.prev('span').show().text(s_value);
                jqobj.remove();
                alert(lang.only_pint);
                return 0;
            }
        }
        return 1;
    }
    //检查所填项的最大值
    function check_max(str,s_value,max,jqobj)
    {
        if(parseInt(str) > parseInt(max))
        {
            jqobj.prev('span').show().text(s_value);
            jqobj.remove();
            alert(lang.small+max);
            return 0;
        }
    return 1;
    }