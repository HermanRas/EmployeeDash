<?php
function sqlQuery($sql, $args)
{
    require("config/db.php");
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($args);
    } catch (PDOException $e) {
        //echo $e->getMessage();
        die('<div class="container">
                <br /><br /><br />
                <div class="alert alert-danger" role="alert">
                    Job Failed to be Created!
                </div>
                <div class="card">
                    <div class="card-header">
                        Transaction Error Details
                    </div>
                    <div class="card-body">
                    <b>SQL Statement: </b>' . $sql . '<br />' . $e . '</div>
                </div>
            </div>
            <br />');
    }

    $results = NULL;
    $count = 0;

    try {
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
    } catch (\Throwable $th) {
        // echo $th;
    }

    return [$results, $count];
}
