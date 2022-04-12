<?php

class UserDAO {
    private $dbManager;

    function UserDAO($DBMngr) {
        $this->dbManager = $DBMngr;
    }

    public function getUsers() {

        $query = 'SELECT * FROM users';
        $stmt = $this->dbManager->prepareQuery ($query);
        $this->dbManager->executeQuery($stmt);
        $rows = $this->dbManager->fetchResults($stmt);

        return ($rows);
    }
}
?>
