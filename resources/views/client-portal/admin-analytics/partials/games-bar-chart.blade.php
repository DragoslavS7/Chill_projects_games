@push('scripts')
<script type="text/javascript" crossorigin="anonymous" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
@endpush
@push('scripts')
<script>
    'use strict';

    var GamesChartResponse = {!!  $gameAnalytics !!};

    var admin_dashboard_data = GamesChartResponse.game_analytics;

    window.gameChartColors = {
        blue: '#0072BC',
        red: '#ED1D24',
        yellow:'#ECDC00',
        green:'#348B35'

    };

    var descriptions = [];
    var admingames_started = [];
    var admingames_completed = [];
    var admingames_deployed = [];

    // ADDS DATA TO ARRAYS
    for (var i = 0; i < admin_dashboard_data.length; i++) {
        var description = admin_dashboard_data[i].description
        var games_started = admin_dashboard_data[i].games_started
        var games_completed = admin_dashboard_data[i].games_completed
        var games_deployed = admin_dashboard_data[i].games_deployed

        descriptions.push(description)
        admingames_started.push(games_started)
        admingames_completed.push(games_completed)
        admingames_deployed.push(games_deployed)
    }
</script>
@endpush
<div id="game_container" style="width: 40%; margin: auto">
    <canvas id="game_canvas"
            style="-moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;">

    </canvas>
</div>
@push('scripts')
<script>
    var game_color = Chart.helpers.color;
    var barGameChartData = {
        labels: descriptions,
        datasets: [{
            label: 'Games Started',
            backgroundColor: game_color(window.gameChartColors.red).alpha(0.75).rgbString(),
            borderColor: window.gameChartColors.red,
            borderWidth: 1,
            data: admingames_started
        }, {
            label: 'Games Completed',
            backgroundColor: game_color(window.gameChartColors.blue).alpha(0.75).rgbString(),
            borderColor: window.gameChartColors.blue,
            borderWidth: 1,
            data: admingames_completed
        }, {
            label: 'Games Deployed',
            backgroundColor: game_color(window.gameChartColors.green).alpha(0.75).rgbString(),
            borderColor: window.gameChartColors.green,
            borderWidth: 1,
            data: admingames_deployed
        }]

    };

    window.onload = function() {
        var game_ctx = document.getElementById('game_canvas').getContext('2d');
        window.myBar = new Chart(game_ctx, {
            type: 'bar',
            data: barGameChartData,

            options: {
                scales:{
                    yAxes:[{
                        display:true,
                        ticks:{
                            beginAtZero: true
                        }
                    }]
                },
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Games Started, Completed & Deployed by Description'
                }
            }
        });
    };
</script>
@endpush