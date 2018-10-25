<?php
$script = "

<script type='text/javascript'>
    function Mac(){
        this._userId = $user->id;
        this._filePathPrefix = '$filePathPrefix';
        this._gameId = $game->id;
    }

    Mac.prototype.getFilePathPrefix = function (){
        return this._filePathPrefix;
    };

    Mac.prototype.getGameId = function (){
        return this._gameId;
    };

    Mac.prototype.getUserId = function (){
        return this._userId;
    };

    Mac.prototype.getAbsolutePath = function(path){

        if(path.charAt(0) != '/'){
            path = '/' + path;
        }

        return this.getFilePathPrefix() + path;
    };

    window.MAC = window.MAC || new Mac();

</script>

";

        $html = \File::get(substr($filePathPrefix, 1) . '/index.html');
        $html = preg_replace('/(<\s*html.*?>)/', "$1$script", $html);
?>


{!! $html !!}