<?php
try {
    $pdo->prepare("SELECT 1");
} catch (PDOException $e) {
    echo "Caught PDO";
} catch (Exception $e) {
    echo "Caught Exception";
} catch (Error $e) {
    echo "Caught Error";
}
echo "Done";
