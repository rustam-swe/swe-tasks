<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Schedule</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .day {
        margin-bottom: 20px;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 8px;
    }

    .form-group {
        margin-bottom: 10px;
    }

    button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }

    .results {
        margin-top: 20px;
    }

    .result-item {
        background-color: #f9f9f9;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .result-item p {
        margin: 5px 0;
    }

</style>
<body>
    <div class="container">
        <form action="Homework.php" method="post">
            <?php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

            foreach ($days as $day) {
                echo "<div class='day'>";
                echo "<h3>$day</h3>";
                echo "<div class='form-group'>";
                echo "<label for='arrivedAt[$day]'>Arrived at:</label>";
                echo "<input type='datetime-local' id='arrivedAt[$day]' name='arrivedAt[$day]' required>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label for='leavedAt[$day]'>Leaved at:</label>";
                echo "<input type='datetime-local' id='leavedAt[$day]' name='leavedAt[$day]' required>";
                echo "</div>";
                echo "</div>";
            }
            ?>
            <button type="submit">Send</button>
        </form>

        <div class="results">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                foreach ($days as $day) {
                    if (isset($_POST["arrivedAt"][$day], $_POST["leavedAt"][$day])) {
                        $workTime = calculateWorkTime($_POST["arrivedAt"][$day], $_POST["leavedAt"][$day])->h * 60 + calculateWorkTime($_POST["arrivedAt"][$day], $_POST["leavedAt"][$day])->i;
                        $formattedWorkTime = calculateWorkTime($_POST["arrivedAt"][$day], $_POST["leavedAt"][$day])->format("%H:%I");

                        $workOffTime = calculateTotalWorkOffTime($workTime);

                        echo "<div class='result-item'>";
                        echo "<p><strong>$day:</strong></p>";
                        echo "<p><strong>Work duration:</strong> $formattedWorkTime</p>";
                        echo "<p><strong>Debt:</strong> $workOffTime minutes</p>";
                        echo "</div>";
                    }
                }
            }
            ?>
        </div>
    </div>
</body>
</html>



<?php
function calculateWorkTime($arrivedAt, $leavedAt) {
    $arrivedAt = new DateTime($arrivedAt);
    $leavedAt = new DateTime($leavedAt);
    $interval = $arrivedAt->diff($leavedAt);
    return $interval;
}

function calculateTotalWorkOffTime($workTime) {
    $workSchedule = 540;

    if ($workTime <= $workSchedule) {
        return $workSchedule - $workTime;
    } else {
        return $workTime - $workSchedule;
    }
}

?>

