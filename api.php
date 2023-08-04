<?php
$db_host = "your hosting name";
$db_user = "your user";
$db_pass = " your password";
$db_name = "database name";

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
$response = array();

if ($conn) {
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Handle insertion of new data
      $data = json_decode(file_get_contents('php://input'), true);

      if ($data) {
          $name = $data['Name'];
          $age = $data['Age'];
          $email = $data['Email'];

          $insertQuery = "INSERT INTO api1 (Name, Age, Email) VALUES ('$name', '$age', '$email')";
          $insertResult = mysqli_query($conn, $insertQuery);

          if ($insertResult) {
              $response['message'] = 'Data inserted successfully';

              // Save data to JSON file
              $jsonFilePath = 'data.json';
              $jsonData = file_get_contents($jsonFilePath);
              $existingData = json_decode($jsonData, true) ?: [];
              $newData = array('Name' => $name, 'Age' => $age, 'Email' => $email);
              $existingData[] = $newData;
              file_put_contents($jsonFilePath, json_encode($existingData, JSON_PRETTY_PRINT));
          } else {
              $response['error'] = 'Failed to insert data';
          }
      }
  }

    $sql = "select *from api1";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");  // Add this line
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");  // Add this line
        header("Access-Control-Allow-Headers: Content-Type");  // Add this line

        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $response[$i]['id'] = $row['id'];
            $response[$i]['Name'] = $row['Name'];
            $response[$i]['age'] = $row['Age'];
            $response[$i]['email'] = $row['Email'];
            $i++;
        }
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
} else {
    echo "Database connection failed";
}
?>
