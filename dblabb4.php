<?php
/* dblabb4.php */
include 'dblabb2.php';
$db = new DB();

// Register loan
if (isset($_POST['addLoan'])) {
    // filtrera input, float för datum fältet tillåter - och .
    $return = filter_input(INPUT_POST, 'returndate', FILTER_SANITIZE_NUMBER_FLOAT);
    $item = filter_input(INPUT_POST, 'item', FILTER_SANITIZE_NUMBER_INT);
    $user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_NUMBER_INT);

    /* Eftersom det är möjligt att föremål ska lånas innan de finns i
     * låntabellen så behöver vi kontrollera om active är 0 eller
     * inte satt.
     */
    $query = "SELECT active FROM loan WHERE item = :id AND active = 0 OR active = ''";
    $sth = $db->prepare($query);
    if ($sth->execute(array(':id' => $item))) {
        // lägg till lånet, vi anger inte active då det automatiskt sätts till 1
        $query = "INSERT INTO loan(item, user, returndate)
                  VALUES (:item, :user, :returndate)";

        $values = array(
            ':item' => $item,
            ':user' => $user,
            ':returndate' => $return
        );

        $sth = $db->prepare($query);
        if ($sth->execute($values)) {
            echo "Loan for item with id " . $item . " registered.";
        } else {
            // om något går fel skriv ut PDO felmeddelande
            echo "<h4>Error</h4>";
            echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
        }
    } else { // det fanns redan ett aktivt lån för det föremålet
        echo "A loan for item with id: " . $item . " is already active.";
    }
}

/* Vid återlämning av föremål
 * Sätter loan.active till 0 för att visa att det är ett inaktivt lån.
 * Detta för att arkivera alla lån.
 */
if (isset($_GET['returnItem'])) {
    $id = filter_input(INPUT_GET, 'returnItem', FILTER_SANITIZE_NUMBER_INT);

    // query för att uppdatera active fältet
    $query = "UPDATE loan SET active='0' WHERE id=(:id)";
    $sth = $db->prepare($query);

    if ($sth->execute(array(':id' => $id))) {
        echo "<h4>Item type with id: " . $id . " returned.";
    } else {
        echo "<h4>Error</h4>";
        echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
    }
}

/* Lista över de lån som finns i databasen, både aktiva och
 * inaktiva skrivs ut med möjlighet att lämna tillbaka föremålet.
 */
$query = "SELECT loan.id AS lid, active, item, loandate, returndate,
                 description, name AS itemtype, loan.user AS loaner
          FROM loan
          LEFT JOIN items ON items.id = loan.item
          LEFT JOIN itemtypes ON itemtypes.id = items.type
          LEFT JOIN user AS loaner ON loaner.id = loan.user
          ORDER BY loandate DESC";

if ($result = $db->query($query)) {
    echo "<h2>Registered loans</h2>";

    // En tabell för överskådlig utskrift av exempeldata
    echo '<table border="1"><tr><th>item id</th><th>item type</th><th>description</th>' .
        '<th>user</th><th>loan date</th><th>return date</th><th>return item</th></tr>';

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['item'] . "</td>";
        echo "<td>" . $row['itemtype'] . "</td>";
        echo "<td>" . $row['description'] . "</td>";
        echo "<td>" . $row['loaner'] . "</td>";
        echo "<td>" . $row['loandate'] . "</td>";
        echo "<td>" . $row['returndate'] . "</td>";

        // Fält för att visa länk för återlämning av föremål
        echo "<td>";
        if ($row['active'] == '1') {
            echo '<a href="dblabb4.php?returnItem=' . $row['lid'] . '">Return item</a>';
        } else {
            echo "No active loan";
        }
        echo "</td></tr>";
    }

    echo "</table>";
}
