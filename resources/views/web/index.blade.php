<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>woann-chat</title>
    <link rel="stylesheet" href="/asset/layui/css/layuiv2.css" media="all">
    <style>
        .layui-edge{
            display: block;
        }
    </style>
</head>
<body>
<header class="header">
    <ul class="layui-nav" >
        <li class="layui-nav-item" style="float: right;">
            <a class="user" href="javascript:;"><img src="" class="layui-nav-img"></a>
            <dl class="layui-nav-child">
                <dd><a href="javascript:;" id="loginOut">退出登录</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item layui-this"><a href="/">首页</a></li>
        <li class="layui-nav-item"><a target="_blank" href="https://www.woann.cn">聊天系统</a></li>
        <li class="layui-nav-item"><a target="_blank" href="https://github.com/cxl769807768/laraveltest">Github</a></li>
    </ul>
</header>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="/asset/layui/layui.js"></script>
<script type="text/javascript">
    //websoket相關
    var ws; //websocket实例
    var ping;
    var wsUrl = 'ws://www.laravelSwoole.com/ws?uid=' + JSON.parse(window.localStorage.getItem('userInfo')).id;
    var btn = false;
    function sendMessage(ws, data) {
        var readyState = ws.readyState;
        if (ws.readyState === ws.OPEN) {
            ws.send(data)
        }

    }
$(function () {
    //獲取用戶信息
    $.ajax({
        type: 'post', // 提交方式 get/post
        url: '/api/auth/getUser', // 需要提交的 url
        dataType: "json",
        beforeSend: function (request) {
            request.setRequestHeader("authorized", window.localStorage.getItem('token'));
        },
    }).success(function (result) {
        console.log(result);
        if (result.code !== 200) {
            window.location.href = '/login';

        } else {
            btn = true;
            $('.header img').attr('src', result.data.avatar);
            $('.header .user').attr('text', result.data.name);
        }

    }).error(function (result) {
        window.location.href = '/login';

    })
    // var userInfo = JSON.parse(window.localStorage.getItem('userInfo'));
    // if(userInfo == null){
    //     window.location.href = '/login';
    // }
    // $('.header img').attr('src', userInfo.avatar);
    // $('.header .user').attr('text',userInfo.name);

    var _hmt = _hmt || [];
    (function () {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?6b614cdee352cbb9f55e05ad81084c3a";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();

    $('#loginOut').on('click', function () {
        $.ajax({
            type: 'post', // 提交方式 get/post
            url: 'api/auth/logOut', // 需要提交的 url
            dataType: "json",
            beforeSend: function (request) {
                request.setRequestHeader("authorized", window.localStorage.getItem('token'));
            },
        }).success(function (res) {
            layer.msg(res.msg, function () {
                window.localStorage.removeItem('token');
                window.localStorage.removeItem('userInfo');
            })
            ws.onclose();
            window.location.href = '/login';
        })
    })
    setTimeout(function () {
        if (window.WebSocket) {

            if(btn) {
                layui.use('element', function () {
                    var element = layui.element;
                });
                layui.use('layim', function (layim) {
                    //基础配置
                    layim.config({
                        init: {
                            url: 'api/userInfo' //接口地址（返回的数据格式见下文）
                            , type: 'post' //默认get，一般可不填
                            , headers: {"authorized": window.localStorage.getItem('token')},
                        }
                        //获取群员接口（返回的数据格式见下文）
                        , members: {
                            url: 'api/groupMembers' //接口地址（返回的数据格式见下文）
                            , type: 'get' //默认get，一般可不填
                            , data: {} //额外参数
                        }
                        //上传图片接口（返回的数据格式见下文），若不开启图片上传，剔除该项即可
                        , uploadImage: {
                            url: 'api/upload?type=im_image&path=images' //接口地址
                            , type: 'post' //默认post
                            , data: {'mod': 'chat', 'token': window.localStorage.getItem('token')}
                            , headers: {"authorized": window.localStorage.getItem('token')}
                        }
                        //上传文件接口（返回的数据格式见下文），若不开启文件上传，剔除该项即可
                        , uploadFile: {
                            url: 'api/upload?type=im_file&path=file' //接口地址
                            , type: 'post' //默认post
                            , data: {'mod': 'chat', 'token': window.localStorage.getItem('token')}
                            , headers: {"authorized": window.localStorage.getItem('token')}
                        }
                        //扩展工具栏，下文会做进一步介绍（如果无需扩展，剔除该项即可）
                        , tool: [{
                            alias: 'code' //工具别名
                            , title: '代码' //工具名称
                            , icon: '&#xe64e;' //工具图标，参考图标文档
                        }]
                        , msgbox: '/messageBox?uid=' + JSON.parse(window.localStorage.getItem('userInfo')).id//消息盒子页面地址，若不开启，剔除该项即可
                        , find: '/find'//发现页面地址，若不开启，剔除该项即可
                        , chatLog: '/chatLog' //聊天记录页面地址，若不开启，剔除该项即可
                    });

                    ws = new WebSocket(wsUrl);


                    ws.onopen = function () {
                        console.log("websocket is connected")
                        ping = setInterval(function () {
                            sendMessage(ws, '{"type":"ping","uid":"' + JSON.parse(window.localStorage.getItem('userInfo')).id + '"}');
                            console.log("ping...");
                        }, 1000 * 2)

                    };
                    ws.onmessage = function (event) {
                        //如果获取到消息，心跳检测重置
                        //拿到任何消息都说明当前连接是正常的
                        console.log('接收到数据' + event.data);
                        data = JSON.parse(event.data);
                        switch (data.type) {
                            case "friend":
                            case "group":
                                layim.getMessage(data); //res.data即你发送消息传递的数据（阅读：监听发送的消息）
                                break;
                            //单纯的弹出
                            case "layer":
                                if (data.code == 200) {
                                    layer.msg(data.msg)
                                } else {
                                    layer.msg(data.msg, function () {
                                    })
                                }
                                break;
                            //将新好友添加到列表
                            case "addList":

                                layim.addList(data.data);
                                break;
                            //好友上下线变更
                            case "friendStatus" :

                                layim.setFriendStatus(data.uid, data.status);
                                break;
                            //消息盒子
                            case "msgBox" :
                                //为了等待页面加载，不然找不到消息盒子图标节点
                                setTimeout(function () {
                                    if (data.count > 0) {
                                        layim.msgbox(data.count);
                                    }
                                }, 1500);
                                break;
                            //token过期
                            case "token is error":
                                layer.msg('非法Token', function () {
                                    $('#loginOut').trigger("click");
                                });
                                break;
                            //加群提醒
                            case "joinNotify":
                                layim.getMessage(data.data);
                                break;

                        }

                    }
                    ws.onclose = function () {
                        console.log("websocket is closed")
                        clearInterval(ping);
                        $('#loginOut').trigger("click");
                    }

                    layim.on('sendMessage', function (res) {
                        var mine = res.mine; //包含我发送的消息及我的信息
                        var to = res.to; //对方的信息
                        sendMessage(ws, JSON.stringify({
                            type: 'chatMessage', //随便定义，用于在服务端区分消息类型,
                            uid: JSON.parse(window.localStorage.getItem('userInfo')).id
                            , data: res
                        }));
                    });
                    layim.on('sign', function (value) {
                        $.ajax({
                            url: "api/updateSign",
                            type: "post",
                            data: {sign: value},
                            dataType: "json",
                            beforeSend: function (request) {
                                request.setRequestHeader("authorized", window.localStorage.getItem('token'));
                            },
                            success: function (res) {
                                if (res.code == 200) {
                                    layer.msg(res.msg)
                                } else {
                                    layer.msg(res.msg, function () {
                                    })
                                }
                            },
                            error: function () {
                                layer.msg("网络繁忙", function () {
                                });
                            }
                        })
                    });
                    layim.on('tool(code)', function (insert, send, obj) { //事件中的tool为固定字符，而code则为过滤器，对应的是工具别名（alias）
                        layer.prompt({
                            title: '插入代码'
                            , formType: 2
                            , shade: 0
                        }, function (text, index) {
                            layer.close(index);
                            insert('[pre class=layui-code]' + text + '[/pre]'); //将内容插入到编辑器，主要由insert完成
                            //send(); //自动发送
                        });
                    });
                    layim.on('chatChange', function (obj) {
                        var type = obj.data.type;
                        if (type === 'friend') {
                            if (obj.data.status == 'online') {
                                layim.setChatStatus('<span style="color:#FF5722;">在线</span>'); //模拟标注好友在线状态
                            } else {
                                layim.setChatStatus('<span style="color:#666;">离线</span>'); //模拟标注好友在线状态
                            }
                        }
                    });


                })
            }
        } else {
            layer.msg('您的浏览器不支持WebSocket', function () {
            });
        }
    },2000)

})
</script>
</body>
</html>
