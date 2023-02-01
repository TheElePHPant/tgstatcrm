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
    protected $title = 'Бухгалтерия';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Transaction());
        $grid->model()->with(['administrator', 'channel', 'client'])->orderBy('id', 'desc');
       // $grid->column('id', __('Id'));
        $grid->column('channel.title', 'Канал');
        $grid->column('administrator.username', 'Менеджер');
        $grid->column('client.name', 'Клиент');
        $grid->column('type_title', 'Операция');
        $grid->column('amount','Сумма');
        $grid->column('comment', 'Примечание');
//        $grid->column('administrator_id', __('Administrator id'));
//        $grid->column('client_id', __('Client id'));
//        $grid->column('channel_id', __('Channel id'));
//        $grid->column('type', __('Type'));
//        $grid->column('amount', __('Amount'));
//        $grid->column('comment', __('Comment'));
        $grid->column('created_at', __('Дата'));
        $grid->disableCreateButton();
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

        \Admin::script($this->script());

        //$form->number('administrator_id', __('Administrator id'));
        $form->row(function ($form) {
            $form->hidden('administrator_id')
                ->default(auth('admin')->id())->value(auth('admin')->id());
            $form->width(4)->select('client_id', 'Клиент')
                ->options(Client::pluck('name', 'id'));
            $form->width(4)->html('<a href="javascript://" class="add-client btn btn-primary"><i class="fa fa-plus"></i> Добавить клиента</a>');
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

    public function script() {
        $url = route('admin.clients.quick-create');
        return <<<SCRIPT
$(function(){
 $('.add-client').unbind('click').click(function(){
    var name = prompt('Введите имя нового клиента');
    console.log(name);
    if(null!==name) {
      $.ajax({
        type:'post',
        url:'$url',
        data:{name:name, _token:$('meta[name="csrf-token"]').attr('content')},
        success: function(res){
            console.log(res);
            $('.client_id').select2("destroy");
            $('.client_id').html('');
            for(id in res.clients) {
                $('.client_id').append('<option value="'+id+'">'+res.clients[id]+'</option>');
            }
            $('.client_id').val(res.id);
            window.setTimeout(function(){
$('.client_id').select2({allowClear:true, placeholder:'Клиент'});
            }, 1000);

            return false;
        }
      });
    }
 })
});
SCRIPT;

    }
}
