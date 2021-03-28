<style>
.db-match {
    padding: 10px 20px;
    color: #d21d29;
    background: #ffffff;
    border-left: 1px solid #d21d29;
border-right: 1px solid #d21d29;
border-bottom: 1px solid #d21d29
}
.new_my_time{
  font-size: 26px;
}
.day_my{
  font-size: 12px;
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
  .my_text_w{
    color: #fff;
  }
  .mobile_size{
    display: none;
  }
  .desk_h{
    display: block;
  }
  .db-content {
    background: rgb(255 255 255);
}
.l-left a{
  text-align: left;
  color: #007bff;
    text-decoration: none;
    padding: 10px;
}
.l-left{
  text-align: left;
}
.dd-padd{
  padding: 10px;
  text-align: left;
}
.h_line_link{
  font-size: 18px;
  font-weight: 600;
}
.start_ul{
  margin-left: 20px;
}
  @media screen and (max-width: 767px) {
    .db-match .league-name{
      width:100%
    }
    .mobile_size{
      display: block;
      background: #d21d29;
      width:100%;
      text-align: center;
    }
    .desk_h{
      display: none;
    }
    .name_ll{
      height: 25px;
        text-align: center;
        color: #fff;
        padding-right: 15px;
        padding-left: 15px;
        font-size: 13px;
    }
  }
</style>
<div class="match-over">
    @if(count($matches) > 0)
        <div class="db-collapse">
          <div class="my_text_w db-match match-0">
              <span class="match-time1">วันเวลา</span>
              <span class="home-team1">ทีมเหย้า</span>
              <span class="vs1">Vs</span>
              <span class="away-team1">ทีมเยือน</span>
              <span class="league-name1">ชื่อลีก</span>
          </div>
        </div>

        <?php
            $common = new App\Http\Controllers\API\CommonController();
        ?>

        @foreach($matches as $k => $val)
            <div class="db-collapse">
                <div class="db-match match-{{ ($k+1) }}">
                    <span class="match-time">{{ $common->showDayOnly(strtotime($val->kickoff_on)) }}</span>
                    <span class="home-team">{{ $val->team_home_name }}</span>
                    <span class="vs">Vs</span>
                    <span class="away-team">{{ $val->team_away_name }}</span>
                    <span class="league-name">{{ $val->program_name }}</span>
                </div>
                <div class="db-content content-{{ ($k+1) }}">
                    @if($val->links)
                        @foreach($val->links as $ele)
                            <p><a href="{{ $ele->url }}" target="_BLANK">{{ $ele->name }}</a></p>
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
</div>
