<?php

namespace App\Admin\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use \App\Models\Channel;
use Illuminate\Http\Request;

class ChannelsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Каналы';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Channel());
        $grid->model()->with(['daily_subscribers', 'all_time_subscribers', 'daily_transaction']);


        $grid->column('id', __('Id'));
        $grid->column('title', __('Название'));
        $grid->column('channel_url', __('Идентификатор канала/URL'));
        $grid->column('tgstat_url', 'TGStat');
        $grid->column('today_subscribers', 'Подписчики за день');
        $grid->column('total_subscribers', 'Всего подписчиков');
        $grid->column('consumption', 'Расход (день)')->editable();
        $grid->column('profit', 'Доход (день)')->editable();
        $grid->column('daily_subscribers.created_at', 'Дата обновления')->datetime('d.m.Y H:i:s');
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
        $show = new Show(Channel::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('identifier', __('Identifier'));
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
        $form = new Form(new Channel());

        $form->text('title', __('Название канала'));
        if ($form->isEditing()) {
            $form->text('channel_url', __('Ссылка'))
                ->help('https://t.me/+aAaAaA')->readonly();
        } else {
            $form->text('channel_url', __('Ссылка'))
                ->help('https://t.me/+aAaAaA');
        }


        return $form;
    }

    public function update($id)
    {
        $update = parent::update($id);
        $data = \request()->all();
        if(isset($data['_editable'])) {
            if(isset($data['profit'])||isset($data['consumption'])) {
                $date = date('Y-m-d');
                $transaction = Transaction::firstOrNew([
                    'channel_id' => $id,
                    'date' => $date
                ], [
                    'updated_at' => Carbon::now(),
                ]);
                if(isset($data['profit'])) {
                    $transaction->profit = $data['profit'];
                }
                if(isset($data['consumption'])) {
                    $transaction->consumption = $data['consumption'];
                }
                $transaction->save();


            }
        }
        return $update;
    }
}