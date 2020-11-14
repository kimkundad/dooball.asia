
function loadTopScore(leagueId) {
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
            // console.log(response.api.topscorers);
            displayTopScore(response.api.topscorers);
        } else {
            $('.topscore-content').html('-- ไม่มีข้อมูล --');
        }
    });
}

function displayTopScore(listDatas) {
    // console.log(listDatas);
    // var fourStars = listDatas.slice(0, 4);

    if (listDatas.length > 0) {
        var html = '';

        for (var i = 0; i < listDatas.length; i++) {
            var data = listDatas[i];

            html = '<div class="d-flex bd-btt">';
            html +=     '<div class="row w-full">';
            html +=         '<div class="col-3 tsc-num text-success text-center text-bold">' + (i+1) + '</div>';
            html +=         '<div class="col-6">';
            html +=             '<div class="d-flex alit-center h-full">';
            html +=                 '<div class="tsc-img">';
            html +=                     '<img src="https://media.api-sports.io/football/players/' + data.player_id + '.png" class="img-fluid" alt="">';
            html +=                 '</div>';
            html +=                 '<div class="tsc-content d-flex df-col">';
            html +=                     '<h3 class="tsc-title">' + data.firstname + ' ' + data.lastname + '</h3>';
            html +=                     '<div class="tsc-lg-info c-content">';
            // html +=                         '<img src="' + mockImg + '" class="img-fluid mr-1" alt="">';
            html +=                         '<span>' + data.team_name + '</span>';
            html +=                     '</div>';
            html +=                 '</div>';
            html +=             '</div>';
            html +=         '</div>';
            html +=         '<div class="col-3 tsc-num text-muted text-center text-bold">' + data.goals.total + '</div>';
            html +=     '</div>';
            html += '</div>';
            $('.topscore-content').append(html);
        }
    }

}