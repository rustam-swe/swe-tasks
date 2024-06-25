<?php

declare(strict_types=1);

/**
 * Continue working with user input.
 * - Calculate work off time (qarz) if you didn’t yet
 * - Show inputs from Monday to Friday and calculate in total work time and work off time
 * */
<form action="lesson_3.php" method="post">
    Kelgan vaqtingiz :<input type="datetime-local" name="arrivedAt" required>
    <br>
    Ketgan vaqtingiz :<input type="datetime-local" name="leavedAt" required>
    <br>
    <button type="submit">Hisoblash</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function Work_Duration($arrivedAt, $leavedAt)
    {
        $startTime = new DateTime($arrivedAt);
        $endTime = new DateTime($leavedAt);
        $interval = $startTime->diff($endTime);
        return $interval;
    }
function qarz_vaqt($workDuration,$standarts_vaqt = 9)
    {
        $standarts_s = $standarts_vaqt * 3600;

        $ishlangan_s = ($workDuration->h*3600) + ($workDuration->i*60) + $workDuration->s;

        $qarz_s = $standarts_s - $ishlangan_s;

        $qarz_h = floor($qarz_s/3600);
        $qarz_m = floor(($qarz_s% 3600)/60);
        $qarz_s %= 60;

        return [$qarz_h,$qarz_m,$qarz_s];
    }
    $arrivedAt = $_POST["arrivedAt"];
    $leavedAt = $_POST["leavedAt"];

    $workDuration = Work_Duration($arrivedAt, $leavedAt);

    echo "bir kunlik ishlagan vaqt".$workDuration->format('%H:%I:%S') . "<br>";

    list($qarz_h,$qarz_m,$qarz_s) = qarz_vaqt($workDuration);
    if ($qarz_h > 0 || $qarz_m > 0 || $qarz_s > 0) {
        echo "Qarz: $qarz_h:$qarz_m:$qarz_s<br>";
    }else{
        echo "Qarzlar yoq malades !'";
    }
}
?>

