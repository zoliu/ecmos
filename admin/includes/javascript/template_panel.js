/* 初始化编辑状态 */
$(function(){
    /* 使页面上的链接都无效 */
    disableLink($(document.body));

    /* 加载面板 */
    var d = DialogManager.create('loading_panel');
    d.disableClose(lang.loading_please);
    d.setTitle(lang.loading);
    d.setContents('loading', {'text' : 'loading...'});
    d.setWidth(270);
    d.show('center');
    $.get(REAL_BACKEND_URL + '/index.php?app=template&act=get_editor_panel', function(data){
        /* 在最开头插入面板 */
        $(document.body).prepend(data);

        /* 由于挂件的初始化依赖挂件信息，因此，该初始化必须要放在此处 */
        $("[widget_type='widget']").each(function(){init_widget(this);});

        /* 关闭载入对话框 */
        d.enableClose();
        DialogManager.close('loading_panel');
    });

    /* 初始化区域 */
    set_widget_area();
    $("[widget_type='area']").sortable({
        items:"[widget_type='widget']",
        connectWith: "[widget_type='area']",
        opacity:0.6,
        forcePlaceholderSize : true,
        update:set_widget_area
    }).disableSelection();

    /* 初始化配置iframe */
    $(document.body).append($('<iframe src="about:blank" style="display:none;height:0px;width:0px;" id="_config_post_iframe_" name="_config_post_iframe_"></iframe>'));
});

function set_widget_area()
{
    $("[widget_type='area']").each(function(){init_widget_area(this);});
}
/**
 *    初始挂件区域
 *
 *    @author    Garbin
 *    @param    none
 *    @return    void
 */
function init_widget_area(area)
{
    /* 为了保证样式优先级，此类样式都通过JS来控制 */
    $(area).css('border', 'yellow 1px solid');
    var _has_widget = $(area).find("[widget_type='widget']").length;
    var _has_empty_placeholder = $(area).find('.empty_widget_area').length;
    if (!_has_widget && !_has_empty_placeholder)
    {
        /* 若没有挂件且没有空占位符，则加上 */
        $(area).prepend('<div class="empty_widget_area">' + lang.empty_area_notice + '</div>');
    }
    if (_has_widget && _has_empty_placeholder)
    {
        /* 若有挂件且有空占位符，则去掉 */
        $(area).find('.empty_widget_area').remove();
    }
}

/**
 *    初始化挂件
 *
 *    @author    Garbin
 *    @param    none
 *    @return    void
 */
function init_widget(widget)
{
    if ($(widget).css('position') != 'absolute')
    {
        $(widget).css('position', 'relative');
    }
    /* 可拖动 */
    $(widget).css('cursor', 'move');

    /* 操作栏 */
    var icon_bar = $('<div class="widget_icons"></div>');
    icon_bar.append($('<span class="delete_widget"></span>').click(function(){
        var d = DialogManager.create('confirm_delete');
        d.setWidth(300);
        d.setTitle(lang.please_confirm);
        d.setContents('message', {
            type:'confirm',
            text:lang.delete_widget_confirm,
            onClickYes:function(){
                $(widget).fadeOut('slow', function(){$(widget).remove();set_widget_area();});
            }
        });
        d.show('center');
    }));

    /* 若可配置，则显示配置按钮 */

    if (__widgets[$(widget).attr('name')]['configurable'])
    {
        icon_bar.append($('<span class="config_widget"></span>').click(function(){config_widget(widget);}));
    }

    $(widget).prepend(icon_bar);
}

/**
 *    保存页面
 *
 *    @author    Garbin
 *    @return    void
 */
function save_page()
{
    var d = DialogManager.create('save_page');
    d.setTitle(lang.saving);
    d.setContents('loading', {'text' : 'saving...'});
    d.setWidth(270);
    d.show('center');

    /* 创建提交表单 */
    create_save_form();

    /* 信息POST到处理脚本并显示结果*/
    $.post(REAL_BACKEND_URL + '/index.php?app=template&act=save&page=' + __PAGE__, $('#_edit_page_form_').serialize(), function(rzt){
        d.setTitle(lang.save_successed);
        d.setContents('message', {text:rzt.msg});
    }, 'json');
}

function create_save_form()
{
    /* 清空 */
    $('#_edit_page_form_').empty();

    /* 重新生成 */
    var widgets = get_widgets_on_page();
    var config  = get_widget_config_on_page();
    for (var widget_id in widgets)
    {
        $('#_edit_page_form_').append('<input type="checkbox" checked="true" name="widgets[' + widget_id + ']" value="' + widgets[widget_id] + '" />');
    }
    for (var area in config)
    {
        for (var nk in config[area])
        {
            $('#_edit_page_form_').append('<input type="checkbox" checked="true" name="config[' + area + '][]" value="' + config[area][nk] + '" />');
        }
    }
}

/**
 *    获取页面中的所有挂件集合
 *
 *    @author    Garbin
 *    @return    array
 */
function get_widgets_on_page()
{
    var rzt = {};
    $("[widget_type='widget']").each(function(k){
        rzt[$(this).attr('id')] = $(this).attr('name');
    });

    return rzt;
}

/**
 *    获取页面中所有挂件区域与挂件ID之间的关系
 *
 *    @author    Garbin
 *    @param    none
 *    @return    void
 */
function get_widget_config_on_page()
{
    var rzt = {};
    $("[widget_type='area']").each(function(k){
        var area = $(this).attr('area');
        var area_widgets = [];
        $(this).find("[widget_type='widget']").each(function(wk){
            area_widgets.push($(this).attr('id'));
        });
        rzt[area] = area_widgets;
    });

    return rzt;
}

/* 配置挂件 */
function config_widget(widget)
{
    var _id = $(widget).attr('id');
    var _name = $(widget).attr('name');
    var d = DialogManager.create('config_dialog');
    d.setTitle(lang.loading);
    d.setContents('loading', {text:'loading...'});
    d.setWidth(400);
    d.show('center');
    $.get(REAL_BACKEND_URL + '/index.php?app=template&act=get_widget_config_form&id=' + _id + '&name=' + _name + '&page=' + __PAGE__, function(rzt){
        var _form = '<div class="widget_config_form"><form enctype="multipart/form-data" method="POST" action="' + REAL_BACKEND_URL + '/index.php?app=template&act=config_widget&id='+_id+'&name='+_name+'&page='+__PAGE__+'" target="_config_post_iframe_" id="_config_widget_form_"><div class="widget_config_form_body">' + rzt + '</div><div class="dialog_buttons_bar" style="margin-top:20px;"><input type="submit" class="btn1" value="' + lang.submit + '" /><input type="reset" class="btn2" value="' + lang.reset + '" /></div></form></div>';
        d.setTitle(lang.config_widget);
        d.setContents($(_form));
        $('#_config_widget_form_').submit(function(){
            d.hide();
            /* 显示loading... */
            var _sd = DialogManager.create('config_submitting');
            _sd.setWidth(270);
            _sd.setTitle(lang.submitting);
            _sd.setContents('loading', {text:'submitting...'});
            _sd.show('center');

            /* 关闭对话框时同时关闭loading对话框 */
            d.onClose = function(){
                DialogManager.close('config_submitting');

                return true;
            };

            return true;
        });
    });
}

function add_widget(){
    /* 通过Ajax取到widget的html */
    var _self = this;
    var d = DialogManager.create('add_widget');
    d.setWidth(270);
    d.setTitle(lang.loading);
    d.setContents('loading', {text: 'loading...'});
    d.show('center');
    $.getJSON(REAL_BACKEND_URL + '/index.php?app=template&act=add_widget&name='+$(this).attr('widget_name')+'&page=' + __PAGE__, function(rzt){
        if (rzt.done)
        {
            var widget_id = rzt.retval.widget_id;
            if ($('#' + widget_id).length)
            {
                $(_self).click();
            }
            var _c = $(rzt.retval.contents);
            disableLink(_c);
            $("[widget_type='area']:first").prepend(_c);
            init_widget($('#' + widget_id));
            $(window).scrollTop($('#' + widget_id).position().top);
            set_widget_area();
            DialogManager.close('add_widget');
            $('#' + widget_id).hide();
            $('#' + widget_id).fadeIn('slow');
        }
        else
        {
            var _msg = rzt;
            if (rzt.msg)
            {
                _msg = rzt.msg;
            }
            d.setTitle(lang.error);
            d.setContents('message', {
                type : 'warning',
                text : rzt.msg
            });
        }
    });
}
/**
 *    隐藏显示面板
 *
 *    @author    Garbin
 *    @param    none
 *    @return    void
 */
function toggle_panel()
{
    if ($(this).attr('status') == 'open')
    {
        $('#template_panel .handle_top ul a').removeClass('handle_hover').addClass('handle_link');//只适合现在一个标签的时候
        $('#template_panel').css('paddingBottom', 0).find('.handle_bot, .handle_line').hide();
        $('#template_panel .handle_top').css('overflow', 'hidden');
        $(this).attr('status', 'close').html(lang.display);
    }
    else
    {
        $('#template_panel .handle_top ul a').removeClass('handle_link').addClass('handle_hover');//只适合现在一个标签的时候
        $('#template_panel').css('paddingBottom', 10).find('.handle_bot, .handle_line').show();
        $('#template_panel .handle_top').css('overflow', '');
        $(this).attr('status', 'open').html(lang.hidden);
    }
}
function disableLink(doc)
{
    /* 将所有不是锚点的a过滤掉 */
    doc.find("a[name='']").attr('href', 'javascript:void(0);').attr('target', '');
}
