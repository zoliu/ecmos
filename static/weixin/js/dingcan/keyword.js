// JavaScript Document

var kk = 0	
var tt = 1;
var nn = 1;
var nn_wz = 1;
var now_n = 0;
var nns = [0];
var nns_wz = [0];
var isdo = false; //是否操作过
var keywords = [];
keywords[0] = [];

/*function check_reply_frm() {
			var rep_type = $("input.rep_type_" + kk + "[name='rep_type']:checked").val();
			alert
			var is_ok = true;

			var kar_name = trim($("#kar_name_" + kk).val());

			var key_word = [];
			if (kar_name == '') {
				alert('规则命名不能为空');
				return false;
			}
			var is_position = 0;
			$("input.is_position_" + kk + ":checked").each(function() {
				is_position = ($(this).val());
			});
			if (!is_position) {
				$("input.key_word_" + kk).each(function() {
					if (trim($(this).val()) != '') {
						key_word.push(trim($(this).val()));
					}

				});
				if (key_word.length <= 0) {
					alert('关键词不能为空！');
					return false;
				}
			}
			if (rep_type == 1) {
				var content = trim($("#content_" + kk).val());
				if (content == '') {
					alert('文本内容不能为空');
					return false;
				}
			} else if (rep_type == 2) {
				$("input.f_title_" + kk + "_" + tt + "[name='f_title[]']").each(function() {
					var m = $(this).attr('data');
					if (trim($(this).val()) == '') {
						edit_tw(m);
						alert('标题不能为空');
						is_ok = false;
						return false;
					}
					if (trim($('#list_tw_' + kk + '_' + tt + '_' + m + ' input[name="f_imgurl[]"]').val()) == '') {
						edit_tw(m);
						alert('图片不能为空');
						is_ok = false;
						return false;
					}

				});
				if (!is_ok) {
					return false;
				}

			}else {
				alert('请选择回复内容的类型');
				return false;
			}

			if (is_ok) {
				$("#frm_" + kk).submit();
			}
			return false;
		}*/
		
function add_keyword(){
		var new_keyword = trim($("#input_keyword_"+kk).val());
		if(new_keyword != ''){
			if(!in_array(new_keyword,keywords[kk])){
				keywords[kk].push(new_keyword);
				var html = '<div class="del-keywords"><input type="hidden" name="keyword[]" class="key_word_'+kk+'" value="'+new_keyword+'" />'+new_keyword+'<img src="images/delicon.png" style="cursor:pointer;" title="点击删除" class="del_btn" data="'+new_keyword+'" /></div>';
				
				$("#show_keywords_"+kk).append(html);
				isdo = true;
			}
			$("#input_keyword_"+kk).val('');
		}
}


function show_add_box(id,t){
	var k = parseInt(id);
	if(kk != k && isdo && !confirm("确定放弃之前的操作吗？")){
		return false;	
	}else{
		if(kk != 0){
			$("#keywdinfo_"+kk).show();
			$("#reply_box_"+kk).hide();
		}

		kk = k;
		$('#reply_box_'+kk).toggle('slow');
		tt = t;
		for(var i=1;i<=4;i++){
			$("#type_box_"+kk+"_"+i).hide();
		}
		$("#type_box_"+kk+"_"+tt).show();
		if(tt == 2 || tt == 4){
			tw_init(tt);	
		}
		isdo = false;
		return false;		
	}
	
		
}

function edit_reply(id,t){
	var kid = parseInt(id);
	if(kk != kid && isdo && !confirm("确定放弃之前的操作吗？")){
		return false;
	}
	if(kk>0){
		$("#keywdinfo_"+kk).show();
		$("#reply_box_"+kk).hide();	
	}
	if(kid>0 && kk==0){
		$("#reply_box_"+kk).hide('slow');
	}		
	kk = kid;
	$("#keywdinfo_"+kk).toggle();
	$("#reply_box_"+kk).toggle();
	
	tt = t;
	for(var i=1;i<=4;i++){
		$("#type_box_"+kk+"_"+i).hide();
	}
	$("#type_box_"+kk+"_"+tt).show();

	$("input.rep_type_"+kk+"[name='rep_type']").live('click',function(){
		if(tt!=$(this).val()){
			tt = $(this).val();
			for(var i=1;i<=4;i++){
				$("#type_box_"+kk+"_"+i).hide();
			}
			isdo=true;
			$("#type_box_"+kk+"_"+tt).show();
			if(tt == 2 || tt == 4){
				tw_init(tt);	
			}
		}
		
		});	
	if(tt == 2 || tt == 4){
		tw_init(tt);	
	}
	isdo = false;
	
	//$("#kar_name_"+kk).textlimit("span i.counter_"+kk,120);
	return false;
}


$(document).ready(function (){
	$("#type_box_"+kk+"_"+tt).show();
	$("input.rep_type_"+kk+"[name='rep_type']").live('click',function(){
			if(tt!=$(this).val()){
				tt = $(this).val();
				for(var i=1;i<=4;i++){
					$("#type_box_"+kk+"_"+i).hide();
				}
				isdo = true; 
				$("#type_box_"+kk+"_"+tt).show();
				if(tt == 2 || tt == 4){
					tw_init(tt);	
				}
			}
		
		});
	$("input[name='kar_name']").live('change',function(){ isdo = true; });
	$("input[name='is_position']").live('change',function(){ isdo = true; });
	$("input[name='rep_type']").live('change',function(){ isdo = true; });
	
	//$("#kar_name_"+kk).textlimit("span i.counter_"+kk,120);
		
});


function add_tw_list(nt){
	
	if(nns.length>=10){
		alert("总共最多十条！");
		return false;
	}else{
		var nnl = nt + nn - 1;
		
		var html='';
		html += '<div class="set-min-title" id="list_tw_'+kk+'_'+tt+'_'+nnl+'">';
		html += '<div class="set-right" style="display:none"> <img src="static/weixin/images/dingcan//keywords_15.jpg" />';
		html += '		<p>标题:<br />';
		html += '				<input name="f_title[]" type="text" data="'+nnl+'" class="input-text1 f_title_'+kk+'_'+tt+'" style="width:300px"/>';
		html += '				<br /><br />';
		html += '				封面:';
		html += '				<input name="f_imgurl[]" type="file"  class="input-text1 f_imgurl_'+kk+'_'+tt+'" style="width:200px" /> ';
		html += '				<br /><br />';
		html += '				URL:';
		html += '				 <input type="type" name="link[]" data="'+nnl+'" id="link_'+kk+'_'+tt+'_'+nnl+'" class="input-text1 f_title_'+kk+'_'+tt+'" style="width:300px"/>';
		html += '				</p>';
		html += '		<img src="static/weixin/images/dingcan/keywords_19.jpg" /> </div>';
		html += '<div onMouseOver="$(this).children(\'.info-opreation\').show();" onMouseOut="$(this).children(\'.info-opreation\').hide();">';
		html += '<div class="info-opreation info-opreation2" style="display:none"><a href="javascript:;" onClick="edit_tw('+nnl+')"><img src="static/weixin/images/dingcan/op-modify.png" title="编辑"  /></a>&nbsp;&nbsp;<a href="javascript:;" onClick="if(confirm(\'确定删除吗？\'))delete_tw('+nnl+')"><img src="static/weixin/images/dingcan/op-del.png" title="删除" /></a></div>';
		html += '<p class="f_tit">标题</p>';
		html += '<img src="static/weixin/images/dingcan/pop-up-img.jpg" width="60px" height="60px" class="f_img" />';
		html += '</div>';
		html += '</div>';
		
		$("#list_tws_"+kk+'_'+tt).append(html);
		nns.push(nnl);
		nn++;
	}
	isdo = true;
	return false;
	
}

function edit_tw(n){
	if(now_n != n){
		$('div.set-right').hide();
		now_n = n;
		$('#list_tw_'+kk+'_'+tt+'_'+n+' div.set-right').show();
	}else if(n==0){
		now_n = n;
		$('#list_tw_'+kk+'_'+tt+'_'+n+' div.set-right').show();	
	}
	
	return false;
}

//删除
function delete_tw(n){
	if(n > 0){
		$('#list_tw_'+kk+'_'+tt+'_'+n).detach();
		var len = nns.length;
		var new_nns = [];
		for(var i=0;i<len;i++){
			if(nns[i] != n ){
				new_nns.push(nns[i]);
			}
		}
		nns = new_nns;
		if(now_n == n){
			now_n = 0;
			edit_tw(now_n);
		}
		isdo = true;
	}
	return false;
}

function trim(str){
return str.replace(/(^\s*)|(\s*$)/,'');
}

function in_array(e,arr) { 
var len = arr.length;
for(var i=0;i<len;i++){
	if(arr[i] == e)
	return true;
}
return false;
}
	
$(function(){
    $('.delkeyword').click(function(){
	    //alert(11);	
		var id = $(this).attr('id');
		//alert(id);
		var url = 'ajax.php?act=delkeyword';
		$.post(url,{id:id},function(data){
		    alert('删除成功！');	
			window.location.reload();
		});
	})	
})					