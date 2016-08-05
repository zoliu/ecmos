var click_id="";//记录点击
var zsc_count = 0;//记录编辑

//查看详细信息
function zsc_show(id){
	if(!flag){
		$('#add_zsc div').remove();
		$('#add_zsc').hide();
		flag = true;
	}
	var zsc_class = $('#zsc_btninfo'+id).attr('class');
	if(zsc_class!='add'){
		$('#zsc_btninfo'+id).attr('class','add');
		click_id="";
		$('#zsc_content_'+id+' p').show();
		$('#zsc_content_'+id+' div').remove();
	}else{
		$('#zsc_btninfo'+id).attr('class','add dec');
		if(click_id!=""){
			$('#zsc_content_'+click_id+' p').show();
			$('#zsc_content_'+click_id+' div').remove();
			$('#zsc_btninfo'+click_id).attr('class','add');
		}
		click_id = id;
		var zsc_id = id;
		var url = "/index.php?g=admin&m=keyword&a=addkeyword_ajax&kid="+zsc_id;
		$.post(url,null,function(data){
			$.each(data,function(i,v){
				var zsc_html = "";
				zsc_html = "<div class=\"edit-info\">"+
						   "<input type=\"hidden\" value=\""+id+"\" id=\"zsc_iid\" />"+
						   "<ul><li>"+
						   "<span>规则命名</span>"+
						   "<input type=\"text\" class=\"text1\" value=\"\" id=\"zsc_kname\" />"+
						   "</li><li><span>关键词</span>"+
						   "<input type=\"text\" class=\"text1\" value=\"\" id=\"zsc_kword\" />"+
						   "</li></ul>"+
						   "</div>"+
						   "<div class=\"replycon-set\">"+
						   "<span>回复内容设置</span>"+
						   "<br>"+
						   "<a><input type=\"radio\" onclick=\"check_tuwen(1)\" id=\"ch_wen\" name=\"type\">文本</a>"+
						   "<a><input type=\"radio\" onclick=\"check_tuwen(2)\" id=\"ch_tu\" name=\"type\">图文</a>"+
						   "</div>"+
						   "<div class=\"clear\"  id=\"wen\">"+
						   "<div class=\"replycon\">"+
						   "<textarea id=\"zsc_kecontent\" style=\"width:695px;height:137px; border:#ccc solid 1px;\"></textarea>"+
						   "</div>"+
						   "<div style=\" padding-top:45px; clear:both;\">"+
						   "<a href=\"javascript:btnfalse(1);\" class=\"save cancel\"></a>"+
						   "<div onclick=\"btnsave(1,1)\" class=\"updatebtn\" style=\"cursor:pointer;\"></div>"+
						   "</div>"+
						   "</div>"+
						   //图文信息
						   "<div class=\"clear\" id=\"tu\">"+
						   "<div class=\"tuwen\">"+
						   "<div class=\"tuwen_left\">"+
						   "<a href=\"javascript:;\" class=\"imgcon\">"+
						   "<img id=\"zsc_imgs0\" src=\"static/weixin/images/dingcan/bigimg.jpg\" width=\"295\" height=\"126\"/>"+
						   "<span id=\"zsc_titles0\">标题</span>"+
						   "<b class=\"modify\"><img id=\"zsc_simg0\""+
						   " src=\"static/weixin/images/dingcan/op-modify.png\" onclick=\"zsc_editimg(0)\"/></b>"+
						   
						   "<input type=\"hidden\" value=\"\" class=\"addtitle\" id=\"zsc_t0\"/>"+
						   "<input type=\"hidden\" value=\"\" class=\"addimg\" id=\"zsc_i0\"/>"+
						   "<input type=\"hidden\" value=\"\" class=\"addurls\" id=\"zsc_urls0\" />"+
						   
						   "</a>"+
						   "<div style=\"width:295px;\" id=\"zsc_add1bg\">"+
						   "<img src=\"static/weixin/images/dingcan/keywords_28.jpg\" class=\"add1\" "+
						   "style=\"cursor:pointer;\" onclick=\"zsc_addimg(1)\" />"+
						   "</div>"+
						   "</div>"+
						   "<div class=\"tuwen_right\">"+
						   "<img src=\"static/weixin/images/dingcan/keywords_15.jpg\">"+
						   "<p>"+
						   "<br />"+
						   "标题：<input id=\"zsc_titles\" type=\"text\" style=\"width:277px\" class=\"text1\""+
						   "onkeyup=\"zsc_keytitle()\" >"+
						   "<br><br>"+
						   "封面：<input type=\"text\" class=\"text1\" style=\"width:199px\" id=\"zsc_imgurls\" readonly=\"readonly\"/>"+
						   "<input class=\"choseimg\" type=\"button\" value=\"选择图片\" onclick=\"choseImg()\">"+
						   "<br><br>"+
						   "链接：<input id=\"zsc_urls\" type=\"text\" style=\"width:277px\" class=\"text1\""+
						   "onkeyup=\"zsc_urlinfo()\">"+
						   "</p>"+
						   "<img src=\"static/weixin/images/dingcan/keywords_19.jpg\">"+ 
						   "</div>"+
						   "<div style=\" padding-top:45px; clear:both;\">"+
						   "<a class=\"save cancel\" href=\"javascript:btnfalse(1);\"></a>"+
						   "<div onclick=\"btnsave(2,1)\" class=\"updatebtn\" style=\"cursor:pointer;\"></div>"+
						   "</div>"+
						   "</div>"+
						   "</div>";
				$('#zsc_content_'+zsc_id+' p').hide();
				$('#zsc_content_'+zsc_id+' div').remove();
				$('#zsc_content_'+zsc_id).append(zsc_html);
				$('#zsc_kname').val(v.kename);
				$('#zsc_kword').val(v.kyword);
				if(v.type==1){
					check_tuwen(1);
					$('#zsc_kecontent').val(v.kecontent);
				}else{
					check_tuwen(2);
					$.each(v.titles,function(i,info){
						if(i>0){
							zsc_addimg(i);
							$('#zsc_imgs'+i+' span').html(info);
							$('#zsc_simg'+i).attr('src',v.imageinfo[i]);
						}else{
							$('#zsc_imgs0').attr('src',v.imageinfo[i]);
							$('#zsc_titles0').html(info);
							$('#zsc_titles').val(info);
							$('#zsc_imgurls').val(v.imageinfo[i]);
							$('#zsc_urls').val(v.linkinfo[i]);
						}
						$('#zsc_t'+i).val(info);
						$('#zsc_i'+i).val(v.imageinfo[i]);
						$('#zsc_urls'+i).val(v.linkinfo[i]);
					});
				}
			});
		},'json');
	}
}

//图文判断
function check_tuwen(num){
	if(num==1){
		$('#wen').show();
		$('#tu').hide();
		$('#ch_wen').attr('checked','checked');
	}else{
		$('#wen').hide();
		$('#tu').show();
		$('#ch_tu').attr('checked','checked');
	}
}

//图文信息新增一条
function zsc_addimg(num){
	if($('.simgcon').length<9){
		$('#zsc_add1bg').remove();
		var zsc_html = "<a href=\"javascript:;\" class=\"simgcon\" id=\"zsc_imgs"+num+"\">"+
					   "<span>标题</span>"+
					   "<img src=\"static/weixin/images/dingcan/smallimg.jpg\" id=\"zsc_simg"+num+"\" />"+
					   "<b class=\"smodify\"><img src=\"static/weixin/images/dingcan/op-modify.png\" onclick=\"zsc_editimg("+num+")\" />"+
					   "<img src=\"static/weixin/images/dingcan/op-del.png\" onclick=\"zsc_delimg("+num+")\" /></b>"+
					   "<input type=\"hidden\" value=\""+num+"\" class=\"simgcons\" />"+
					   "<input type=\"hidden\" value=\"\" id=\"zsc_urls"+num+"\" class=\"addurls\"/>"+
					   "<input type=\"hidden\" value=\"\" class=\"addimg\" id=\"zsc_i"+num+"\"/>"+
					   "<input type=\"hidden\" value=\"\" class=\"addtitle\" id=\"zsc_t"+num+"\"/>"+
					   "</a>";
		$('.tuwen_left').append(zsc_html);
		num = parseInt(num)+1;
		zsc_html = "<div style=\"width:295px;\" id=\"zsc_add1bg\">"+
				   "<img src=\"static/weixin/images/dingcan/keywords_28.jpg\" class=\"add1\" "+
				   "style=\"cursor:pointer;\" onclick=\"zsc_addimg("+num+")\" />"+
				   "</div>";
		$('.tuwen_left').append(zsc_html);
	}else{
		alert('图文信息上限！');
	}
}

//删除某一条图文信息
function zsc_delimg(num){
	if(!confirm('是否删除该图文信息')){
		return false;	
	}
	$('#zsc_imgs'+num).remove();
	$('.tuwen_right').css('top','40px');
	$('#zsc_titles').val($('#zsc_titles0').html());
	$('#zsc_urls').val($('#zsc_urls0').val());
}

//编辑某一条图文信息
function zsc_editimg(num){
	var count = 0;
	zsc_count = 0;
	for(var i=0;i<$('.simgcons').length;i++){	
		var zsc_co = $('.simgcons')[i];
		if(num==zsc_co.value){
			count = i+1;
			zsc_count = num;
		}
	}
	var num = parseInt(count)*80+40;
	$('.tuwen_right').css('top',num+'px');
	if(0==zsc_count){
		$('#zsc_titles').val($('#zsc_titles0').html());
		$('#zsc_urls').val($('#zsc_urls0').val());
		if('static/weixin/images/dingcan/bigimg.jpg'==$('#zsc_imgs0').attr('src')){
			$('#zsc_imgurls').val();
		}else{
			$('#zsc_imgurls').val($('#zsc_imgs0').attr('src'));
		}
	}else{
		$('#zsc_titles').val($('#zsc_imgs'+zsc_count+" span").html());
		$('#zsc_urls').val($('#zsc_urls'+zsc_count).val());
		var ssurl = $('#zsc_simg'+zsc_count).attr('src');
		if('static/weixin/images/dingcan/smallimg.jpg'!=ssurl){
			$('#zsc_imgurls').val($('#zsc_simg'+zsc_count).attr('src'));
		}else{
			$('#zsc_imgurls').val('');
		}
	}
}

//标题改变
function zsc_keytitle(){
	var zsc_title = $('#zsc_titles').val();
	if(0==zsc_count){
		if(""==zsc_title){
			$('#zsc_titles0').html('标题');
		}else{
			$('#zsc_titles0').html(zsc_title);
			$('#zsc_t0').val(zsc_title);
		}
	}else{
		if(""==zsc_title){
			$('#zsc_imgs'+zsc_count+" span").html('标题');
		}else{
			$('#zsc_imgs'+zsc_count+" span").html(zsc_title);
			$('#zsc_t'+zsc_count).val(zsc_title);
		}
	}
}

//链接改变
function zsc_urlinfo(){
	var zsc_url = $('#zsc_urls').val();
	if(0==zsc_count){
		$('#zsc_urls0').val(zsc_url);
	}else{
		$('#zsc_urls'+zsc_count).val(zsc_url);
	}
}

var flag = true;
//显示添加新回复
$(function(){
	$('.addreply').click(function(){
		var zsc_class = $('#zsc_btninfo'+click_id).attr('class');
		if(zsc_class!='add'){
			$('#zsc_btninfo'+click_id).attr('class','add');	
			$('#zsc_content_'+click_id+' p').show();
			$('#zsc_content_'+click_id+' div').remove();
			click_id="";
		}
		if(flag){
			var zsc_html = ""
			zsc_html = "<div><h3>添加新的回复</h3></div>"+
					   "<div class=\"edit-info\">"+
					   "<ul><li>"+
					   "<span>规则命名</span>"+
					   "<input type=\"text\" class=\"text1\" value=\"\" id=\"zsc_kname\" />"+
					   "</li><li><span>关键词</span>"+
					   "<input type=\"text\" class=\"text1\" value=\"\" id=\"zsc_kword\" />"+
					   "</li></ul>"+
					   "</div>"+
					   "<div class=\"replycon-set\">"+
					   "<span>回复内容设置</span>"+
					   "<br>"+
					   "<a><input type=\"radio\" onclick=\"check_tuwen(1)\" id=\"ch_wen\" checked=\"checked\" name=\"type\">文本</a>"+
					   "<a><input type=\"radio\" onclick=\"check_tuwen(2)\" id=\"ch_tu\" name=\"type\">图文</a>"+
					   "</div>"+
					   "<div class=\"clear\"  id=\"wen\">"+
					   "<div class=\"replycon\">"+
					   "<textarea id=\"zsc_kecontent\" style=\"width:695px;height:137px; border:#ccc solid 1px;\"></textarea>"+
					   "</div>"+
					   "<div style=\" padding-top:45px; clear:both;\">"+
					   "<a href=\"javascript:btnfalse(0);\" class=\"save cancel\"></a>"+
					   "<div onclick=\"btnsave(1,0)\" class=\"save\" style=\"cursor:pointer;\"></div>"+
					   "</div>"+
					   "</div>"+
					   //图文信息
					   "<div class=\"clear\" id=\"tu\">"+
					   "<div class=\"tuwen\">"+
					   "<div class=\"tuwen_left\">"+
					   "<a href=\"javascript:;\" class=\"imgcon\">"+
					   "<img id=\"zsc_imgs0\" src=\"static/weixin/images/dingcan/bigimg.jpg\" width=\"295\" height=\"126\"/>"+
					   "<span id=\"zsc_titles0\">标题</span>"+
					   "<b class=\"modify\"><img id=\"zsc_simg0\""+
					   " src=\"static/weixin/images/dingcan/op-modify.png\" onclick=\"zsc_editimg(0)\"/></b>"+
					   
					   "<input type=\"hidden\" value=\"\" class=\"addtitle\" id=\"zsc_t0\"/>"+
					   "<input type=\"hidden\" value=\"\" class=\"addimg\" id=\"zsc_i0\"/>"+
					   "<input type=\"hidden\" value=\"\" class=\"addurls\" id=\"zsc_urls0\" />"+
					   
					   "</a>"+
					   "<div style=\"width:295px;\" id=\"zsc_add1bg\">"+
					   "<img src=\"static/weixin/images/dingcan/keywords_28.jpg\" class=\"add1\" "+
					   "style=\"cursor:pointer;\" onclick=\"zsc_addimg(1)\" />"+
					   "</div>"+
					   "</div>"+
					   "<div class=\"tuwen_right\">"+
					   "<img src=\"static/weixin/images/dingcan/keywords_15.jpg\">"+
					   "<p>"+
					   "<br />"+
					   "标题：<input id=\"zsc_titles\" type=\"text\" style=\"width:277px\" class=\"text1\""+
					   "onkeyup=\"zsc_keytitle()\" >"+
					   "<br><br>"+
					   "封面：<input type=\"text\" class=\"text1\" style=\"width:199px\" id=\"zsc_imgurls\" readonly=\"readonly\"/>"+
					   "<input class=\"choseimg\" type=\"button\" value=\"选择图片\" onclick=\"choseImg()\">"+
					   "<br><br>"+
					   "链接：<input id=\"zsc_urls\" type=\"text\" style=\"width:277px\" class=\"text1\""+
					   "onkeyup=\"zsc_urlinfo()\">"+
					   "</p>"+
					   "<img src=\"static/weixin/images/dingcan/keywords_19.jpg\">"+ 
					   "</div>"+
					   "<div style=\" padding-top:45px; clear:both;\">"+
					   "<a class=\"save cancel\" href=\"javascript:btnfalse(0);\"></a>"+
					   "<div onclick=\"btnsave(2,0)\" class=\"save\" style=\"cursor:pointer;\"></div>"+
					   "</div>"+
					   "</div>"+
					   "</div>";
			$('#add_zsc').append(zsc_html);
			$('#add_zsc').show();
			check_tuwen(1);
			flag = false;
		}else{
			$('#add_zsc div').remove();
			$('#add_zsc').hide();
			flag = true;
		}
	});
});

//取消按钮
function btnfalse(num){
	if(0==num){
		$('#add_zsc div').remove();
		$('#add_zsc').hide();
		flag = true;
	}else{
		var zsc_class = $('#zsc_btninfo'+click_id).attr('class');
		$('#zsc_btninfo'+click_id).attr('class','add');	
		$('#zsc_content_'+click_id+' p').show();
		$('#zsc_content_'+click_id+' div').remove();
		click_id="";
	}
}

//关闭遮罩层
function zsc_close(){
	$('.showimg').hide();
	$('.zhe').hide();
}

//弹出遮罩层
function choseImg(){
	chosenum='';
	$('.showimg').show();
	$('.zhe').show();
	$('.imgbox a').remove();
	
	var url = "/index.php?g=admin&m=keyword&a=allimages";
	$.post(url,null,function(data){
		$.each(data,function(i,v){
			$('.imgbox').append("<a href=\"javascript:choseImages('"+v.iid+"');\"><img src=\""+v.imgurl+"\" id=\"zsc_imgid"+v.iid+"\"/></a>");
		});
	},'json');
}

//选择图片
var chosenum;//记录选择的图片
function choseImages(iid){
	if(''!=chosenum){
		$('#zsc_imgid'+chosenum).css('borderColor','#CCC');
	}
	$('#zsc_imgid'+iid).css('borderColor','#F00');
	chosenum=iid;
}
//确定图片的选择
$(function(){
	$('#zsc_surebtn').click(function(){
		var zsc_src = $('#zsc_imgid'+chosenum).attr('src');
		$('#zsc_imgurls').val(zsc_src);
		zsc_close();
		if(0==zsc_count){
			$('#zsc_imgs0').attr('src',zsc_src);
			$('#zsc_i0').val(zsc_src);
		}else{
			$('#zsc_simg'+zsc_count).attr('src',zsc_src);
			$('#zsc_i'+zsc_count).val(zsc_src);
		}
	});	
});

//图片上传
function zsc_upload(){
	
	$('.loadsubmit').html('正在上传...');
	$('.loadsubmit').fadeToggle();
	var zsc_submit = $('#zsc_myform');
    zsc_submit.submit();
	
	window.setTimeout(function(){
		$('.loadsubmit').html('上传成功！');
		$('.loadsubmit').fadeToggle(1500);
		choseImg();
	},3000);
}

//添加、修改 消息
function btnsave(num,count){
	var kname = $('#zsc_kname').val();//规则
	var kword = $('#zsc_kword').val();//关键字
	if(''==kname){
		alert('请输入规则');
		return false;
	}
	if(''==kword){
		alert('请输入关键字');
		return false;
	}
	
	var ktype = num;//1:文本、2:图文
	if(ktype==1){
		var kecontent = $('#zsc_kecontent').val();//内容
		if(''==kecontent){
			alert('请输入内容');
			return false;
		}
		if(count==0){
			var url = "/index.php?g=admin&m=keyword&a=addkeyword&op=add";
			$.post(url,{kename:kname,keword:kword,kecontent:kecontent,ketype:ktype},function(data){
				alert('添加成功！');
				window.location.reload();
			});
		}else{
			var url = "/index.php?g=admin&m=keyword&a=addkeyword&op=update";
			var kid = $('#zsc_iid').val();
			$.post(url,{kename:kname,keword:kword,kecontent:kecontent,ketype:ktype,kid:kid},function(data){
				alert('修改成功！');
				window.location.reload();
			});
		}
		
	}else{
		var titles = $('.addtitle'); 
		var arrTitles = new Array();	//标题数组
		var imgurls = $('.addimg'); 	//图片路径数组
		var arrImgurls = new Array();
		var curl = $('.addurls');		//链接路径数组
		var arrCurl = new Array();
		var flag = true;
		$.each(titles,function(i,v){
			if('标题'==v.value||''==v.value||null==v.value){
				flag = false;
			}
			arrTitles[i]=v.value;
		});
		$.each(imgurls,function(i,v){
			if(''==v.value||null==v.value){
				flag = false;
			}
			arrImgurls[i]=v.value;
		});
		$.each(curl,function(i,v){
			if(''==v.value||null==v.value){
				flag = false;
			}
			arrCurl[i]=v.value;
		});
		
		if(!flag){
			alert('请输入完整信息再提交');
			return false;
		}
		
		if(count==0){
			var url = "/index.php?g=admin&m=keyword&a=addkeyword&op=add";
			
			$.post(url,{kename:kname,keword:kword,ketype:ktype,titles:arrTitles,imageinfo:arrImgurls,linkinfo:arrCurl},function(data){
				alert('添加成功！');
				window.location.reload();
			});
		}else{
			var url = "/index.php?g=admin&m=keyword&a=addkeyword&op=update";
			var kid = $('#zsc_iid').val();
			$.post(url,{kename:kname,keword:kword,ketype:ktype,titles:arrTitles,imageinfo:arrImgurls,linkinfo:arrCurl,kid:kid},
			function(data){
				alert('修改成功！');
				window.location.reload();
			});
		}
	}
}

//删除某条信息
function zscdel(num){
	if(!confirm('是否删除')){
		return false;	
	}
	var url = "/index.php?g=admin&m=keyword&a=addkeyword&op=del";
	$.post(url,{kid:num},function(data){
		alert('删除成功！');
		window.location.reload();
	});
}