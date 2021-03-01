<?php
    function get_mac_count($txt_input)
    {
        $lines = file($txt_input, FILE_IGNORE_NEW_LINES);
        $words = explode(' ', array_pop($lines));
        return array_pop($words);
    }

    // function calculate_crowded_level($count)
    // {
    //     $LANDMARKS = array(50, 40, 30, 20);
    //     $level = landmarks.length + 1;
    //     for ($i = 0; $i <= $landmarks.length; $i++) {
    //         if($count > landmarks[$i])
    //         {
    //             break;
    //         }
    //         $level--;
    //     }
    //     return $level;
    // }

    function query_data_path()
    {
        $query = $_GET["hospital"];
        $path = "";
        $all_pi = json_decode(file_get_contents("./data/hospital_pi.json"), true);
        
        // slow, need to optimize this
        foreach ($all_pi as $id => $data) {
            if(strcmp($data['name'], $query) == 0)
            {
                $path = $data['path'];
                break;
            }
        }
        return $path;
    }
    $MAX_DEVICE_COUNT = 150;
    $mac_count = get_mac_count(query_data_path());
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Bang Nguyen Huu">
        <link rel="stylesheet" href="gauge.css">
        <title>沖縄国際通り混雑検知</title>
        <style>
            @keyframes needle-animation {
                0% {
                    transform: rotate(0);
                }
                100% {
                    <?php 
                        $angle = $mac_count/$MAX_DEVICE_COUNT*180;
                        echo('transform: rotate('.$angle.'deg);'); 
                    ?>
                }
            }
        </style>
        <link href="./assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <main class="w-100">
            <div class="container-fluid">
                <div class="wrapper">
                    <div class="panel">
                        <div class="gauge">
                            <div class="gauge-step"><div class="icon"></div></div>
                            <div class="gauge-step"><div class="icon"></div></div>
                            <div class="gauge-step"><div class="icon"></div></div>
                            <div class="gauge-step"><div class="icon"></div></div>
                            <div class="gauge-step"><div class="icon"></div></div>
                        </div>
                        <div class="needle" style="animation: needle-animation 2s 1;animation-fill-mode: forwards;"></div>
                        <div class="gauge-center" <?php echo('data-mac-count="'.$mac_count.'/'.$MAX_DEVICE_COUNT.'"'); ?> ></div>
                    </div>
                </div>
            </div>
        </main>
        <script src="./assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
