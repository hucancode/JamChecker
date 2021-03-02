<?php
    
    $MAX_JAM_LEVEL = 5;
    $MAX_DEVICE_COUNT = 35;
    $hospital = $_GET["hospital"];
    $data_path = "";
    $last_updated = "__:__";
    $mac_count = get_mac_count(query_data_path());
    function get_mac_count($txt_input)
    {
        global $last_updated;
        if (!file_exists($txt_input))
        {
            return "0";
        }
        // slow, need to optimize this
        $lines = file($txt_input, FILE_IGNORE_NEW_LINES);
        $words = explode(' ', array_pop($lines));
        $last_updated = date ("Y年m月d日 H:i", filemtime($txt_input));
        return array_pop($words);
    }

    function calculate_jam_level($count)
    {
        global $MAX_DEVICE_COUNT, $MAX_JAM_LEVEL;
        return floor(min($count / $MAX_DEVICE_COUNT, 0.9999) * $MAX_JAM_LEVEL);
    }

    function query_data_path()
    {
        global $hospital, $data_path;
        $all_pi = json_decode(file_get_contents("./data/hospital_pi.json"), true);
        // slow, need to optimize this
        foreach ($all_pi as $id => $data) {
            if(strcmp($data['name'], $hospital) == 0)
            {
                $data_path = $data['path'];
                break;
            }
        }
        return $data_path;
    }
    
?>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Bang Nguyen Huu">
        <link href="./assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="gauge.css">
        <title>沖縄国際通り混雑検知</title>
        <style>
            @keyframes needle-animation {
                0% {
                    transform: rotate(0);
                }
                100% {
                    <?php 
                        $angle = min($mac_count/$MAX_DEVICE_COUNT, 1.0)*180;
                        echo('transform: rotate('.$angle.'deg);'); 
                    ?>
                }
            }
        </style>
        
    </head>
    <body>
        <main class="w-100 h-100">
        <div class="title"><h1><?php echo($hospital); ?></h1></div>
            <div class="description"><h3>混み具合表示</h3></div>
            <div class="status-text"><h3>現在の混雑状況<span class="level-<?php echo(calculate_jam_level($mac_count)); ?>"></span></h3></div>
            <div class="status-img"><img src="assets/status_<?php echo(calculate_jam_level($mac_count)); ?>.png" /></div>
            <div class="time-stamp"><h4>更新時点「<?php echo($last_updated); ?>」</h4></div>
            <div class="panel">
                <div class="gauge">
                    <div class="gauge-step"><div class="icon"></div></div>
                    <div class="gauge-step"><div class="icon"></div></div>
                    <div class="gauge-step"><div class="icon"></div></div>
                    <div class="gauge-step"><div class="icon"></div></div>
                    <div class="gauge-step"><div class="icon"></div></div>
                </div>
                <div class="gauge-center"></div>
                <div class="needle-img" style="animation: needle-animation 2s 1;animation-fill-mode: forwards;"></div>
                <div class="needle-img-root">
                    <div class="hole"></div>
                </div>
            </div>
            <div class="share-button"><h3>アプリを知人に知らせる</h3></div>
        </main>
        <script src="./assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
