@extends('laradmin::layout')

@section('page-title')
{{ !$merchant->id ? 'Add' : 'Edit' }} Merchant
@stop

@section('page-subtitle')
{{ $merchant->name }} ({{ $merchant->id }})
@stop

@section('breadcrumb')
@parent
<li><a href="{{ route('merchant.list') }}" class="goOnCancel"><i class="fa fa-group"></i> Manage Merchants</a></li>
<li class="active">{{ !$merchant->id ? 'Add' : 'Edit' }} Merchant</li>
@stop

@section('page-menu')
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('content')
<div class="box box-primary">
    {{ Form::open(array('route' => ['merchant.save', $merchant->id], 'method' => 'POST', 'role' => 'form')) }}
    <div class="box-body">
        <div class="row">
        	<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <h2>General</h2>
                <div class="form-group">
                    {{ Form::label('name', 'Name:') }}
                    {{ Form::text('merchant[name]', $merchant->name, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('title', 'Title:') }}
                    {{ Form::text('merchant[title]', $merchant->title, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('slug', 'Slug:') }}
                    {{ Form::text('merchant[slug]', $merchant->slug, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('description', 'Description:') }}
                    {{ Form::textarea('merchant[description]', $merchant->description, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('logo_url', 'Logo_url:') }}
                    {{ Form::text('merchant[logo_url]', $merchant->logo_url, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('merchant_url', 'Merchant_url:') }}
                    {{ Form::text('merchant[merchant_url]', $merchant->merchant_url, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('city_street', 'City_street:') }}
                    {{ Form::text('merchant[city_street]', $merchant->city_street, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('rss_url', 'Rss_url:') }}
                    {{ Form::text('merchant[rss_url]', $merchant->rss_url, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('status', 'Status:') }}
                    {{ Form::select('merchant[status]', array('A' => 'Active', 'D' => 'Disabled'), $merchant->status, array('class' => 'form-control')) }}
                </div>
        	</div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <h2>Network</h2>
            	<div class="form-group">
                    {{ Form::label('status', 'Campaign ID @ Network:') }}
                    {{ Form::text('merchant[network_campaign_id]', $merchant->network_campaign_id, array('class' => 'form-control')) }}
            	</div>

                <h2>Timestamps</h2>
            	<div class="form-group">
                    {{ Form::label('', 'Updated@: '.$merchant->updated_at) }}
            	</div>
            	<div class="form-group">
                    {{ Form::label('', 'Created@: '.$merchant->created_at) }}
            	</div>

                <h2>Feeds</h2>
                @foreach ($merchant->feeds as $feed)
                <div class="form-group">
                    <h4><a href="{{route('feed.update', [$feed->id])}}">Feed: {{ $feed->name }} ({{ $feed->id }})</a><br/></h4>
                    <a href="{{ $feed->feed_url }}" title="{{ $feed->feed_url }}" target="_blank">{{ Str::limit($feed->feed_url, 100) }}</a>
                </div>
                @endforeach
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