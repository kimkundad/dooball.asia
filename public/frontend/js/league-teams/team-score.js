function teamScore(leagueId, teamId, year) {
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://api-football-v1.p.rapidapi.com/v2/statistics/" + leagueId + "/" + teamId,
        "method": "GET",
        "headers": {
            "x-rapidapi-host": "api-football-v1.p.rapidapi.com",
            "x-rapidapi-key": "7bda35ecb3msh3d82de21eb9e622p172217jsnb5e04c3a8d51"
        }
    }

    $.ajax(settings)
        .done(function (response) {
            if (response.api.results > 0) {
                teamStatistics(response.api.statistics, year);
            } else {
                console.log('--- no data for team score in: ' + year);
            }
        })
        .fail(function() {
            console.log('--- Failed to load team score: ' + leagueId + ' ---');
            $('#y' + year + '-score-content .num-of-match').html('-'); // แข่ง
            $('#y' + year + '-score-content .num-of-win').html('-'); // ชนะ
            $('#y' + year + '-score-content .num-of-draw').html('-'); // เสมอ
            $('#y' + year + '-score-content .num-of-lose').html('-'); // แพ้
            $('#y' + year + '-score-content .num-of-goal').html('-'); // การทำประตู
            $('#y' + year + '-score-content .num-of-defeat').html('-'); // โดนยิงประตู
        });
}

function teamScoreRepeat(leagueId, year) {
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://api-football-v1.p.rapidapi.com/v2/fixtures/league/" + leagueId,
        "method": "GET",
        "headers": {
            "x-rapidapi-host": "api-football-v1.p.rapidapi.com",
            "x-rapidapi-key": "7bda35ecb3msh3d82de21eb9e622p172217jsnb5e04c3a8d51"
        }
    }

    $.ajax(settings)
        .done(function (response) {
            if (response.api.results > 0) {
                fixtureDatas = response.api.fixtures;

                var teamData = loadPlayerTransfers(fixtureDatas, 'search', teamName);
                teamScore(leagueId, teamData.team_id, year); // topic 7
            } else {
                console.log('--- no data for team score in: ' + year);
                $('#y' + year + '-score-content .num-of-match').html('-'); // แข่ง
                $('#y' + year + '-score-content .num-of-win').html('-'); // ชนะ
                $('#y' + year + '-score-content .num-of-draw').html('-'); // เสมอ
                $('#y' + year + '-score-content .num-of-lose').html('-'); // แพ้
                $('#y' + year + '-score-content .num-of-goal').html('-'); // การทำประตู
                $('#y' + year + '-score-content .num-of-defeat').html('-'); // โดนยิงประตู
            }
        })
        .fail(function() {
            console.log('--- Failed to load team score: ' + leagueId + ' ---');
            $('#y' + year + '-score-content .num-of-match').html('-'); // แข่ง
            $('#y' + year + '-score-content .num-of-win').html('-'); // ชนะ
            $('#y' + year + '-score-content .num-of-draw').html('-'); // เสมอ
            $('#y' + year + '-score-content .num-of-lose').html('-'); // แพ้
            $('#y' + year + '-score-content .num-of-goal').html('-'); // การทำประตู
            $('#y' + year + '-score-content .num-of-defeat').html('-'); // โดนยิงประตู
        });
}

function teamStatistics(row, year) {
    var matchTotal = 0;
    var winTotal = 0;
    var drawTotal = 0;
    var loseTotal = 0;
    var goalTotal = 0;
    var againstTotal = 0;

    if (row) {
        // console.log(row);
        matchTotal = row.matchs.matchsPlayed.total;
        winTotal = row.matchs.wins.total;
        drawTotal = row.matchs.draws.total;
        loseTotal = row.matchs.loses.total;
        goalTotal = row.goals.goalsFor.total;
        againstTotal = row.goals.goalsAgainst.total;
    }

    $('#y' + year + '-score-content .num-of-match').html(matchTotal); // แข่ง
    $('#y' + year + '-score-content .num-of-win').html(winTotal); // ชนะ
    $('#y' + year + '-score-content .num-of-draw').html(drawTotal); // เสมอ
    $('#y' + year + '-score-content .num-of-lose').html(loseTotal); // แพ้
    $('#y' + year + '-score-content .num-of-goal').html(goalTotal); // การทำประตู
    $('#y' + year + '-score-content .num-of-defeat').html(againstTotal); // โดนยิงประตู
}