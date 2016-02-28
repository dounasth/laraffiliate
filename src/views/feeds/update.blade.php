@extends('laradmin::layout')

@section('page-title')
{{ !$feed->id ? 'Add' : 'Edit' }} Feed
@stop

@section('page-subtitle')
    @if ($feed->id)
        {{ $feed->name }} ({{ $feed->id }})
        @if ($feed->merchant)
        for <a href="{{route('merchant.update', [$feed->merchant->id])}}">{{ $feed->merchant->name }} ({{ $feed->merchant->id }})</a>
        @endif
    @endif
@stop

@section('breadcrumb')
@parent
<li><a href="{{ route('feed.list') }}" class="goOnCancel"><i class="fa fa-group"></i> Manage Feeds</a></li>
<li class="active">{{ !$feed->id ? 'Add' : 'Edit' }} Feed</li>
@stop

@section('page-menu')
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('content')
<div class="box box-primary">
    {{ Form::open(array('route' => ['feed.save', $feed->id], 'method' => 'POST', 'role' => 'form')) }}
    <div class="box-body">
        <div class="row">
        	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <h2>General</h2>
                <div class="form-group">
                    {{ Form::label('merchant_id', 'Merchant:') }}
                    {{$feed->merchant_id}}
                    {{ Form::select('feed[merchant_id]', array_replace(array(''=>'Select One'), $merchants), $feed->merchant_id, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('format_id', 'Format_id:') }}
                    {{ Form::select('feed[format_id]', array_replace(array(''=>'Select One'), $formats), $feed->format_id, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('name', 'Name:') }}
                    {{ Form::text('feed[name]', $feed->name, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('feed_url', 'Feed_url:') }}
                    {{ Form::textarea('feed[feed_url]', $feed->feed_url, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('status', 'Status:') }}
                    {{ Form::select('feed[status]', array('A' => 'Active', 'D' => 'Disabled'), $feed->status, array('class' => 'form-control')) }}
                </div>

                <h2>Timestamps</h2>
                <div class="form-group">
                    {{ Form::label('', 'Updated@: '.$feed->updated_at) }}
                </div>
                <div class="form-group">
                    {{ Form::label('', 'Created@: '.$feed->created_at) }}
                </div>
        	</div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

                <h2>Parsing</h2>
                <div class="form-group">
                    {{ Form::label('parser', 'Parser:') }}
                    {{ Form::select('feed[parser]', array_replace(array(''=>'Select One'), fireEventAsFlatArray('laraffiliate.parsers')), $feed->parser, array('class' => 'form-control')) }}
                    <a href="{{ route('feed.map-fields', [$feed->id]) }}" class="btn btn-default">map fields</a>
                </div>
                <div class="form-group">
                    {{ Form::label('category_field', 'Category Field:') }}
                    {{ Form::select('feed[category_field]', $feed->fields(), $feed->category_field, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('target_category_model', 'Target Categories Model:') }}
                    {{ Form::select('feed[target_category_model]', array_replace(array(''=>'Select One'), fireEventAsFlatArray('laraffiliate.target-models')), $feed->target_category_model, array('class' => 'form-control')) }}
                </div>

                <h2>Sample</h2>
                {{ niceprintr($feed->sample) }}
                <a href="{{ route('feed.refresh_sample', [$feed->id]) }}" class="btn btn-primary">{{trans('laraffiliate::actions.refresh_sample')}}</a>
                <a class="btn btn-flat btn-primary" href="{{route('feed.map_categories', [$feed->id])}}">{{trans('laraffiliate::actions.map_categories')}}</a>
                <a class="btn btn-flat btn-primary" href="{{route('feed.parse', [$feed->id])}}">{{trans('laraffiliate::actions.preview_parse_feed')}}</a>
                <a class="btn btn-flat btn-primary" href="{{route('feed.parse', [$feed->id, true])}}">{{trans('laraffiliate::actions.do_parse_feed')}}</a>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button class="btn btn-success" type="submit"><i class="fa fa-check-square-o"></i> Save</button>
        <a class="btn btn-danger btn-cancel"><i class="fa fa-square-o"></i> Cancel</a>
        <button class="btn btn-warning pull-right" type="submit" name="saveNew" value="1"><i class="fa fa-plus"></i> Save as new</button>
    </div>
    {{ Form::close() }}
</div>
@stop