
<div class="row">
    <div class="col-lg-9 col-sm-12">

        @var $feed_map = array_merge($parser->config, $feed->mapAsArray());

        <div class="form-group">
            {{ Form::label('initial-status', 'Initial Status:') }}
            {{ Form::select('config[initial-status]', array('A' => 'Active', 'D' => 'Disabled'), $feed_map['initial-status'], array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('name-from', 'Make Product Name from (in this sequence):') }}
            {{ Form::text('config[name-from]', $feed_map['name-from'],
            array('class' => 'form-control', 'data-role' => 'tagsinput')
            ) }}
        </div>

        <div class="form-group">
            {{ Form::label('seo-title-from', 'Make Product SEO Title from (in this sequence):') }}
            {{ Form::text('config[seo-title-from]', $feed_map['seo-title-from'],
            array('class' => 'form-control', 'data-role' => 'tagsinput')
            ) }}
        </div>

        <div class="form-group">
            {{ Form::label('seo-description-from', 'Make Product SEO Title from (in this sequence):') }}
            {{ Form::text('config[seo-description-from]', $feed_map['seo-description-from'],
            array('class' => 'form-control', 'data-role' => 'tagsinput')
            ) }}
        </div>

        <div class="form-group">
            {{ Form::label('seo-keywords-from', 'Make Product SEO Title from (in this sequence):') }}
            {{ Form::text('config[seo-keywords-from]', $feed_map['seo-keywords-from'],
            array('class' => 'form-control', 'data-role' => 'tagsinput')
            ) }}
        </div>

        <h2>Meta Fields</h2>
        @foreach (Config::get('laracart::product-meta') as $key => $name)
        <div class="form-group">
            {{ Form::label("meta-$key", $name) }}
            {{ Form::text("config[meta-fields][$key]", (isset($feed_map['meta-fields'][$key])) ? $feed_map['meta-fields'][$key] : '' , array('class' => 'form-control', 'list' => 'fields')) }}
        </div>
        @endforeach

        <datalist id="fields">
            @foreach ($feed->fields() as $field)
            <option value="{{$field}}">{{$field}}</option>
            @endforeach
        </datalist>

    </div>
    <div class="col-lg-3 col-sm-12">
        Available fields:<br/>
        <br/>
        {{ implode('<br/>', $feed->fields())}}
    </div>
</div>