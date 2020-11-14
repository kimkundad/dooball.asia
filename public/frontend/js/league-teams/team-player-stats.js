function loadTeamPlayerStats(teamId, year) {
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://api-football-v1.p.rapidapi.com/v2/players/team/" + teamId + "/" + (parseInt(year) - 1),
        "method": "GET",
        "headers": {
            "x-rapidapi-host": "api-football-v1.p.rapidapi.com",
            "x-rapidapi-key": "7bda35ecb3msh3d82de21eb9e622p172217jsnb5e04c3a8d51"
        }
    }

    $.ajax(settings)
        .done(function (response) {
            if (response.api.results > 0) {
                var datas = response.api.players;
                var fiveStars = datas;

                if (datas.length > 5) {
                    fiveStars = datas.slice(0, 5);
                }

                groupSevenSet(fiveStars, year);
            } else {
                console.log('--- no player stats team id: ' + teamId, (parseInt(year) - 1));
                $('#star_content_' + year + ' tbody tr').html('<td class="text-muted text-center"><h5 class="text-center text-muted">--- ไม่มีข้อมูล ---</h5></td>');
            }
        })
        .fail(function() {
            console.log('--- Failed to load team data: ' + leagueId + ' ---');
            $('#star_content_' + year + ' tbody tr').html('<td class="text-muted text-center"><h5 class="text-center text-muted">--- ไม่มีข้อมูล ---</h5></td>');
        });
}

function repeatLoadTeamPlayerStats(leagueId, year) {
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

    $.ajax(settings).done(function (response) {
        if (response.api.results > 0) {
            fixtureDatas = response.api.fixtures;

            var teamData = loadPlayerTransfers(fixtureDatas, 'search', teamName);
            loadTeamPlayerStats(teamData.team_id, year); // topic 8
        } else {
            // var tr = '<tr><td class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
            // $('.tb-transfer').html(tr);
            console.log('--- no data for team in league: ' + leagueId);
            $('#star_content_' + year + ' tbody tr').html('<td class="text-muted text-center"><span class="text-muted">--- ไม่มีข้อมูล ---</span></td>');
        }
    });
}

// --- start repeat topic 8 --- //
function loadPlayerStatsEachYear(leagueId, year) {
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://api-football-v1.p.rapidapi.com/v2/topscorers/" + leagueId,
        "method": "GET",
        "headers": {
            "x-rapidapi-host": "api-football-v1.p.rapidapi.com",
            "x-rapidapi-key": "7bda35ecb3msh3d82de21eb9e622p172217jsnb5e04c3a8d51"
        }
    }

    $.ajax(settings).done(function (response) {
        if (response.api.results > 0) {
            groupSevenSet(response.api.topscorers, year);
        } else {
            console.log('--- no team player stats for league id: ' + leagueId, year);
            $('#star_content_' + year + ' tbody tr').html('<td class="text-muted text-center"><span class="text-muted">--- ไม่มีข้อมูล ---</span></td>');
        }
    });
}
// --- end repeat topic 8 --- //