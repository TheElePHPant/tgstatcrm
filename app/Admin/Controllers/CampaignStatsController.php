<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use \App\Models\CampaignStats;

class CampaignStatsController extends AdminController
{

    protected $select1 = [
        'active'=>'Активно',
        'pzrd'=>'ПЗРД',
        'check'=>'Чек',
        'zrd'=>'ЗРД',
    ];
    protected $select2 = [
        'active'=>'Активно',
        'moderate'=>'Модерация',
        'check'=>'Чек',
        'billing'=>'Биллинг',
        'ban'=>'БАН',
    ];
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'CampaignStats';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CampaignStats());

        $grid->column('id', __('Id'));
        $grid->column('status1', __('Статус'))->select($this->select1);
        $grid->column('status2', __('Статус'))->select($this->select2);
        $grid->column('card', __('Карта'));
        $grid->column('campaign', __('РК'));
        $grid->column('result', __('Результат'));
        $grid->column('start', __('Запуск'));
        $grid->column('hold', __('Hold'));
        $grid->column('consumption', __('Расход'));
        $grid->column('paid', __('Оплачено'));
        $grid->column('cp', __('Ц.П'))->display(function(){
            return '<i>'.($this->paid+$this->start)/$this->result.'</i>';
        });
        $grid->column('cfb', 'Ц.фб')->display(function (){
            return '<b>'.$this->consumption/$this->result.'</b>';
        });
        $grid->column('audience', __('Аудитория'));
        $grid->column('link', __('Ссылка'));
        $grid->column('leads', __('Лиды'));
        $grid->column('comment', __('Коммент'));

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
        $show = new Show(CampaignStats::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('status1', __('Статус'));
        $show->field('status2', __('Статус'));
        $show->field('card', __('Карта'));
        $show->field('campaign', __('Кампания'));
        $show->field('result', __('Result'));
        $show->field('start', __('Start'));
        $show->field('hold', __('Hold'));
        $show->field('consumption', __('Расход'));
        $show->field('paid', __('Оплачено'));
        //$show->field('cp', __('Cp'));//цп рассчитывается
        $show->field('audience', __('Аудитория'));
        $show->field('link', __('Ссылка'));
        $show->field('leads', __('Лиды'));
        $show->textarea('comment', __('Коммент'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CampaignStats());

//        $form->field('status1', __('Статус'));
        $form->select('status1', 'Статус 1')->options($this->select1);
        $form->select('status2', 'Статус 2')->options($this->select2);
//        $form->field('status2', __('Статус'));
        $form->text('card', __('Карта'));
        $form->text('campaign', __('Кампания'));
        $form->text('result', __('Результат'));
        $form->text('start', __('Запуск'));
        $form->text('hold', __('Hold'));
        $form->text('consumption', __('Расход'));
        $form->text('paid', __('Оплачено'));
        //$form->field('cp', __('Cp'));//цп рассчитывается
        $form->text('audience', __('Аудитория'));
        $form->text('link', __('Ссылка'));
        $form->text('leads', __('Лиды'));
        $form->textarea('comment', __('Коммент'));

        return $form;
    }
}
