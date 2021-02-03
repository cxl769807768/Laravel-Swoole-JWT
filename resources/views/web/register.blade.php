<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>注册</title>
    <link rel="stylesheet" href="/asset/layuiv2/css/layui.css" media="all">
</head>
<body>
<div class="layui-row">
    <div class="layui-col-xs12 layui-col-sm12 layui-col-md12 layui-col-lg6" style="padding-top: 50px;padding-right: 20px;margin:0 auto;">
        <form class="layui-form " action="">
            <div class="layui-form-item">
                <label class="layui-form-label">用户名</label>
                <div class="layui-input-block">
                    <input type="text" name="name" required  lay-verify="required" placeholder="请输入用户名" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">手机号</label>
                <div class="layui-input-block">
                    <input type="text" name="mobile" required  lay-verify="required" placeholder="请输入手机号" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">密码</label>
                <div class="layui-input-inline">
                    <input type="password" name="password" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">确认密码</label>
                <div class="layui-input-inline">
                    <input type="password" name="password_confirmation" required lay-verify="required|confirmPass" placeholder="请输入确认密码" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">昵称</label>
                <div class="layui-input-block">
                    <input type="text" name="nickname" required  lay-verify="required" placeholder="请输入昵称" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">头像</label>
                <div class="layui-input-block">
                    <button type="button" class="layui-btn" id="avatar">
                        <i class="layui-icon">&#xe62f;</i>  上传头像
                    </button><br>
                    <img id="yl" src="" style="display: none;width: 100px;">
                    <input type="hidden" name="avatar">
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">个性签名</label>
                <div class="layui-input-block">
                    <textarea name="sign" placeholder="请输入个性签名" class="layui-textarea"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">验证码</label>
                <div class="layui-input-block">
                    <input type="text" style="width: 200px;float: left;" name="captcha" required  lay-verify="required" placeholder="验证码" autocomplete="off" class="layui-input form-control{{ $errors->has('captcha') ? ' is-invalid' : '' }}">
                    <img style="width: 150px;height: 40px;margin-left: 10px" src="{{ captcha_src('flat') }}" alt="" onclick="this.src='/captcha/flat?'+Math.random()" title="点击图片重新获取验证码">
                    @if ($errors->any())
                        <div class="alert">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="/asset/layuiv2/layui.js"></script>
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?6b614cdee352cbb9f55e05ad81084c3a";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
<script>
    layui.use('upload', function(){
        var upload = layui.upload;
        //执行实例
        upload.render({
            elem: '#avatar',
            url: 'api/uploadFile',
            field:'upload_file',
            data:{'_token':"{{ csrf_token() }}",'mod':'avatar'}
            ,before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                layer.load(); //上传loading
            }
            ,done: function(res, index, upload){
                layer.closeAll('loading'); //关闭loading
                if (res.code == 200){
                    $('#yl').attr('src',res.data).show();
                    $('input[name="avatar"]').val(res.data);
                }else{
                    layer.msg(res.msg,function(){});
                }

            }
            ,error: function(index, upload){
                layer.closeAll('loading'); //关闭loading
                layer.msg("网络繁忙",function(){});
            }
        });
    });
    //Demo
    layui.use('form', function(){
        var form = layui.form;
        form.verify({
            confirmPass:function(value){
                if($('input[name=password]').val() !== value)
                    return '两次密码输入不一致！';
            }
        });
        //监听提交
        form.on('submit(formDemo)', function(data){
            $.ajax({
                url:"api/auth/register",
                data:data.field,
                type:"post",
                dataType:"json",
                success:function(res){
                    console.log(res);
                    if (res.code == 200) {
                        layer.msg('注册成功',function(){
                            window.localStorage.setItem('token',res.data.access_token);
                            window.localStorage.setItem('userInfo',JSON.stringify(res.data.userInfo));
                        });

                        setTimeout(function(){
                            window.location = "/";
                        },1500);
                    }else{
                        layer.msg(res.msg,function(){});
                    }
                },
                error:function(){
                    layer.msg(res.msg,function(){});
                }
            });
            return false;
        });
    });
</script>
</body>
</html>