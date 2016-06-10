$(function(){
	
	//验证码的刷新
	var VerifyUrl = $('#verify-img').attr('src');
	$('#verify-img').click(function(){
		 $('#verify-img').attr('src',VerifyUrl+'?'+Math.random());
	});
	
	//使用jqueyr-validate进行注册表单验证
	//添加验证方法：以字母开头：5-17 字母、数字、下划线'_'
	$.validator.addMethod("user",function(value,element){
		var tel = /^[a-zA-Z][a-zA-Z0-9_]{4,16}$/;
		return this.optional(element) ||(tel.test(value));
	}," ");
	$('form[name=login]').validate({
		errorElement:"span",
		rules:{
			account:{
				required:true,
				user:true
			},
			pwd:{
				required:true,
				user:true
			}
		},
		messages:{
			account:{
				required:' '
			},
			pwd:{
				required:' '
			}
		}
	});
});