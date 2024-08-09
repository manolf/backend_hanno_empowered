
<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


// database connection
$dsn = 'mysql:host=localhost;dbname=Advent2023';
$username = 'root';
$password = 'm2d2023';

//user.php

$pdo = new PDO($dsn, $username, $password);

$data = array();
if (isset($_GET['action']) && $_GET['action'] == 'fetchuserdata') {
    
    // Check if userId is provided
    if (isset($_GET['userId'])) 
    {
        $userId = $_GET['userId'];

        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT dayId, tabata.tabataId, tabata.linkShort, tabata.tabataName
            FROM calendar
            JOIN tabata ON (tabata.tabataId = calendar.tabataId)
            WHERE userId = :userId";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['userId' => $userId]);

            // Fetches just one record
            //$userData = $stmt->fetch(PDO::FETCH_ASSOC);

            //Fetches all data
            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return the data as JSON
            echo json_encode($userData);
            
        } 
        catch (PDOException $e) {
                // Handle database connection errors
                echo json_encode(['error' => 'Database connection error: ' . $e->getMessage()]);
            }

    } 
    else 
    {
        // Handle case where userId parameter is missing
        echo json_encode(['error' => 'Missing userId parameter']);
    }

}

/*if (isset($_GET['action']) && $_GET['action'] == 'fetchSingleDay2') {

     $dayId= $_GET['dayId'];
    $query = "
        SELECT * FROM day
        JOIN tabata ON (day.easy  = tabata.tabataId)
    WHERE dayId = $dayId
    ";
    
    $statement = $connect->prepare($query);
    
    $statement->execute();
    
    $result = $statement->fetchAll();
    
    foreach($result as $row)
    {
        $data['id'] = $row['id'];
        $data['first_name'] = $row['first_name'];
        $data['last_name'] = $row['last_name'];
    }
    
    echo json_encode($data);
}*/
if($received_data->action == 'insert')
{
    $data = array(
        ':first_name' => $received_data->firstName,
        ':last_name' => $received_data->lastName
    );
    
    $query = "
    INSERT INTO tbl_sample 
    (first_name, last_name) 
    VALUES (:first_name, :last_name)
    ";
    
    $statement = $connect->prepare($query);
    
    $statement->execute($data);
    
    $output = array(
        'message' => 'Data Inserted'
    );
    
    echo json_encode($output);
}
if($received_data->action == 'update')
{
    $data = array(
        ':first_name' => $received_data->firstName,
        ':last_name' => $received_data->lastName,
        ':id'   => $received_data->hiddenId
    );
    
    $query = "
    UPDATE tbl_sample 
    SET first_name = :first_name, 
    last_name = :last_name 
    WHERE id = :id
    ";
    
    $statement = $connect->prepare($query);
    
    $statement->execute($data);
    
    $output = array(
        'message' => 'Data Updated'
    );
    
    echo json_encode($output);
}

if($received_data->action == 'delete')
{
    $query = "
    DELETE FROM tbl_sample 
    WHERE id = '".$received_data->id."'
    ";
    
    $statement = $connect->prepare($query);
    
    $statement->execute();
    
    $output = array(
        'message' => 'Data Deleted'
    );
    
    echo json_encode($output);
}

/*else {
    // Handle case where action parameter is missing or incorrect
    echo json_encode(['error' => 'Invalid action']);
}*/

?>