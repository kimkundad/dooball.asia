<style>
.db-match {
    padding: 10px 20px;
    color: #d21d29;
    background: #ffffff;
}
.new_my_time{
  font-size: 26px;
}
.day_my{
  font-size: 18px;
}
.match-over .home-team, .match-over .away-team {
    padding-top: 35px;
    font-size: 18px;
}
.mv_vs{
  padding-top: 30px;
  font-size: 24px;
}
.name_ll{
  height: 25px;
    text-align: right;
    background: #d21d29;
    color: #fff;
    padding-right: 15px;
    padding-left: 15px;
    font-size: 13px;
}
.border_match{
  border-right: 1px solid #7b030b;
    border-bottom: 1px solid #7b030b;
    border-left: 1px solid #7b030b;
}
.text-r{
  text-align: right;
}
.db-match:not(.match-0):hover {
    cursor: pointer;
    background: #fff;
  }
  .ffp-outmost .db-match:not(.match-0):hover {
    cursor: pointer;
    background: #fff;
  }
</style>
<div class="match-over">
    @if($matches)
        @if($matches['total'] != 0)
            <div class="db-collapse ">
                <div class="db-match match-0">
                    <span class="match-time new_my_time">วันเวลา</span>
                    <span class="home-team">ทีมเหย้า</span>
                    <span class="vs">Vs</span>
                    <span class="away-team">ทีมเยือน</span>
                    <span class="league-name">ชื่อลีก</span>
                </div>
            </div>

            @foreach($matches['records'] as $k => $val)
                <div class="db-collapse border_match">
                    <div class="db-match match-{{ ($k+1) }}">
                        <?php $pieces = explode(",", $val->match_time); ?>
                        <span class="match-time new_my_time">{{ $pieces[1] }} <br /> <strong class="day_my">{{ $pieces[0] }}</strong></span>
                        <span class="home-team">{{ $val->home_team }}</span>
                        <span class="vs mv_vs">-</span>
                        <span class="away-team">{{ $val->away_team }}</span>
                        <span class="league-name text-r"><strong class="name_ll">{{ $val->match_name }}</strong></span>
                    </div>
                    <div class="db-content content-{{ ($k+1) }}">
                        @if($val->sponsor_links)
                            @foreach($val->sponsor_links as $ele)
                                <p><a href="{{ $ele->url }}" target="_BLANK">{{ $ele->name }}</a></p>
                            @endforeach
                        @endif
                        @if($val->normal_links)
                            @foreach($val->normal_links as $e)
                                <p><a href="{{ $e->url }}" target="_BLANK">{{ $e->name }}</a></p>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12 pt-24 pb-24">
                <h4>-- ไม่พบรายการแข่งขันในวันนี้ --</h4>
            </div>
        @endif
    @else
        <div class="col-12 pt-24 pb-24">
            <h4>-- ไม่พบรายการแข่งขันในวันนี้ --</h4>
        </div>
    @endif
</div>
