
function loadStatistics() {
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://api-football-v1.p.rapidapi.com/v2/statistics/fixture/" + $('#fixture_id').val(),
        "method": "GET",
        "headers": {
            "x-rapidapi-host": "api-football-v1.p.rapidapi.com",
            "x-rapidapi-key": "7bda35ecb3msh3d82de21eb9e622p172217jsnb5e04c3a8d51"
        }
    }

    $.ajax(settings).done(function (response) {
        if (response.api.results > 0) {
            // console.log(response.api.statistics);
            displayStatistics(response.api.statistics);
        } else {
            $('.statistics-content').html('-- ไม่มีข้อมูล --');
        }
    });
}

function displayStatistics(objDatas) {
    var html = '';
    var home = 0;
    var away = 0;
    var max = 0;
    var percentHome = 0.00;
    var percentAway = 0.00;

    if (objDatas) {
        for (let key in objDatas) {
            // console.log(key, objDatas[key]);

            if (objDatas[key].home && objDatas[key].away) {
                home = parseInt(objDatas[key].home);
                away = parseInt(objDatas[key].away);

                max = home + away;

                if (max != 0) {
                    percentHome = (home * 100) / max;
                    percentAway = (away * 100) / max;
                }

                html += '<div class="ele my-4">';
                html +=     '<div class="home-score">' + objDatas[key].home + '</div>';
                html +=     '<h4 class="text-center c-theme">' + key + '</h4>';
                html +=     '<div class="progress-bg d-flex">';
                html +=         '<div class="home-side d-flex just-end">';
                html +=             '<div class="fill" style="width: ' + percentHome + '%;"></div>';
                html +=         '</div>';
                html +=         '<div class="away-side d-flex">';
                html +=             '<div class="fill" style="width: ' + percentAway + '%;"></div>';
                html +=         '</div>';
                html +=     '</div>';
                html +=     '<div class="away-score">' + objDatas[key].away + '</div>';
                html += '</div>';
            }
        }
    }

    $('.statistics-content').html(html);
}