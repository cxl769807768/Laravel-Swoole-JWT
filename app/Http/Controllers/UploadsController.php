<?php

namespace App\Http\Controllers;
use App\Handlers\FileUploadHandler;
use App\Handlers\ImageUploadHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadsController extends CommonController
{
    public function uploadImg(Request $request,ImageUploadHandler $uploader)
    {

        // 初始化返回数据，默认是失败的
        $data = [
            'code'   => 500,
        ];
        if ($request->hasFile('upload_file') && $request->file('upload_file')->isValid()) {
            $result = $uploader->save($request->file('upload_file'), $request->input('mod'), $request->input('mod'));
            // 图片保存成功的话
            if ($result['code'] == 200) {
                $data['data'] = $result['data'];
                $data['code']   = 200;
                $data['msg']   = '上传成功';
            }else{
                return response()->json(['code' => 500, 'msg' =>$result['msg']]);
            }
        } else {

        }

        return $data;
    }

    public function uploadFile(Request $request,FileUploadHandler $uploader)
    {

        $file  = $request->upload_file;
        if ($file->isValid()) {
            $result = $uploader->save($file, 'web', 'chat');

            return response()->json([
                'code'=>$result['code'],
                'msg'=>"获取成功",
                'data'=>$result['data']
            ]);
        }
    }


}