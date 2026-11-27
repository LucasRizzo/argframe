<?php

class UserDAO {
    private $dbManager;

    public function __construct($DBMngr) {
        $this->dbManager = $DBMngr;
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->dbManager->prepareQuery('SELECT email, password FROM users WHERE email = ?');
        $this->dbManager->bindValue($stmt, 1, $email, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery($stmt);
        $user = $this->dbManager->getNextRow($stmt);
        return $user ?: null;
    }

    public function replacePasswordHash(string $email, string $oldHash, string $newHash): bool {
        $stmt = $this->dbManager->prepareQuery('UPDATE users SET password = ? WHERE email = ? AND BINARY password = ?');
        $this->dbManager->bindValue($stmt, 1, $newHash, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 2, $email, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 3, $oldHash, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery($stmt);
        return $this->dbManager->getNumberOfAffectedRows($stmt) === 1;
    }
}
?>
