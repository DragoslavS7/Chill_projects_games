
<div style="height:260px;
            width:260px;
            background-color:white;
            border: 22px solid #0072BC;
            border-radius: 150px;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            font-family: 'Roboto', sans-serif;"
     class="circle-counter">
    <div style="color:rgb(85,85,85); font-size:30px;">Average</div>
    <div style="color:rgb(85,85,85); font-size:30px;">Game Duration:</div>
    <div style="color:rgba(85,85,85,.85); font-size:15px;">(seconds)</div>
    <div style="color: rgb(85,85,85); font-size:70px"><span id="average_duration_counter_text">0</span></div>
</div>
@push('scripts')
<script>
    $(document).ready(function(){
        var AvgGameScoreResponse = JSON.parse('{!!  $gameDuration !!}');
        // SETS THE Average Game Duration
        var AvgGameDuration = AvgGameScoreResponse[0].average_game_duration_in_seconds * 1;

        // STARTING NUMBER
        var counter_number = 0;
        // HOW QUICKLY YOU COUNT IN MS
        var counter_duration = 1;

        var myCounter = setInterval(myTimer, counter_duration);
        function myTimer() {
            counter_number++;
            document.getElementById('average_duration_counter_text').innerHTML = counter_number;
            if(counter_number > AvgGameDuration){
                document.getElementById('average_duration_counter_text').innerHTML = AvgGameDuration.toFixed(1)
                return myStopFunction();
            }
        }

        function myStopFunction() {
            clearInterval(myCounter);
        }
    });
</script>
@endpush
