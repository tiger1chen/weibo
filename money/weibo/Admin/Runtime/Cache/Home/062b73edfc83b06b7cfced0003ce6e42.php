<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>HDWeiBo 后台登录</title>
    <link rel="stylesheet" href="/go/apublic/Css/login.css" />
    <link rel="stylesheet" href="/go/apublic/Js/JqueryUI/jquery-ui-1.9.2.min.css" />
    <script type="text/javascript" src='/go/apublic/Js/jquery-1.8.2.min.js'></script>
    <script type="text/javascript" src='/go/apublic/Js/JqueryUI/jquery-ui-1.9.2.min.js'></script>
    <script type="text/javascript" src='/go/apublic/Js/login.js'></script>
</head>
<body>
    <div id='top'>
        <a href='http://www.houdunwang.com' target='_blank'>
            <img src='/go/apublic/Images/blogo.png' width='270' height='52'/>
        </a>
        <a href='/go/index.php' class='home'>-微博首页-</a>
    </div>
    <div id='main'>
        <div id="login">
            <p class='user_logo'><b>登录</b></p>
            <div class='login_form'>
                <form action="<?php echo U('login');?>" method="post" name="login">
                    <p>
                        <label>用户名：</label>
                        <input type="text" name="uname" class='input-big'/>
                    </p>
                    <p>
                        <label>密码：</label>
                        <input type="password" name="pwd" class='input-big'/>
                    </p>
                    <p class='verify'>
                        <span>验证码：</span>
                        <input type="text" name="verify" class='input-medium'/>
                        <img width="80" height="25" src="<?php echo U('verify');?>" id="getCode"/>
                    </p>
                    <p class='login_btn'>
                        <input type='submit' name='submit' value='' class='loginbg'/>
                    </p>
                </form>
            </div>
        </div>
    </div>
    <div id='dialog'></div>
</body>
</html>