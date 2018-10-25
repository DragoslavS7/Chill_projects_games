
@push('scripts')
<script type="text/javascript" crossorigin="anonymous" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
@endpush
@push('scripts')
<script>
    'use strict';

    var adminAnalyticsResponse = JSON.parse('{!!  $adminAnalytics !!}');

    var admin_analytics_dashboard_data = adminAnalyticsResponse.users;

    window.adminChartColors = {
        blue: '#0072BC',
        red: '#ED1D24',
        yellow:'#ECDC00',
        green:'#348B35'

    };

    var adminNames = [];
    var adminQuizzesCreated = [];
    var adminGamesCreated = [];
    var adminGamesDeployed = [];

    // ADDS DATA TO ARRAYS
    for (var i = 0; i < admin_analytics_dashboard_data.length; i++) {
        var adminName = admin_analytics_dashboard_data[i].name;
        var quizzesCreated = admin_analytics_dashboard_data[i].quizzes_created;
        var gamesCreated = admin_analytics_dashboard_data[i].game_created;
        var gamesDeployed = admin_analytics_dashboard_data[i].games_deployed;

        adminNames.push(adminName)
        adminQuizzesCreated.push(quizzesCreated)
        adminGamesCreated.push(gamesCreated)
        adminGamesDeployed.push(gamesDeployed)
    }
</script>
@endpush

<div id="admin_analytics_container" style="width: 40%; margin: auto">
    <canvas id="admin_analytics_canvas" width="800" height="450"
            style=" -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;">

    </canvas>
</div>
@push('scripts')
<script>
    var admin_analytics_color = Chart.helpers.color;
    var barAdminChartData = {
        labels: adminNames,
        datasets: [{
            label: 'Quizzes Created',
            backgroundColor: admin_analytics_color(window.adminChartColors.red).alpha(0.75).rgbString(),
            borderColor: window.adminChartColors.red,
            borderWidth: 1,
            data: adminQuizzesCreated
        }, {
            label: 'Games Created',
            backgroundColor: admin_analytics_color(window.adminChartColors.blue).alpha(0.75).rgbString(),
            borderColor: window.adminChartColors.blue,
            borderWidth: 1,
            data: adminGamesCreated
        }, {
            label: 'Games Deployed',
            backgroundColor: admin_analytics_color(window.adminChartColors.green).alpha(0.75).rgbString(),
            borderColor: window.adminChartColors.green,
            borderWidth: 1,
            data: adminGamesDeployed
        }]

    };


        var admin_analytics_ctx = document.getElementById('admin_analytics_canvas').getContext('2d');
        window.myAdminBar = new Chart(admin_analytics_ctx, {
            type: 'bar',
            data: barAdminChartData,

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
                    text: 'Admin Manager Chart'
                }
            }
        });


</script>
@endpush