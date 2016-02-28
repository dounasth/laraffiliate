@extends('laradmin::layout')

@section('page-title')
Skroutz Categories Importer
@stop

@section('page-subtitle')
dashboard subtitle, some description must be here
@stop

@section('breadcrumb')
@parent
<li class="active">Skroutz Categories Importer</li>
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
                {{ImportSkroutzCategories::printTree($categories)}}
            </div>
        </div>

    </div>
</div>


@stop