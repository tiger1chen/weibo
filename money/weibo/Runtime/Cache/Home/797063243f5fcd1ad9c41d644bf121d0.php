<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <?php $style = M('userinfo')->where(array('uid'=>session('uid')))->getField('style'); S(array( 'type'=>'memcache', 'host'=>'127.0.0.1', 'port'=>'11211')); ?>
    	<title><?php echo (C("WEBNAME")); ?>-用户个人页</title>
	<link rel="stylesheet" href="/go/Public/Theme/<?php echo ($style); ?>/Css/nav.css" />
	<link rel="stylesheet" href="/go/Public/Theme/<?php echo ($style); ?>/Css/user.css" />
	<link rel="stylesheet" href="/go/Public/Theme/<?php echo ($style); ?>/Css/bottom.css" />
    <link rel="stylesheet" href="/go/Public/Uploadify/uploadify.css"/>
	<script type="text/javascript" src='/go/Public/Js/jquery-1.7.2.min.js'></script>
    <script type="text/javascript" src='/go/Public/Uploadify/jquery.uploadify.min.js'></script>
     <script type='text/javascript'>
       	var getMsgUrl = '<?php echo U('User/getMsg');?>';
    	var PUBLIC = "/go/Public";
    	var picURL =  "<?php echo U('Index/uploadPic');?>";
    	var sname = "<?php echo session_name();?>"
		var sid = "<?php echo session_id();?>";
		var ROOT = '/go';
		var delURL = "<?php echo U('Index/del');?>";
    </script>
	<script type='text/javascript' src='/go/Public/Js/nav.js'></script>
    <script type="text/javascript">
    	var commentUrl = "<?php echo U('Index/Comment');?>";
    	var getComment = "<?php echo U('Index/getComment');?>";
    	var keepUrl = "<?php echo U('Index/keep');?>";
    </script>
	<script type='text/javascript' src='/go/Public/Js/index.js'></script>
<!--==========顶部固定导行条==========-->

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
<!--==========内容主体==========-->
	<div style='height:40px;opcity:10'></div>
	<div id='userinfo'>
		<div class='info-list'>
			<div class='info-face'>
				<p>
					<img src="
						<?php if($user["face80"] != ''): ?>/go/<?php echo ($user['face80']); ?>
							 <?php else: ?>
								/go/Public/Images/noface.gif<?php endif; ?>
			
					" width='180' height='180' alt="<?php echo ($user['username']); ?>" />
				</p>
				<ul>
					<li>
						<a href="<?php echo U('Follow/'.$user['uid']);?>">
							<strong><?php echo ($user['follow']); ?></strong><br/>关注
						</a>
					</li>
					<li>
						<a href="<?php echo U('Fans/'.$user['uid']);?>">
							<strong><?php echo ($user['fans']); ?></strong><br/>粉丝
						</a>
					</li>
					<li>
						<a href="<?php echo U('User/'.$user['uid']);?>">
							<strong><?php echo ($user['weibo']); ?></strong><br/>微博
						</a>
					</li>
				</ul>
			</div>
			<ul class='uinfo'>
				<li class='uname full'><?php echo ($user['username']); ?></li>
				<li class='uintro full'><?php echo ($user['intro']); ?></li>
				<li class='ulist full'>
					<ul>
						<li><i class='icon icon-<?php if($user['sex'] == '男'): ?>boy<?php else: ?>girl<?php endif; ?>'></i></li>
						<li><?php echo ($user['location']); ?></li>
						<li class='nobr'><?php echo ($user['constellation']); ?></li>
					</ul>
				</li>
			<?php if($_SESSION['uid'] == $_GET['id']): ?><li class='uedit full'>
					<a href="<?php echo U('UserSetting/index');?>">修改个人资料</a>
				</li><?php endif; ?>	
				 </ul>
		</div>
	</div>
    <div class="main">
    <!--=====左侧=====-->
        <div id="middle" class='fleft'>
     <if condition="$_SESSION['uid'] eq $_GET['id']">
        <!--微博发布框-->
        <div class='send_wrap'>
                <div class='send_title fleft'></div>
                <div class='send_prompt fright'>
                    <span>你还可以输入<span id='send_num'>140</span>个字</span>
                </div>
                <div class='send_write'>
                    <form action='<?php echo U('Index/sendWeibo');?>' method='post' name='weibo'>
                        <textarea sign='weibo' name='content'></textarea>
                        <span class='ta_right'></span>
                        <div class='send_tool'>
                            <ul class='fleft'>
                                <li title='表情'>
                                	<i class='icon icon-phiz phiz' sign='weibo'></i>
                                </li>
                                <li title='图片'><i class='icon icon-picture'></i>
                                <!--图片上传框-->
                                    <div id="upload_img" class='hidden'>
                                        <div class='upload-title'><p>本地上传</p><span class='close'></span></div>
                                        <div class='upload-btn'>
                                            <input type="hidden" name='max' value=''/>
                                            <input type="hidden" name='medium' value=''/>
                                            <input type="hidden" name='mini' value=''/>
                                            <input type="file" name='picture' id='picture'/>
                                        </div>
                                    </div>
                                <!--图片上传框-->
                                    <div id='pic-show' class='hidden'>
                                        <img src="" alt=""/>
                                    </div>
                                </li>
                            </ul>
                            <input type='submit' value='' class='send_btn fright' title='发布微博按钮'/>
                        </div>
                    </form>
                </div>
            </div>        <!--微博发布框-->
            <div class='view_line'>
                <strong>微博</strong>
            </div>
<?php if(empty($indexInfo)) : ?>
没有发布的微博
 <?php  else: ?>
<?php if(is_array($indexInfo)): foreach($indexInfo as $k=>$v): if($v['isturn']): ?><!--====================转发样式====================-->
            <div class="weibo">

                <div class="wb_cons">
                    <dl>
 <!--用户名-->
                        <dt class='author hidden'>
                            <a href="/go/User/<?php echo ($v["id"]); ?>"><?php echo ($v["username"]); ?></a>
                        </dt>
                    <!--发布内容-->
                        <dd class='content'>
                            <p><?php echo (replace_weibo($v["content"])); ?></p>
                        </dd> 
                    <!--转发的微博内容-->
                                            <dd>
                            <div class="wb_turn">
                                <dl>
                                <!--原作者-->
                                    <dt class='turn_name'>
                                        <a href="<?php echo U('User/'.$v['isturn']['uid']);?>">@<?php echo ($v['isturn']['username']); ?></a>
                                    </dt>
                                <!--原微博内容-->
                                    <dd class='turn_cons'>
                                        <p><?php echo (replace_weibo($v['isturn']['content'])); ?></p>
                                    </dd>
                                <!--原微博图片-->
  					 	<?php if($v['isturn']['max']): ?><dd>
                            <div class='turn_img'>
                            <!--小图-->
                                <img src="/go/<?php echo ($v['isturn']['mini']); ?>" class='turn_mini_img'/>
                                <div class="turn_img_tool hidden">
                                    <ul>
                                        <li>
                                            <i class='icon icon-packup'></i>
                                            <span class='packup'>&nbsp;收起</span>
                                        </li>
                                        <li>|</li>
                                        <li>
                                            <i class='icon icon-bigpic'></i>
                                            <a href="/go/<?php echo ($v['isturn']['max']); ?>" target='_blank'>&nbsp;查看大图</a>
                                        </li>
                                    </ul>
                                <!--中图-->
                                    <div class="turn_img_info"><img src="/go/<?php echo ($v['isturn']['medium']); ?>"/></div>
                                </div>
                            </div>
                        </dd><?php endif; ?>  
                                                                </dl>
                                <!--转发微博操作-->
                                <div class="turn_tool">
                                    <span class='send_time'>
                                    <?php echo (format_time($v['isturn']['time'])); ?>                                   </span>
                                    <ul>
                                        <li><a href="">转发<?php if($v['isturn']['turn']): ?>(<?php echo ($v['isturn']['turn']); ?>)<?php endif; ?></a></li>
                                        <li>|</li>
                                        <li><a href="">评论<?php if($v['isturn']['comment']): ?>(<?php echo ($v['isturn']['comment']); ?>)<?php endif; ?></a></li>
                                    </ul>
                                </div>
                            </div>
                        </dd>                    </dl>
                    <!--操作-->
                    <div class="wb_tool">
                    <!--发布时间-->
                        <span class="send_time"><?php echo (format_time($v["time"])); ?></span>
                        <ul>
		                <?php if(isset($_SESSION['uid']) && $_SESSION['uid'] == $v['uid']): ?><li class='del-li hidden'><span class='del-weibo' wid='<?php echo ($v["id"]); ?>'>删除</span></li><?php endif; ?>
                            <li class='del-li hidden'>|</li>                            
                            <li><span class='turn' id='<?php echo ($v['id']); ?>' tid='<?php echo ($v['isturn']['id']); ?>'>转发<?php if($v['turn']): ?>(<?php echo ($v['turn']); ?>)<?php endif; ?></span></li>
                            <li>|</li>
                            <li class='keep-wrap'>
                                <span class='keep' wid='<?php echo ($v['id']); ?>'>收藏<?php if($v['keep']): ?>(<?php echo ($v['keep']); ?>)<?php endif; ?></span>
                                <div class='keep-up hidden'></div>
                            </li>
                            <li>|</li>
                            <li><span class='comment' wid='<?php echo ($v['id']); ?>'>评论<?php if($v['comment']): ?>(<?php echo ($v['comment']); ?>)<?php endif; ?></span></li>
                        </ul>
                    </div>
                    <!--回复框-->
                    <div class='comment_load hidden'>
                        <img src="/go/Public/Images/loading.gif">评论加载中，请稍候...
                    </div>
                    <div class='comment_list hidden'>
                        <textarea name="" sign='comment<?php echo ($k); ?>'></textarea>
                        <ul>
                            <li class='phiz fleft' sign='comment<?php echo ($k); ?>'></li>
                            <li class='comment_turn fleft'>
                                <label>
                                    <input type="checkbox" name=''/>同时转发到我的微博
                                </label>
                            </li>
                            <li class='comment_btn fright' wid='<?php echo ($v["id"]); ?>'>评论</li>
                        </ul>

                    </div>
                    <!--回复框结束-->
                </div>
<!--====================转发样式结束====================-->
            <?php else: ?>
    <!--====================普通微博样式====================-->
            <div class="weibo">

                <div class="wb_cons">
                    <dl>
                    <!--用户名-->
                        <dt class='author hidden'>
                            <a href="/go/User/<?php echo ($v["id"]); ?>"><?php echo ($v["username"]); ?></a>
                        </dt>
                    <!--发布内容-->
                        <dd class='content'>
                            <p><?php echo (replace_weibo($v['content'])); ?></p>
                        </dd>
                    <!--微博图片-->
  					 <dd>
  					 	<?php if($v['max']): ?><div class='wb_img'>
                            <!--小图-->
                                <img src="/go/<?php echo ($v['mini']); ?>" class='mini_img'/>
                                <div class="img_tool hidden">
                                    <ul>
                                        <li>
                                            <i class='icon icon-packup'></i>
                                            <span class='packup'>&nbsp;收起</span>
                                        </li>
                                        <li>|</li>
                                        <li>
                                            <i class='icon icon-bigpic'></i>
                                            <a href="/go/<?php echo ($v['max']); ?>" target='_blank'>&nbsp;查看大图</a>
                                        </li>
                                    </ul>
                                <!--中图-->
                                    <div class="img_info"><img src="/go/<?php echo ($v['medium']); ?>"/></div>
                                </div>
                            </div>
                        </dd><?php endif; ?>                 
                   </dl>                                    
                <!--操作-->
                    <div class="wb_tool">
                    <!--发布时间-->
                        <span class="send_time"><?php echo (format_time($v["time"])); ?></span>
                        <ul>
                        
                        <?php if(isset($_SESSION['uid']) && $_SESSION['uid'] == $v['uid']): ?><li class='del-li hidden'><span class='del-weibo' wid='<?php echo ($v["id"]); ?>'>删除</span></li><?php endif; ?>
                            <li class='del-li hidden'>|</li>                            
                            <li><span class='turn' id='<?php echo ($v['id']); ?>'>转发<?php if($v['turn']): ?>(<?php echo ($v['turn']); ?>)<?php endif; ?></span></li>
                            <li>|</li>
                            <li class='keep-wrap'>
                                <span class='keep' wid='<?php echo ($v['id']); ?>'>收藏<?php if($v['keep']): ?>(<?php echo ($v['keep']); ?>)<?php endif; ?></span>
                                <div class='keep-up hidden'></div>
                            </li>
                            <li>|</li>
                            <li><span class='comment' wid='<?php echo ($v['id']); ?>'>评论<?php if($v['comment']): ?>(<?php echo ($v['comment']); ?>)<?php endif; ?></span></li>
                        </ul>
                    </div>
                <!--=====回复框=====-->
                    <div class='comment_load hidden'>
                        <img src="/go/Public/Images/loading.gif">评论加载中，请稍候...
                    </div>
                    <div class='comment_list hidden'>
                        <textarea name="" sign='comment<?php echo ($k); ?>'></textarea>
                        <ul>
                            <li class='phiz fleft' sign='comment<?php echo ($k); ?>'></li>
                            <li class='comment_turn fleft'>
                                <label>
                                    <input type="checkbox" name=''/>同时转发到我的微博
                                </label>
                            </li>
                            <li class='comment_btn fright' wid='<?php echo ($v["id"]); ?>' uid='<?php echo ($v["uid"]); ?>'>评论</li>
                        </ul>
                    </div>
                <!--=====回复框结束=====-->
                </div><?php endif; ?>
            </div><?php endforeach; endif; ?>
                <div id='page'> <?php echo ($show); ?>         </div>
               
        <?php endif;?>
        </div>
        <!--==========右侧==========-->
        <div id='right'>
        	<dl>
        		<dt>我的关注(<?php echo ($user["follow"]); ?>) </dt> 
        		<?php if(is_array($follow)): foreach($follow as $key=>$v): ?><dd>
        			<a href="<?php echo U('User/'.$v['uid']);?>">
        				<img src="
		        		      <?php if($v['face']): ?>/go/<?php echo ($v['face']); ?> 
		                      <?php else: ?>
		                       /go/Public/Images/noface.gif<?php endif; ?>
        				" alt="<?php echo ($v["username"]); ?>" width='50' height='50'/>
        			</a>
        			<p>
        				<a href="<?php echo U('User/'.$v['uid']);?>"><?php echo ($v["username"]); ?></a>
        			</p>
        		</dd><?php endforeach; endif; ?>
        		</dl>
        	<dl>
        		<dt>我的粉丝(<?php echo ($user["fans"]); ?>)</dt>
        		<?php if(is_array($fans)): foreach($fans as $key=>$v): ?><dd>
        			<a href="<?php echo U('User/'.$v['uid']);?>">
        				<img src="
        					 <?php if($v['face']): ?>/go/<?php echo ($v['face']); ?> 
		                      <?php else: ?>
		                       /go/Public/Images/noface.gif<?php endif; ?>
        				" alt="<?php echo ($v["username"]); ?>" width='50' height='50'/>
        			</a>
        			<p>
        				<a href="<?php echo U('User/'.$v['uid']);?>"><?php echo ($v["username"]); ?></a>
        			</p>
        		</dd><?php endforeach; endif; ?>
        		</dl>
        </div>
    </div>
<!--==========内容主体结束==========-->
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