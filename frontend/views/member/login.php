<?php $this->context->layout='head'; //不使用布局?>


<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="<?=\yii\helpers\Url::to('http://www.yiishop.com')?>"><img src="/images/1.png" alt="" width="200"></a></h2>
    </div>
</div>
<!-- 页面头部 end -->

<!-- 登录主体部分start -->
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">
            <form action="" method="POST" id="signupForm">
                <ul>
                    <li>
                        <label for="">用户名：</label>
                        <input type="text" class="txt" name="username" />
                        <p>3-20位字符，可由中文、字母、数字和下划线组成</p>
                    </li>
                    <li>
                        <label for="">密码：</label>
                        <input type="password" class="txt" name="password" id="password"/>
                        <p>6-20位字符，可使用字母、数字和符号的组合，不建议使用纯数字、纯字母、纯符号</p>
                    </li>
                    <li>
                        <label for="">确认密码：</label>
                        <input type="password" class="txt" name="password1" />
                        <p> <span>请再次输入密码</p>
                    </li>
                    <li>
                        <label for="">邮箱：</label>
                        <input type="text" class="txt" name="email" />
                        <p>邮箱必须合法</p>
                    </li>
                    <li>
                        <label for="">手机号码：</label>
                        <input type="text" class="txt" value="" name="tel" id="tel" placeholder=""/>
                    </li>
                    <li>
                        <label for="">短信验证码：</label>
                        <input type="text" class="txt" value="" placeholder="请输入短信验证码" name="captcha" id="captcha" /> <input type="button" onclick="bindPhoneNum(this)" id="get_captcha" value="获取验证码" style="height: 25px;padding:3px 8px"/>

                    </li>
                    <li class="checkcode">
                        <label for="">图形验证码：</label>
                        <input type="text"  name="yzm" />
                        <img id="img_captcha"src="<?=\yii\helpers\Url::to(['site/captcha'])?>" alt="" />
                        <span>看不清？<a id="change_captcha" href="javascript:;">换一张</a></span>
                    </li>

                    <li>
                        <label for="">&nbsp;</label>
                        <input type="checkbox" class="chb" checked="checked" /> 我已阅读并同意《用户注册协议》
                    </li>
                    <li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="" class="login_btn" />
                    </li>
                </ul>
            </form>


        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt15">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="/images/xin.png" alt="" /></a>
        <a href=""><img src="/images/kexin.jpg" alt="" /></a>
        <a href=""><img src="/images/police.jpg" alt="" /></a>
        <a href=""><img src="/images/beian.gif" alt="" /></a>
    </p>
</div>
<!-- 底部版权 end -->

<script src="http://static.runoob.com/assets/jquery-validation-1.14.0/lib/jquery.js"></script>
<script src="http://static.runoob.com/assets/jquery-validation-1.14.0/dist/jquery.validate.min.js"></script>
<script src="http://static.runoob.com/assets/jquery-validation-1.14.0/dist/localization/messages_zh.js"></script>
<script type="text/javascript">
    $().ready(function() {
// 在键盘按下并释放及提交后验证提交表单
        $("#signupForm").validate({
            rules: {
                username: {
                    required: true,
                    minlength: 3,
                    remote: {
                        url: "<?=\yii\helpers\Url::to(['member/validate-user'])?>",     //后台处理程序
                    }
                },
                password: {
                    required: true,
                    minlength: 5
                },
                password1: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password"
                },
                email: {
                    required: true,
                    minlength: 5,
                    isZipCode: {    //自定义验证邮箱
                        isZipCode: true
                    },
                    remote: {
                        url: "<?=\yii\helpers\Url::to(['member/validate-email'])?>",     //后台处理程序
                    }
                },
                tel: {
                    required: true,
                    minlength: 11,
                    maxlength: 11,
                    isMobile : true,
                    remote: {
                        url: "<?=\yii\helpers\Url::to(['member/validate-tel'])?>",     //后台处理程序
                    }
                },
                captcha:{
                    required: true,
                    minlength: 4,
                    maxlength:4,
                    remote: {
                        url: "<?=\yii\helpers\Url::to(['member/validate-code'])?>",     //后台处理程序
                        data:{
                            //>>获取手机号码数据
                            tel:function() {
                                return $("#tel").val();
                            }
                        }
                    }
                },
                yzm:{
                    required: true,
                    captcha_yzm:true
                }
            },
            messages: {
                username: {
                    required: "请输入用户名",
                    minlength: "用户名格式错误",
                    remote:"用户名已存在"
                },
                password: {
                    required: "请输入密码",
                    minlength: "密码长度不能小于 5 个字母"
                },
                password1: {
                    required: "请输入确认密码",
                    minlength: "密码长度不能小于 5 个字母",
                    equalTo: "确认密码错误"
                },
                email: {
                    required: "请输入邮箱",
                    minlength: "请正确输入邮箱格式",
                    remote:"邮箱已存在"
                },
                tel: {
                    required: "请输入手机号码",
                    minlength: "请正确填写您的手机号码",
                    maxlength:"请正确填写您的手机号码",
                    remote:"手机号已存在",
                    isMobile : "请正确填写您的手机号码"
                },
                captcha:{
                    required: "请输入手机验证码",
                    minlength: "请正确填写手机验证码",
                    maxlength:"请正确填写手机验证码",
                    remote:'验证码错误'
                },
                yzm:{
                    required: "请输入验证码",
                }
            },
            errorElement:'span'
        })
    });
    var hash;//>>定义保存验证的验证码哈希值
    //邮箱正则验证
    jQuery.validator.addMethod("isZipCode", function(value, element) {
        var tel = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;
        return this.optional(element) || (tel.test(value));
    }, "请正确输入邮箱格式");
    // 手机号正则码验
    jQuery.validator.addMethod("isMobile", function(value, element) {
        var length = value.length;
        var mobile = /^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/;
        return this.optional(element) || (length == 11 && mobile.test(value));
    }, "请正确填写您的手机号码");
    // 验证码验证(笛卡尔和验证)
    jQuery.validator.addMethod("captcha_yzm", function(value, element) {
        var v=value;
        var h;
        for (var i = v.length-1, h = 0 ; i>= 0 ; --i){
                h += v.charCodeAt(i);
        }
        return h==hash;
    }, "请正确填写正确的验证码");
    // 手机短信验证码
    function bindPhoneNum(){
        //启用输入框
        $('#captcha').prop('disabled',false);

        var time=30;
        var interval = setInterval(function(){
            time--;
            if(time<=0){
                clearInterval(interval);
                var html = '获取验证码';
                $('#get_captcha').prop('disabled',false);
            } else{
                var html = time + ' 秒后再次获取';
                $('#get_captcha').prop('disabled',true);
            }
            $('#get_captcha').val(html);
        },1000);
        //>>获取手机号
        var phone = $("#tel").val();
        //>>发送短信
        $.get('<?=\yii\helpers\Url::to(['member/sms'])?>',{phone:phone},function (result) {

        })
    }
    //>>点击切换验证码
    $("#change_captcha").click(function () {
        $.getJSON("<?=\yii\helpers\Url::to(['site/captcha','refresh'=>1])?>",function (json) {
            //>>改变验证码的地址
            $("#img_captcha").attr('src',json.url);
            hash = json.hash1;

        })
    });
    //>>刷新页面切换验证码
    $("#change_captcha").click();
</script>
</body>
</html>