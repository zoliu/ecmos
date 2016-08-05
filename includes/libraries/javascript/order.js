$(function(){
   regionInit("region");
   $("select[name='shipping_id']").change(function(){
        if(this.value == 0){
            //重置
            var price = price_format(0);
            $('#shipping_desc').hide();
            $('#shipping_fee').html(price);
            $('#amount_shipping_fee').html(price);
            $('#order_amount').html(price_format(goods_amount));
        }else{
            //计算出运费及订单总价，实时显示
            var shipping_data = shippings[this.value];
            var first_price   = Number(shipping_data['first_price']);
            var step_price   = Number(shipping_data['step_price']);
            var shipping_fee = first_price + (goods_quantity - 1) * step_price;
            $('#shipping_desc').text(shipping_data['shipping_desc']).show();  //显示简介
            $('#shipping_fee').html(price_format(shipping_fee));  //显示配送费用
            $('#amount_shipping_fee').html(price_format(shipping_fee));   //显示配送费用
            $('#order_amount').html(price_format(goods_amount + shipping_fee)); //显示订单总价
        }
   });
});
function check_phone(){
    return ($('#phone_tel').val() == '' && $('#phone_mob').val() == '');
}
function hide_error(){
    $('#region').find('.error').hide();
}
