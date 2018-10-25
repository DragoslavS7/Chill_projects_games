
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
    <div style="color:rgb(85,85,85); font-size:30px;">Total Players</div>
    <div style="color: rgb(85,85,85); font-size:70px"><span id="number_of_players_counter_text">0</span></div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        // SETS THE NUMBER OF USERS
        var NumberOfPlayersResponse = JSON.parse('{!!  $players !!}');

        var numberOfUsers = NumberOfPlayersResponse.number_of_users;
        // STARTING NUMBER
        var counter_number = 0;
        // HOW QUICKLY YOU COUNT IN MS
        var counter_duration = 100;

        var myCounter = setInterval(myTimer, counter_duration);

        function myTimer() {
            counter_number++;
            document.getElementById('number_of_players_counter_text').innerHTML = counter_number;
            if(counter_number === numberOfUsers){
                return myStopFunction();
            }
        }

        function myStopFunction() {
            clearInterval(myCounter);
        }
    });
</script>
@endpush
