<?php

class Workly{

    public $username;
    public $password;
    public $dbname;
    public $host;
    public $pdo;
    public $workDuration;
    public $totalWorkOfTime;
    
    public function __construct($username,$password,$dbname,$host){
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->host = $host;
    }

    public function connect(){
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname",$this->username,$this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed :" . $e->getMessage();
        }
    }

    public function fetchAllRows(){
        try{
            $query = "SELECT * FROM daily";
            $stmt = $this->pdo->query($query);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        } catch(PDOException $e){
            echo "Error : " . $e->getMessage();
        }
    }

    public function insertData($arrivedAt,$leavedAt){
        try {
            $query = "INSERT INTO daily (arrived_at,leaved_at) VALUES (:arrivedAt,:leavedAt);";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(":arrivedAt",$arrivedAt);
            $stmt->bindValue(":leavedAt",$leavedAt);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error : " . $e->getMessage();
        }
    }

}

$data = new Workly('root','root','Workly','localhost');
$data->connect();
$rows = $data->fetchAllRows();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $arrivedAt = $_POST['arrived_at'];
    $leavedAt = $_POST['leaved_at'];

    $data->insertData($arrivedAt, $leavedAt);

    header("Location: {$_SERVER['PHP_SELF']}");

    exit();

}

?>
<!DOCTYPE html>
<html>
<head>
    <title>PWOT - Personal Work Off Tracker</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-10">PWOT - Personal Work Off Tracker</h1>

    <form method="post" action="" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="arrived_at">Arrived at</label>
                <input type="datetime-local" class="form-control" name="arrived_at" required>
            </div>
            <div class="form-group col-md-6">
                <label for="leaved_at">Leaved at</label>
                <input type="datetime-local" class="form-control" name="leaved_at" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <table class="table table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Arrived at</th>
            <th>Leaved at</th>
            <th>Required work off</th>
            <th>Worked off</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($rows)) : ?>
            <?php foreach ($rows as $index => $row) {
                $arrived_at = new DateTime($row['arrived_at']);
                $leaved_at = new DateTime($row['leaved_at']);
                $requiredWorkHours = new DateTime('09:00:00');

                $duration = $arrived_at->diff($leaved_at);
                $intervalInSeconds = $duration->s + ($duration->i * 60) + ($duration->h * 3600);
                $requiredSeconds = $requiredWorkHours->format('s') + ($requiredWorkHours->format('i') * 60) + ($requiredWorkHours->format('h') * 3600);

                $debtInSeconds = $requiredSeconds - $intervalInSeconds;
                $total_debt += $debtInSeconds;
                ?>
                <tr>
                    <td><?php echo $index + 1;?></td>
                    <td><?php echo $arrived_at->format('Y-m-d H:i:s'); ?></td>
                    <td><?php echo $leaved_at->format('Y-m-d H:i:s'); ?></td>
                    <td><?php
                            if ($debtInSeconds > 0) {
                                if(($debtInSeconds / 60) >= 60 ){
                                        echo (int)($debtInSeconds / 3600) . ' hours and ' . (int)(($debtInSeconds%3600) / 60) . " min";
                                } else {
                                        echo (int)($debtInSeconds / 60) . " min"; 
                                }
                            } else {
                                echo "0 min.";
                            }
                            ?></td>
                    <td><?php echo ($debtInSeconds < 0) ? "Worked on" : "Worked off"?></td>
                </tr>
            <?php } ?>
        <?php endif; ?>
    </tbody>
</table>
    <h5>Total Work Of Time : <?php echo (int)($total_debt / 3600) . ' hours and ' . (int)(($total_debt%3600) / 60) . " min"?> </h5>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>