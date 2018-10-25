@push('scripts')
<script type="text/javascript" crossorigin="anonymous" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
@endpush
<div id="container" style="width: 40%; margin: auto">
    <div style="background-color: #2C2C2C;display:inline-block; padding: 20px; margin: 20px">
        <div  style="color:white; font-size: 30px; min-width: 500px; text-align: center;">
            Number of Quizzes Deployed:
            <span id="quizzes_deployed_counter_text"></span>
        </div>
    </div>
    <canvas id="doughnut-chart"></canvas>
</div>
@push('scripts')
<script>
    $(document).ready(function(){
        var QuizzesDeployedResponse = JSON.parse('{!!  $quizAnalytics !!}');
        // SETS THE  Quizzes Deployed
        var NumberQuizzesDeployed = QuizzesDeployedResponse.quizzes_deployed;
        var quizzesStarted  = QuizzesDeployedResponse.quizzes_started;
        var quizzesFinished  = QuizzesDeployedResponse.quizzes_finished;
        var quizzesNotFinished = quizzesStarted - quizzesFinished;
        var quizData = [quizzesFinished,quizzesNotFinished]

        // STARTING NUMBER
        var counter_number = 0;
        // HOW QUICKLY YOU COUNT IN MS
        var counter_duration = 100;

        var myCounter = setInterval(myTimer, counter_duration);

        function myTimer() {
            counter_number++;
            document.getElementById('quizzes_deployed_counter_text').innerHTML = counter_number;
            if(counter_number > NumberQuizzesDeployed){
                document.getElementById('quizzes_deployed_counter_text').innerHTML = NumberQuizzesDeployed
                return myStopFunction();
            }
        }

        function myStopFunction() {
            clearInterval(myCounter);
        }

        new Chart(document.getElementById("doughnut-chart"), {
            type: 'doughnut',
            data: {
                labels: ["Finished", "Unfinished"],
                datasets: [
                    {
                        label: "Quizzes Finished vs. Unfinished",
                        backgroundColor: ["#0072BC", "#ED1D24"],
                        data: quizData
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: `Total Quizzes Started: ${quizzesStarted} `
                }
            }
        });
    });
</script>
@endpush