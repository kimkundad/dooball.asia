@extends('frontend.layouts.new-theme')

@section('title')
    <title>{{ $seo_title }}</title>
@endsection

@if($website_robot == 1)
    @section('robots')
        <meta name="robots" content="index, follow">
    @endsection
@endif

@section('description')
    <meta name="description" content="{{ $seo_description }}">
@endsection

@section('fb_url')
    <meta property="og:url"				content="https://dooball-th.com/ทีเด็ดบอลสเต็ป2">
@endsection
@section('fb_title')
    <meta property="og:title"			content="{{ $seo_title }}">
@endsection
@section('fb_description')
    <meta property="og:description"		content="{{ $seo_description }}">
@endsection
@section('fb_image')
    <meta property="og:image" 			content="https://dooball-th.com/images/social/game_1200x600.jpg">
@endsection
@section('tw_title')
    <meta name="twitter:title"			content="{{ $seo_title }}">
@endsection
@section('tw_description')
    <meta name="twitter:description"	content="{{ $seo_description }}">
@endsection
@section('tw_image')
    <meta name="twitter:image" content="https://dooball-th.com/images/game_800x418.jpg">
@endsection
@section('global_url')
    <link href="https://dooball-th.com/ทีเด็ดบอลสเต็ป2" rel="canonical">
@endsection

@section('content')
<div class="container main-content">
    @include('frontend.layouts.new-theme-top-nav')
    @include('frontend.layouts.new-theme-navbar')
    @include('frontend._partials.new-theme.league-slider')
    @include('frontend.layouts.new-theme-navbar-second')

    <div class="content-box">
        <div class="row my-4 prediction-table-area">
            <div class="col-12">
                {!! $top_content !!}

                {{-- <h1 class="prediction-title">ทีเด็ดบอลล้มโต๊ะ ทีเด็ดฟุตบอลวันนี้</h1>
                <p>
                    ทีเด็ดบอล ล้มโต๊ะ ที่ว่าแน่น 4 เซียน 5 เซียน 7 เซียน ฟันธง บอลชุด บอลสเต็ป บอลแม่นๆ วันนี้ มีอัพเดต ล้มโต๊ะ วันละ1ทีม ทีเด็ดบอลวันนี้ จากเหล่าเซียนบอลชั้นนำ บอลแม่นๆวันละ 3 เน้นๆ บอลสเต็ปเทพ รวมทีเด็ดเว็บ DoobalTH เซียนสเต็ปบอลชัวร์ 100 ฟรี จัดเต็มวิเคราะห์บอล จากข้อมูล สถิติย้อนหลัง เหย้าเยือน ล้มโต๊ะวันนี้ จัดเต็มทุกคู่ บอลวันนี้ 4 คู่ ฟรีเน้นๆ ฟันธง ทีเด็ดบอลชุด
                </p> --}}

                <div class="table-responsive">
                    <table class="table table-condensed text-white">
                        <thead class="card-prediction">
                            <tr>
                                <th class="text-center">วันที่</th>
                                <th class="text-center">ทีม 1</th>
                                <th class="text-center">ทีม 2</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($tded_datas) > 0)
                                @foreach($tded_datas as $tded)
                                    <tr>
                                        <td class="text-center">{{ $tded['match_date'] }}</td>
                                        <td class="text-center">
                                            <div class="d-flex just-center">
                                                {{ $tded['selected_one'] }}&nbsp;&nbsp;
                                                {!! $tded['result_one'] !!}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex just-center">
                                                {{ $tded['selected_two'] }}&nbsp;&nbsp;
                                                {!! $tded['result_two'] !!}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        @if($prev != '' || $next != '')
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-left">{!! $prev !!}</th>
                                    <th class="text-right">{!! $next !!}</th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                <br>
                {!! $bottom_content !!}
            </div>
        </div>
    </div>
    <div class="d-flex alit-center just-center banner-content">BANNER</div>
</div>
{{-- <input type="hidden" id="http_host" value="{{ $httpHost . $domain }}" /> --}}
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <script>
        // var thisHost = $('#http_host').val();
        var baseURL = $('#base_url').val();
        var fakeIp = '58.18.145.72';

        var apiKey = 'vYjzXcOwJFl5UoSN';
        var apiSecret = 'bhMd1P4MD6dmbUXGTgfQt4ndJJiE7arj';
        var apiLivescore = 'http://livescore-api.com/api-client/scores/live.json?key=' + apiKey + '&secret=' + apiSecret;

        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });

        $(function() {
            clearSlide();

            // loadLivescoreAPI();
        });

        function loadLivescoreAPI() {
            /*
            var xhr = new XMLHttpRequest();

            xhr.open("GET", apiLivescore, true);
            xhr.onreadystatechange = function() {
                console.log(xhr);
            }

            xhr.send();
            */

            // $.get(apiLivescore, function( data ) {
            //     console.log(data);
            // });

            /*
            var params = {};

            $.ajax({
                url: apiLivescore,
                type: 'GET',
                // data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    console.log(response);
                    arrangeData(response);
                },
                error: function(response) {
                    console.log(response);
                }
            });*/
        }

        function arrangeData(response) {
            // tbody_livescore
        }
    </script>
@stop