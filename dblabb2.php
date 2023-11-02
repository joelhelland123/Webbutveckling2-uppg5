<?php
class DB extends PDO
{
    public function __construct($dbname = "dblabb")
    {
        try {
            parent::__construct("mysql:host=localhost;dbname=$dbname;charset=utf8", "root", "mysql");
        } catch (Exception $e) {
            echo "<pre>" . print_r($e, 1) . "</pre>";
        }
    }

    public function fetchUserData($id)
    {
        $query = "SELECT username, firstname, lastname, birthdate, address 
                  FROM user LEFT JOIN userdata ON userdata.userid = user.id WHERE user.id = :id";

        if ($sth = $this->prepare($query)) {
            $sth->execute(array(':id' => $id));
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            echo $result['username'] . "<br>";
            echo $result['firstname'] . " " . $result['lastname'] . "<br>";
            echo $result['birthdate'];
        }
    }

    public function fetchItems()
    {
        $query = "SELECT * FROM items";
        $result = $this->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<pre>" . print_r($row, 1) . "</pre>";
        }
    }
}

$test = new DB();
$test->fetchItems();
$test->fetchUserData(1);
