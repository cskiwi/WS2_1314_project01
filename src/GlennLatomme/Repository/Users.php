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
    public function findUserByEmail($email) {
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('*')
            ->from($this->getTableName(), 'u')
            ->where('u.email = ?');

        return $this->db->fetchAssoc($queryBuilder->getSql(), array($email));
    }
    public function findUserByUsername($username) {
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('*')
            ->from($this->getTableName(), 'u')
            ->where('u.username = ?');

        return $this->db->fetchAssoc($queryBuilder->getSql(), array($username));
    }

    public function lastID(){
        return $this->db->lastInsertId();
    }
}