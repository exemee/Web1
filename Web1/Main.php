<?php
$x_values = array(-2,-1.5,-1,-0.5,0,0.5,1,1.5,2);
$r_values = array(1,1.5,2,2.5,3);
$y = null;
$x = null;
$r = null;
$time_elapsed_secs = 0;
$result_array = [];

session_start();
if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = array();
}

function checkData()
{
    global $x_values;
    global $r_values;
    global $y, $x, $r;
    global $resultArray;

    $y = $_GET['y'];
    $x = $_GET['x'];
    $r = $_GET['select'];

    if ($y < -3 || $y > 3 ||  !in_array($r, $r_values) ||  !in_array($x, $x_values ) || !is_numeric($r) || !is_numeric($y) || !is_numeric($x)) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        $resultArray = array($x, $y, $r, checkSpotInArea());
        array_push($_SESSION['history'], $resultArray);
    }

}

function checkSpotInArea()
{
    global $y, $x, $r;
    $inArea = false;

    if (($x <= $r/2 && $x >= 0 && $y >= -$r && $y <= 0) ||
    ($y <= ($r - $x) && $y >= 0 && $x >= 0) ||
    (($x*$x + $y*$y) <= $r*$r && $x <= 0 && $y >= 0)) {
        $inArea = true;
    }

    return $inArea;
}

checkData();

$inArea = checkSpotInArea();
$time_elapsed_secs = number_format((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000000, 2, ",", ".") . " мкс";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Web1</title>
    <link rel="stylesheet" href="Static/css/Table.css">
    <script src="Static/js/Script.js"></script>
</head>
<body>
<section>
    <table style="width: 100%; height: 100%;">
        <tr>
            <td>
                <main class="contentRect animated zoomIn">
                    <a class='photo cross' href='index.html'></a>
                    <a class='photo tableButton' id="tableButton" onclick="showTable()"></a>

                    <div id="infoContent">
                        <div class="baseInsideRect actionTimeRect">
                            <span>Время выполнения</span>
                            <p id="actionTime"></p>
                        </div>

                        <div class="baseInsideRect currentTimeRect">
                            <span>Текущее время</span>
                            <p id="currentTime"></p>
                            <script type="text/javascript">
                                function setTime() {
                                    document.getElementById("currentTime").innerHTML = new Date().toLocaleTimeString();
                                }

                                setInterval(setTime, 1000);
                                setTime();
                            </script>

                            <script type="text/javascript">
                                function setTime() {
                                    let x = '<?php echo $x;?>';
                                    let y = '<?php echo $y;?>';
                                    let r = '<?php echo $r;?>';
                                    let inArea = '<?php echo $inArea;?>';
                                    let elapsedTime = '<?php echo $time_elapsed_secs?>';
                                    document.getElementById("pX").innerHTML = x;
                                    document.getElementById("pY").innerHTML = y;
                                    document.getElementById("pR").innerHTML = r;
                                    document.getElementById("actionTime").innerHTML = elapsedTime;
                                    if (inArea) {
                                        document.getElementById("pResult").innerHTML = "Попал"
                                        document.getElementById("pResult").style.color = "green"
                                    } else {
                                        document.getElementById("pResult").innerHTML = "Промах"
                                        document.getElementById("pResult").style.color = "red"
                                    }


                                }

                                setTimeout(setTime, 1);
                            </script>
                        </div>

                        <div class="baseInsideRect xRect">
                            <span>X</span>
                            <p id="pX"></p>
                        </div>

                        <div class="baseInsideRect yRect">
                            <span>Y</span>
                            <p id="pY"></p>
                        </div>

                        <div class="baseInsideRect rRect">
                            <span>R</span>
                            <p id="pR"></p>
                        </div>

                        <div class="baseInsideRect resultRect">
                            <span>Результат</span>
                            <p id="pResult"></p>
                        </div>
                    </div>

                    <div class="tableHead" id="table" style="visibility: hidden">
                        <table id="dataTable" style="text-align: center;" class="hide" width="100%">
                            <thead align="center">
                            <tr>
                                <th width="20%">X</th>
                                <th width="20%">Y</th>
                                <th width="20%">R</th>
                                <th width="40%">Результат</th>
                            </tr>
                            </thead>


                            <tbody id="tableBody">
                            <?php foreach ($_SESSION['history'] as $value) { ?>
                                <tr>
                                    <td><?php echo $value[0] ?></td>
                                    <td><?php echo $value[1] ?></td>
                                    <td><?php echo $value[2] ?></td>
                                    <?php
                                    if($value[3] == "1") echo "<td style='color:green;'>Попал!</td>";
                                    else echo "<td style='color:red;'>Промах!</td>";
                                    ?>
                                </tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </main>
            </td>
        </tr>
    </table>
</section>
</body>
</html>

