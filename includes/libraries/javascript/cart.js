function drop_cart_item(store_id, rec_id){
    var tr = $('#cart_item_' + rec_id);
    var amount_span = $('#cart' + store_id + '_amount');
    var cart_goods_kinds = $('#cart_goods_kinds');
    $.getJSON('index.php?app=cart&act=drop&rec_id=' + rec_id, function(result){
        if(result.done){
            //删除成功
            if(result.retval.cart.quantity == 0){
                window.location.reload();    //刷新
            }
            else{
                tr.remove();        //移除
                amount_span.html(price_format(result.retval.amount));  //刷新总费用
                cart_goods_kinds.html(result.retval.cart.kinds);       //刷新商品种类
				
				// psmb
				$(".J_C_T_GoodsKinds").html(result.retval.cart.kinds);
			    $(".J_C_T_Amount").html(price_format(result.retval.amount));
				$("#cart_goods"+rec_id).remove();
				// end
                window.location.reload();    //刷新
            }
        }
    });
}
// tyioocom 批量收藏，为了避免弹出多个确认框
function batch_move_favorite(store_id,rec_id,goods_id,alt) {
	$.getJSON('index.php?app=my_favorite&act=add&type=goods&item_id=' + goods_id, function(result){
        if(result.done){
           if(alt){ // 批量收藏的时候，只弹出一次确认对话框
			   alert(result.msg);
		   }
        }
        else{
            alert(result.msg);
        }

    });
}

function move_favorite(store_id, rec_id, goods_id){
    var tr = $('#cart_item_' + rec_id);
    $.getJSON('index.php?app=my_favorite&act=add&type=goods&item_id=' + goods_id, function(result){
        //没有做收藏后的处理，比如从购物车移除
        if(result.done){
            //drop_cart_item(store_id, rec_id);
            alert(result.msg);
        }
        else{
            alert(result.msg);
        }

    });
}
function change_quantity(store_id, rec_id, spec_id, input, orig){
    var subtotal_span = $('#item' + rec_id + '_subtotal');
    var amount_b = $('#cart_amount_top');
    var amount_span = $('#cart_amount_bottom');    
    var amount_quantity = $('#cart_quantity');
    //暂存为局部变量，否则如果用户输入过快有可能造成前后值不一致的问题
    var _v = input.value;
	if(_v < 1 || isNaN(_v)) {alert(lang.invalid_quantity); $(input).val($(input).attr('orig'));return false}
	
    $.getJSON('index.php?app=cart&act=update&spec_id=' + spec_id + '&quantity=' + _v, function(result){
        if(result.done){
            //更新成功
            $(input).attr('changed', _v);
            subtotal_span.attr('amount',result.retval.subtotal);
            subtotal_span.html(price_format(result.retval.subtotal));

            /*amount_span.attr('amount',result.retval.cart_amount);
            amount_span.html(price_format(result.retval.cart_amount));
            amount_b.html(price_format(result.retval.cart_amount));
            
            amount_quantity.html(result.retval.cart_quantity);*/
            try{
                set_amount();
            }catch(ex){

            }
        }
        else{
            //更新失败
            alert(result.msg);
            $(input).val($(input).attr('changed'));
        }
    });
}
function decrease_quantity(rec_id){
    var item = $('#input_item_' + rec_id);
    var checkbox = $('input[name="rec_id['+rec_id+']"]');
    checkbox.attr("checked",true);
    var orig = Number(item.val());
    if(orig > 1){
        item.val(orig - 1);
        item.keyup();
    }
}
function add_quantity(rec_id){
    var item = $('#input_item_' + rec_id);
    var orig = Number(item.val());
    var checkbox = $('input[name="rec_id['+rec_id+']"]');
    checkbox.attr("checked",true);
    item.val(orig + 1);
    item.keyup();
}