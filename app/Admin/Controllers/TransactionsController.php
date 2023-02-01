<?php

namespace App\Admin\Controllers;

use App\Enums\TransactionType;
use App\Models\Channel;
use App\Models\Client;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use \App\Models\Transaction;

class TransactionsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Продажи';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Transaction());
        $grid->model()->with(['administrator', 'channel'])->orderBy('id', 'desc');
       // $grid->column('id', __('Id'));
        $grid->column('channel.title', 'Канал');
        $grid->column('administrator.username', 'Менеджер');
        $grid->column('type_title', 'Операция');
        $grid->column('amount','Сумма');
        $grid->disableCreateButton();
//        $grid->column('administrator_id', __('Administrator id'));
//        $grid->column('client_id', __('Client id'));
//        $grid->column('channel_id', __('Channel id'));
//        $grid->column('type', __('Type'));
//        $grid->column('amount', __('Amount'));
//        $grid->column('comment', __('Comment'));
        $grid->column('created_at', __('Дата'));
//        $grid->column('updated_at', __('Updated at'));
//        $grid->column('deleted_at', __('Deleted at'));

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
        $show = new Show(Transaction::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('administrator_id', __('Administrator id'));
        $show->field('client_id', __('Client id'));
        $show->field('channel_id', __('Channel id'));
        $show->field('type', __('Type'));
        $show->field('amount', __('Amount'));
        $show->field('comment', __('Comment'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Transaction());

        //$form->number('administrator_id', __('Administrator id'));
        $form->row(function ($form) {
            $form->hidden('administrator_id')
                ->default(auth('admin')->id())->value(auth('admin')->id());
            $form->width(4)->select('client_id', 'Клиент')
                ->options(Client::pluck('name', 'id'));
        });
        $form->row(function ($form) {
            $form->width(4)->select('channel_id', 'Выберите канал')

                ->options(Channel::pluck('title', 'id')->toArray())->default(request('channel'));
            //$form->text('type', __('Type'));

        });
        $form->row(function ($form) {
            $form->hidden('type')->value(TransactionType::PROFIT->value)->default(TransactionType::PROFIT->value);
            $form->width(8)->decimal('amount', __('Сумма'));

        });
        $form->row(function($form){
            $form->width(4)->textarea('comment', __('Комментарий'));
        });
        $form->saved(function () {
            admin_success('Продажа добавлена');
            return redirect()->route('admin.channels.index');
        });

        return $form;
    }
}
