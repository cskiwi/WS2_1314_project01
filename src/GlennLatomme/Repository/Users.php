<?php
/**
 * User: Glenn Latomme
 * Date: 5/13/14
 */
namespace GlennLatomme\Repository;

use Knp\Repository;

class Users extends Repository {
    public function getTableName() {
        return 'users';
    }
    public function findUserByUsername($email) {
        return $this->db->fetchAssoc('SELECT * FROM '. $this->getTableName() . ' WHERE username = ?', array($email));
    }

    public function lastID(){
        return $this->db->lastInsertId();
    }
}