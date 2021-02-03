<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AuthWebController extends CommonController
{
    public function __construct()
    {

        $this->middleware('jwt.auth', ['except' => [
            'registerShow','loginShow','find','createGroupShow','chatLog','messageBox','index']
        ]);
    }
    /**
     * @author xiaolong
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @des首页
     */
    public function index(Request $request)
    {

        return view('web/index');
    }
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 注册页面
     */
    public function registerShow(Request $request)
    {
        return view('web/register');
    }
    public function loginShow(Request $request)
    {
        return view('web/login');
    }
    /**
     * @author woann<304550409@qq.com>
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @des 查找页面
     */
    public function find(Request $request)
    {
        $type = $request->get('type');
        $wd = $request->get('wd');
        $user_list = [];
        $group_list = [];
        switch ($type) {
            case "user" :
                $user_list = DB::table('users')->select('id','nickname','avatar')->where('id','like','%'.$wd.'%')->orWhere('nickname','like','%'.$wd.'%')->orWhere('name','like','%'.$wd.'%')->get();
                break;
            case "group" :
                $group_list = DB::table('c_group')->select('id','groupname','avatar')->where('id','like','%'.$wd.'%')->orWhere('groupname','like','%'.$wd.'%')->get();
                break;
            default :
                break;
        }
        return view('web/find',['user_list' => $user_list,'group_list' => $group_list,'type' => $type,'wd' => $wd]);
    }

    /**
     * @author woann<304550409@qq.com>
     * @param Request $request
     * @return GroupController|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @des 创建群显示页面
     */
    public function createGroupShow(Request $request)
    {
        return view('web/create_group');
    }

    public function loginOut()
    {
        session(['user'=>null]);
        return redirect('/');
    }
    public function chatLog(Request $request)
    {
        $id = $request->get('id');
        $type = $request->get('type');
        return view('web/chat_log',['id' => $id,'type' => $type]);
    }

    public function messageBox(Request $request)
    {
        $uid = $request->input('uid');
        DB::table('c_system_message')->where('user_id',$uid)->update(['read' => 1]);
        $list = DB::table('c_system_message as sm')
            ->leftJoin('users as f','f.id','=','sm.from_id')
                ->select('sm.id','f.id as uid','f.avatar','f.nickname','sm.remark','sm.time','sm.type','sm.group_id','sm.status')
                ->where('user_id',$uid)
                ->orderBy('id', 'DESC')
                ->paginate(10);
            foreach ($list as $k => $v) {
                $list[$k]->time = time_tranx($v->time);
        }
        return view('web/message_box',['list' => $list]);
    }

}
