<!DOCTYPE html>
<html>
<head>
    <title>Ishchining ishlagan soatlari hisoboti </title>
</head>
<body>
    <h1>Hisobot javali</h1>
    <form method="post" action="">
        <?php
        $daysOfWeek = ['Dushanba', 'Seshanba', 'Chorshanba', 'Payshanba', 'Juma'];
        
        foreach ($daysOfWeek as $day) {
            echo "<h3>$day</h3>";
            echo '<label for="arrived_'.$day.'">Kirish vaqti:</label>';
            echo '<input type="datetime-local" id="arrived_'.$day.'" name="arrived_'.$day.'" required><br>';
            
            echo '<label for="leaved_'.$day.'">Chiqish vaqti:</label>';
            echo '<input type="datetime-local" id="leaved_'.$day.'" name="leaved_'.$day.'" required><br><br>';
        }
        ?>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        function calculateWorkDuration($arrived, $leaved) {
            $time_to_work = strtotime($arrived);
            $left = strtotime($leaved);
            $duration = ($left - $time_to_work) / 3600; 
            $creditTime = $duration - 8; 

            return array(
                'arrived' => $arrived, 
                'leaved' => $leaved, 
                'credit_time' => $creditTime, 
                'total_worked_hours' => $duration
            );
        }

        $workDays = array();

        foreach ($daysOfWeek as $day) {
            $arrived = $_POST['arrived_'.$day];
            $leaved = $_POST['leaved_'.$day];

            $workDays[] = calculateWorkDuration($arrived, $leaved);
        }

        usort($workDays, function($a, $b) {
            return strcmp($a['arrived'], $b['arrived']);
        });

        echo "<h2>Natija:</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Kirish vaqti</th><th>Chiqish vaqti</th><th>Kredit vaqti (soat)</th><th>Jami ishlangan soatlar</th></tr>";

        $totalCreditTime = 0;
        $totalWorkedHours = 0;

        foreach ($workDays as $day) {
            echo "<tr>";
            echo "<td>".$day['arrived']."</td>";
            echo "<td>".$day['leaved']."</td>";
            echo "<td>".$day['credit_time']."</td>";
            echo "<td>".$day['total_worked_hours']."</td>";
            echo "</tr>";

            $totalCreditTime += $day['credit_time'];
            $totalWorkedHours += $day['total_worked_hours'];
        }

        echo "</table>";

        echo "<p>Umumiy ish soatlari qarzi: " . $totalCreditTime . " soat</p>";
        echo "<p>Jami ishlangan soatlar: " . $totalWorkedHours . " soat</p>";
    }
    ?>
