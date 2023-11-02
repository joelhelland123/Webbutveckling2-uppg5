<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>dblabbd</title>
</head>

<body>
    <?php
    // inkludera dblabb2 och skapa en ny instans av DB
    include('dblabb2.php');
    $db = new DB();
    ?>

    <h4>List categories</h4>
    <!-- Placera koden här -->
    <?php
    // skriv ut en lista över existerande kategorier med delete länk
    $query = "SELECT * FROM itemtypes ORDER BY name ASC";
    if ($result = $db->query($query)) {
        echo "<ul>";

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo '<li>id: ' . $row['id'] . ' type: ' . $row['name'] .
                ' <a href="dblabb3.php?deltype=' . $row['id'] .
                '">Delete type</a></li>';
        }

        echo "</ul>";
    }
    ?>
    <!-- Slut på kodplacering -->

    <h4>Add itemtype</h4>
    <!--Formulär för kategorier -->
    <form action="dblabb3.php" method="post">
        <input type="text" name="name" placeholder="name">
        <input type="submit" name="addType" value="Submit">
    </form>

    <h4>Add item</h4>
    <!--Formulär för föremål -->
    <form action="dblabb3.php" method="post">
        <select name="type">
            <?php
            /* Här hämtas all data från itemtypes tabellen för
             * att enkelt skapa en lista över existerande itemtype
             * kategorier till formuläret.
             * Detta gör även att vi slipper uppdatera vår html när vi
             * lägger till nya kategorier.
             */
            $query = "SELECT * FROM itemtypes ORDER BY name ASC";
            if ($result = $db->query($query)) {
                while ($row = $result->fetch(PDO::FETCH_NUM)) {
                    // skriv ut varje rad som en <option>
                    echo '<option value="' . $row['0'] . '">' . $row['1'] . '</option>';
                }
            }
            ?>
        </select>
        <input type="text" name="desc" placeholder="description">
        <input type="submit" name="addItem" value="Submit">
    </form>
</body>

</html>