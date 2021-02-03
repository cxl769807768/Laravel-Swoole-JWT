<?php

namespace App\Admin\Extensions;
use Encore\Admin\Form\Field;
class WangEditor extends Field
{
    protected $view = 'admin.wangeditor';

    protected static $css = [
        '/vendor/laravel-admin-ext/wang-editor/wangEditor-3.0.10/release/wangEditor.min.css',
    ];

    protected static $js = [
        '/vendor/laravel-admin-ext/wang-editor/wangEditor-3.0.10/release/wangEditor.min.js',
    ];


    public function render()
    {
        $name = $this->formatName($this->column);
        $this->script = <<<EOT
var E = window.wangEditor
var editor = new E('#{$this->id}');
editor.customConfig.uploadFileName = 'upload_file[]';
editor.customConfig.uploadImgHeaders = {
    'X-CSRF-TOKEN': $('input[name="_token"]').val()
}
editor.customConfig.zIndex = 0;
editor.customConfig.uploadImgServer = '/admin/uploadFile';
editor.customConfig.onchange = function (html) {
    $('input[name=$name]').val(html);
}
editor.customConfig.uploadImgHooks = {
    customInsert: function (insertImg, result, editor) {

        if (result.code ==  200) {
            for (var i = 0; i <= result.data.length - 1; i++) {
                console.log(result.data[i]);
                var url = result.data[i];
                insertImg(url);
            }
            toastr.success(result.msg);
        }else{
            switch (result['ResultData']) {
                case 6:
                    toastr.error("最多可以上传4张图片");
                    break;
                case 4:
                    toastr.error("上传失败");
                    break;
                case 3:
                    toastr.error(result['info']);
                    break;
                case 2:
                    toastr.error("文件类型不合法");
                    break;
                case 1:
                    toastr.error(result['info']);
                    break;
            }
        }
        
    }
}
editor.create();
EOT;
        return parent::render();
    }
}