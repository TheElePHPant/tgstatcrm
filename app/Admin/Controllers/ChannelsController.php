<?php

namespace App\Admin\Controllers;

use App\Models\Topic;
use App\Models\Transaction;
use App\Services\TgStatService;
use Carbon\Carbon;
use danog\MadelineProto\auth;
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

        $topics = Topic::withCount(['channels'])->get();
        $withoutTopicsCount = Channel::whereNull('topic_id')->count();
        $channelsCount = Channel::count();
        $currentTopic = \request('topic');
        $grid = new Grid(new Channel());
        $grid->model()
            ->when(null==$currentTopic, fn($q)=>$q)
            ->when(-1==$currentTopic, fn($q)=>$q->whereNull('topic_id'))
            ->when(null!==$currentTopic&&$currentTopic>0, fn($q)=>$q->where('topic_id', $currentTopic))
            ->byUser()->with(['daily_subscribers', 'all_time_subscribers'])
            ->withSum('consumptions', 'amount')
        ->withSum('profit', 'amount');


        if(!auth()->user()->roles->pluck('slug')->contains('administrator')) {
            $grid->disableCreateButton();
        }

        $grid->column('id', __('Id'));
        $grid->column('title', __('Название'));
        $grid->column('channel_url', __('Идентификатор канала/URL'));
        $grid->column('tgstat_url', 'TGStat');
        $grid->column('today_subscribers', 'Подписчики за день');
        $grid->column('total_subscribers', 'Всего подписчиков');

        $grid->column('daily_consumption', 'Расход (день)');
        $grid->column('daily_profit', 'Доход (день)');
        $grid->column('consumptions_sum_amount', 'Расход(всего)')->display(fn()=>$this->consumptions_sum_amount??0);
        $grid->column('profit_sum_amount', 'Доход(всего)')->display(fn()=>$this->profit_sum_amount??0);
        $grid->column('daily_subscribers.created_at', 'Дата обновления')->datetime('d.m.Y H:i:s');
        $grid->actions(function ($actions) {
            $actions->disableDelete()->disableView();
            $actions->prepend('<a href="' . route('admin.channels.update-info', ['id' => $this->row->id]) . '" title="Получить актуальные данные"><i class="fa fa-refresh"></i></a>&nbsp;&nbsp;');
            $actions->append('<a href="' . route('admin.transactions.create', ['channel' => $this->row->id]) . '" class="btn btn-xs btn-primary">+ Продажа</a>&nbsp;');
            if (auth('admin')->user()->roles->pluck('slug')->contains('administrator')) {

                $actions->append('<a href="' . route('admin.transactions.create-consumption', ['channel' => $this->row->id]) . '" class="btn btn-xs btn-danger">- Расход</a>&nbsp;');
            }
        });
        $grid->setView('grid.crm.channels', ['topics'=>$topics, 'withoutTopicsCount'=>$withoutTopicsCount, 'channelsCount'=>$channelsCount, 'currentTopic'=>$currentTopic]);
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
        $form->select('topic_id', 'Тематика канала')->options(Topic::query()->pluck('title', 'id'));
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
        if (isset($data['_editable'])) {
            if (isset($data['profit']) || isset($data['consumption'])) {
                $date = date('Y-m-d');
                $transaction = Transaction::firstOrNew([
                    'channel_id' => $id,
                    'date' => $date
                ], [
                    'updated_at' => Carbon::now(),
                ]);
                if (isset($data['profit'])) {
                    $transaction->profit = $data['profit'];
                }
                if (isset($data['consumption'])) {
                    abort_if(!auth('admin')->user()->roles->pluck('slug')->contains('administrator'), 403);
                    $transaction->consumption = $data['consumption'];
                }
                $transaction->save();


            }
        }
        return $update;
    }

    public function updateInfo($id, TgStatService $statService)
    {
        $channel = Channel::find($id);
        $statService->parseChannel($channel->channel_url);
        admin_success('Информация обновлена');
        return redirect()->back();
    }
}
