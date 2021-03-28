<?php

namespace App\Http\Controllers\API\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\Front\PageController as PageAPI;
use App\Models\Match;
use App\Models\MatchLink;
use App\Models\Page;
use App\Models\General;
use Illuminate\Support\Facades\DB;
use App\Models\Key;
use \stdClass;

class WelcomeController extends Controller
{
    private $common;
    private $page;

    public function __construct()
    {
        $this->common = new CommonController();
        $this->page = new PageAPI();
    }

    public function generalData()
    {
        $web = new stdClass();
        $web->website_name = '';
        $web->media_id = 0;
        $web->web_image = asset('frontend/images/logo.png');
        $web->website_description = '';
        $web->website_robot = 0;
        $web->website_gg_ua = '';

        $wData = $this->webData();
        if ($wData) {
            $web = $wData;
        }

        return $web;
    }

    public function webData()
    {
        $web = new stdClass();
        $web->website_name = '';
        $web->media_id = 0;
        $web->web_image = asset('frontend/images/logo.png');
        $web->website_description = '';
        $web->website_robot = 0;
        $web->website_gg_ua = '';

        // ------------- start get general --------------- //
        // DB::enableQueryLog();
        $gnData = General::find(1);
        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // dd(DB::getQueryLog()[0]['time']);

        if ($gnData) {
            $web = $gnData;

            if ((int) $web->media_id != 0) {
                $image = $this->common->getImage($web->media_id);
                if ($image) {
                    $web->web_image = asset('storage' . $image);
                }
            }
        }
        // ------------- end get general --------------- //

        return $web;
    }

    public function matchDatas()
    {
        $total = 0;
        $matchDatas = null;

        $date = new \DateTime();
        $date->modify('-120 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');

        // --- start new algorithm --- //
		$mid_this_date = Date('Y-m-d 11:00:00');
        $ten_tomorrow_date = Date('Y-m-d 10:00:00', strtotime("+1 days"));
        //dd($ten_tomorrow_date);

		if (strtotime(date("Y-m-d H:i:s")) < strtotime(date("Y-m-d 10:00:00"))) {
			$mid_this_date = Date('Y-m-d 12:00:00', strtotime("-1 days"));
            $ten_tomorrow_date = Date('Y-m-d 10:00:00');

		} else {
            $mid_this_date = Date('Y-m-d H:i:s', strtotime("-150 minutes"));
        }
        // --- end new algorithm --- //

        // DB::enableQueryLog();
        $matches = Match::whereBetween('match_time', [$mid_this_date, $ten_tomorrow_date])
                    ->where('match_time', '>', $formatted_date)
                    ->orderBy('match_time', 'asc');
        //dd($matches);

        $total = $matches->count();
        // $q = DB::getQueryLog()[0]['query'];
        // dd($total);

        if ($total > 0) {
            $matchDatas = $matches->get();
            foreach ($matchDatas as $val) {
                $val->match_time = $this->common->showDayOnly(strtotime($val->match_time));

                $linkSpsDatas = MatchLink::where('match_id', $val->id)->where('link_type', 'Sponsor');
                if ($linkSpsDatas->count() > 0) {
                    $tspon_links = $linkSpsDatas->get();
                    $val->sponsor_links = collect($tspon_links)->sortBy('link_seq');
                }

                $linkNmDatas = MatchLink::where('match_id', $val->id)->where('link_type', 'Normal');
                if ($linkNmDatas->count() > 0) {
                    $tlinks = $linkNmDatas->get();
                    $val->normal_links = collect($tlinks)->sortBy('link_seq');
                }
            }
        }
    //    dd($matchDatas);

        return array('total' => $total, 'records' => $matchDatas);
    }

    public function filterMatchDatas($page = null)
    {
        $total = 0;
        $matchDatas = array();
        $get_all_team = array();
        if ($page) {
            $pageCondition = $page->page_condition;
            $league_name = ($page->league_name) ? trim($page->league_name) : '';
            $team_name = ($page->team_name) ? trim($page->team_name) : '';
          //  dd($page->league_name);
          //  dd($league_name); พรีเมียร์ลีก SELECT * FROM `matches` WHERE `match_name` = 'พรีเมียร์ลีก'

            // --- start new algorithm --- //
            $mid_this_date = Date('Y-m-d 11:00:00');
            $ten_tomorrow_date = Date('Y-m-d 10:00:00', strtotime("+1 days"));

            if (strtotime(date("Y-m-d H:i:s")) < strtotime(date("Y-m-d 10:00:00"))) {
                $mid_this_date = Date('Y-m-d 12:00:00', strtotime("-1 days"));
                $ten_tomorrow_date = Date('Y-m-d 10:00:00');
            } else {
                $mid_this_date = Date('Y-m-d H:i:s', strtotime("-150 minutes"));
            }
            // --- end new algorithm --- //

            // --- start key filter --- //
            $homeTeam = $this->page->keyInfo('page', $page, 'home_team');
            $awayTeam = $this->page->keyInfo('page', $page, 'away_team');
            $keyDate = $this->page->keyInfo('page', $page, 'key_date');
            $keyTime = $this->page->keyInfo('page', $page, 'key_time');
            $keyProgram = $this->page->keyInfo('page', $page, 'key_program');
            // --- end key filter --- //

            // DB::enableQueryLog();
            $matches = Match::whereBetween('match_time', [$mid_this_date, $ten_tomorrow_date]);
            $get_all_team = Match::whereBetween('match_time', [$mid_this_date, $ten_tomorrow_date]);



          //  dd($pageCondition);
            // L premierleague T team
            if ($pageCondition == 'L') {
                // $matches->where('match_name', 'LIKE', '%' . $league_name . '%');
                $matches->where('match_name', $page->league_name);
                $get_all_team->where('match_name', $page->league_name);

            } else if ($pageCondition == 'T') {

                $matches->where(function ($query) use ($team_name) {
                            $query->where('home_team', $team_name)
                            ->orWhere('away_team', $team_name);
                });
                $total5 = $matches->count();
                $all7 = $matches->get();
                if($total5 > 0){
                  foreach ($all7 as $all77) {

                    $l_m_name = $all77->match_name;

                  }
                }
              //  dd($l_m_name);
                $get_all_team->where('match_name', $l_m_name);



              //  dd($matches->get());

              /*  if ($homeTeam || $awayTeam) {
                    if ($homeTeam && $awayTeam) {
                        $matches->where('home_team', 'LIKE', '%' . $homeTeam . '%')->where('away_team', 'LIKE', '%' . $awayTeam . '%');
                    } else if ($homeTeam && ! $awayTeam) {
                        $matches->where('home_team', 'LIKE', '%' . $homeTeam . '%');
                    } else if (! $homeTeam && $awayTeam) {
                        $matches->where('away_team', 'LIKE', '%' . $awayTeam . '%');
                    }
                }*/


            }


            $matches->orderBy('match_time', 'asc');
            $get_all_team->orderBy('match_time', 'asc');
            $total = $matches->count();
            $total2 = $get_all_team->count();
            //dd($total);
            // $q = DB::getQueryLog()[0]['query'];
            // dd($q);

            if ($total > 0) {
                $allMatchDatas = $matches->get();
                $inCondition = 0;

                foreach ($allMatchDatas as $val) {
                    // --- start condition --- //
                    if ($keyDate || $keyTime) {
                        if ($keyDate && $keyTime) {
                            $kDateTime = $this->common->formToSqlDate($keyDate) . ' ' . $keyTime . ':00';
                            if ($val->match_time == $kDateTime) {
                                $inCondition = 1;
                            }
                        } else if ($keyDate && ! $keyTime) {
                            $mDate = $this->common->sqlToFormDate($val->match_time);
                            if ($keyDate == $mDate) {
                                $inCondition = 1;
                            }
                        } else if (! $keyDate && $keyTime) {
                            $kDateTime = $this->common->formToSqlDate(Date('d/m/Y')) . ' ' . $keyTime . ':00';
                            if ($val->match_time == $kDateTime) {
                                $inCondition = 1;
                            }
                        }
                    } else {
                        $inCondition = 1;
                    }

                    if ($keyProgram) {
                        if (trim($keyProgram) == trim($val->match_name)) {
                            $inCondition = 1;
                        } else {
                            $inCondition = 0;
                        }
                    } else {
                        $inCondition = 1;
                    }
                    // --- end condition --- //

                    if ($inCondition == 1) {
                        $val->match_time = $this->common->showDayOnly(strtotime($val->match_time));

                        $linkSpsDatas = MatchLink::where('match_id', $val->id)->where('link_type', 'Sponsor');
                        if ($linkSpsDatas->count() > 0) {
                            $tspon_links = $linkSpsDatas->get();
                            $val->sponsor_links = collect($tspon_links)->sortBy('link_seq');
                        }

                        $linkNmDatas = MatchLink::where('match_id', $val->id)->where('link_type', 'Normal');
                        if ($linkNmDatas->count() > 0) {
                            $tlinks = $linkNmDatas->get();
                            $val->normal_links = collect($tlinks)->sortBy('link_seq');
                        }
                        $matchDatas[] = $val;

                    }
                }

                $allMatchDatas2 = $get_all_team->get();
                $inCondition = 0;

                foreach ($allMatchDatas2 as $val) {
                    // --- start condition --- //
                    if ($keyDate || $keyTime) {
                        if ($keyDate && $keyTime) {
                            $kDateTime = $this->common->formToSqlDate($keyDate) . ' ' . $keyTime . ':00';
                            if ($val->match_time == $kDateTime) {
                                $inCondition = 1;
                            }
                        } else if ($keyDate && ! $keyTime) {
                            $mDate = $this->common->sqlToFormDate($val->match_time);
                            if ($keyDate == $mDate) {
                                $inCondition = 1;
                            }
                        } else if (! $keyDate && $keyTime) {
                            $kDateTime = $this->common->formToSqlDate(Date('d/m/Y')) . ' ' . $keyTime . ':00';
                            if ($val->match_time == $kDateTime) {
                                $inCondition = 1;
                            }
                        }
                    } else {
                        $inCondition = 1;
                    }

                    if ($keyProgram) {
                        if (trim($keyProgram) == trim($val->match_name)) {
                            $inCondition = 1;
                        } else {
                            $inCondition = 0;
                        }
                    } else {
                        $inCondition = 1;
                    }
                    // --- end condition --- //

                    if ($inCondition == 1) {
                        $val->match_time = $this->common->showDayOnly(strtotime($val->match_time));

                        $linkSpsDatas = MatchLink::where('match_id', $val->id)->where('link_type', 'Sponsor');
                        if ($linkSpsDatas->count() > 0) {
                            $tspon_links = $linkSpsDatas->get();
                            $val->sponsor_links = collect($tspon_links)->sortBy('link_seq');
                        }

                        $linkNmDatas = MatchLink::where('match_id', $val->id)->where('link_type', 'Normal');
                        if ($linkNmDatas->count() > 0) {
                            $tlinks = $linkNmDatas->get();
                            $val->normal_links = collect($tlinks)->sortBy('link_seq');
                        }
                        $data_all[] = $val;

                    }
                }
            }









        }

        return array('total' => $total, 'records' => $matchDatas, 'all_match' => $data_all );
    }

    public function arrangeLink($links = null)
    {
        $arrSeqLink = array();
        if ($links) {
            foreach ($links as $k => $v) {
                $arrSeqLink[] = $v->link_seq;
            }

        }
    }
}
