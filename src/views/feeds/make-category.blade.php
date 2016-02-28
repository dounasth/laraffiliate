
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="ajaxModalLabel">Δημιουργία νέας κατηγορίας</h4>
        </div>
        <div class="modal-body">
            <br/>
            <input type="hidden" name="feedid" value="{{$feed->id}}" class="form-control">
            <input type="text" name="name" value="{{$feed_category}}" class="form-control">
            <input type="text" name="seoname" value="{{$feed_category}}" class="form-control">
            <br/>
            <div class="btn-group nestable-menu">
                <a type="button" class="btn btn-default" data-action="expand-all">Expand All</a>
                <a id="collapser" type="button" class="btn btn-default" data-action="collapse-all">Collapse All</a>
            </div>
            <div class=" dd nestable">
                <ol class="dd-list ">
                    @foreach($categories as $category)
                    @include('laracart::categories.partials.row', ['category'=>$category, 'readonly' => true, 'checkable' => true])
                    @endforeach
                </ol>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="modal-footer">
            <button id="save-category" type="button" class="btn btn-success pull-left" data-dismiss="modal">Save</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
    </div>
</div>

<style type="text/css">
    .dd3-content {
        padding: 5px 10px 5px 40px;
        line-height: 20px;
    }
</style>
<script type="text/javascript">
    jQuery(document).ready(function() {

        $('.nestable').each(function () {
            $(this).nestable({
                group: 1
            }).on('change', updateFromNestable);
        });

        $('.nestable-menu').on('click', function(e)
        {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });
        jQuery('#collapser').click();

        jQuery('#save-category').click(function(){
            var data = {};
            data.feedid = jQuery('input[name="feedid"]').val();
            data.name = jQuery('input[name="name"]').val();
            data.seoname = jQuery('input[name="seoname"]').val();
            data.pos = jQuery('input[name="selection"]:checked').val();
            data.sibling = jQuery('input[name="selection"]:checked').closest('li').data('id');
            data.parent = jQuery('input[name="selection"]:checked').closest('li').parent().closest('li').data('id');
            var url = '{{route("feed.save-pos-category")}}'
            jQuery.get(url, data, function(response) {
                console.info(response);
                reloadCategories();
            });
            return confirm('close?');
        });

    });
    function updateFromNestable(e) {
//        var list = e.length ? e : $(e.target);
//        var action_url = $(e.currentTarget).data('action');
//        $.post(action_url, {
//            menu_order: list.nestable('serialize')
//        }, function (response) {
////                notifyJs(response);
//        });
    }
</script>