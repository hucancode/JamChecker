
<?php
    
    function get_mac_count($txt_input)
    {
        
        $arr_file = file($txt_input, FILE_IGNORE_NEW_LINES);

        $num = count($arr_file);
        $mac_pass = $arr_file[$num -1];
        $mac_num = mb_substr($mac_pass, -3);
        $mac_time = $arr_file[0];
        $mac_time_1 = mb_substr($mac_time, 21,2);
        $mac_time_2 = mb_substr($mac_time, 23,2);
        $mac_time = "[".$mac_time_1.":".$mac_time_2."]";
        return $mac_num;
    }

    function calculate_crowded_level($count)
    {
        $LANDMARKS = array(50, 40, 30, 20);
        $level = landmarks.length + 1;
        for ($i = 0; $i <= $landmarks.length; $i++) {
            if($count > landmarks[$i])
            {
                break;
            }
            $level--;
        }
        return $level;
    }

    $DATA_LOCATION = '/usr/share/nginx/html/linebot/okinawa/macaddr/hospital02.txt';
    $MAX_DEVICE_COUNT = 150;
    ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="okinawa.css">
    <title>沖縄国際通り混雑検知</title>
    <script src="scripts/gauge.js"></script>
</head>

<body>
    <center>
        <!-- <span id="okinawa_font"><?php echo date("Y年m月d日 H時i分");?>&emsp;３密状況</span> -->
        <h1 id="preview-textfield"></h1>
        <canvas width=600 height=300 id="gauge-canvas"></canvas>
    </center>
    <script type="text/javascript">
            percentColors = [[0.0, "#a9d70b" ], [0.50, "#f9c802"], [1.0, "#ff0000"]];
            var opts = {
            angle: -0.2, // The span of the gauge arc
            lineWidth: 0.2, // The line thickness
            radiusScale: 1, // Relative radius
            pointer: {
                length: 0.6, // // Relative to gauge radius
                strokeWidth: 0.035, // The thickness
                color: '#000000' // Fill color
            },
            limitMax: false,     // If false, max value increases automatically if value > maxValue
            limitMin: false,     // If true, the min value of the gauge will be fixed
            colorStart: '#6FADCF',   // Colors
            colorStop: '#8FC0DA',    // just experiment with them
            strokeColor: '#E0E0E0',  // to see which ones work best for you
            generateGradient: true,
            highDpiSupport: true,     // High resolution support
            percentColors: [[0.2, "#a9d70b" ], [0.4, "#f9c802"], [0.6, "#ff0000"],[0.8, "#09d20b" ], [1.0, "#f90002"]],
            };
            var target = document.getElementById('gauge-canvas');
            var gauge = new Gauge(target).setOptions(opts);
            gauge.setTextField(document.getElementById("preview-textfield"));
            gauge.animationSpeed = 32; // set animation speed (32 is default value)
            <?php
                echo('gauge.maxValue = '.$MAX_DEVICE_COUNT.';');
                echo('gauge.setMinValue(0);');
                $level = calculate_crowded_level(get_mac_count($DATA_LOCATION));
                $level = 60;
                echo('gauge.set('.$level.');');
            ?>
    </script>
</body>
</html>
