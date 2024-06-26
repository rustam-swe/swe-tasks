<form action="proekt.php" method="POST">

    <h2>Dushanba</h2>
    <label for="DushanbaarrivedAt">arrivedAt</label>
    <input type="datetime-local" name="Dushanba[arrivedAt]" id="DushanbaarrivedAt" style="background-color:aqua;"><br><br>
    <label for="DushanbaleavedAt">leavedAt</label>
    <input type="datetime-local" name="Dushanba[leavedAt]" id="DushanbaleavedAt" style="background-color:lawngreen;"><br><br>

    <h2>Seshanba</h2>
    <label for="SeshanbaarrivedAt">arrivedAt</label>
    <input type="datetime-local" name="Seshanba[arrivedAt]" id="SeshanbaarrivedAt" style="background-color:aqua;"><br><br>
    <label for="SeshanbaleavedAt">leavedAt</label>
    <input type="datetime-local" name="Seshanba[leavedAt]" id="SeshanbaleavedAt" style="background-color:lawngreen;"><br><br>

    <h2>Chorshanba</h2>
    <label for="ChorshanbaarrivedAt">arrivedAt</label>
    <input type="datetime-local" name="Chorshanba[arrivedAt]" id="ChorshanbaarrivedAt" style="background-color:aqua;"><br><br>
    <label for="ChorshanbaleavedAt">leavedAt</label>
    <input type="datetime-local" name="Chorshanba[leavedAt]" id="ChorshanbaleavedAt" style="background-color:lawngreen;"><br><br>

    <h2>Payshanba</h2>
    <label for="PayshanbaarrivedAt">arrivedAt</label>
    <input type="datetime-local" name="Payshanba[arrivedAt]" id="PayshanbaarrivedAt" style="background-color:aqua;"><br><br>
    <label for="PayshanbaleavedAt">leavedAt</label>
    <input type="datetime-local" name="Payshanba[leavedAt]" id="PayshanbaleavedAt" style="background-color:lawngreen;"><br><br>

    <h2>Juma</h2>
    <label for="JumaarrivedAt">arrivedAt</label>
    <input type="datetime-local" name="Juma[arrivedAt]" id="JumaarrivedAt" style="background-color:aqua;"><br><br>
    <label for="JumaleavedAt">leavedAt</label>
    <input type="datetime-local" name="Juma[leavedAt]" id="JumaleavedAt" style="background-color:lawngreen;"><br><br>

    <h2>Shanba</h2>
    <label for="ShanbaarrivedAt">arrivedAt</label>
    <input type="datetime-local" name="Shanba[arrivedAt]" id="ShanbaarrivedAt" style="background-color:aqua;"><br><br>
    <label for="ShanbaarrivedAt">leavedAt</label>
    <input type="datetime-local" name="Shanba[leavedAt]" id="ShanbaarrivedAt" style="background-color:lawngreen;"><br><br>

    <h2>Yakshanba</h2>
    <label for="YakshanbaarrivedAt">arrivedAt</label>
    <input type="datetime-local" name="Shanba[arrivedAt]" id="YakshanbaarrivedAt" style="background-color:aqua;"><br><br>
    <label for="YakshanbaarrivedAt">leavedAt</label>
    <input type="datetime-local" name="Yakshanba[leavedAt]" id="YakshanbaarrivedAt" style="background-color:lawngreen;"><br><br>
    <button type="submit">Submit</button>
</form>


<pre>
<?php

function calculateWorkTime($arrivedAt, $leavedAt) {
    $arrivedAt = new DateTime($arrivedAt);
    $leavedAt = new DateTime($leavedAt);
    $interval = $arrivedAt->diff($leavedAt);
    return $interval->h * 60 + $interval->i;
}

function calculateTotalWorkOffTime($workTime, $workSchedule = 540) {
    return $workTime <= $workSchedule ? $workSchedule - $workTime : $workTime - $workSchedule;
}

$days = ['Dushanba', 'Seshanba', 'Chorshanba', 'Payshanba', 'Juma', 'Shanba', 'Yakshanba'];

foreach ($days as $day) {
    if (isset($_POST[$day])) {
        $arrivedAt = $_POST[$day]['arrivedAt'];
        $leavedAt = $_POST[$day]['leavedAt'];

        $workTime = calculateWorkTime($arrivedAt, $leavedAt);
        $formattedWorkTime = (new DateTime($arrivedAt))->diff(new DateTime($leavedAt))->format('%H:%i');

        echo "Work duration on $day: $formattedWorkTime";
        echo "\n";
        $workOffTime = calculateTotalWorkOffTime($workTime);
        echo "Debt on $day: $workOffTime minutes";
        echo "\n\n";
    }
}
?>

</pre>
