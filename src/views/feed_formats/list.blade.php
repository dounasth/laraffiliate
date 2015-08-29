@extends('laradmin::layout')

@section('page-title')
Manage Feed Formats
@stop

@section('page-subtitle')
dashboard subtitle, some description must be here
@stop

@section('breadcrumb')
@parent
<li class="active">Manage Feed Formats</li>
@stop

@section('page-menu')
<li role="presentation"><a role="menuitem" tabindex="-1" href="{{ route('feed_format.update') }}"><i class="fa fa-plus"></i> Add a new feed format</a></li>
<li role="presentation" class="divider"></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-trash-o"></i> Delete selected feed formats</a></li>
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
                <th>Title</th>
                <th>Format</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($formats as $format)
            <tr>
                <td>
                    <input type="checkbox" value="{{ $format->id }}">{{ $format->id }}
                </td>
                <td>
                    <a href="{{route('feed_format.update', [$format->id])}}">{{ $format->title }} </a>
                </td>
                <td>
                    <a href="{{route('feed_format.update', [$format->id])}}">{{ $format->format }} </a>
                </td>
                <td>
                    <a class="btn btn-flat btn-info" href="{{route('feed_format.update', [$format->id])}}"><i class="fa fa-edit"></i> {{trans('laradmin::actions.edit')}}</a>
                    <a class="btn btn-flat btn-danger" href="{{route('feed_format.delete', [$format->id])}}"><i class="fa fa-edit"></i> {{trans('laradmin::actions.delete')}}</a>
                </td>
            </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Format</th>
                <th>&nbsp;</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
@stop