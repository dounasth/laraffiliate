<!-- Primary box -->
<div class="box box-solid box-primary">
    <div class="box-header">
        <h3 class="box-title">Parsing Statistics</h3>

        <div class="box-tools pull-right">
            <button data-widget="collapse" class="btn btn-primary btn-sm"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body" style="display: block;">
        @var $stats = ImportParserStats::orderBy('id', 'desc')->take(10)->get();
        <table class="table table-striped table-condensed">
            <tbody>
            <tr>
                <th style="width: 10px">#</th>
                <th>Merchant</th>
                <th>Feed</th>
                <th>Start / End</th>
                <th>Run Time</th>
            </tr>
            @foreach ($stats as $stat)
            <tr>
                <td>{{$stat->id}}.</td>
                <td>{{$stat->merchant->title}}</td>
                <td>{{$stat->feed->name}}</td>
                <td>
                    {{ImportParserStats::asDateTime($stat->start_time)}} / <br/>
                    {{ImportParserStats::asDateTime($stat->end_time)}}
                </td>
                <td>~{{human_time_diff($stat->end_time, $stat->start_time)}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div><!-- /.box -->