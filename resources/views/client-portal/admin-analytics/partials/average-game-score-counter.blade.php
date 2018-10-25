
<div style="height:260px;
            width:260px;
            background-color:white;
            border: 22px solid #0072BC;
            border-radius: 150px;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            margin: 0 auto;
            font-family: 'Roboto', sans-serif;"
     class="circle-counter">
    <div style="color:rgb(85,85,85); font-size:30px;">Average</div>
    <div style="color:rgb(85,85,85); font-size:30px;">Game Score:</div>
    <div style="color: rgb(85,85,85); font-size:70px"><span id="average_score_counter_text">0</span></div>

</div>
@push('scripts')
<script>
    $(document).ready(function(){
        var GameScoreResponse = JSON.parse('{!!  $gameScore !!}');
        // SETS THE Average Game Duration
        var AvgGameDuration = GameScoreResponse[0].average_game_score * 1;

        // STARTING NUMBER
        var counter_number = 0;
        // HOW QUICKLY YOU COUNT IN MS
        var counter_duration = 1;

        var myCounter = setInterval(myTimer, counter_duration);

        function myTimer() {
            counter_number++;
            document.getElementById('average_score_counter_text').innerHTML = counter_number;
            if(counter_number > AvgGameDuration){
                document.getElementById('average_score_counter_text').innerHTML = AvgGameDuration.toFixed(1)
                return myStopFunction();
            }
        }

        function myStopFunction() {
            clearInterval(myCounter);
        }
    });
</script>
@endpush
