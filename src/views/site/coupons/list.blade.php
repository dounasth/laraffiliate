@extends('laradmin::site.site-layout')

@section('page-title')
Κουπόνια και προσφορές
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
                    <span> Κουπόνια και προσφορές </span>
                </h1>
                <p class="lead text-center">
                </p>
                <div class="row">
                @foreach ($xml->offer as $offer)
                    @var $offercats = (fn_is_not_empty($offer->categories->category)) ? "για ".$offer->categories->category : ''
                    @var $until = date("d-m-Y", strtotime($offer->end_date));
                    <div class="col-sm-12 col-lg-4 col-md-4 col-xs-12 well coupon">
                        <a href="{{$offer->tracking_url}}" target="_blank">
                            <div class="title">{{ "Κουπόνι {$offercats}" }}</div>
                            <div class="code">{{$offer->coupon_code}}</div>
                            <div class="shop">στο {{$offer->program_name}}</div>
                            <div class="date">μέχρι {{$until}}</div>
                        </a>
                        <a href="{{ route('site.coupons.one', [$offer->creative_id]) }}" class="btn btn-more">
                            Όροι κουπονιού
                        </a>
                        <a href="{{$offer->tracking_url}}" target="_blank" class="btn btn-more">
                            Χρήση κουπονιού
                        </a>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="gap"></div>


@stop