@extends('laradmin::layout')

@section('page-title')
Map Feed Categories
@stop

@section('page-subtitle')
    @if ($feed->id)
        of feed <a href="{{route('feed.update', [$feed->id])}}">{{ $feed->name }} ({{ $feed->id }})</a>
        for merchant <a href="{{route('merchant.update', [$feed->merchant->id])}}">{{ $feed->merchant->name }} ({{ $feed->merchant->id }})</a>
    @endif
@stop

@section('breadcrumb')
@parent
<li class="active">Map Feed Categories</li>
@stop

@section('page-menu')
@stop

@section('styles')
<!-- DATA TABLES -->
<link href="{{ Config::get('laradmin::general.asset_path') }}/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="{{ Config::get('laradmin::general.asset_path') }}/css/bootstrap-tags/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    #example1 .bootstrap-tagsinput {
        width: auto !important;
    }
</style>
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
<script src="{{ Config::get('laradmin::general.asset_path') }}/js/bootstrap-tags/bootstrap-tagsinput.js" type="text/javascript"></script>
<script src="{{ Config::get('laradmin::general.asset_path') }}/js/typeahead.bundle.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
    var categories = new Bloodhound({
        name: 'categories',
        datumTokenizer: function(d) {
            return Bloodhound.tokenizers.whitespace(d.name);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: '/json/categories'
    });
    categories.initialize();

    var elms = $('.categories');
    elms.tagsinput({
        maxTags: 1,
        freeInput: false,
        itemValue: 'id',
        itemText: 'name',
        typeaheadjs: {
            highlight: true,
            limit: 20,
            name: 'categories',
            displayKey: 'name',
            source: categories.ttAdapter()
        }
    });
    elms.each(function(i, elm){
        var id = $(elm).attr('data-selected-id');
        if (id > 0) {
            var name = $(elm).attr('data-selected-name');
            $(elm).tagsinput('add', { "id": id , "name": name });
        }
    });
    elms.on('itemAdded', function(event) {
        // event.item: contains the item
        var url = "{{ route('aff.feed.map-feed-cat', ['XX', 'YY']) }}";
        url = url.replace('XX', $(event.target).attr('data-feed-cat-id'));
        url = url.replace('YY', event.item.id);
        $.ajax({
            type: "POST",
            url: url,
            data: {},
            success: function(response){
                console.info(response);
            },
            dataType: 'json'
        });
    });

    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });
</script>
@stop

@section('content')

<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="ajaxModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<div class="box box-primary">
    <div class="box-body table-responsive">
        <div class="input-group margin">
            <input id="map-checked" type="text" name="mapto_category_id" value="" class="form-control categories" data-role="tagsinput" />
            <span class="input-group-btn">
                <button class="btn btn-default">map selected to this one</button>
            </span>
        </div>
    </div>
</div>

<div class="box box-primary">
    <div class="box-body table-responsive">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Current Mapping</th>
                <th>Map To</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $ic)
            @var $cm = $categoryModel::find($ic->mapto_category_id)
            <tr>
                <td>
                    <input id="check_{{ $ic->id }}" type="checkbox" value="{{ $ic->id }}">{{ $ic->id }}
                </td>
                <td>
                    <label for="check_{{ $ic->id }}">{{ $ic->category }}</label>
                    <a data-toggle="modal" href="{{ route('feed.products-sample', [$feed->id, urlencode($ic->category)]) }}"
                       data-target="#ajaxModal" class="btn btn-default btn-small">sample</a>
                </td>
                <td>
                    {{ ($cm) ? $cm->path() : '---' }}
                </td>
                <td>
                    <input id="{{ 'mapto_category_id_'.$ic->id }}" type="text" name="mapto_category_id" value="{{ $ic->mapto_category_id }}"
                           class="form-control categories" data-role="tagsinput"
                           data-feed-cat-id="{{ $ic->id }}"
                           data-selected-id="{{ ($cm) ? $cm->id : 0 }}"
                           data-selected-name="{{ ($cm) ? $cm->path() : '' }}"
                    />
                    <button class="btn btn-default">unmap</button>
                </td>
            </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <<th>ID</th>
                <th>Name</th>
                <th>Current Mapping</th>
                <th>Map To</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

@stop