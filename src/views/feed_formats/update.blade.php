@extends('laradmin::layout')

@section('page-title')
{{ !$format->id ? 'Add' : 'Edit' }} Feed Format
@stop

@section('page-subtitle')
{{ $format->title }} ({{ $format->id }})
@stop

@section('breadcrumb')
@parent
<li><a href="{{ route('feed_format.list') }}" class="goOnCancel"><i class="fa fa-group"></i> Manage Feed Formats</a></li>
<li class="active">{{ !$format->id ? 'Add' : 'Edit' }} Feed</li>
@stop

@section('page-menu')
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('content')
<div class="box box-primary">
    {{ Form::open(array('route' => ['feed_format.save', $format->id], 'method' => 'POST', 'role' => 'form')) }}
    <div class="box-body">
            <div class="form-group">
                {{ Form::label('title', 'Title:') }}
                {{ Form::text('feed_format[title]', $format->title, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('format', 'Format:') }}
                {{ Form::text('feed_format[format]', $format->format, array('class' => 'form-control')) }}
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