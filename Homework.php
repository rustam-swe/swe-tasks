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

    function format_time_difference($difference)
    {
        return $difference->format('%H hour %i minute');
    }

    function format_time($time)
    {
        $hours = floor($time);
        $minutes = round(($time - $hours) * 60);
        return $hours . " hour " . $minutes . " minute";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $total_worked_time = 0;
        $total_free_time = 0;
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        foreach ($days as $day) {
            if (!empty($_POST['Access_Time'][$day]) && !empty($_POST['Exit_Time'][$day])) {
                $access_time_str = $_POST['Access_Time'][$day];
                $exit_time_str = $_POST['Exit_Time'][$day];

                $access_time = new DateTime($access_time_str);
                $exit_time = new DateTime($exit_time_str);

                if ($exit_time > $access_time) {
                    $difference = calculate_time_difference($access_time, $exit_time);
                    $free_time = calculate_free_time($difference);

                    $total_worked_time += $difference->h + ($difference->i / 60);
                    $total_free_time += $free_time;

                    echo "$day Access time: " . $access_time->format('Y-m-d H:i') . "\n";
                    echo "$day Exit time: " . $exit_time->format('Y-m-d H:i') . "\n";
                    echo "$day Work time: " . format_time_difference($difference) . "\n";

                    if ($free_time > 0) {
                        echo "$day Extra time: " . format_time($free_time) . "\n";
                    } else {
                        echo "$day Credit time: " . format_time(-$free_time) . "\n";
                    }
                    echo "\n";
                } else {
                    echo "Error: Exit time must be after Access time for $day.\n\n";
                }
            } else {
                echo "Enter check In and check Out times for $day !!!\n\n";
            }
        }

        echo "Total worked time: " . format_time($total_worked_time) . "\n";
        if ($total_free_time > 0) {
            echo "Total extra time: " . format_time($total_free_time) . "\n";
        } else {
            echo "Total credit time: " . format_time(-$total_free_time) . "\n";
        }
    }
    ?>
    </pre>
