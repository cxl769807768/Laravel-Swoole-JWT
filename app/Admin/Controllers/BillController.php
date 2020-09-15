<?php

namespace App\Admin\Controllers;

use App\Bill;
use App\BillType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BillController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '账单';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Bill());

        $grid->column('id', __('Id'));
        $grid->column('money', "金额");
        $grid->column('billType.name', "类型")->label("primary");
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Bill::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('money', "金额");
        $show->field('billType.name', "类型")->label("primary");;

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
        $types = BillType::all()->pluck('name', 'id');
        $form = new Form(new Bill());
        $form->text('money', __('Money'));
        $form->select('type', '类型')->options($types);

        return $form;
    }
}
