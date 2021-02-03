<?php

namespace App\Admin\Controllers;
use App\Handlers\ImageUploadHandler;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UploadsController extends Controller
{
    public function uploadImg(Request $request,ImageUploadHandler $uploader)
    {
        // 初始化返回数据，默认是失败的
        $data = [
            'code'   => 500,
        ];
        $file  = $request->upload_file;
        $len = 0;
        foreach ($file as $key => $value) {
            $len = $key;
        }
        if ($len > 25) {
            return response()->json(['ResultData' => 6, 'info' => '最多可以上传4张图片']);
        }

        for ($i = 0; $i <= $len; $i++) {
            // $n 表示第几张图片
            $n = $i + 1;
            if ($file[$i]->isValid()) {
                $result = $uploader->save($file[$i], 'admin', 'editor');
                // 图片保存成功的话
                if ($result['code'] == 200) {
                    $data['data'][] = $result['data'];
                    $data['code']   = 200;
                    $data['msg']   = '上传成功';
                }elseif ($result['code'] == 0){
                    return response()->json(['ResultData' => 3, 'info' => '第' . $n . '张图片后缀名不合法!<br/>' . '只支持jpeg/jpg/png/gif格式']);
                }
            } else {
                return response()->json(['ResultData' => 1, 'info' => '第' . $n . '张图片超过最大限制!<br/>' . '图片最大支持2M']);
            }
        }





        return $data;
    }


}