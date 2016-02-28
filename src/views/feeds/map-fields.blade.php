@extends('laradmin::layout')

@section('page-title')
Map Feed Fields
@stop

@section('page-subtitle')
@if ($feed->id)
    of feed <a href="{{route('feed.update', [$feed->id])}}">{{ $feed->name }} ({{ $feed->id }})</a>
    @if ($feed->merchant)
    for merchant <a href="{{route('merchant.update', [$feed->merchant->id])}}">{{ $feed->merchant->name }} ({{ $feed->merchant->id }})</a>
    @endif
@endif
(only products belonging to mapped categories will be imported to the site)
@stop

@section('breadcrumb')
@parent
<li class="active">Map Feed Fields</li>
@stop

@section('page-menu')
@stop

@section('styles')
<link href="{{ Config::get('laradmin::general.asset_path') }}/css/bootstrap-tags/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    #example1 .bootstrap-tagsinput {
        width: auto !important;
    }
</style>
@stop

@section('scripts')
<script src="{{ Config::get('laradmin::general.asset_path') }}/js/bootstrap-tags/bootstrap-tagsinput.js" type="text/javascript"></script>
<script src="{{ Config::get('laradmin::general.asset_path') }}/js/typeahead.bundle.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
    var tags = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: '{{ route("json.feed-fields", [$feed->id]) }}',
            cache: false,
            filter: function(list) {
                return $.map(list, function(cityname) {
                    return { name: cityname }; });
            }
        }
    });
    tags.initialize();

    $('[data-role="tagsinput"]').tagsinput({
        freeInput: false,
        typeaheadjs: {
            name: 'tags',
            displayKey: 'name',
            valueKey: 'name',
            source: tags.ttAdapter()
        }
    });
    $('[data-role="tagdelimiters"]').tagsinput({
        freeInput: true
    });
</script>
@stop

@section('content')
<div class="box box-primary">
    {{ Form::open(array('route' => ['feed.map-fields.save', $feed->id], 'method' => 'POST', 'role' => 'form', 'enctype'=>'multipart/form-data')) }}
    <div class="box-body table-responsive">
        @var $parser = new $feed->parser($feed)
        @include($parser->config_view, ['parser' => $parser, 'feed' => $feed])
    </div>
    <div class="box-footer">
        <button class="btn btn-success" type="submit"><i class="fa fa-check-square-o"></i> Save</button>
        <a class="btn btn-danger btn-cancel"><i class="fa fa-square-o"></i> Cancel</a>
    </div>
    {{ Form::close() }}
</div>
@stop