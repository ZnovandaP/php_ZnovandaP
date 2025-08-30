<?php
class Database
{
  private $pdo;
  // sqlite database path
  private $databasePath = __DIR__ . '/database/mydb.sqlite';

  public function __construct()
  {
    $this->connect();
  }

  private function connect()
  {
    try {
      $dsn = "sqlite:" . $this->databasePath;
      $this->pdo = new PDO($dsn);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die("Connection Failed: " . $e->getMessage());
    }
  }

  // GET data dengan optional filter
  public function get($table, $where = "", $params = [])
  {
    $sql = "SELECT * FROM $table";
    if ($where)
      $sql .= " WHERE $where";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getHobi($where = "", $params = [])
  {
    $sql = "SELECT hobi.hobi, count(hobi.person_id) as jumlah FROM hobi
    JOIN person ON hobi.person_id = person.id";
    if ($where) {
      $sql .= " WHERE $where";
    }
    $sql .= " GROUP BY hobi.hobi";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}

$db = new Database();
$searchHobi = $_GET['hobi'] ?? '';
$searchHobiLower = strtolower($_GET['hobi'] ?? '');
$hobi = $db->getHobi('hobi.hobi LIKE LOWER(:hobi)', ['hobi' => "%$searchHobiLower%"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Soal 2 A</title>
  <style>
    th,
    td {
      padding: 0.5rem;
    }
  </style>
</head>

<body>
  <h1>Soal 2 A: Zidane Novanda Putra</h1>

  <div style="display: flex; align-items: center; gap: 1rem">
    <form>
      <div style="display: flex; align-items: center; gap: 1rem">
        <div>
          <label for="hobi">
            Search by hobi:
          </label>
          <input type="text" name="hobi" id="hobi" value="<?= $searchHobi ?>">
        </div>

        <button id="submit-search">
          submit
        </button>
      </div>
    </form>

    <button>
      <a href="soal2a.php">reset search</a>
    </button>
  </div>

  <?php
  if ($searchHobi) {
    echo "<p>result search: $searchHobi</p>";
  }
  ?>


  <table border="1" style="margin-top: 1rem; border-collapse: collapse;">
    <thead>
      <tr>
        <th>Hobi</th>
        <th>Jumlah Person</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($hobi as $h): ?>
        <tr>
          <td><?= $h['hobi'] ?></td>
          <td><?= $h['jumlah'] ?></td>
        </tr>
      <?php endforeach; ?>

      <?php
      if (!$hobi) {
        echo "<tr><td colspan='2'>Tidak ada data</td></tr>";
      }
      ?>
    </tbody>

  </table>
</body>

</html>