<?php
/**
 * User: Glenn Latomme
 * Date: 5/13/14
 */
namespace GlennLatomme\Repository;

use Knp\Repository;

class Tools extends Repository {
    public function getTableName() {
        return 'tools';
    }

    public function findForUser($id, $userId) {

        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('*')
            ->from($this->getTableName(), 't')
            ->where('t.id = ?')
            ->Andwhere('t.user_id = ?');

        return $this->db->fetchAssoc($queryBuilder->getSql(), array($id, $userId));
    }

    public function findAll() {
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('t.*', 'u.name')
            ->from($this->getTableName(), 't')
            ->innerJoin('t', 'users', 'u', 't.user_id = u.id');

        return $this->db->fetchAll($queryBuilder->getSql());
    }

    public function findAllForUser($userId, $amount = PHP_INT_MAX) {
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('t.id','t.title', 't.date', 't.status')
            ->from($this->getTableName(), 't')
            ->innerJoin('t', 'users', 'u', 't.user_id = u.id')
            ->where('u.id = ?')
            ->orderBy('t.date', 'DESC')
            ->setMaxResults($amount);

        return $this->db->fetchAll($queryBuilder->getSql(),array($userId));
    }

    public function lastID(){
        return $this->db->lastInsertId();
    }
    public function search($tags, $params = null){
        $queryBuilder = $this->db->createQueryBuilder()
            ->select('t.id', 't.status', 't.title', 't.price', 't.user_id', 'u.username', 'u.zipPostal')
            ->from('tools', 't')
            ->innerJoin('t', 'key_for_tools', 'kft', 'kft.tools_id = t.id')
            ->innerJoin('t', 'keywords', 'k', 'kft.key_id = k.id')
            ->innerJoin('t', 'users', 'u', 't.user_id = u.id');


        // filter
        if (array_key_exists('userId', $params)){
            $queryBuilder->andWhere($queryBuilder->expr()->neq('t.user_id', $queryBuilder->expr()->literal($params['userId'])));
        }
        if (array_key_exists('zip', $params)){
            $queryBuilder->andWhere($queryBuilder->expr()->eq('u.zipPostal', $queryBuilder->expr()->literal($params['zip'])));
        }

        // keys
        for($i=0; $i < sizeof($tags); $i++){
            $queryBuilder->andWhere($queryBuilder->expr()->like('k.key', $queryBuilder->expr()->literal('%' . $tags[$i] . '%')));
        }


        $resultSet = $this->db->fetchAll($queryBuilder->getSql( ) );
        return $resultSet;
    }

    public function countTools(){
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('count(*)')
            ->from($this->getTableName(), 't');

        return $this->db->fetchAssoc($queryBuilder->getSql());

    }
}