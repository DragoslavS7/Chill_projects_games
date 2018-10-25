@push('scripts')
<script type="text/javascript" crossorigin="anonymous" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
@endpush
<div id="container" style="width: 40%; margin: auto">
    <canvas id="admin-doughnut-chart"></canvas>
</div>
@push('scripts')
<script>
    'use strict';

    var quizesDonutResponse = JSON.parse('{!!  $adminAnalytics !!}');


    // SETS THE  Quizzes Deployed
    var NumberQuizzesCreated = 0;
    var NumberGamesCreated  = 0;
    var NumberGamesDeployed = 0;

    for (var i = quizesDonutResponse.users.length - 1; i >= 0; i--) {
        NumberQuizzesCreated = NumberQuizzesCreated + quizesDonutResponse.users[i].quizzes_created * 1;
        NumberGamesCreated = NumberGamesCreated + quizesDonutResponse.users[i].game_created * 1;
        NumberGamesDeployed = NumberGamesDeployed + quizesDonutResponse.users[i].games_deployed * 1;

    }

    var quizData = [NumberQuizzesCreated,NumberGamesCreated,NumberGamesDeployed]

    new Chart(document.getElementById("admin-doughnut-chart"), {
        type: 'doughnut',
        data: {
            labels: ["Quizzes Created", "Games Created", "Games Deployed"],
            datasets: [
                {
                    label: "LABEL HERE",
                    backgroundColor: ["#0072BC", "#ED1D24",'#348B35'],
                    data: quizData
                }
            ]
        },
        options: {
            title: {
                display: true,
                text: `Admin Dashboard`
            }
        }
    });

</script>
@endpush