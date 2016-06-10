$(function(){
	
	
	
	/**
	 *评论框处理
	 */
	//评论框显示
	$('.comment').toggle(function(){
		//异步加载状态DIV
		var commentLoad = $(this).parents('.wb_tool').next();
		var commentList = commentLoad.next();		
		//获取微博id
		var wid = $(this).attr('wid');
		$(this).parents('div').siblings('.comment_list').show();

		$.ajax({
			url:getComment,
			data:{"wid":wid},
			dataType:'html',
			type:'post',
			beforeSend:function(){
				commentLoad.show();
			},
			success:function(data){
				if (data != 'false') {
					commentList.append(data);
				}				
			},
			complete:function(){
				commentLoad.hide();
				commentList.show().find('textarea').val('').focus();				
			}
		});
	},function(){
		$(this).parents('.wb_tool').next().next().find('dl').remove();
		$(this).parents('div').siblings('.comment_list').hide();
	});
	//提交评论
	$('.comment_btn').click(function(){
		var commentList = $(this).parents('.comment_list');
		var _textarea = commentList.find('textarea');
		var content = _textarea.val();
		if(content == ''){
			_textarea.focus();
			return false;
		} 
		var wid = $(this).attr('wid');
		var isturn = $(this).prev().find('input:checked').val() ? 1 : 0;
		$.post(commentUrl,{"data":content,"wid":wid,"isturn":isturn},function(data){
				if(isturn){
					window.location.reload();
				}else{
					//如果添加成功则显示样式
					_textarea.val('').focus();
					commentList.find('ul').after(data);

				}
		},'html');
	}
	);
	
	//评论中的分页处理
	$('.comment_list').delegate('.comment-page dd','click',function(){
		var commentList = $(this).parents('.comment_list');
		var commentLoad = commentList.prev();
		var wid = $(this).parents('.comment-page').attr('wid');
		var page = $(this).attr('page');		
		$.ajax({
			url:getComment,
			data:{"wid":wid,"page":page},
			dataType:'html',
			type:'post',
			beforeSend:function(){
				commentList.hide().find('dl').remove();
				commentLoad.show();
			},
			success:function(data){
				if (data != 'false') {
					commentList.append(data);
				}				
			},
			complete:function(){
				commentLoad.hide();
				commentList.show().find('textarea').val('').focus();				
			}
		});
	});
	
	//收藏处理
	$('.keep').click(function(){
		var keep = $(this).next();
		var wid = $(this).attr('wid');
		$.post(
			keepUrl,
			{"wid":wid},
			function(data){
				keep.html(data).fadeIn();
				setTimeout(function(){
					keep.fadeOut();
				},3000);
			},
			'json'
		);

	});
	
	//点击:上传图片框
	$('.icon-picture').click(function(){
		$('#upload_img').show();
		$('#phiz').hide();
	});
	
	//点击关闭按钮
	$('.close').hover(function(){
		$(this).css('backgroundPosition', '-100px -200px')
	},function(){
		$(this).css('backgroundPosition', '-75px -200px')
	}).click(function(){
		$(this).parent().parent().hide();
		$('#phiz').hide();
		if ($('#turn').css('display') == 'none') {
			$('#opacity_bg').remove();
		};
	});
	
	//微博发布框效果
	$('.send_write textarea').focus(function(){
		//聚焦时边框变色
		$(this).css('borderColor', 'yellow');

		//当输入内容时
		$(this).keyup(function(){
			var content = $(this).val();
			var length = check(content);
			
			if(length[0] >= 140) {
				$(this).val(content.substring(0,Math.ceil(length[1])));
			}
			//输入文字的时候提交按钮变化
			if(length[0] > 0) {
				$('.send_btn').css('backgroundPosition', '-133px -50px');
			}else{
				$('.send_btn').css('backgroundPosition', '-50px -50px');
			}
			
			//还可以输入多少文字
			var msg = 140 - Math.ceil(length[0]);
			msg = msg > 0?msg:0;
			$('#send_num').html(msg);
		});
	}).blur(function(){
		$(this).css('borderColor', '#CCCCCC');	
	});
	
	
	
	//内容提交时处理
	$('form[name=weibo]').submit(function(){
		var cons = $('textarea',this);
		var timeOut = 0;
		if(cons.val() == ''){
			var clock = setInterval(function(){
				if(timeOut %2 == 0){
					cons.css('background','#FFA0C2');
				}else{
					cons.css('background','#fff');
				}
				timeOut++;
				if(timeOut >7) {
					clearInterval(clock);
					cons.focus();
				}
			},100);	
			return false;
		}
	});
	
	
	
	//表情框统一处理
	$('.phiz').click(function(){
		//表情框的位置
		$('#phiz').show().css({
			'left' : $(this).offset().left,
			'top' : $(this).offset().top + $(this).height() + 5
		});
		sign = $(this).attr('sign');//区别具体表情框

	});	
	
	//为每个表情添加事件  ====实验证明这个不能放在上面那个函数里面
	$('#phiz li img').click(function(){
			var con = $(this).attr('title');
			var area = $('textarea[sign='+ sign +']');
			var val = area.val();
			val +='['+con+']';
			area.val(val);
			$('#phiz').hide();
	});
	
	
	
	//点击小图，出现中图
	$('.mini_img').click(function(){
		$(this).hide().next().show();
	});
	//点击中图，出现小图
	$('.img_tool').click(function(){
		$(this).hide().prev().show();
	});
	$('.packup').click(function () {
		$(this).parent().parent().parent().hide().prev().show();
	});
	
	//转发框中的图片大小转变
	$('.turn_mini_img').click(function () {
		$(this).hide().next().show();
	});
	$('.turn_img_info img').click(function () {
		$(this).parents('.turn_img_tool').hide().prev().show();
	});
	
	
	//点击回复框:使用事件委托，因为先添加的节点用click是没有用的
	$('.comment_list').delegate('.reply a','click', function () {
		var reply = $(this).parent().siblings('a').html();
		$(this).parents('.comment_list').find('textarea').val('回复@' + reply + ' ：');
		return false;
	});
	

	//删除按钮处理
	$('.wb_tool').hover(function(){
		var del = $(this).find('.del-li');
		del.show();//展示删除按钮
	},function(){
		$(this).find('.del-li').hide();
	});
	
	$('.del-li').click(function(){
			var wid = $(this).find('.del-weibo').attr('wid');
			var obj = $(this).parents('.weibo');
			var isDel = confirm('确定要删除该条微博');
			if(isDel){
				$.post(
				 delURL,
				 {"wid":wid},
				 function(data){
				 	if(data == 1){
				 		obj.slideUp(function(){
				 			$(this).remove();
				 		});
				 	}else{
				 		alert('删除失败');
				 	}
				 },
				 'json'
				);
			}
	});
	
	
/*	//移除已关注或互相关注
	$('.list-right dd').click(function(){
		alert($(this).attr('uid'));
	});
*/
	
	
	//显示转发微博框
	$('.turn').click(function(){
		//获取原微博的内容并添加到转发框
		var org = $(this).parents('.wb_tool').prev();
		var author = $.trim(org.find('.author').html());//获取作者的名称
		var content = org.find('.content p').html();//获取作者微博文字内容
		var tid = $(this).attr('tid')?$(this).attr('tid'):0;//如果是转发微博，获取转发微博的id
		var cons = '';
		
		//出现多重转发时
		if(tid){
			cons = replace_weibo('//@'+author+' : '+content);
			author = $.trim(org.find('.turn_name').html());
			content = org.find('.turn_cons p').html();
		}
		$('form[name=turn] p').html(author+':'+content); //转发框显示内容
		$('.turn-cname').html(author);//同时评论给
		$('form[name=turn] textarea').val(cons);
		
		//提取id
		$('form[name=turn] input[name=id]').val($(this).attr('id'));
		$('form[name=turn] input[name=tid]').val(tid);
		
	 	//隐藏表情框
	 	$('#phiz').hide();
	 	//点击转发创建透明背景层
	 	createBg('opacity_bg');
	 	//定位转发框居中
	 	var turnLeft = ($(window).width() - $('#turn').width()) / 2;
	 	var turnTop = $(document).scrollTop() + ($(window).height() - $('#turn').height()) / 2;
	 	$('#turn').css({
	 		'left' : turnLeft,
	 		'top' : turnTop
	 	}).fadeIn().find('textarea').focus(function () {
	 		$(this).css('borderColor', '#FF9B00').keyup(function () {
				var content = $(this).val();
				var lengths = check(content);  //调用check函数取得当前字数
				//最大允许输入140个字
				if (lengths[0] >= 140) {
					$(this).val(content.substring(0, Math.ceil(lengths[1])));
				}
				var num = 140 - Math.ceil(lengths[0]);
				var msg = num < 0 ? 0 : num;
				//当前字数同步到显示提示
				$('#turn_num').html(msg);
			});
	 	}).focus().blur(function () {
	 		$(this).css('borderColor', '#CCCCCC');	//失去焦点时还原边框颜色
	 	});
	 });
	drag($('#turn'), $('.turn_text'));  //拖拽转发框
	
	
	
});


	//检查字符串字数
	function check(str) {
		var num = [0,140];
		for(var i=0; i< str.length;i++) {
			if(str.charCodeAt(i) > 0 && str.charCodeAt(i) <255){
				num[0] = num[0] + 0.5;
				num[1] = num[1] + 0.5;//这是为后面截取字符用的：substring是按字符来走的，所以英文的字数还打不到
				
			}else{
				num[0] ++;
			}
		}
		return num;
	}
	
	/**
	 * 替换微博内容，去除 <a> 链接与表情图片
	 */
	function replace_weibo (content) {
		content = content.replace(/<img.*?title=['"](.*?)['"].*?\/?>/ig, '[$1]');
		content = content.replace(/<a.*?>(.*?)<\/a>/ig, '$1'+' ');
		return content.replace(/<span.*?>\&nbsp;(\/\/)\&nbsp;<\/span>/ig, '$1');
	}