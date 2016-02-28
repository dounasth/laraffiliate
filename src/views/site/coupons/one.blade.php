@extends('laradmin::site.site-layout')

@var $offercats = (fn_is_not_empty($offer->categories->category)) ? "για ".$offer->categories->category : ''
@var $from = date("d-m-Y", strtotime($offer->valid_from));
@var $until = date("d-m-Y", strtotime($offer->end_date));

@section('page-title')
{{ "Κουπόνι {$offercats}" }} στο {{$offer->program_name}} από {{$from}} μέχρι {{$until}}
@stop

@section('page-subtitle')
@stop

@section('styles')
<link href="{{ Config::get('laradmin::general.theme_path') }}/assets/css/blog.css" rel="stylesheet">
<style type="text/css">
    .coupon {
        text-align: center;
    }
    .coupon .code {
        font-size: 40px;
        padding: 30px;
        border-radius: 50%;
        background: #e74c3c;
        display: inline-block;
        color: white;
    }
    .coupon .shop{
        font-size: 18px;
        line-height: 24px;
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .coupon .date{
        font-size: 18px;
        line-height: 24px;
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .coupon .title{
        font-size: 24px;
        line-height: 36px;
        padding-bottom: 10px;
    }
</style>
@stop

@section('scripts')
@stop

@section('content')


<div class="blog-wrapper parallaxOffset">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-centered blog-left">
                <h1 class="title-big text-center section-title-style2">
                    <span> {{ "Κουπόνι {$offercats}" }} </span>
                </h1>
                <div class="row">
                    <div class="col-sm-12 col-lg-4 col-md-4 col-xs-12 well coupon">
                        <a href="{{$offer->tracking_url}}" target="_blank">
                            <div class="code">{{$offer->coupon_code}}</div>
                            <div class="shop">στο {{$offer->program_name}}</div>
                            <div class="date">από {{$from}}</div>
                            <div class="date">μέχρι {{$until}}</div>
                        </a>
                    </div>
                    <div class="col-sm-12 col-lg-8 col-md-8 col-xs-12">
                        <p class="lead text-center">
                            {{ $offer->description }}
                            <br/>
                            <br/>
                            <a href="{{$offer->tracking_url}}" target="_blank" class="btn btn-more">
                                Χρήση κουπονιού
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row featuredPostContainer style2">
            <h3 class="section-title style2 text-center"><span>Παπουτσια απο το {{$offer->program_name}}</span></h3>
            <div id="productslider" class="owl-carousel owl-theme">
                @foreach ($products as $product)
                <div class="item">
                    @include('laracart::site.product-mini', ['product'=>$product])
                </div>
                @endforeach
            </div>

        </div>

    </div>
</div>
<div class="gap"></div>


@stop