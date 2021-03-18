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
    @if($matches)
        @if($matches['total'] != 0)
            <div class="db-collapse ">
                <div class="my_text_w db-match match-0">
                    <span class="match-time1">วันเวลา</span>
                    <span class="home-team1">ทีมเหย้า</span>
                    <span class="vs1">Vs</span>
                    <span class="away-team1">ทีมเยือน</span>
                    <span class="league-name1">ชื่อลีก</span>
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
                        <span class="league-name text-r desk_h"><strong class="name_ll">{{ $val->match_name }}</strong></span>
                    </div>
                    <span class="league-name text-r mobile_size"><strong class="name_ll">{{ $val->match_name }}</strong></span>
                    <div class="db-content content-{{ ($k+1) }} dd-padd">
                      <ul class="start_ul">
                        @if($val->sponsor_links)
                            @foreach($val->sponsor_links as $ele)

                              <li class="l-left">
                                <a href="{{ $ele->url }}" target="_BLANK">{{ $ele->name }}</a>
                              </li>

                            @endforeach
                        @endif
                        </ul>
                        <ul class="start_ul"><strong class="h_line_link">ลิงค์ดูบอล</strong>
                        @if($val->normal_links)
                            @foreach($val->normal_links as $e)

                                  <li class="l-left">
                                    <a href="{{ $e->url }}" target="_BLANK">{{ $e->name }}</a>
                                  </li>

                            @endforeach
                        @endif
                        </ul>
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
<style>
.row {
    margin-left: -15px;
    margin-right: -15px;
}
.col-md-6{
  float: left;
  position: relative;
    min-height: 1px;
    padding-left: 15px;
    padding-right: 15px;
        width: 50%;
}
.h2_con_link{
  font-size: 1rem !important;
    line-height: 1.25rem !important;
    text-align: left;
}
.my_card{
  background: rgba(255, 255, 255, 0.85);
  border-radius: 5px;
}
</style>
<div class="my_card">
  <div class="row">
    <div class="col-md-6">
      <h2 class="h2_con_link">ดูบอลสด เลือกดูแยกลีก</h2>
    </div>
    <div class="col-md-6">
      <h2 class="h2_con_link">ลิ้งดูบอลออนไลน์ แยกทีม</h2>
    </div>
  </div>
</div>
