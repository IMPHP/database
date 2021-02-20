<?php

/* ---
 * Include the ClassLoader
 */
require "../../base/src/ImClassLoader.php";

$loader = im\ImClassLoader::load();
$loader->addBasePath( realpath("../src") );

/*
 * ----------------------------------------
 */

use im\database\mysqli\Connection;
use im\exc\DBException;

echo "Connecting to database\n";
$conn = new Connection("127.0.0.1", "Test_DB", $user, $passwd);

echo "Creating Table\n";
try {
    $conn->execute("CREATE TABLE MyTBL (cId int PRIMARY KEY, cName varchar(255) NOT NULL)");

} catch (DBException $e) {
    $conn->execute("DROP TABLE MyTBL");
    $conn->execute("CREATE TABLE MyTBL (cId int PRIMARY KEY, cName varchar(255) NOT NULL)");
}

echo "Preparing statement\n";
$stmt = $conn->stmt("INSERT INTO MyTBL (cId, cName) VALUES (%i, %s)");
foreach ([2 => "Christian", 1 => "Hans", 3 => "Morten"] as $id => $name) {
    echo "Inserting user $name ($id)\n";

    if ($id == 1) {
        $stmt->enquire($id, $name);

    } else {
        $stmt->execute($id, $name);
    }
}
$stmt->close();

echo "Retrieving all users via stmt\n";
$id = 1;
$stmt = $conn->stmt("SELECT * FROM MyTBL WHERE cId >= %i");
$result = $stmt->enquire($id);
while (($row = $result->fetchAssoc()) != null) {
    echo "{$row->get('cId')} {$row->get('cName')}\n";
}
$result->destroy();
$stmt->close();

echo "Retrieving all users via normal query\n";
$result = $conn->enquire("SELECT * FROM MyTBL WHERE cId >= %i ORDER BY cId ASC", 1);
$result->destroy(true);
while (($row = $result->fetchAssoc()) != null) {
    echo "{$row->get('cId')} {$row->get('cName')}\n";
}

echo "Removing Table\n";
$conn->execute("DROP TABLE MyTBL");
$conn->close();
