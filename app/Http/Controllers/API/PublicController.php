<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\CheckConnectionController;
use App\Http\Controllers\API\DooballScraperController as DBScraperAPI;

use App\Models\ContentDetail;
use App\Models\DirList;
use App\Models\FileDetail;
use App\Models\HeadToHead;
use Illuminate\Support\Facades\DB;
use \stdClass;
// use Illuminate\Support\Facades\Log;
// use Storage;

class PublicController extends Controller
{
    private $common;
    private $scraper;

    private $connDatas;
    private $apiFootballHeaders;

    public function __construct()
    {
        $this->common = new CommonController();
        $this->scraper = new DBScraperAPI();

        ini_set('max_execution_time', 300);
        set_time_limit(300);

        $conn = new CheckConnectionController();
        $this->connDatas = $conn->checkConnServer();

        $this->apiFootballHeaders = array(
            'X-RapidAPI-Host' => env('RapidAPI_Host'),
            'X-RapidAPI-Key' => env('RapidAPI_Key')
        );
    }

    public function index()
    {
        //
    }

    public function game()
    {
        $datas = array();
        $successList = array();

        $curDatas = $this->common->realCurrentContent();
        $dirName = $curDatas['dirName'];
        // $latestContent = $curDatas['latestContent'];

        if ($dirName) {
            $detailDatas = ContentDetail::select(['id', 'league_name', 'vs', 'event_time', 'link'])->where('dir_name', $dirName)->orderBy('code', 'asc');

            if ($detailDatas->count() > 0) {
                // --- start special query --- //
                $dayList = ContentDetail::select('dir_name')->groupBy('dir_name')->orderBy('dir_name', 'asc');
                $totalInner = $dayList->count();
                if ($totalInner > 0) {
                    $dirList = $dayList->get();
                    foreach($dirList as $key => $dName) {
                        $dlDatas = dirList::select('dir_name')->where('scraping_status', '1')->where('dir_name', $dName->dir_name);
                        if ($dlDatas->count() > 0) {
                            $successList[] = $dName->dir_name;
                        }
                    }
                }
                // --- end special query --- //

                foreach($detailDatas->get() as $k => $v) {
                    $detailId = $v->id;
                    $leagueName = $v->league_name;

                    if ($leagueName != '-- no league --') {
                        $eventTime = $v->event_time;
                        $vs = $v->vs;
                        $homeTeamName = '';
                        $awayTeamName = '';
                        
                        if (trim($vs) && !empty($vs)) {
                            $vsList = preg_split('/-vs-/', $vs);
                            $homeTeamName = $vsList[0];
                            $awayTeamName = array_key_exists(1, $vsList) ? $vsList[1] : '-';
                        }

                        if ($awayTeamName == '-' || $awayTeamName == '') {
                            $leagueInfo = $this->common->findLeagueInfoFromDetailLink($dirName, $v->link, $detailId);
                            $leagueName = $leagueInfo['league_name'];
                            $homeTeamName = $leagueInfo['home_team'];
                            $awayTeamName = $leagueInfo['away_team'];
                        }
    
                        // --- start check disabled button --- //
                        $newTime = $this->common->skudTimeBeforeOneHr($eventTime, ' ');
                        $dayList = explode(' ', $newTime);
                        $monthNum = $this->common->monthNumFromText($dayList[0]);
                        $match_time = Date('Y-' . $monthNum . '-d') . ' 00:00:00';

                        if (array_key_exists(1, $dayList) && array_key_exists(2, $dayList)) {
                            $match_time = Date('Y-' . $monthNum . '-' . $dayList[1]) . ' ' . $dayList[2] . ':00';
                        }

                        $leftMinutes = $this->common->leftToMatch($match_time);
    
                        $disabled = ((int) $leftMinutes <= 30) ? 'disabled' : '';
                        // --- end check disabled button --- //
    
                        $scoreDatas = $this->common->scoreFromLink($v->link, $successList);

                        $score = $scoreDatas['asian']['score'];

                        if (gettype($score) != 'NULL') {
                            $datas[] = array('league_name' => $leagueName,
                            'date_time_before' => $newTime,
                            'event_time' => $eventTime,
                            'disabled' => $disabled,
                            'home_team' => $homeTeamName,
                            'away_team' => $awayTeamName,
                            'datas' => $scoreDatas,
                            'link' => $v->link,
                            'id' => $detailId);
                        }
                    }
                }
            }
        }

        $lList = array();
        $structureDatas = array();

        if (count($datas) > 0) {
            foreach($datas as $data) {
                if (! in_array($data['league_name'], $lList)) {
                    $lList[] = $data['league_name'];
                }
            }

            foreach($lList as $league_name) {
                $rows = array();
                foreach($datas as $data) {
                    if ($data['league_name'] == $league_name) {
                        $rows[] = $data;
                    }
                }
                
                $structureDatas[] = array('league_name' => $league_name, 'rows' => $rows);
            }
        }

        $mainDatas = array('datas' => $structureDatas);

        return response()->json($mainDatas);
    }

    public function live()
    {
        $liveList = array();
        $callNew = 0;
        $tableLive = 'live';
        $timeNow = Date('Y-m-d H:i:s');

        if ($this->connDatas['connected']) {
            $liveDB = DB::table($tableLive);

            if ($liveDB->count() > 0) {
                $liveDatas = $liveDB->get();

                // --- start check sync every 10 seconds --- //
                $diffSeconds = strtotime($timeNow) - strtotime($liveDatas[0]->created_at);

                if ($diffSeconds <= 10) {
                    $liveList = $this->common->groupLeagueLivescore($liveDatas, 'db');
                } else {
                    $callNew = 1;
                }
                // --- end check sync every 10 seconds --- //
            } else {
                $callNew = 1;
            }
        }
        
        if ($callNew == 1) {
            $apiUrl = 'https://api-football-v1.p.rapidapi.com/v2/fixtures/live';
            $response = \Unirest\Request::get($apiUrl, $this->apiFootballHeaders);

            if ($response->code == 200) {
                $datas = $response->body;
    
                if ($datas->api->results > 0) {
                    $insertIds = array();
                    $liveScoreDatas = $datas->api->fixtures;
    
                    $liveList = $this->common->groupLeagueLivescore($liveScoreDatas, 'new-call');
                    DB::table($tableLive)->delete();
    
                    foreach($liveScoreDatas as $row) {
                        // dd($row);
                        $tempId = DB::table($tableLive)->insertGetId(
                            [
                                'fixture_id' => $row->fixture_id,
                                'league_name' => $row->league->name,
                                'country' => $row->league->country,
                                'league_logo_path' => $row->league->logo,
                                'event_date' => $row->event_date, // 2020-07-30T00:00:00+00:00
                                'event_timestamp' => $row->event_timestamp, // 1596067200
                                'first_half_start' => $row->firstHalfStart, // 1596067200
                                'second_half_start' => $row->secondHalfStart, // 1596070800
                                'round' => $row->round,
                                'status' => $row->status,
                                'status_short' => $row->statusShort,
                                'elapsed' => $row->elapsed,
                                'venue' => $row->venue,
                                'referee' => $row->referee,
                                'home_name' => $row->homeTeam->team_name,
                                'home_logo_path' => $row->homeTeam->logo,
                                'away_name' => $row->awayTeam->team_name,
                                'away_logo_path' => $row->awayTeam->logo,
                                'goals_home_team' => $row->goalsHomeTeam,
                                'goals_away_team' => $row->goalsAwayTeam,
                                'score_halftime' => $row->score->halftime,
                                'score_fulltime' => $row->score->fulltime,
                                'score_extratime' => $row->score->extratime,
                                'score_penalty' => $row->score->penalty
                            ]
                        );
    
                        $insertIds[] = $tempId;
    
                        // "league_id": 1328
                        // "league": {
                        //     "flag": "https://media.api-sports.io/flags/us.svg"
                        // }
                        // "homeTeam": {
                        //     "team_id": 4000
                        // }
                        // "awayTeam": {
                        //     "team_id": 4019
                        // }
                    }
                }
            }
        }

        return response()->json($liveList);
    }

    public function livescore(Request $request)
    {
        $liveScoreList = array();
        $callNew = 0;
        $tableLivescore = '';
        $timeNow = Date('Y-m-d H:i:s');

        if ($this->connDatas['connected']) {
            if ($request->date == Date('Y-m-d')) {
                $tableLivescore = 'livescore_today';
                $liveScoreDB = DB::table($tableLivescore);

                if ($liveScoreDB->count() > 0) {
                    $livescoreDatas = $liveScoreDB->get();

                    // --- start check sync every minute --- //
                    $diffSeconds = strtotime($timeNow) - strtotime($livescoreDatas[0]->created_at);
    
                    if ($diffSeconds <= 60) {
                        $liveScoreList = $this->common->groupLeagueLivescore($livescoreDatas, 'db');
                    } else {
                        $callNew = 1;
                    }
                    // --- end check sync every minute --- //
                } else {
                    $callNew = 1;
                }
            } else {
                $livescoreDatas = array();

                if ($request->date == Date('Y-m-d', strtotime("+1 days"))) {
                    $tableLivescore = 'livescore_tomorrow';
                    $liveScoreDB = DB::table($tableLivescore);

                    if ($liveScoreDB->count() > 0) {
                        $livescoreDatas = $liveScoreDB->get();

                        // --- start check sync every 1 hr --- //
                        $diffSeconds = strtotime($timeNow) - strtotime($livescoreDatas[0]->created_at);
        
                        if ($diffSeconds <= 3600) {
                            $liveScoreList = $this->common->groupLeagueLivescore($livescoreDatas, 'db');
                        } else {
                            $callNew = 1;
                        }
                        // --- end check sync every 1 hr --- //
                    }
                } else if ($request->date == Date('Y-m-d', strtotime("+2 days"))) {
                    $tableLivescore = 'livescore_after_tomorrow';
                    $liveScoreDB = DB::table($tableLivescore);

                    if ($liveScoreDB->count() > 0) {
                        $livescoreDatas = $liveScoreDB->get();

                        // --- start check sync every 1 hr --- //
                        $diffSeconds = strtotime($timeNow) - strtotime($livescoreDatas[0]->created_at);
        
                        if ($diffSeconds <= 3600) {
                            $liveScoreList = $this->common->groupLeagueLivescore($livescoreDatas, 'db');
                        } else {
                            $callNew = 1;
                        }
                        // --- end check sync every 1 hr --- //
                    }
                } else if ($request->date == Date('Y-m-d', strtotime("-1 days"))) {
                    $tableLivescore = 'livescore_result';
                    $liveScoreDB = DB::table($tableLivescore);

                    if ($liveScoreDB->count() > 0) {
                        $livescoreDatas = $liveScoreDB->get();

                        // --- start check sync every 1 hr --- //
                        $diffSeconds = strtotime($timeNow) - strtotime($livescoreDatas[0]->created_at);
        
                        if ($diffSeconds <= 3600) {
                            $liveScoreList = $this->common->groupLeagueLivescore($livescoreDatas, 'db');
                        } else {
                            $callNew = 1;
                        }
                        // --- end check sync every 1 hr --- //
                    }
                } else if ($request->date == Date('Y-m-d', strtotime("-2 days"))) {
                    $tableLivescore = 'livescore_twodays_ago';
                    $liveScoreDB = DB::table($tableLivescore);

                    if ($liveScoreDB->count() > 0) {
                        $livescoreDatas = $liveScoreDB->get();

                        // --- start check sync every 1 hr --- //
                        $diffSeconds = strtotime($timeNow) - strtotime($livescoreDatas[0]->created_at);
        
                        if ($diffSeconds <= 3600) {
                            $liveScoreList = $this->common->groupLeagueLivescore($livescoreDatas, 'db');
                        } else {
                            $callNew = 1;
                        }
                        // --- end check sync every 1 hr --- //
                    }
                }

                if (count($livescoreDatas) > 0) {
                    $liveScoreList = $this->common->groupLeagueLivescore($livescoreDatas, 'db');
                } else {
                    $callNew = 1;
                }
            }
        }

        if ($callNew == 1 && $tableLivescore != '') {
            $apiUrl = 'https://api-football-v1.p.rapidapi.com/v2/fixtures/date/' . $request->date;
            $response = \Unirest\Request::get($apiUrl, $this->apiFootballHeaders);
    
            if ($response->code == 200) {
                $datas = $response->body;
    
                if ($datas->api->results > 0) {
                    $insertIds = array();
                    $liveScoreDatas = $datas->api->fixtures;
    
                    $liveScoreList = $this->common->groupLeagueLivescore($liveScoreDatas, 'new-call');
                    DB::table($tableLivescore)->delete();
    
                    foreach($liveScoreDatas as $row) {
                        // dd($row);
                        $tempId = DB::table($tableLivescore)->insertGetId(
                            [
                                'fixture_id' => $row->fixture_id,
                                'league_name' => $row->league->name,
                                'country' => $row->league->country,
                                'league_logo_path' => $row->league->logo,
                                'event_date' => $row->event_date, // 2020-07-30T00:00:00+00:00
                                'event_timestamp' => $row->event_timestamp, // 1596067200
                                'first_half_start' => $row->firstHalfStart, // 1596067200
                                'second_half_start' => $row->secondHalfStart, // 1596070800
                                'round' => $row->round,
                                'status' => $row->status,
                                'status_short' => $row->statusShort,
                                'elapsed' => $row->elapsed,
                                'venue' => $row->venue,
                                'referee' => $row->referee,
                                'home_name' => $row->homeTeam->team_name,
                                'home_logo_path' => $row->homeTeam->logo,
                                'away_name' => $row->awayTeam->team_name,
                                'away_logo_path' => $row->awayTeam->logo,
                                'goals_home_team' => $row->goalsHomeTeam,
                                'goals_away_team' => $row->goalsAwayTeam,
                                'score_halftime' => $row->score->halftime,
                                'score_fulltime' => $row->score->fulltime,
                                'score_extratime' => $row->score->extratime,
                                'score_penalty' => $row->score->penalty
                            ]
                        );
    
                        $insertIds[] = $tempId;
    
                        // "league_id": 1328
                        // "league": {
                        //     "flag": "https://media.api-sports.io/flags/us.svg"
                        // }
                        // "homeTeam": {
                        //     "team_id": 4000
                        // }
                        // "awayTeam": {
                        //     "team_id": 4019
                        // }
                    }
                }
            }
        }

        return response()->json($liveScoreList);
    }

    public function headToHead(Request $request)
    {
        $fixtureId = $request->fixture_id;

        $headToHeadData = new stdClass();
        $callNew = 0;
        $tableHeadToHead = 'head_to_heads';
        $timeNow = Date('Y-m-d H:i:s');

        if ($this->connDatas['connected']) {
            $h2hDB = DB::table($tableHeadToHead)->where('fixture_id', $fixtureId)->first();
            if ($h2hDB) {
                $headToHeadData = $h2hDB;

                // --- start check sync every hour --- //
                $diffSeconds = strtotime($timeNow) - strtotime($headToHeadData->created_at);

                if ($diffSeconds > 3600) {
                    $callNew = 1;
                }
                // --- end check sync every hour --- //
            } else {
                $callNew = 1;
            }
        }

        if ($callNew == 1) {
            $apiUrl = 'https://api-football-v1.p.rapidapi.com/v2/fixtures/id/' . $fixtureId;
            $response = \Unirest\Request::get($apiUrl, $this->apiFootballHeaders);
    
            if ($response->code == 200) {
                $datas = $response->body;
    
                if ($datas->api->results > 0) {
                    $headToHeadRow = $datas->api->fixtures;

                    if (array_key_exists(0, $headToHeadRow)) {
                        $row = $headToHeadRow[0];

                        $hDB = DB::table($tableHeadToHead)->where('fixture_id', $fixtureId)->first();

                        if ($hDB) {
                            $arrayUpdate = array('league_id' => $row->league_id // h2h only
                                                    , 'league_name' => $row->league->name
                                                    , 'country' => $row->league->country
                                                    , 'league_logo_path' => $row->league->logo
                                                    , 'event_date' => $row->event_date
                                                    , 'event_timestamp' => $row->event_timestamp
                                                    , 'events' => (($row->events) ? json_encode($row->events) : null) // h2h only
                                                    , 'first_half_start' => (($row->firstHalfStart) ? $row->firstHalfStart : 0)
                                                    , 'second_half_start' => (($row->secondHalfStart) ? $row->secondHalfStart : 0)
                                                    , 'round' => $row->round
                                                    , 'status' => $row->status
                                                    , 'status_short' => $row->statusShort
                                                    , 'elapsed' => $row->elapsed
                                                    , 'venue' => $row->venue
                                                    , 'referee' => $row->referee
                                                    , 'home_id' => $row->homeTeam->team_id // h2h only
                                                    , 'home_name' => $row->homeTeam->team_name
                                                    , 'home_logo_path' => $row->homeTeam->logo
                                                    , 'away_id' => $row->awayTeam->team_id// h2h only
                                                    , 'away_name' => $row->awayTeam->team_name
                                                    , 'away_logo_path' => $row->awayTeam->logo
                                                    , 'goals_home_team' => (($row->goalsHomeTeam) ? $row->goalsHomeTeam : 0)
                                                    , 'goals_away_team' => (($row->goalsAwayTeam) ? $row->goalsAwayTeam : 0)
                                                    , 'score_halftime' => $row->score->halftime
                                                    , 'score_fulltime' => $row->score->fulltime
                                                    , 'score_extratime' => $row->score->extratime
                                                    , 'score_penalty' => $row->score->penalty
                                                    , 'lineups' => (($row->lineups) ? json_encode($row->lineups) : null) // h2h only
                                                    , 'statistics' => (($row->statistics) ? json_encode($row->statistics) : null) // h2h only
                                                    , 'players' => (($row->players) ? json_encode($row->players) : null) // h2h only
                                                );

                            HeadToHead::where('fixture_id', $fixtureId)->update($arrayUpdate);

                            $headToHeadData = HeadToHead::where('fixture_id', $fixtureId)->first();
                        } else {
                            $headToHeadData = new HeadToHead;
                            $headToHeadData->fixture_id = $row->fixture_id;
                            $headToHeadData->league_id = $row->league_id;
                            $headToHeadData->league_name = $row->league->name;
                            $headToHeadData->country = $row->league->country;
                            $headToHeadData->league_logo_path = $row->league->logo;
                            $headToHeadData->event_date = $row->event_date; // 2021-05-23T00:00:00+00:00
                            $headToHeadData->event_timestamp = $row->event_timestamp; // 1621728000
                            $headToHeadData->events = ($row->events) ? json_encode($row->events) : null; // h2h only
                            $headToHeadData->first_half_start = ($row->firstHalfStart) ? $row->firstHalfStart : 0;
                            $headToHeadData->second_half_start = ($row->secondHalfStart) ? $row->secondHalfStart : 0;
                            $headToHeadData->round = $row->round;
                            $headToHeadData->status = $row->status;
                            $headToHeadData->status_short = $row->statusShort;
                            $headToHeadData->elapsed = $row->elapsed;
                            $headToHeadData->venue = $row->venue;
                            $headToHeadData->referee = $row->referee;
                            $headToHeadData->home_id = $row->homeTeam->team_id; // h2h only
                            $headToHeadData->home_name = $row->homeTeam->team_name;
                            $headToHeadData->home_logo_path = $row->homeTeam->logo;
                            $headToHeadData->away_id = $row->awayTeam->team_id; // h2h only
                            $headToHeadData->away_name = $row->awayTeam->team_name;
                            $headToHeadData->away_logo_path = $row->awayTeam->logo;
                            $headToHeadData->goals_home_team = ($row->goalsHomeTeam) ? $row->goalsHomeTeam : 0;
                            $headToHeadData->goals_away_team = ($row->goalsAwayTeam) ? $row->goalsAwayTeam : 0;
                            $headToHeadData->score_halftime = $row->score->halftime;
                            $headToHeadData->score_fulltime = $row->score->fulltime;
                            $headToHeadData->score_extratime = $row->score->extratime;
                            $headToHeadData->score_penalty = $row->score->penalty;
                            $headToHeadData->lineups = ($row->lineups) ? json_encode($row->lineups) : null; // h2h only
                            $headToHeadData->statistics = ($row->statistics) ? json_encode($row->statistics) : null; // h2h only
                            $headToHeadData->players = ($row->players) ? json_encode($row->players) : null; // h2h only

                            // "league": {
                            //     "flag": "https://media.api-sports.io/flags/us.svg"
                            // }

                            $saved = $headToHeadData->save();

                            // if ($saved) {
                            //     dd($headToHeadData->fixture_id);
                            // }
                        }
                    }
                }
            }
        }

        return response()->json($headToHeadData);
    }

    /*
    public function livescoreOld()
    {
        $apiKey = env('API_SCORE_KEY');
        $apiSecret = env('API_SCORE_SECRET');
        $apiLivescore = 'http://livescore-api.com/api-client/scores/live.json?key=' . $apiKey . '&secret=' . $apiSecret;

        $liveScoreDatas = array();
        $liveScoreRaw = @file_get_contents($apiLivescore);

        if ($liveScoreRaw) {
            $liveScoreSet = json_decode($liveScoreRaw);

            if ($liveScoreSet->success) {
                $liveScoreNoGroup = $liveScoreSet->data;

                $uniqueLeague = array();
                $ungroupList = $liveScoreNoGroup->match;

                if (count($ungroupList) > 0) {
                    foreach($ungroupList as $match) {
                        if (! in_array($match->competition_name, $uniqueLeague)) {
                            $uniqueLeague[] = $match->competition_name;
                        }
                    }

                    foreach($uniqueLeague as $league_name) {
                        $matchDatas = array();

                        foreach($ungroupList as $match) {
                            if ($match->competition_name == $league_name) {
                                $matchDatas[] = $match;
                            }
                        }

                        $liveScoreDatas[] = array('league_name' => $league_name, 'match_datas' => $matchDatas);
                    }
                }
            }
        }

        return $liveScoreDatas;
    }

    public function scheduledGame()
    {
        $apiKey = env('API_SCORE_KEY');
        $apiSecret = env('API_SCORE_SECRET');
        $apiLivescore = 'http://livescore-api.com/api-client/fixtures/matches.json?key=' . $apiKey . '&secret=' . $apiSecret;

        $liveScoreDatas = array();
        $liveScoreRaw = @file_get_contents($apiLivescore);

        $uniqueLeague = array();
        $liveScoreNoGroup = array();

        if ($liveScoreRaw) {
            $liveScoreSet = json_decode($liveScoreRaw);

            if ($liveScoreSet->success) {
                $liveScoreNoGroup = $liveScoreSet->data;
            }

            $ungroupList = $liveScoreNoGroup->fixtures;
            $nextPage = $liveScoreNoGroup->next_page;
            $prevPage = $liveScoreNoGroup->prev_page;

            if (count($ungroupList) > 0) {
                foreach($ungroupList as $match) {
                    $name = $match->competition->name;

                    if (! in_array($name, $uniqueLeague)) {
                        $uniqueLeague[] = $name;
                    }
                }

                foreach($uniqueLeague as $league_name) {
                    $matchDatas = array();

                    foreach($ungroupList as $match) {
                        $name = $match->competition->name;
    
                        if ($name == $league_name) {
                            $matchDatas[] = $match;
                        }
                    }

                    $liveScoreDatas[] = array('league_name' => $league_name, 'match_datas' => $matchDatas);
                }
            }
        }

        // dd($liveScoreDatas);

        return $liveScoreDatas;
    }*/

    public function matchPostHope(Request $request)
    {
        $count = 0;
        $matches = $request->matches;

        if (count($matches) > 0) {
            foreach($matches as $key => $value) {
                $this->scraper->algorithmCheckExistingMatch($value);
                $count++;
            }
        }

        return response()->json(['total' => $count]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
