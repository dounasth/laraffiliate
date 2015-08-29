@extends('laradmin::layout')

@section('page-title')
Manage Feeds
@stop

@section('page-subtitle')
dashboard subtitle, some description must be here
@stop

@section('breadcrumb')
@parent
<li class="active">Manage Feeds</li>
@stop

@section('page-menu')
<li role="presentation"><a role="menuitem" tabindex="-1" href="{{ route('feed.update') }}"><i class="fa fa-plus"></i> Add a new feed</a></li>
<li role="presentation" class="divider"></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-trash-o"></i> Delete selected feeds</a></li>
<li role="presentation" class="divider"></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="{{ route('feed.trashed') }}"><i class="fa fa-trash-o"></i> Trashed</a></li>
@stop

@section('styles')
<!-- DATA TABLES -->
<link href="{{ Config::get('laradmin::general.asset_path') }}/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
@stop

@section('scripts')
<!-- DATA TABES SCRIPT -->
<script src="{{ Config::get('laradmin::general.asset_path') }}/js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="{{ Config::get('laradmin::general.asset_path') }}/js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("#example1").dataTable();
    });
</script>
@stop

@section('content')
<div class="box box-primary">
    <div class="box-body table-responsive">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>URL</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($feeds as $feed)
            <tr>
                <td>
                    <input type="checkbox" value="{{ $feed->id }}">{{ $feed->id }}
                </td>
                <td>
                    <a href="{{route('feed.update', [$feed->id])}}">{{ $feed->name }} </a> for
                    <a href="{{route('merchant.update', [$feed->merchant->id])}}">{{ $feed->merchant->name }} ({{ $feed->merchant->id }})</a>
                </td>
                <td><a href="{{ $feed->feed_url }}" title="{{ $feed->feed_url }}" target="_blank">{{ Str::limit($feed->feed_url, 100) }}</a></td>
                <td>
                    <a class="btn btn-flat btn-primary" href="{{route('feed.map_categories', [$feed->id])}}"><i class="fa fa-edit"></i> {{trans('laradmin::actions.map_categories')}}</a>
                    <a class="btn btn-flat btn-info" href="{{route('feed.update', [$feed->id])}}"><i class="fa fa-edit"></i> {{trans('laradmin::actions.edit')}}</a>
                    <a class="btn btn-flat btn-danger" href="{{route('feed.delete', [$feed->id])}}"><i class="fa fa-edit"></i> {{trans('laradmin::actions.delete')}}</a>
                </td>
            </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>URL</th>
                <th>&nbsp;</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
@stop