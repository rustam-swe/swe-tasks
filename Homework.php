<form action="Homework.php" method="post">
    <?php
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    foreach ($days as $day) {
        echo $day . " Access time: <input type='datetime-local' name='Access_Time[$day]' required><br>";
        echo $day . " Exit time: <input type='datetime-local' name='Exit_Time[$day]' required><br><br>";
    }
    ?>
    <button type="Submit">Submit</button>
</form>
<pre>
    <?php
    function calculate_time_difference($start_time, $end_time)
    {
        return $start_time->diff($end_time);
    }

    function calculate_free_time($difference)
    {
        $worked_hours = $difference->h + ($difference->i / 60);
        return $worked_hours - 9;
    }

    function format_time($hours, $minutes)
    {
        return "$hours hour $minutes minute";
    }

    function process_day($day, $access_time, $exit_time)
    {
        $difference = calculate_time_difference($access_time, $exit_time);
        $free_time = calculate_free_time($difference);

        $worked_time_str = format_time($difference->h, $difference->i);
        $free_time_str = format_time(floor(abs($free_time)), round((abs($free_time) - floor(abs($free_time))) * 60));

        echo "$day Access time: " . $access_time->format('Y-m-d H:i') . "\n";
        echo "$day Exit time: " . $exit_time->format('Y-m-d H:i') . "\n";
        echo "$day Work time: $worked_time_str\n";

        if ($free_time > 0) {
            echo "$day Extra time: $free_time_str\n";
        } else {
            echo "$day Credit time: $free_time_str\n";
        }
        echo "\n";

        return [$difference->h + ($difference->i / 60), $free_time];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $total_worked_time = 0;
        $total_free_time = 0;
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        foreach ($days as $day) {
            if (!empty($_POST['Access_Time'][$day]) && !empty($_POST['Exit_Time'][$day])) {
                $access_time_str = $_POST['Access_Time'][$day];
                $exit_time_str = $_POST['Exit_Time'][$day];

                $access_time = new DateTime($access_time_str);
                $exit_time = new DateTime($exit_time_str);

                if ($exit_time > $access_time) {
                    list($worked_time, $free_time) = process_day($day, $access_time, $exit_time);
                    $total_worked_time += $worked_time;
                    $total_free_time += $free_time;
                } else {
                    echo "Exit time must be after Access time for $day.\n\n";
                }
            } else {
                echo "Enter check In and check Out times for $day !!!\n\n";
            }
        }

        echo "Total worked time: " . format_time(floor($total_worked_time), round(($total_worked_time - floor($total_worked_time)) * 60)) . "\n";
        if ($total_free_time > 0) {
            echo "Total extra time: " . format_time(floor($total_free_time), round(($total_free_time - floor($total_free_time)) * 60)) . "\n";
        } else {
            echo "Total credit time: " . format_time(floor(abs($total_free_time)), round((abs($total_free_time) - floor(abs($total_free_time))) * 60)) . "\n";
        }
    }
    ?>
</pre>
