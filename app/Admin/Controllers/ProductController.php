<?php

namespace App\Admin\Controllers;

use App\ModType;
use App\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use PhpParser\Node\Expr\AssignOp\Mod;
use Symfony\Component\Console\Helper\Helper;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Product';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());
        $name = request()->input('name');


        $grid->column('id', 'ID')->sortable();
        $grid->model()->orderBy('id', 'desc');
        if(!empty($name)){
            $grid->model()->where('name', 'like', $name);
        }
        if(!empty($name)){
            $grid->model()->where('name', 'like', $name);
        }
        $grid->column('id', __('Id'));
        $grid->column('name', "名称");
        $grid->column('cover', "封面")->image();
        $grid->column('phone', "手机号");
        $grid->column('tid', "栏目ID");
        $grid->column('productTid.name', "栏目")->label("primary");
        $grid->column('status',"状态")->using(['0' => '禁用', '1' => '正常'])->label();
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));


        $grid->filter(function(Grid\Filter $filter){
            $filter->like('name', '名称');
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Product::findOrFail($id));
        $show->field('name', "名称");
        $show->field('cover', "封面");
        $show->field('phone', "手机号");
        $show->field('tid', "栏目ID");
        $show->field('productTid.name', "栏目")->label("primary");
        $show->field('status',"状态")->using(['0' => '禁用', '1' => '正常'])->label([
            0=> 'warning',
            1 => 'success',
        ]);
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));


        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $types = ModType::all()->pluck('name', 'id');
        $form = new Form(new Product());
        $directors = [
            0 => '禁用',
            1 => '正常',
        ];
        $form->text('name', '名称');
        //$form->image('cover','封面')->thumbnail('small', $width = 356, $height = 200)->removable();
        $form->image('cover','封面')->removable();
        //$form->multipleImage('slideshow','轮播图')->thumbnail('small', $width = 356, $height = 200)->removable();
        $form->multipleImage('slideshow','轮播图')->removable();
        $form->textarea('introduce', '详情');
        $form->mobile('phone', __('联系电话'));
        $form->select('tid', '栏目')->options($types);
        $form->select('status', '状态')->options($directors);
        return $form;
    }
}
