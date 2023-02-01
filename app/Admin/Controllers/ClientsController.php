<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use \App\Models\Client;

class ClientsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Клиенты';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Client());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Имя'));
        $grid->column('created_at', __('Дата создания'))->datetime(format:'d.m.Y H:i');

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
        $show = new Show(Client::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('Имя', __('Name'));
        $show->field('created_at', __('Дата создания'));
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
        $form = new Form(new Client());

        $form->text('name', __('Имя клиента'));

        return $form;
    }

    public function quickCreate() {
        $data = request()->all();
        $client = Client::create([
            'name'=>$data['name'],
        ]);
        return response(['id'=>$client->id, 'name'=>$client->name, 'clients'=>Client::all()->pluck('name', 'id')]);
    }
}
