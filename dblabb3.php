<?php

// Lägg till itemtype
if (isset($_POST['addType'])) {
    // query för att lägga till name i itemtypes
    $query = "INSERT INTO itemtypes(name) VALUES (:name)";
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
    // filtrera input
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);

    $sth = $db->prepare($query);

    if ($sth->execute(array(':name' => $name))) {
        echo "<h4>Itemtype added</h4>";
    } else {
        // om något går fel skriv ut PDO felmeddelande
        echo "<h4>Error</h4>";
        echo "
<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
    }
}
