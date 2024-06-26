<?php
declare(strict_types=1);

function calculateWorkTime(string $arrivedAt, string $leavedAt): int {
    $arrivedAt = new DateTime($arrivedAt);
    $leavedAt = new DateTime($leavedAt);
    $interval = $arrivedAt->diff($leavedAt);
    return $interval->h * 60 + $interval->i;
}

function calculateTotalWorkOffTime(int $workTime, int $workSchedule = 540): int {
    return $workSchedule - $workTime;
}

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($days as $day) {
        $arrivedAt = $_POST["{$day}_come"];
        $leavedAt = $_POST["{$day}_gone"];
        
        $workTime = calculateWorkTime($arrivedAt, $leavedAt);
        $formattedWorkTime = (new DateTime($arrivedAt))->diff(new DateTime($leavedAt))->format('%H:%I');
        
        $workOffTime = calculateTotalWorkOffTime($workTime);
        
        $results[$day] = [
            'workTime' => $formattedWorkTime,
            'workOffTime' => $workOffTime
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        label {
            font-weight: bold;
        }
        input[type="datetime-local"] {
            margin-bottom: 10px;
            width: 100%;
            padding: 5px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .result {
            margin-top: 20px;
        }
        .result p {
            font-size: 18px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <form action="" method="POST">
        <?php foreach ($days as $day): ?>
            <h2><?php echo $day; ?></h2>
            <label for="<?php echo $day; ?>_come">Come</label>
            <input type="datetime-local" name="<?php echo $day; ?>_come" id="<?php echo $day; ?>_come"><br><br>
            <label for="<?php echo $day; ?>_gone">Gone</label>
            <input type="datetime-local" name="<?php echo $day; ?>_gone" id="<?php echo $day; ?>_gone"><br><br>
        <?php endforeach; ?>
        <input type="submit" value="Submit">
    </form>

    <?php if (!empty($results)): ?>
        <div class="result">
            <?php foreach ($results as $day => $result): ?>
                <p><strong>Work duration on <?php echo $day; ?>:</strong> <?php echo $result['workTime']; ?></p>
                <p><strong>Debt on <?php echo $day; ?>:</strong> <?php echo $result['workOffTime']; ?> minutes</p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>
