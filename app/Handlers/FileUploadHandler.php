<?php
namespace App\Handlers;
class FileUploadHandler {

    protected $file_types=array('docx','doc','excel');
    protected $file_max_size=1024 * 1024*50; //文件最大

    public function save($file, $folder, $file_prefix){

        $folder_name = "uploads/file/$folder/" . date("Ym/d", time());
        $upload_path =  public_path() .'/'.$folder_name;
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = $file_prefix . '_' . time() . '_' . random_int(1000,9999) . '.' . $extension;
        // 如果上传的不是图片将终止操作
        if ( ! in_array($extension, $this->file_types)) {

            return ['msg'=>'文件不合要求','code'=>0];
        }
        // 将图片移动到我们的目标存储路径中
        if(!$file->move($upload_path, $filename)) {

            return ['msg' => '文件移动失败', 'code' => 0];
        }
        $file_size=$file->getSize();
        if($file_size>$this->file_max_size)
        {
            return ['msg' => '文件移动失败', 'code' => 0];

        }
        return [
            'code'=>200,
            'msg'=>'上传成功',
            'data' => "/$folder_name/$filename"
        ];

    }


}