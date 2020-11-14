function loadLeagueTable(leagueId, homeTeamName, awayTeamName) {
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://api-football-v1.p.rapidapi.com/v2/leagueTable/" + leagueId,
        "method": "GET",
        "headers": {
            "x-rapidapi-host": "api-football-v1.p.rapidapi.com",
            "x-rapidapi-key": "7bda35ecb3msh3d82de21eb9e622p172217jsnb5e04c3a8d51"
        }
    }

    $.ajax(settings)
        .done(function (response) {
            if (response.api.results > 0) {
                leagueTableRender(response.api.standings, homeTeamName, awayTeamName);
            } else {
                var tr = '<tr><td colspan="8" class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
                $('.tbd-league-table').html(tr);
                $('.tbd-league-table-home').html(tr);
                $('.tbd-league-table-away').html(tr);
            }
        })
        .fail(function() {
            console.log('--- Failed to load league table id: ' + leagueId + ' ---');
            var tr = '<tr><td colspan="8" class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
            $('.tbd-league-table').html(tr);
            $('.tbd-league-table-home').html(tr);
            $('.tbd-league-table-away').html(tr);
        });
}

function leagueTableRender(datas, homeTeamName, awayTeamName) {
    // console.log(datas);
    var tr = '';

    if (datas.length > 0) {
        if (datas[0]) {
            var mp = 0, w = 0, d = 0, l = 0, g = 0;
    
            var point = 0;
            var rows = datas[0];
            var homeRows = datas[0];
            var awayRows = datas[0];

            var topFiveTeam = rows.slice(0, 5);

            for (var j = 0; j < topFiveTeam.length; j++) {
                var row = topFiveTeam[j];

                mp = row.all.matchsPlayed;
                w = row.all.win;
                d = row.all.draw;
                l = row.all.lose;
                g = row.all.goalsFor;

                point = row.points;

                tr += '<tr>';
                tr +=   '<td>' + (j+1) + '.</td>';
                tr +=   '<td><img src="' + row.logo + '" width="30"><span class="ml-2">' + row.teamName + '</span></td>';
                tr +=   '<td>' + mp + '</td>';
                tr +=   '<td>' + w + '</td>';
                tr +=   '<td>' + d + '</td>';
                tr +=   '<td>' + l + '</td>';
                tr +=   '<td>' + g + '</td>';
                tr +=   '<td>' + point + '</td>';
                tr += '</tr>';
            }

            var newHome = reOrderTeamSide(homeRows, 'home');
            newHome.sort(compareWin);
            var newAway = reOrderTeamSide(awayRows, 'away');
            newAway.sort(compareWin);

            renderSide(newHome, 'home', homeTeamName, awayTeamName);
            renderSide(newAway, 'away', homeTeamName, awayTeamName);
        }
    }

    $('.tbd-league-table').html(tr);
}

function renderSide(rows, side, homeTeamName, awayTeamName) {
    if (rows.length > 0) {
        var tr = '';
        var mp = 0, w = 0, d = 0, l = 0, g = 0;
        var activeTeam = '';

        var topFiveTeam = rows.slice(0, 5);

        for (var j = 0; j < topFiveTeam.length; j++) {
            var row = topFiveTeam[j];
    
            mp = row[side].matchsPlayed;
            w = row[side].win;
            d = row[side].draw;
            l = row[side].lose;
            g = row[side].goalsFor;
            
            activeTeam = (row.teamName == homeTeamName || row.teamName == awayTeamName) ? ' class="league-name"' : '';
    
            tr += '<tr ' + activeTeam + '>';
            tr +=   '<td>' + (j+1) + '.</td>';
            tr +=   '<td><img src="' + row.logo + '" width="30"><span class="ml-2">' + row.teamName + '</span></td>';
            tr +=   '<td>' + mp + '</td>';
            tr +=   '<td>' + w + '</td>';
            tr +=   '<td>' + d + '</td>';
            tr +=   '<td>' + l + '</td>';
            tr +=   '<td>' + g + '</td>';
            tr +=   '<td>' + row.points + '</td>';
            tr += '</tr>';
        }

        $('.tbd-league-table-' + side).html(tr);
    }
}

function reOrderTeamSide(datas, side) {
    var newList = [];

    if (datas.length > 0) {
        for (var i = 0; i < datas.length; i++) {
            datas[i]['winRate'] = datas[i][side].win;
            newList.push(datas[i]);
        }
    }

    return newList;
}

function compareWin(a, b) {
    if ( a.winRate < b.winRate ){
        return 1;
    }
    if ( a.winRate > b.winRate ){
        return -1;
    }

    return 0;
}