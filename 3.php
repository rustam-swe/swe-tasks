<?php

declare(strict_types=1);
date_default_timezone_set('Asia/Tashkent'); 
$conn = new PDO(
    'mysql:host=localhost;dbname=vaqt1',
    'root',
    'root');


?>

    <form action="3.php" method="post">
        <label>
            Arrived At
            <input type="datetime-local" name="vaqt" required>
        </label><br>

        <label>
            Leaved At
            <input type="datetime-local" name="vaqt1" required>
        </label>
        <button>Submit</button>
    </form>

<?php

if (($_POST["vaqt"])!== '' && ($_POST["vaqt1"]) !== '') {
    $arrived_at = (new DateTime($_POST['vaqt']))->format('Y-m-d H:i:s');
    $leaved_at  = (new DateTime($_POST['vaqt1']))->format('Y-m-d H:i:s');
    echo $arrived_at."\n";
    echo $leaved_at;
    
    $sql = "INSERT INTO vaqt(id,arrived_at, leaved_at) VALUES ('$arrived_at','$leaved_at')";


    $sql = "SELECT * FROM vaqt";
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<h2>' . $row["id"] . '</h2>';
            echo '<h2>' . $row["vaqt"] . '</h2>'
            echo '<h2>' . $row["vaqt1"] . '</h2>'
            }
        }
    
    if($conn->query($sql) === TRUE){
        echo "yes";

    }else{
        echo "no";
    }
}
else {
    echo 'Please fill the inputs';
}
$stmt->cloce();
?>




