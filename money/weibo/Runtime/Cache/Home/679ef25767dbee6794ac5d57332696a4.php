<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <?php $style = M('userinfo')->where(array('uid'=>session('uid')))->getField('style'); S(array( 'type'=>'memcache', 'host'=>'127.0.0.1', 'port'=>'11211')); ?>
    	<title><?php echo (C("WEBNAME")); ?>-我的私信</title>
	<link rel="stylesheet" href="/go/Public/Theme/<?php echo ($style); ?>/Css/nav.css" />
	<link rel="stylesheet" href="/go/Public/Theme/<?php echo ($style); ?>/Css/letter.css" />
	<link rel="stylesheet" href="/go/Public/Theme/<?php echo ($style); ?>/Css/bottom.css" />
	<script type="text/javascript" src='/go/Public/Js/jquery-1.7.2.min.js'></script>
    <script type="text/javascript" src='/go/Public/Js/nav.js'></script>
    <script type="text/javascript">
    	var delLetter="<?php echo U('User/delLetter');?>";
    	var replyUrl ="<?php echo U('User/reply');?>";
    </script>
    <script type='text/javascript' src='/go/Public/Js/letter.js'></script>
    
	 <script type='text/javascript'>
	    var delFollow = "/weibo/index.php/User/delFollow";
	    var editStyle = "/weibo/index.php/User/editStyle";
    	var getMsgUrl = '<?php echo U('User/getMsg');?>';
	</script>
<script type="text/javascript">
	var delFof = "<?php echo U('User/delFaf');?>";
	var editStyle = "<?php echo U('User/editStyle');?>";
</script>
</head>
<body>
<!--==========顶部固定导行条==========-->
    <div id='top_wrap'>
        <div id="top">
            <div class='top_wrap'>
                <div class="logo fleft"></div>
                <ul class='top_left fleft'>
                    <li class='cur_bg'><a href='/go/index.php'>首页</a></li>
                    <li><a href="<?php echo U('User/letter');?>">私信</a></li>
                    <li><a href="<?php echo U('User/comment');?>">评论</a></li>
                    <li><a href="<?php echo U('User/atme');?>">@我</a></li>
                </ul>
                <div id="search" class='fleft'>
                    <form action='<?php echo U('Search/searchUser');?>' method='get'>
                        <input type='text' name='keyword' id='sech_text' class='fleft' value='搜索微博、找人'/>
                        <input type='submit' value='' id='sech_sub' class='fleft'/>
                    </form>
                </div>
                <div class="user fleft">
                <?php
 $userinfo = M('userinfo')->where(array('uid'=>session('uid')))->find(); ?>
                    <a href="<?php echo U('User/'.$userinfo['uid']);?>"><?php echo ($userinfo["username"]); ?></a>
                </div>
                <ul class='top_right fleft'>
                    <li title='快速发微博' class='fast_send'><i class='icon icon-write'></i></li>
                    <li class='selector'><i class='icon icon-msg'></i>
                        <ul class='hidden'>
                            <li><a href="<?php echo U('User/comment');?>">查看评论</a></li>
                            <li><a href="<?php echo U('User/letter');?>">查看私信</a></li>
                            <li><a href="<?php echo U('User/keep');?>">查看收藏</a></li>
                            <li><a href="<?php echo U('User/atme');?>">查看@我</a></li>
                        </ul>
                    </li>
                    <li class='selector'><i class='icon icon-setup'></i>
                        <ul class='hidden'>
                            <li><a href="<?php echo U('UserSetting/index');?>">帐号设置</a></li>
                            <li><a href="" class='set_model'>模版设置</a></li>
                            <li><a href="<?php echo U('Index/loginOut');?>">退出登录</a></li>
                        </ul>
                    </li>
                <!--信息推送-->
                    <li id='news' class='hidden'>
                        <i class='icon icon-news'></i>
                        <ul>
                            <li class='news_comment hidden'>
                                <a href="<?php echo U('User/comment');?>"></a>
                            </li>
                            <li class='news_letter hidden'>
                                <a href="<?php echo U('User/letter');?>"></a>
                            </li>
                            <li class='news_atme hidden'>
                                <a href="<?php echo U('User/atme');?>"></a>
                            </li>
                        </ul>
                    </li>
                <!--信息推送-->
                </ul>
            </div>
        </div>
    </div>

 <!--==========自定义模版==========-->
    <div id='model' class='hidden'>
        <div class="model_head">
            <span class="model_text">个性化设置</span>
            <span class="close fright"></span>
        </div>
        <ul>
            <li style='background:url(/go/Public/Images/default.jpg) no-repeat;' theme='default'></li>
            <li style='background:url(/go/Public/Images/style2.jpg) no-repeat;' theme='style2'></li>
            <li style='background:url(/go/Public/Images/style3.jpg) no-repeat;' theme='style3'></li>
            <li style='background:url(/go/Public/Images/style4.jpg) no-repeat;' theme='style4'></li>
        </ul>
        <div class='model_operat'>
            <span class='model_save'>保存</span>
            <span class='model_cancel'>取消</span>
        </div>
    </div>
<!--==========自定义模版==========--> 
    
     
    <!--==========加关注弹出框==========-->
    <?php
 $groupName = M('group')->where(array('uid'=>session('uid')))->select(); ?>
    <script type="text/javascript">
    	var addFollow ="<?php echo U('UserSetting/addFollow');?>";
    </script>
        <div id='follow'>
        <div class="follow_head">
            <span class='follow_text fleft'>关注好友</span>
        </div>
        <div class='sel-group'>
            <span>好友分组：</span>
            <select name="gid">
                <option value="0">默认分组</option>
                <?php if($groupName): if(is_array($groupName)): foreach($groupName as $key=>$g): ?><option value="<?php echo ($g["id"]); ?>"><?php echo ($g["name"]); ?></option><?php endforeach; endif; endif; ?>
            </select>
        </div>
        <div class='fl-btn-wrap'>
            <input type="hidden" name='follow'/>
            <span class='add-follow-sub'>关注</span>
            <span class='follow-cencle'>取消</span>
        </div>
    </div>
<!--==========加关注弹出框==========-->
<!--==========顶部固定导行条==========-->

<!--==========顶部固定导行条==========-->
<!--==========内容主体==========-->
<div style='height:60px;opcity:10'></div>
    <div class="main">
    <!--=====左侧=====-->
    <!--=====左侧=====-->
    <div id="left" class='fleft'>
        <ul class='left_nav'>
            <li><a href="<?php echo U('Index/index');?>"><i class='icon icon-home'></i>&nbsp;&nbsp;首页</a></li>
            <li><a href="<?php echo U('User/atme');?>"><i class='icon icon-at'></i>&nbsp;&nbsp;提到我的</a></li>
            <li><a href="<?php echo U('User/comment');?>"><i class='icon icon-comment'></i>&nbsp;&nbsp;评论</a></li>
            <li><a href="<?php echo U('User/letter');?>"><i class='icon icon-letter'></i>&nbsp;&nbsp;私信</a></li>
            <li><a href="<?php echo U('User/keep');?>"><i class='icon icon-keep'></i>&nbsp;&nbsp;收藏</a></li>
        </ul>
        <div class="group">
            <fieldset><legend>分组</legend></fieldset>
            <ul> 	
            	<?php
 $groupName = M('group')->where(array('uid'=>session('uid')))->select(); ?>				
                <li><a href="<?php echo U('Index/index');?>"><i class='icon icon-group'></i>&nbsp;&nbsp;全部</a></li>
                <?php if(is_array($groupName)): foreach($groupName as $key=>$m): ?><li><a href="<?php echo U('Index/index',array('gid'=>$m['id']));?>"><i class='icon icon-group'></i>&nbsp;&nbsp;<?php echo ($m["name"]); ?></a></li><?php endforeach; endif; ?>
            </ul>
            <span id='create_group'>创建新分组</span>
        </div>
    </div>
        <!--==========创建分组==========-->
    <script type='text/javascript'>
        var addGroup = "<?php echo U('userSetting/addGroup');?>";
    </script>
    <div id='add-group'>
        <div class="group_head">
            <span class='group_text fleft'>创建好友分组</span>
        </div>
        <div class='group-name'>
            <span>分组名称：</span>
            <input type="text" name='name' id='gp-name'>
        </div>
        <div class='gp-btn-wrap'>
            <span class='add-group-sub'>添加</span>
            <span class='group-cencle'>取消</span>
        </div>
    </div>
    <!--==========创建分组==========-->
    <!--=====中部=====-->
        <div id="middle" class='fleft'>
			<p class='title'>我的私信（共<?php echo ($count); ?>条）<span class='send'>发送私信</span></p>
			<?php if(is_array($letter)): foreach($letter as $key=>$v): ?><dl>
				<dt>
					<a href="<?php echo U('User/'.$v['uid']);?>"><img src="
					  <?php if($v['face']): ?>/go/<?php echo ($v['face']); ?> 
                      <?php else: ?>
                       /go/Public/Images/noface.gif<?php endif; ?>
					" width='50' height='50'/></a>
				</dt>
				<dd>
					<a href="<?php echo U('User/'.$v['uid']);?>"><?php echo ($v['username']); ?></a>：
					<span><?php echo ($v['content']); ?></span>
				</dd>
				<dd class='tright'>
                    <span class="send_time"><?php echo (format_time($v['time'])); ?></span>
					<span class='del-letter' lid='<?php echo ($v['id']); ?>'>删除</span> &nbsp;|&nbsp;
					<span class='l-reply'>回复</span>
				</dd>
			</dl><?php endforeach; endif; ?>
						 <div style="text-align: center;" >  <?php echo ($show); ?>         </div>
			</div>
<!--==========右侧==========-->
       <?php $userinfo = M("userinfo")->where(array("uid"=>$_SESSION["uid"]))->find();extract($userinfo); ?><div id="right">
    <div class="edit_tpl"><a href="" class='set_model'></a></div> 
<dl class="user_face">
        <dt>
            <a href="<?php echo U('User/'.$uid);?>'">
                <img src="
				<?php if($face80 != ''): ?>/go/<?php echo ($face80); ?>
				<?php else: ?>
					/go/Public/Images/noface.gif<?php endif; ?>                	
                " width='80' height='80' alt="<?php echo ($username); ?>" />
            </a>
        </dt>
        <dd>
            <a href="<?php echo U('User/'.$uid);?>"><?php echo ($username); ?></a>
        </dd>
    </dl>
    <ul class='num_list'>
        <li><a href="<?php echo U('Follow/'.$uid);?>"><strong><?php echo ($follow); ?></strong><span>关注</span></a></li>
        <li><a href="<?php echo U('Fans/'.$uid);?>"><strong><?php echo ($fans); ?></strong><span>粉丝</span></a></li>
        <li class='noborder'>
            <a href="<?php echo U('User/'.$uid);?>"><strong><?php echo ($weibo); ?></strong><span>微博</span></a>
        </li>
    </ul>
    <div class="maybe">
        <fieldset>
        <legend>可能感兴趣的人</legend>
 		<?php $follow = M("follow")->where(array("fans"=>$_SESSION["uid"]))->field("follow")->select();foreach($follow as $k=>$v):$follow[$k] = $v["follow"];endforeach;if(!empty($follow)):$arr = implode(",",$follow); $sql = "select b.face50,b.username,b.uid,count(a.follow) f from wei_follow as a left join  wei_userinfo as b  on a.follow=b.uid where a.fans in (".$arr.") and a.follow not in (".$arr.") and a.follow <> ".session("uid")." group by a.follow order by f desc  limit 3";$row = M("follow")->query($sql);endif;foreach($row as $r):extract($r); ?><ul>
            	<li>
            		<dl>
            			<dt>
            				<a href="<?php echo U('User/'.$uid);?>"><img src="
			                     <?php if($face50): ?>/go/<?php echo ($face50); ?> 
			                      <?php else: ?>
			                       /go/Public/Images/noface.gif<?php endif; ?>	
            				" alt="" width="30" height="30"></a>
            			</dt>
            			<dd><a href="<?php echo U('User/'.$uid);?>"><?php echo ($username); ?></a></dd>
            			<dd>共<?php echo ($r['f']); ?>个共同好友关注</dd>
            		</dl>
            		<span class="heed_btn add-fl" uid=<?php echo ($uid); ?>><strong>+&nbsp;</strong>关注</span>
            	</li>
            </if>
            </ul><?php endforeach; ?>
        </fieldset>
    </div>
    <div class="post">
        <div class='post_line'>
            <span>公告栏</span>
        </div>
        <ul>
            <li><a href="">php网DIV+CSS视频教程</a></li>
            <li><a href="">php网PHP视频教程</a></li>
            <li><a href="">php网MySQL视频教程</a></li>
        </ul>
    </div>
</div> 
    </div>
<!--==========内容主体结束==========-->

<!--私信弹出框-->
<div id='letter'>
    <form action="<?php echo U('User/sendLetter');?>" method='post'>
        <div class="letter_head">
            <span class='letter_text fleft'>发送私信</span>
        </div>
        <div class='send-user'>
            用户昵称：<input type="text" name='name'/>
        </div>
        <div class='send-cons'>
            内容：<textarea name="content"></textarea>
        </div>
        <div class='lt-btn-wrap'>
            <input type="submit" value='发送' class='send-lt-sub'/>
            <span class='letter-cencle'>取消</span>
        </div>
    </form>
</div>
<!--==========底部==========-->
<!--==========底部==========-->
    <div id="bottom">
        <div class='link'>
            <dl>
                <dt>php网论坛</dt>
                <dd><a href="">让代码飞一会儿</a></dd>
                <dd><a href="">让代码飞一会儿</a></dd>
                <dd><a href="">让代码飞一会儿</a></dd>
            </dl>
            <dl>
                <dt>php网论坛</dt>
                <dd><a href="">让代码飞一会儿</a></dd>
                <dd><a href="">让代码飞一会儿</a></dd>
                <dd><a href="">让代码飞一会儿</a></dd>
            </dl>
            <dl>
                <dt>php网论坛</dt>
                <dd><a href="">让代码飞一会儿</a></dd>
                <dd><a href="">让代码飞一会儿</a></dd>
                <dd><a href="">让代码飞一会儿</a></dd>
            </dl>
            <dl>
                <dt>php网论坛</dt>
                <dd><a href="">让代码飞一会儿</a></dd>
                <dd><a href="">让代码飞一会儿</a></dd>
                <dd><a href="">让代码飞一会儿</a></dd>
            </dl>
            <dl>
                <dt>php网论坛</dt>
                <dd><a href="">让代码飞一会儿</a></dd>
                <dd><a href="">让代码飞一会儿</a></dd>
                <dd><a href="">让代码飞一会儿</a></dd>
            </dl>
        </div>
        <div id="copy">
            <div>
                <p>
                    版权所有：<?php echo (C("copy")); ?>京ICP备10027771号-1 站长统计 All rights reserved, chenriwei.com services for Beijing 2015-2030 
                </p>
            </div>
        </div>
    </div>
    
    



<!--==========转发输入框==========-->
    <div id='turn' class='hidden'>
        <div class="turn_head">
            <span class='turn_text fleft'>转发微博</span>
            <span class="close fright"></span>
        </div>
        <div class="turn_main">
            <form action="<?php echo U('Index/turn');?>" method='post' name='turn'>
                <p></p>
                <div class='turn_prompt'>
                    你还可以输入<span id='turn_num'>140</span>个字</span>
                </div>
                <textarea name='content' sign='turn'></textarea>
                <ul>
                    <li class='phiz fleft' sign='turn'></li>
                    <li class='turn_comment fleft'>
                        <label>
                            <input type="checkbox" name='becomment'/>同时评论给<span class='turn-cname'></span>
                        </label>
                    </li>
                    <li class='turn_btn fright'>
                        <input type="hidden" name='id' value=''/>
                        <input type="hidden" name='tid' value=''/>
                        <input type="submit" value='转发' class='turn_btn'/>
                    </li>
                </ul>
            </form>
        </div>
    </div>
<!--==========转发输入框==========-->

<!--==========表情选择框==========-->
    <div id="phiz" class='hidden'>
        <div>
            <p>常用表情</p>
            <span class='close fright'></span>
        </div>
        <ul>
            <li><img src="/go/Public/Images/phiz/hehe.gif" alt="呵呵" title="呵呵" /></li>
            <li><img src="/go/Public/Images/phiz/xixi.gif" alt="嘻嘻" title="嘻嘻" /></li>
            <li><img src="/go/Public/Images/phiz/haha.gif" alt="哈哈" title="哈哈" /></li>
            <li><img src="/go/Public/Images/phiz/keai.gif" alt="可爱" title="可爱" /></li>
            <li><img src="/go/Public/Images/phiz/kelian.gif" alt="可怜" title="可怜" /></li>
            <li><img src="/go/Public/Images/phiz/wabisi.gif" alt="挖鼻屎" title="挖鼻屎" /></li>
            <li><img src="/go/Public/Images/phiz/chijing.gif" alt="吃惊" title="吃惊" /></li>
            <li><img src="/go/Public/Images/phiz/haixiu.gif" alt="害羞" title="害羞" /></li>
            <li><img src="/go/Public/Images/phiz/jiyan.gif" alt="挤眼" title="挤眼" /></li>
            <li><img src="/go/Public/Images/phiz/bizui.gif" alt="闭嘴" title="闭嘴" /></li>
            <li><img src="/go/Public/Images/phiz/bishi.gif" alt="鄙视" title="鄙视" /></li>
            <li><img src="/go/Public/Images/phiz/aini.gif" alt="爱你" title="爱你" /></li>
            <li><img src="/go/Public/Images/phiz/lei.gif" alt="泪" title="泪" /></li>
            <li><img src="/go/Public/Images/phiz/touxiao.gif" alt="偷笑" title="偷笑" /></li>
            <li><img src="/go/Public/Images/phiz/qinqin.gif" alt="亲亲" title="亲亲" /></li>
            <li><img src="/go/Public/Images/phiz/shengbin.gif" alt="生病" title="生病" /></li>
            <li><img src="/go/Public/Images/phiz/taikaixin.gif" alt="太开心" title="太开心" /></li>
            <li><img src="/go/Public/Images/phiz/ldln.gif" alt="懒得理你" title="懒得理你" /></li>
            <li><img src="/go/Public/Images/phiz/youhenhen.gif" alt="右哼哼" title="右哼哼" /></li>
            <li><img src="/go/Public/Images/phiz/zuohenhen.gif" alt="左哼哼" title="左哼哼" /></li>
            <li><img src="/go/Public/Images/phiz/xiu.gif" alt="嘘" title="嘘" /></li>
            <li><img src="/go/Public/Images/phiz/shuai.gif" alt="衰" title="衰" /></li>
            <li><img src="/go/Public/Images/phiz/weiqu.gif" alt="委屈" title="委屈" /></li>
            <li><img src="/go/Public/Images/phiz/tu.gif" alt="吐" title="吐" /></li>
            <li><img src="/go/Public/Images/phiz/dahaqian.gif" alt="打哈欠" title="打哈欠" /></li>
            <li><img src="/go/Public/Images/phiz/baobao.gif" alt="抱抱" title="抱抱" /></li>
            <li><img src="/go/Public/Images/phiz/nu.gif" alt="怒" title="怒" /></li>
            <li><img src="/go/Public/Images/phiz/yiwen.gif" alt="疑问" title="疑问" /></li>
            <li><img src="/go/Public/Images/phiz/canzui.gif" alt="馋嘴" title="馋嘴" /></li>
            <li><img src="/go/Public/Images/phiz/baibai.gif" alt="拜拜" title="拜拜" /></li>
            <li><img src="/go/Public/Images/phiz/sikao.gif" alt="思考" title="思考" /></li>
            <li><img src="/go/Public/Images/phiz/han.gif" alt="汗" title="汗" /></li>
            <li><img src="/go/Public/Images/phiz/kun.gif" alt="困" title="困" /></li>
            <li><img src="/go/Public/Images/phiz/shuijiao.gif" alt="睡觉" title="睡觉" /></li>
            <li><img src="/go/Public/Images/phiz/qian.gif" alt="钱" title="钱" /></li>
            <li><img src="/go/Public/Images/phiz/shiwang.gif" alt="失望" title="失望" /></li>
            <li><img src="/go/Public/Images/phiz/ku.gif" alt="酷" title="酷" /></li>
            <li><img src="/go/Public/Images/phiz/huaxin.gif" alt="花心" title="花心" /></li>
            <li><img src="/go/Public/Images/phiz/heng.gif" alt="哼" title="哼" /></li>
            <li><img src="/go/Public/Images/phiz/guzhang.gif" alt="鼓掌" title="鼓掌" /></li>
            <li><img src="/go/Public/Images/phiz/yun.gif" alt="晕" title="晕" /></li>
            <li><img src="/go/Public/Images/phiz/beishuang.gif" alt="悲伤" title="悲伤" /></li>
            <li><img src="/go/Public/Images/phiz/zuakuang.gif" alt="抓狂" title="抓狂" /></li>
            <li><img src="/go/Public/Images/phiz/heixian.gif" alt="黑线" title="黑线" /></li>
            <li><img src="/go/Public/Images/phiz/yinxian.gif" alt="阴险" title="阴险" /></li>
            <li><img src="/go/Public/Images/phiz/numa.gif" alt="怒骂" title="怒骂" /></li>
            <li><img src="/go/Public/Images/phiz/xin.gif" alt="心" title="心" /></li>
            <li><img src="/go/Public/Images/phiz/shuangxin.gif" alt="伤心" title="伤心" /></li>
        </ul>
    </div>
<!--==========表情==========-->

<!--[if IE 6]>
    <script type="text/javascript" src="/go/Public/DD_belatedPNG_0.0.8a-min.js"></script>
    <script type="text/javascript">
        DD_belatedPNG.fix('#top','background');
        DD_belatedPNG.fix('.logo','background');
        DD_belatedPNG.fix('#sech_text','background');
        DD_belatedPNG.fix('#sech_sub','background');
        DD_belatedPNG.fix('.send_title','background');
        DD_belatedPNG.fix('.icon','background');
        DD_belatedPNG.fix('.ta_right','background');
    </script>
<![endif]-->
</body>
</html>