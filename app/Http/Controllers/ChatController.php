<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JWTAuth;

class ChatController extends CommonController
{

    public function __construct()
    {

//        $this->middleware('api.refresh')->except(['index']);
    }
    public function userInfo(Request $request)
    {
        $token = $request->header('authorized');
        $user = JWTAuth::toUser($token);

        $user = DB::table('users')->find($user->id);
        if (!$user) {
            return $this->json(500,"获取用户信息失败");
        }
        $groups = DB::table('c_group_member as gm')
            ->leftJoin('c_group as g','g.id','=','gm.group_id')
            ->select('g.id','g.groupname','g.avatar')
            ->where('gm.user_id', $user->id)->get();
        foreach ($groups as $k=>$v) {
            $groups[$k]->groupname = $v->groupname.'('.$v->id.')';
        }
        $friend_groups = DB::table('c_friend_group')->select('id','groupname')->where('user_id', $user->id)->get();
        foreach ($friend_groups as $k => $v) {
            $friend_groups[$k]->list = DB::table('c_friend as f')
                ->leftJoin('users as u','u.id','=','f.friend_id')
                ->select('u.nickname as username','u.id','u.avatar','u.sign','u.line_status')
                ->where('f.user_id',$user->id)
                ->where('f.friend_group_id',$v->id)
                ->orderBy('u.line_status','DESC')
                ->get();
        }
        $data = [
            'mine'      => [
                'username'  => $user->nickname.'('.$user->id.')',
                'id'        => $user->id,
                'status'    => $user->line_status,
                'sign'      => $user->sign,
                'avatar'    => $user->avatar
            ],
            "friend"    => $friend_groups,
            "group"     => $groups
        ];
        return $this->json(0,'',$data);

    }
    public function index(Request $request){

    }

    /**
     * @author woann<304550409@qq.com>
     * @param Request $request
     * @return GroupController
     * @des 获取群成员
     */
    public function groupMember(Request $request)
    {
        $id = $request->get('id');
        $list = DB::table('c_group_member as gm')
            ->leftJoin('users as u','u.id','=','gm.user_id')
            ->select('u.username','u.id','u.avatar','u.sign')
            ->where('group_id', $id)
            ->get();
        if (!count($list)) {
            return $this->json(500,"获取群成员失败");
        }
        return $this->json(0,"",['list' => $list]);
    }



    /**
     * @author woann<304550409@qq.com>
     * @param Request $request
     * @return GroupController
     * @des 加入群
     */
    public function joinGroup(Request $request)
    {
        $token = $request->header('authorized');
        $user = JWTAuth::toUser($token);
        $id = $request->post('groupid');
        $isIn = DB::table('c_group_member')->where('group_id',$id)->where('user_id', $user->id)->first();
        if ($isIn) {
            return $this->json(500,"您已经是该群成员");
        }
        $group = DB::table('c_group')->find($id);
        $res = DB::table('c_group_member')->insert(['group_id' => $id,'user_id' => $user->id]);
        if (!$res) {
            return $this->json(500,"加入群失败");
        }
        $data = [
            "type" => "group",
            "avatar"    => $group->avatar,
            "groupname" =>$group->groupname,
            "id"        =>$group->id
        ];
        return $this->json(200,"加入成功",$data);
    }

    /**
     * @author woann<304550409@qq.com>
     * @param Request $request
     * @return GroupController|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @des 创建群
     */
    public function createGroup(Request $request)
    {
        if($request->isMethod("POST")){

            $token = $request->header('authorized');
            $user = JWTAuth::toUser($token);
            $post = $request->post();
            $data = [
                'groupname' => $post['groupname'],
                'user_id'   => $user->id,
                'avatar'    => str_replace(env('APP_URL'),'',$post['avatar'])
            ];
            DB::beginTransaction();
            $group_id = DB::table('c_group')->insertGetId($data);
            $res_join = DB::table('c_group_member')->insert(['group_id' => $group_id,'user_id' => $user->id]);
            if ($group_id && $res_join) {
                DB::commit();
                $data = [
                    "type" => "group",
                    "avatar"    => $post['avatar'],
                    "groupname" => $post['groupname'],
                    "id"        => $group_id
                ];
                return $this->json(200,"创建成功！",$data);
            } else {
                DB::callback();
                return $this->json(500,"创建失败！");
            }
        }
    }

    /**
     * @param Request $request
     * @return $this  上传文件或者图片
     */
    public function upload(Request $request)
    {

        $file = $request->file('file');
        $type = $request->input('type');
        $path = $request->input('path') ?? '';
        $folder_name  = 'uploads/'.$path.'/chat/'.date("Ym/d", time());
        $upload_path = public_path() . '/' . $folder_name;
        if (!$file) {
            return $this->json(500,'请选择上传的文件');
        }
        if (!$file->isValid()) {
            return $this->json(500,'文件验证失败！');
        }
        $size = $file->getSize();
        if($size > 1024 * 1024 * 5 ){
            return $this->json(500,'图片不能大于5M！');
        }
        $ext = $file->getClientOriginalExtension();     // 扩展名
        if ($type == 'im_image') {

            if(!in_array($ext,['png','jpg','gif','jpeg','pem','ico']))
            {
                return $this->json(500,'文件类型不正确！');
            }
        }

        $filename = time() . '_' . rand(1000,9999) . '.' . $ext;
        $res = $file->move($upload_path, $filename);
        if($res){
            $data = ['src'=>env('APP_URL')."/$folder_name/$filename"];
            if ($type == 'im_file') {
                $data['name'] = $file->getFilename();
            }
            return $this->json(0,'上传成功',$data);
        }else{
            return $this->json(500,'上传失败！');
        }
    }

    public function messageBox(Request $request)
    {
        $token = $request->header('authorized');
        $user = JWTAuth::toUser($token);
        DB::table('c_system_message')->where('user_id',$user->id)->update(['read' => 1]);
        $list = DB::table('c_system_message as sm')
            ->leftJoin('users as f','f.id','=','sm.from_id')
            ->select('sm.id','f.id as uid','f.avatar','f.nickname','sm.remark','sm.time','sm.type','sm.group_id','sm.status')
            ->where('user_id',$user->id)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        foreach ($list as $k => $v) {
            $list[$k]->time = time_tranx($v->time);
        }
        return view('web/message_box',['list' => $list]);
    }

    /**
     * @param Request $request
     * @return $this 聊天记录
     */
    public function chatRecordData(Request $request)
    {
        $token = $request->header('authorized');
        $user = JWTAuth::toUser($token);
        $id = $request->get('id');
        $type = $request->get('type');
        if ($type == 'group') {
            $list = DB::table('c_chat_record as cr')
                ->leftJoin('users as u','u.id','=','cr.user_id')
                ->select('u.nickname as username','u.id','u.avatar','time as timestamp','cr.content')
                ->where('cr.group_id',$id)
                ->orderBy('time','DESC')
                ->paginate(10);
        } else {
            $list = DB::table('c_chat_record as cr')
                ->leftJoin('users as u','u.id','=','cr.user_id')
                ->select('u.nickname as username','u.id','u.avatar','time as timestamp','cr.content')
                ->where(function ($query) use($user, $id) {
                    $query->where('user_id', $user->id)
                        ->where('friend_id', $id);
                })
                ->orWhere(function ($query) use($user, $id) {
                    $query->where('friend_id', $user->id)
                        ->where('user_id', $id);
                })
                ->orderBy('time','DESC')
                ->paginate(10);
        }
        foreach ($list as $k=>$v){
            $list[$k]->timestamp = $v->timestamp * 1000;
        }
        return $this->json(0,'',$list);

    }
    
    public function refuseFriend(Request $request)
    {
        $id = $request->post('id');
        $system_message = DB::table('c_system_message')->find($id);
        DB::beginTransaction();
        $res = DB::table('c_system_message')->where('id',$id)->update(['status' => 2]);
        $data = [
            'user_id'   => $system_message->from_id,
            'from_id'   => $system_message->user_id,
            'type'      => 1,
            'status'    => 2,
            'time'      => time()
        ];
        $res1 = DB::table('c_system_message')->insert($data);
        if ($res && $res1){
            DB::commit();
            return $this->json(200,"已拒绝");
        } else {
            DB::callback();
            return $this->json(500,"操作失败");
        }
    }

    /**
     * @author woann<304550409@qq.com>
     * @param Request $request
     * @return UserController
     * @des 添加好友
     */
    public function addFriend(Request $request)
    {

        $id = $request->post('id');
        $system_message = DB::table('c_system_message')->find($id);
        $isFriend = DB::table('c_friend')->where('user_id',$system_message->user_id)->where('friend_id',$system_message->from_id)->first();
        if ($isFriend) {
            return $this->json(500,'已经是好友了');
        }
        $data = [
            [
                'user_id' => $system_message->user_id,
                'friend_id' =>$system_message->from_id,
                'friend_group_id' => $request->post('groupid')
            ],
            [
                'user_id' =>$system_message->from_id,
                'friend_id' => $system_message->user_id,
                'friend_group_id' => $system_message->group_id
            ]
        ];
        $res = DB::table('c_friend')->insert($data);
        if (!$res) {
            return $this->json(500,'添加失败');
        }
        DB::table('c_system_message')->where('id',$id)->update(['status' => 1]);
        $user = DB::table('users')->find($system_message->from_id);
        $data = [
            "type"  => "friend",
            "avatar"    => $user->avatar,
            "username" => $user->nickname,
            "groupid" => $request->post('groupid'),
            "id"        => $user->id,
            "sign"    => $user->sign
        ];
        $system_message_data = [
            'user_id'   => $system_message->from_id,
            'from_id'   => $system_message->user_id,
            'type'      => 1,
            'status'    => 1,
            'time'      => time()
        ];
        $res1 = DB::table('c_system_message')->insert($system_message_data);
        return $this->json(200,'添加成功',$data);
    }

    public function updateSign(Request $request)
    {
        $token = $request->header('authorized');
        $user = JWTAuth::toUser($token);
        $sign = $request->post('sign');
        $res = DB::table('users')->where('id', $user->id)->update(['sign' => $sign]);
        if (!$res) {
            return $this->json(500,'签名修改失败');
        }
        return $this->json(200,'签名修改成功');
    }
}


