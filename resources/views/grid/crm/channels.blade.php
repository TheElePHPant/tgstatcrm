<div class="box grid-box">
    @if(isset($title))
        <div class="box-header with-border">
            <h3 class="box-title"> {{ $title }}</h3>
        </div>
    @endif

    @if ( $grid->showTools() || $grid->showExportBtn() || $grid->showCreateBtn() )
        <div class="box-header with-border">
            <div class="pull-right">
                {!! $grid->renderColumnSelector() !!}
                {!! $grid->renderExportButton() !!}

                {!! $grid->renderCreateButton() !!}

            </div>
            @if ( $grid->showTools() )
                <div class="pull-left">
                    {!! $grid->renderHeaderTools() !!}
                </div>
            @endif
            <div class="pull-left">
                <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px;">
                    <a class="btn btn-sm btn-primary" href="{{route('admin.transactions.create')}}"><i
                            class="fa fa-plus"></i>&nbsp;&nbsp;Добавить продажу</a>
                </div>
            </div>
        </div>
    @endif

    {!! $grid->renderFilter() !!}

    {!! $grid->renderHeader() !!}

    <!-- /.box-header -->
    <ul class="nav nav-tabs">
        <li @class(['active'=>$currentTopic==null])><a class="" href="{{route('admin.channels.index')}}">Все тематики ({{$channelsCount}})</a></li>
        @foreach($topics as $topic)
            <li @class(['active'=>$currentTopic==$topic->id])><a href="{{route('admin.channels.index', ['topic'=>$topic->id])}}">{{$topic->title}} ({{$topic->channels_count}})</a></li>
        @endforeach
        <li @class(['active'=>$currentTopic==-1])><a href="{{route('admin.channels.index', ['topic'=>-1])}}">Без тематики ({{$withoutTopicsCount}})</a></li>
    </ul>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover grid-table" id="{{ $grid->tableID }}">
            <thead>
            <tr>
                @foreach($grid->visibleColumns() as $column)
                    <th {!! $column->formatHtmlAttributes() !!}>{!! $column->getLabel() !!}{!! $column->renderHeader() !!}</th>
                @endforeach
            </tr>
            </thead>

            @if ($grid->hasQuickCreate())
                {!! $grid->renderQuickCreate() !!}
            @endif

            <tbody>

            @if($grid->rows()->isEmpty() && $grid->showDefineEmptyPage())
                @include('admin::grid.empty-grid')
            @endif

            @foreach($grid->rows() as $row)
                <tr {!! $row->getRowAttributes() !!}>
                    @foreach($grid->visibleColumnNames() as $name)
                        <td {!! $row->getColumnAttributes($name) !!}>
                            {!! $row->column($name) !!}
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>

            {!! $grid->renderTotalRow() !!}

        </table>

    </div>

    {!! $grid->renderFooter() !!}

    <div class="box-footer clearfix">
        {!! $grid->paginator() !!}
    </div>
    <!-- /.box-body -->
</div>
<style>
    .column-__actions__ {
        width: 195px;
    }
</style>

