$(function(){
    // 添加店铺分类
    $("#add_sgcategory").click(function(){
        $(".sgcategory:last").after($(".sgcategory:last").clone().val(0));
    });

    // 商品分类
    gcategoryInit("gcategory");

    // 开启规格编辑器
    $('*[ectype="edit_spec"],*[ectype="add_spec"]').click(function(){
        spec_editor();
    });

    // 关闭规格
    $('*[ectype="disable_spec"]').click(function(){
        SPEC = {"spec_qty":0,"spec_name_1":"","spec_name_2":"","specs":null};
        spec_update();
    });
});

function spec_update(){

    /* spec name */
    var spec_name_1 = $('*.[ectype="spec_result"]').find('*[col="spec_name_1"]');
    var spec_name_2 = $('*.[ectype="spec_result"]').find('*[col="spec_name_2"]');
    if(SPEC.spec_name_1){
        spec_name_1.show();
        spec_name_1.text(SPEC.spec_name_1);
        spec_name_1.append('<input type="hidden" name="spec_name_1" value="' + SPEC.spec_name_1 + '" />');
    }else{
        spec_name_1.hide();
        spec_name_1.html('');
    }
    if(SPEC.spec_name_2){
        spec_name_2.show();
        spec_name_2.text(SPEC.spec_name_2);
        spec_name_2.append('<input type="hidden" name="spec_name_2" value="' + SPEC.spec_name_2 + '" />');
    }else{
        spec_name_2.hide();
        spec_name_2.html('');
    }

    /* spec item */
    $('*.[ectype="spec_result"]').find('*[ectype="data"]').remove();
    var d_spec_item = $('*.[ectype="spec_result"]').find('*[ectype="spec_item"]');
    d_spec_item.hide();
    SPEC.specs && $.each(SPEC.specs,function(i,item){
        var tpl = d_spec_item.clone(true);
        tpl.attr('ectype', 'data');
        if(SPEC.spec_name_1){
            tpl.find('*[item="spec_1"]').text(item.spec_1);
            tpl.find('*[item="spec_1"]').append('<input type="hidden" name="spec_1['+ item.spec_id +']" value="' + item.spec_1 + '" />');
        }else{
            tpl.find('*[item="spec_1"]').append('<input type="hidden" name="spec_1['+ item.spec_id +']" value="" />');
            (SPEC.spec_qty == "1" || SPEC.spec_qty == "0") && tpl.find('*[item="spec_1"]').hide();
        }
        if(SPEC.spec_name_2){
            tpl.find('*[item="spec_2"]').text(item.spec_2);
            tpl.find('*[item="spec_2"]').append('<input type="hidden" name="spec_2['+ item.spec_id +']" value="' + item.spec_2 + '" />');
        }else{
            tpl.find('*[item="spec_2"]').append('<input type="hidden" name="spec_2['+ item.spec_id +']" value="" />');
            (SPEC.spec_qty == "1" || SPEC.spec_qty == "0") && tpl.find('*[item="spec_2"]').hide();
        }
        tpl.find('*[item="price"]').append('<input type="hidden" name="price['+ item.spec_id +']" value="' + item.price + '" />' + item.price);
        tpl.find('*[item="stock"]').append('<input type="hidden" name="stock['+ item.spec_id +']" value="' + item.stock + '" />' + item.stock);
        tpl.find('*[item="sku"]').append('<input type="hidden" name="sku['+ item.spec_id +']" value="' + item.sku + '" /><input type="hidden" name="spec_id['+ item.spec_id +']" value="' + item.spec_id + '" />' + item.sku);
        tpl.show();
        d_spec_item.before(tpl);
    });

    if(SPEC.spec_qty == 0){
        $('*[ectype="no_spec"]').find('input').attr('disabled', false);
        $('*[ectype="no_spec"]').show();
        $('*[ectype="has_spec"]').find('input').attr('disabled', true);
        $('*[ectype="has_spec"]').hide();
    }else{
        $('*[ectype="no_spec"]').find('input').attr('disabled', true);
        $('*[ectype="no_spec"]').hide();
        $('*[ectype="has_spec"]').find('input').attr('disabled', false);
        $('*[ectype="has_spec"]').show();
    }
}

function drop_image(goods_file_id)
{
    if (confirm(lang.uploadedfile_drop_confirm))
        {
            var url = SITE_URL + '/index.php?app=my_goods&act=drop_image';
            $.getJSON(url, {'id':goods_file_id}, function(data){
                if (data.done)
                {
                    $('*[file_id="' + goods_file_id + '"]').remove();
                    set_cover($("#goods_images li:first-child").attr('file_id'));
                }
                else
                {
                    alert(data.msg);
                }
            });
        }
}

/* 创建规格编辑器 */
function spec_editor(){
    var hide_drop_button = function ()
    {
        $('#dialog_object_spec_editor').find('*[ectype="drop_spec_item"]').show();
        $('#dialog_object_spec_editor').find('*[ectype="drop_spec_item"]:first').hide();
    }

    /* 规格名称 */
    $('*.[ectype="spec_editor"]').find('*[col="spec_name_1"]').val(SPEC.spec_name_1);
    $('*.[ectype="spec_editor"]').find('*[col="spec_name_2"]').val(SPEC.spec_name_2);

    /* 初始化规格项 */
    $('*.[ectype="spec_editor"]').find('*[ectype="data"]').remove(); // 移除所有规格项
    var d_spec_item = $('*.[ectype="spec_editor"]').find('*[ectype="spec_item"]'); // 规格项模板
    d_spec_item.hide(); // 隐藏模板
    var spec_item; // 规格项目json数组
    if(SPEC.spec_qty ==0){
        spec_item = ['','']; // 如果没有规格则显示两行空白规格项
    }else{
        spec_item = SPEC.specs;
    }
    spec_item && $.each(spec_item,function(i,item){ // 遍历生成规格项
        var tpl = d_spec_item.clone(true); // 克隆一个规格项
        tpl.attr('ectype', 'data'); // 赋值一个ectype与规格项模板区别
        item.spec_1 && tpl.find('*[item="spec_1"]').val(item.spec_1);
        item.spec_2 && tpl.find('*[item="spec_2"]').val(item.spec_2);
        tpl.find('*[item="price"]').val(item.price);
        tpl.find('*[item="stock"]').val(item.stock);
        tpl.find('*[item="sku"]').val(item.sku);
        tpl.find('*[item="spec_id"]').val(item.spec_id);
        tpl.show();
        d_spec_item.before(tpl); // 将克隆的规格项放到模板前面，新增的规格项能按正序排列
    });

    // 创建规格编辑对话框
    var _d = DialogManager.create('spec_editor');
    _d.setTitle($('*[ectype="dialog_contents"]').attr('dialog_title'));
    _d.setContents($('*[ectype="dialog_contents"]').children().clone(true));
    _d.setStyle('add_spec');
    _d.setStyle({'padding' : '0'});
    _d.setWidth(550);
    ScreenLocker.style.opacity = 0;
    _d.show('center');
    hide_drop_button();

    // 添加规格项
    $('*[ectype="add_spec_item"]').unbind('click');
    $('*[ectype="add_spec_item"]').click(function(){
        var new_spec = $('#dialog_object_spec_editor').find('*[ectype="data"]:last').clone(true);
        new_spec.find('input[item="spec_id"]').val('');
        new_spec.insertAfter($('#dialog_object_spec_editor').find('*[ectype="data"]:last'));
        hide_drop_button();
    });

    // 删除规格项
    $('*[ectype="drop_spec_item"]').click(function(){
        $('#dialog_object_spec_editor').find('*[ectype="data"]').length > 1 && $(this).parent().parent().remove();
        hide_drop_button();
    });

    // 规格项排序
    $('*[ectype="up_spec_item"]').click(function(){
        var prev = $(this).parent().parent().prev('*[ectype="data"]').clone(true);
        $(this).parent().parent().prev('*[ectype="data"]').remove();
        $(this).parent().parent().after(prev);
        hide_drop_button();
    });
    $('*[ectype="down_spec_item"]').click(function(){
        var prev = $(this).parent().parent().next('*[ectype="data"]').clone(true);
        $(this).parent().parent().next('*[ectype="data"]').remove();
        $(this).parent().parent().before(prev);
        hide_drop_button();
    });

    // 保存规格名称和规格项
    $('*[ectype="save_spec"]').unbind('click');
    $('*[ectype="save_spec"]').click(function(){

        var bak_spec =  SPEC; // 备份

        /* 保存规格名称 */
        var spec_name_1 = $.trim($('#dialog_object_spec_editor').find('*[col="spec_name_1"]').val());
        var spec_name_2 = $.trim($('#dialog_object_spec_editor').find('*[col="spec_name_2"]').val());

        /* 规格名称是否重复和为空 */
        if(!spec_name_1 && !spec_name_2){
            alert(lang.get('spec_name_required'));
            return;
        }else{
            if(spec_name_1 == spec_name_2){
                alert(lang.get('duplicate_spec_name') + '\n' + '[' + spec_name_1+ ']');
                return;
            }
        }
        SPEC = {};
        SPEC.spec_name_1 = spec_name_1;
        SPEC.spec_name_2 = spec_name_2;

        /* 保存规格数量 */
        if(SPEC.spec_name_1 && SPEC.spec_name_2){
            SPEC.spec_qty = "2";
        }else if(!SPEC.spec_name_1 && !SPEC.spec_name_2){
            SPEC.spec_qty = "0"; // 这种情况不会出现，因前面为空检查已经返回
        }else{
            SPEC.spec_qty = "1";
        }

        /* 保存规格项 */
        var arr_spec_name = new Array(); // 累积规格项名称。检查重复
        var spec_duplicate = new Array(); // 重复的规格项
        var price_error = new Array();
        var complate = true; // 是否完成
        SPEC.specs = [];
        $('#dialog_object_spec_editor').find('*[ectype="data"]').each(function(){
            var spec_1 = SPEC.spec_name_1 ? $.trim($(this).find('*[item="spec_1"]').val()) : null;
            var spec_2 = SPEC.spec_name_2 ? $.trim($(this).find('*[item="spec_2"]').val()) : null;
            var price = $.trim($(this).find('*[item="price"]').val());
            var stock = $.trim($(this).find('*[item="stock"]').val());
            var sku = $.trim($(this).find('*[item="sku"]').val());
            var spec_id = $.trim($(this).find('*[item="spec_id"]').val());

            var valid = (spec_1 || spec_2) ? true : false; // 该行数据是否有效

            if(SPEC.spec_qty == 1){ // 一个规格
                var spec_pos = SPEC.spec_name_1 ? 1 : 2;
                eval('if(spec_' + spec_pos + ' || (!spec_' + spec_pos + ' && !price && !stock && !sku)){}else{complate = false;}');
            }else{ // 两个规格
                if((spec_1 && spec_2) || (!spec_1 && !spec_2 && !price && !stock && !sku)){

                }else{
                    complate = false;
                }
            }

            var item = [spec_1,spec_2].join(';');
            if($.inArray(item, arr_spec_name) > -1){
                if($.inArray(item, spec_duplicate) == -1){
                    spec_duplicate.push(item);
                }
            }else{
                item != ';' && arr_spec_name.push(item);
            }
            /* 判断价格非法 */
            if(isNaN(price) || price <0 || !price){
                valid && price_error.push(item);
            }
            item != ';' && SPEC.specs.push({
                'spec_1':spec_1,
                'spec_2':spec_2,
                'price':number_format(price, 2),
                'stock':number_format(stock, 0),
                'sku':sku,
                'spec_id':spec_id
                });
        });
        if(arr_spec_name.length == 0){
                complate = false;
        }
        if(complate == false){
            alert(lang.get('spec_not_complate'));
            SPEC = {};
            SPEC = bak_spec; // 还原备份
            return;
        }
        if(spec_duplicate.length>0){
            var spec_msg = '';
            $.each(spec_duplicate,function(i,val){
                spec_msg += val + '\n';
            });

            alert(lang.duplicate_spec + '\n' + spec_msg);
            SPEC = {};
            SPEC = bak_spec; // 还原备份
            return;
        }
        /* 判断价格 */
        if(price_error.length>0){
            var msg = lang.follow_spec_price_invalid + '\n';
            $.each(price_error,function(i,val){
                msg += val + '\n';
            });

            alert(msg);
            SPEC = {};
            SPEC = bak_spec; // 还原备份
            return;
        }

        // 更新显示规格项
        spec_update();

        DialogManager.close('spec_editor');

    });
}