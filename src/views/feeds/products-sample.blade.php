<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="ajaxModalLabel">{{$category}}</h4>
        </div>
        <div class="modal-body">
            @foreach ($sample as $row)
                <img src="{{$row['IMAGE_URL']}}" style="max-height: 200px; max-width: 32%; display: inline-block;"/>
            @endforeach
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>