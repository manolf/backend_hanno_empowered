
<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


// Example PDO database connection
$dsn = 'mysql:host=localhost;dbname=Advent2023';
$username = 'root';
$password = 'm2d2023';

//day.php

$pdo = new PDO($dsn, $username, $password);

$data = array();
if (isset($_GET['action']) && $_GET['action'] == 'fetchall') {
    // Führen Sie die entsprechende Aktion aus, z.B. alle Datensätze aus der Datenbank abrufen

    $query = "
        SELECT * FROM day
        JOIN tabata ON (day.easy  = tabata.tabataId)
        ORDER BY day.dayId
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        $data[] = $row;
    }
    echo json_encode($data);
}

if (isset($_GET['action']) && $_GET['action'] === 'fetchsingle') {
    
    // Check if dayId is provided
    if (isset($_GET['dayId'])) 
    {
        $dayId = $_GET['dayId'];

        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql =  "SELECT * FROM day
                    JOIN tabata ON (day.easy  = tabata.tabataId)
                     WHERE dayId = :dayId";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['dayId' => $dayId]);

            // Fetch the data
            $dayData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return the data as JSON
            echo json_encode($dayData);
            
        } 
        catch (PDOException $e) {
                // Handle database connection errors
                echo json_encode(['error' => 'Database connection error: ' . $e->getMessage()]);
            }

    } 
    else 
    {
        // Handle case where dayId parameter is missing
        echo json_encode(['error' => 'Missing dayId parameter']);
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
else {
    // Handle case where action parameter is missing or incorrect
    echo json_encode(['error' => 'Invalid action']);
}

?>