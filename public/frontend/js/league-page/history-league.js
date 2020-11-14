var histOne = $('#hist_one').val();
var histTwo = $('#hist_two').val();
var histThree = $('#hist_three').val();
var histFour = $('#hist_four').val();
var histFive = $('#hist_five').val();
var histList = [histOne, histTwo, histThree, histFour, histFive];
var histListText = [$('#hist_one_text').val(), $('#hist_two_text').val(), $('#hist_three_text').val(), $('#hist_four_text').val(), $('#hist_five_text').val()];

function histData(index, leagueId) {
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://api-football-v1.p.rapidapi.com/v2/fixtures/date/" + histList[index-1],
        "method": "GET",
        "headers": {
            "x-rapidapi-host": "api-football-v1.p.rapidapi.com",
            "x-rapidapi-key": "7bda35ecb3msh3d82de21eb9e622p172217jsnb5e04c3a8d51"
        }
    }

    $.ajax(settings)
        .done(function (response) {
            // console.log(response);
            if (response.api.results > 0) {
                filterLeague(response.api.fixtures, index, leagueId);
            } else {
                $('.result-box').html('<h5 class="text-center text-muted">-- ไม่มีข้อมูล --</h5>');
            }

            if (index == 1) {
                $('.result-box .graph-loading').remove();
            }

            setTimeout(function() {
                if (index < 5) {
                    histData(++index, leagueId);
                }
            }, 1000);
        })
        .fail(function() {
            console.log('--- Failed to load result: ' + leagueId + ' ---');
            $('.result-box .graph-loading').remove();
            $('.result-box').html('<h5 class="text-center text-muted">-- ไม่มีข้อมูล --</h5>');
        });
}

function filterLeague(datas, index, leagueId) {
    if (datas.length > 0) {
        var html = '';
        var row = null;

        html += '<div class="ele-right-th pl-2">' + histListText[index-1] + '</div>';
        html += '<div class="d-flex df-col">';

        var countPm = 0;

        for (var i = 0; i < datas.length; i++) {
            row  = datas[i];

            if (leagueId == 524) {
                if (row.league.name == 'Premier League' && row.league.country == 'England') {
                    html += '<div class="right-hist d-flex p-2">';
                    html +=     '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" target="_BLANK" class="team-show d-flex alit-center">';
                    html +=         '<span class="t-name">' + row.homeTeam.team_name + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.homeTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-score text-bold">' + row.score.fulltime + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.awayTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-name">' + row.awayTeam.team_name + '</span>';
                    html +=     '</a>';
                    html += '</div>';
                    countPm++;
                }
            } else if (leagueId == 775) {
                if (row.league.name == 'Primera Division') {
                    html += '<div class="right-hist d-flex p-2">';
                    html +=     '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" target="_BLANK" class="team-show d-flex alit-center">';
                    html +=         '<span class="t-name">' + row.homeTeam.team_name + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.homeTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-score text-bold">' + row.score.fulltime + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.awayTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-name">' + row.awayTeam.team_name + '</span>';
                    html +=     '</a>';
                    html += '</div>';
                    countPm++;
                }
            } else if (leagueId == 891) {
                if (row.league.name == 'Serie A') {
                    html += '<div class="right-hist d-flex p-2">';
                    html +=     '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" target="_BLANK" class="team-show d-flex alit-center">';
                    html +=         '<span class="t-name">' + row.homeTeam.team_name + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.homeTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-score text-bold">' + row.score.fulltime + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.awayTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-name">' + row.awayTeam.team_name + '</span>';
                    html +=     '</a>';
                    html += '</div>';
                    countPm++;
                }
            } else if (leagueId == 754) {
                if (row.league.name == 'Bundesliga 2') {
                    html += '<div class="right-hist d-flex p-2">';
                    html +=     '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" target="_BLANK" class="team-show d-flex alit-center">';
                    html +=         '<span class="t-name">' + row.homeTeam.team_name + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.homeTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-score text-bold">' + row.score.fulltime + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.awayTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-name">' + row.awayTeam.team_name + '</span>';
                    html +=     '</a>';
                    html += '</div>';
                    countPm++;
                }
            } else if (leagueId == 525) {
                if (row.league.name == 'Ligue 1') {
                    html += '<div class="right-hist d-flex p-2">';
                    html +=     '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" target="_BLANK" class="team-show d-flex alit-center">';
                    html +=         '<span class="t-name">' + row.homeTeam.team_name + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.homeTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-score text-bold">' + row.score.fulltime + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.awayTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-name">' + row.awayTeam.team_name + '</span>';
                    html +=     '</a>';
                    html += '</div>';
                    countPm++;
                }
            }
        }

        html += '</div>';

        if (countPm > 0) {
            $('.result-box').append(html);
        }
    }
}