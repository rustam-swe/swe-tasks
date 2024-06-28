<?php

class Database {
    private $host;      
    private $dbname;  
    private $username;  
    private $password;  
    private $pdo;       

    public function __construct($host, $dbname, $username, $password) {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }

    public function connect() {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Ulanishda xatolik: " . $e->getMessage();
        }
    }

    public function fetchAllRows() {
        try {
            $query = "SELECT * FROM daily";
            $stmt = $this->pdo->query($query);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        } catch (PDOException $e) {
            echo "Xatolik: " . $e->getMessage();
        }
    }

    public function insertData($arrivedAt, $leavedAt) {
        try {
            $query = "INSERT INTO daily (arrived_at, leaved_at) VALUES (:arrivedAt, :leavedAt)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':arrivedAt', $arrivedAt);
            $stmt->bindValue(':leavedAt', $leavedAt);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Xatolik: " . $e->getMessage();
        }
    }
}

$data = new Database('localhost', 'vaqt', 'root', 'Valijon9601!');
$data->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arrivedAt = $_POST['arrived_at'];
    $leavedAt = $_POST['leaved_at'];

    $data->insertData($arrivedAt, $leavedAt);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$rows = $data->fetchAllRows();

?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaqt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="datetime-local"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 10px;
            padding: 5px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1> Vaqt</h1>

    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <label for="arrived_at">Kelgan vaqti:</label>
                <input type="datetime-local" id="arrived_at" name="arrived_at" required>
            </div>

            <div class="form-group">
                <label for="leaved_at">Ketgan vaqti:</label>
                <input type="datetime-local" id="leaved_at" name="leaved_at" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Jo'natish">
            </div>
        </form>
    </div>

    <?php if (!empty($rows)) : ?>
        <ul>
            <?php foreach ($rows as $row) : ?>
                <li>ID: <?php echo $row['id']; ?>, Kelgan vaqti: <?php echo $row['arrived_at']; ?>, Ketgan vaqti: <?php echo $row['leaved_at']; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
