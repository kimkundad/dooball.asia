var years = $('#years').val();
var yearList = years.split(',');
var yearStructure = {};

function loadPlayerTransfer(leagueId) {
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
            leagueYearGroup(response.api.fixtures);
        } else {
            var tr = '<tr><td class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
            $('.tb-transfer').html(tr);
        }
    });
}

function leagueYearGroup(fixtures) {
    var teamIds = loadPlayerTransfers(fixtures, 'id');

    if (teamIds.length > 0) {
        for (var n = 0; n < teamIds.length; n++) {
            teamInfo(teamIds[n], n, (teamIds.length - 1));
        }
    }
}

function teamInfo(teamId, n, max) {
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://api-football-v1.p.rapidapi.com/v2/transfers/team/" + teamId,
        "method": "GET",
        "headers": {
            "x-rapidapi-host": "api-football-v1.p.rapidapi.com",
            "x-rapidapi-key": "7bda35ecb3msh3d82de21eb9e622p172217jsnb5e04c3a8d51"
        }
    }

    $.ajax(settings).done(function (response) {
        if (response.api.results > 0) {
            groupByYear(response.api.transfers, n, max);
        }
    });
}

function groupByYear(transfers, n, max) {
    if (transfers.length > 0) {
        var date = '';
        var y = '';

        for (var i = 0; i < yearList.length; i++) {
            var tempYear = [];

            for (var j = 0; j < transfers.length; j++) {
                date = transfers[j].transfer_date;
                y = date.substr(0, 4);

                if (parseInt(y) == yearList[i]) {
                    tempYear.push(transfers[j]);
                }
            }

            genYearTab(yearList[i], tempYear, n, max);
        }
    }
}

function genYearTab(year, yearDatas, n, max) {
    if (yearDatas.length > 0) {
        var tbHtml = '';
        var noImage = "'images/no-image.jpg'";
        var price = 0;
        var rawPrice = 0;

        for (var f = 0; f < yearDatas.length; f++) {
            price = 0;
            rawPrice = 0;

            if (yearDatas[f].type != 'Loan' && yearDatas[f].type != 'N/A' && yearDatas[f].type != 'Free') {
                rawPrice = yearDatas[f].type;

                if (rawPrice) {
                    rawPrice = rawPrice.replace('€', '');
                    rawPrice = rawPrice.replace('M', '');
                    price = parseFloat(rawPrice.trim());
                }
            }

            yearDatas[f]['price'] = price;
        }

        yearDatas.sort(comparePrice);
        var topFive = yearDatas;

        var row = null;
        var outTeam = null;
        var inTeam = null;
        var urlOut = '';
        var urlIn = '';

        for (var i = 0; i < topFive.length; i++) {
            row = topFive[i];

            urlOut = 'https://media.api-sports.io/football/teams/' + row.team_out.team_id + '.png';
            urlIn = 'https://media.api-sports.io/football/teams/' + row.team_in.team_id + '.png';

            outTeam = '<img src="' + urlOut + '" title="' + row.team_out.team_name + '" class="img-round" height="35" onerror="javascript:this.src=' + noImage + '">';
            inTeam = '<img src="' + urlIn + '" title="' + row.team_out.team_name + '" title="' + row.team_in.team_name + '" class="img-round" height="35" onerror="javascript:this.src=' + noImage + '">';

            tbHtml += '<tr class="text-white">';
            tbHtml +=   '<td><img src="https://media.api-sports.io/football/players/' + row.player_id + '.png" class="img-round" width="50"><span class="ml-2">' + row.player_name + '</span></td>';
            tbHtml +=   '<td class="text-center">' + outTeam + '<br>' + row.team_out.team_name + '</td>';
            tbHtml +=   '<td class="text-center">' + inTeam + '<br>' + row.team_in.team_name + '</td>';
            tbHtml +=   '<td class="text-center">' + ((row.type) ? row.type : '-') + '</td>';
            tbHtml += '</tr>';
        }

        yearStructure[year] = tbHtml;

        // console.log(n, max, year);

        if (n == max) {
            if (Object.keys(yearStructure).length > 0) {
                for (var year in yearStructure) {
                    if (yearStructure.hasOwnProperty(year)) {
                        $('.tbody-y' + year).html(yearStructure[year]);
                    }
                }
            }

            $('.tb-transfer').css('visibility', 'visible');
            $('.tb-transfer').css('height', 'auto');

            $('#pml_tab_content .graph-loading').remove();
        }
    }
}

function comparePrice(a, b) {
    if ( a.price < b.price ){
        return 1;
    }
    if ( a.price > b.price ){
        return -1;
    }

    return 0;
}