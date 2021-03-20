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
.row:before{
  content: " ";
    display: table;
}
.col-md-6{
  float: left;
  position: relative;
    min-height: 1px;
    padding-left: 15px;
    padding-right: 15px;
        width: 50%;
}
.col-md-12{
  width: 100%;
  position: relative;
    min-height: 1px;
    padding-left: 15px;
    padding-right: 15px;
}

.my_card{
  min-height: 900px;
  margin-top:35px;
  padding: 15px;
}
.dashboard-list-box {
    margin: 30px 0 0 0;
    box-shadow: 0 0 12px 0 rgb(0 0 0 / 6%);
    border-radius: 4px;
}
.dashboard-list-box ul {
    list-style: none;
    padding: 0;
    margin: 0;
    background-color: #fff;
    border-radius: 0 0 4px 4px;
}
.dashboard-list-box ul li {
    margin-top: 15px;
    padding: 30px 30px;
    border: 1px solid #d21d29;
    transition: 0.3s;
    position: relative;
}
.dashboard-list-box.with-icons ul li {
    padding-left: 87px;
}
.detail-h5{
    margin-top: 5px;
    text-align: left;
}
.app1 {position:relative;}
.blocklink{
  position:absolute;top:0px;left:0px;width:100%;height:100%;
  text-decoration:none;
  padding: 10px 15px 10px 20px;
  }​
  .h2_con_link{
    font-size: 24px;
      line-height: 1.25rem !important;
      text-align: left;
          color: #666;
  }
  @media screen and (max-width: 767px) {
    .h2_con_link{
      font-size: 14px;
    font-weight: 600;
    }
    .detail-h5{
      font-size: 13px;
    font-weight: 600;
    }
    .dashboard-list-box ul li {
        margin-top: 10px;
    }
  }
</style>


<div class="my_card">
  <div class="row">
      <div class="col-md-6">
        <h2 class="h2_con_link">ดูบอลแยกลีก</h2>
        <div class="dashboard-list-box">
            <ul>
              <li class="app1">
                <a href="/premierleague" class="blocklink"><h5 class="detail-h5">ดูบอลพรีเมียร์ลีก</h5></a>
              </li>
              <li class="app1">
                <a href="/thaileague" class="blocklink"><h5 class="detail-h5">ดูบอลไทย</h5></a>
              </li>
              <li class="app1">
                <a href="/jleague" class="blocklink"><h5 class="detail-h5">ดูบอลเจลีก</h5></a>
              </li>
              <li class="app1">
                <a href="/basketball" class="blocklink"><h5 class="detail-h5">ดูบาสสด ออนไลน์</h5></a>
              </li>
              <li class="app1">
                <a href="/bundesliga" class="blocklink"><h5 class="detail-h5">ดูบอลบุนเดสลีกา</h5></a>
              </li>
              <li class="app1">
                <a href="/laliga" class="blocklink"><h5 class="detail-h5">ดูบอลลาลีกา</h5></a>
              </li>
              <li class="app1">
                <a href="/calcio" class="blocklink"><h5 class="detail-h5">บอลกัลโช่</h5></a>
              </li>
              <li class="app1">
                <a href="/ligue" class="blocklink"><h5 class="detail-h5">บอล ลีกเอิง ฝรั่งเศส</h5></a>
              </li>
              <li class="app1">
                <a href="/carabao-cup" class="blocklink"><h5 class="detail-h5">บอลคาราบาวคัพ</h5></a>
              </li>
              <li class="app1">
                <a href="/fa-cup" class="blocklink"><h5 class="detail-h5">บอลเอฟเอคัพ</h5></a>
              </li>
              <li class="app1">
                <a href="/europa" class="blocklink"><h5 class="detail-h5">บอลยูโรป้า</h5></a>
              </li>
            </ul>
        </div>
      </div>
      <div class="col-md-6">
        <h2 class="h2_con_link">ดูบอลแยกทีม</h2>
        <div class="dashboard-list-box">
            <ul>
              <li class="app1">
                <a href="/liverpool" class="blocklink"><h5 class="detail-h5">ดูบอลสด ลิเวอร์พูล</h5></a>
              </li>
              <li class="app1">
                <a href="/manu" class="blocklink"><h5 class="detail-h5">ดูบอลสด แมนยู</h5></a>
              </li>
              <li class="app1">
                <a href="/sapporo" class="blocklink"><h5 class="detail-h5">ดูบอลสด ซัปโปโร</h5></a>
              </li>
              <li class="app1">
                <a href="/arsenal" class="blocklink"><h5 class="detail-h5">ดูบอล อาร์เซน่อล</h5></a>
              </li>
              <li class="app1">
                <a href="/Chelsea" class="blocklink"><h5 class="detail-h5">ดูบอล เชลซี</h5></a>
              </li>
              <li class="app1">
                <a href="/mancity" class="blocklink"><h5 class="detail-h5">ดูบอล แมนซิตี้</h5></a>
              </li>
              <li class="app1">
                <a href="/burirum" class="blocklink"><h5 class="detail-h5">ดูบอล บุรีรัมย์</h5></a>
              </li>
              <li class="app1">
                <a href="/juventus" class="blocklink"><h5 class="detail-h5">ยูเวนตุส ล่าสุด</h5></a>
              </li>
              <li class="app1">
                <a href="/barcelona" class="blocklink"><h5 class="detail-h5">บาร์เซโลน่า คืนนี้สด</h5></a>
              </li>
              <li class="app1">
                <a href="/realmadrid" class="blocklink"><h5 class="detail-h5">เรอัล มาดริด ล่าสุด</h5></a>
              </li>

            </ul>
        </div>
      </div>
  </div>
</div>
<br /><br /><br /><br />
