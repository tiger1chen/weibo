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
	},"必须以字母开头：5-17 字母、数字、下划线'_'");
	$('form[name=register]').validate({
		errorElement:"span",
		rules:{
			account:{
				required:true,
				user:true,
				remote:{
					url:checkAccount,
					type:'post',
					dataType:'json',
					data:{
						account:function(){
							return $('#account').val();
						}
					}
				}
			},
			pwd:{
				required:true,
				user:true
			},
			pwded:{
				equalTo:"#pwd"
			},
			uname:{
				required:true,
				rangelength:[5,8]
			},
			verify:{
				required:true,
				remote:{
					url:checkVerify,
					type:'post',
					dataType:'json',
					data:{
						verify:function(){
							return $('#verify').val();
						}
					}
				}
			}
		},
		messages:{
			account:{
				required:'账号不能为空',
				remote:'用户已经存在'
			},
			pwd:{
				required:'密码不能为空'
			},
			pwded:{
				required:'请确认密码',
				equalTo:'两次密码输入不一致'
			},
			uname:{
				required:'请输入昵称',
				rangelength:'昵称的长度为5-8位'
			},
			verify:{
				required:' ',
				remote:' '
			}
		}
	});
});