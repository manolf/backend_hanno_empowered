
<?php

// Setze die CORS-Header
header("Access-Control-Allow-Origin: *"); // Erlaubt Anfragen von allen Domains
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Erlaubt die Methoden POST, GET und OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Erlaubt den Content-Type Header und andere spezifizierte Header
header('Content-Type: application/json; charset=utf-8');

// Prüfen, ob es sich um eine OPTIONS-Anfrage handelt (Preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Die Preflight-Anfrage benötigt keine weitere Verarbeitung
    http_response_code(204); // Antwortet mit No Content
    exit;
}


// header('Content-Type: application/json; charset=utf-8');
// header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json');


// database connection
$dsn = 'mysql:host=localhost;dbname=Advent2023';
$username = 'root';
$password = 'm2d2023';

//user.php

$pdo = new PDO($dsn, $username, $password);
            
//why not?
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

//insertTabata
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['action']) && $input['action'] === 'inserttabata') {
        $dayId = $input['dayId'] ?? null;
        $tabataId = $input['tabataId'] ?? null;
        $userId = $input['userId'] ?? null;

        try {
            $sql = "
                INSERT INTO
                    calendar (dayId, tabataId, userId)  
                VALUES
                (:dayId, :tabataId, :userId)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([':dayId' => $dayId, ':tabataId' => $tabataId, ':userId' => $userId]);
            
            echo json_encode(['message' => 'Hurra, dein Wod wurde erfolgreich gespeichert!.']);
        }
        catch (PDOException $e) {
            echo json_encode(['message' => 'Fehler: ' . $e->getMessage()]);
        }
    } 
    else {
        echo json_encode(['message' => 'Ungültige Aktion.']);
    }
} 

else {
    echo json_encode(['message' => 'Ungültige Anfrage.']);
}

?>