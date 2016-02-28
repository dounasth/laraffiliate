@extends('laradmin::layout')

@section('page-title')
Google Categories Importer
@stop

@section('page-subtitle')
dashboard subtitle, some description must be here
@stop

@section('breadcrumb')
@parent
<li class="active">Google Categories Importer</li>
@stop

@section('page-menu')
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('content')

<div class="row">
    <div class="col-md-9">

        <div class="box box-primary">
            <div class="box-body table-responsive">
                @foreach ($categories as $cat => $subs)
                {{'<a href="'. route('utilities.google-categories.import', [$cat]) .'">' .$cat.'</a><br/>'}}
                @endforeach
            </div>
        </div>

    </div>
</div>


@stop