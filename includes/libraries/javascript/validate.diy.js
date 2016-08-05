// 手机号码验证
jQuery.validator.addMethod("mobile", function(value, element) {
    var length = value.length;
    var mobile =  /^[1][3-9][0-9]{9}$/
    return this.optional(element) || (length == 11 && mobile.test(value));
}, "手机号码格式错误");   

// 电话号码验证   
jQuery.validator.addMethod("phone", function(value, element) {
    var tel = /^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/;
    return this.optional(element) || (tel.test(value));
}, "电话号码格式错误");

// 邮政编码验证   
jQuery.validator.addMethod("zipCode", function(value, element) {
    var tel = /^[0-9]{6}$/;
    return this.optional(element) || (tel.test(value));
}, "邮政编码格式错误");

// QQ号码验证   
jQuery.validator.addMethod("qq", function(value, element) {
    var tel = /^[1-9]\d{4,9}$/;
    return this.optional(element) || (tel.test(value));
}, "qq号码格式错误");

// IP地址验证
jQuery.validator.addMethod("ip", function(value, element) {
    var ip = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
    return this.optional(element) || (ip.test(value) && (RegExp.$1 < 256 && RegExp.$2 < 256 && RegExp.$3 < 256 && RegExp.$4 < 256));
}, "Ip地址格式错误");

// 字母和数字的验证
jQuery.validator.addMethod("charNum", function(value, element) {
    var chrnum = /^([a-zA-Z0-9]+)$/;
    return this.optional(element) || (chrnum.test(value));
}, "只能输入数字和字母(字符A-Z, a-z, 0-9)");
// 用户信息验证，可以是中英文，邮箱，手机
jQuery.validator.addMethod("isUser", function(value, element) {
    var chrnum = /^(([a-zA-Z][a-zA-Z0-9]+)|([1][3-9][0-9]{9})|([a-zA-Z0-9_\.]+@[a-zA-Z0-9-]+[\.a-zA-Z])+)$/;
    return this.optional(element) || (chrnum.test(value));
}, "可以输入手机号码或者邮箱或英文，为英文时第一个字符必须是英文");
// 包含汉字，英文，中文验证
jQuery.validator.addMethod("isCEN", function(value, element) {
    var chrnum = /^([0-9a-zA-Z\u4e00-\u9fa5]+)$/;
    return this.optional(element) || (chrnum.test(value));
}, "可以输入中英文和数字");

// 金额验证
jQuery.validator.addMethod("money", function(value, element) {
    var chrnum = /^[0-9]+[\.][0-9]{0,3}$/;
    return this.optional(element) || (chrnum.test(value));
}, "金额的格式为1.00,或1000.00");

// 中文的验证
jQuery.validator.addMethod("chinese", function(value, element) {
    var chinese = /^[\u4e00-\u9fa5]+$/;
    return this.optional(element) || (chinese.test(value));
}, "只能输入中文");
// 中文的验证

// 下拉框验证
$.validator.addMethod("selectNone", function(value, element) {
    return value == "请选择";
}, "必须选择一项");

// 字节长度验证
jQuery.validator.addMethod("byteRangeLength", function(value, element, param) {
    var length = value.length;
    for (var i = 0; i < value.length; i++) {
        if (value.charCodeAt(i) > 127) {
            length++;
        }
    }
    return this.optional(element) || (length >= param[0] && length <= param[1]);
}, $.validator.format("请确保输入的值在{0}-{1}个字节之间(一个中文字算2个字节)"));