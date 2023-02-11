<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use \App\Models\Topic;
use Encore\Admin\Widgets\Tab;

class TopicsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Тематики каналов';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {


//        $tab = new Tab();
//
//        $tab->add('Pie', 'asdasdsd');
//        $tab->add('Table', 'fooo');
//        $tab->add('Text', 'blablablabla....');
//        return $tab;
        $grid = new Grid(new Topic());

        $grid->column('id', __('Id'));
        $grid->column('title', __('Название'));
        //->display(fn()=>'<a href="'.route('admin.channels.index', ['topic'=>$this->id]).'">'.$this->title.'</a>');
        
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
        $show = new Show(Topic::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('sort', __('Sort'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Topic());

        $form->text('title', __('Название'));

        return $form;
    }
}
