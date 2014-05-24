<?php

namespace GlennLatomme\Repository;
class Reservations extends \Knp\Repository {

    public function getTableName() {
        return 'reservations';
    }
    public function findForUser($id, $userId) {

        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('r.*')
            ->from($this->getTableName(), 'r')
            ->innerJoin('r', 'users', 'u', 'r.user_id = u.id')
            ->innerJoin('r', 'tools', 't', 'r.tool_id = t.id')
            ->where('r.id = ?')
            ->Andwhere('t.user_id = ?');

        return $this->db->fetchAssoc($queryBuilder->getSql(), array($id, $userId));
    }

    public function findAll() {
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('r.*', 'u.name')
            ->from($this->getTableName(), 'r')
            ->innerJoin('r', 'users', 'u', 'r.user_id = u.id')
            ->where('u.id = ?');

        return $this->db->fetchAll($queryBuilder->getSql());
    }

    public function findAllForUser($userId,  $status = 'waiting',$amount = PHP_INT_MAX) {
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('r.id','t.id as toolId','t.title', 'r.start_date', 'r.end_date', 'u.username')
            ->from($this->getTableName(), 'r')
            ->innerJoin('r', 'users', 'u', 'r.user_id = u.id')
            ->innerJoin('r', 'tools', 't', 'r.tool_id = t.id')
            ->where('t.user_id = ?')
            ->andWhere('r.status = ?')
            ->orderBy('r.start_date', 'DESC')
            ->setMaxResults($amount);

        return $this->db->fetchAll($queryBuilder->getSql(),array($userId, $status));
    }

    public function lastID(){
        return $this->db->lastInsertId();
    }

    // run daily
    public function processReservations(){
        // set status to rented
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select ('*')
            ->from($this->getTableName(), 'r')
            ->where('r.status =' . $queryBuilder->expr()->literal('accepted' ));

        $queryBuilder->andWhere($queryBuilder->expr()->lte('r.start_date', 'CURRENT_DATE'));
        $areRented = $this->db->fetchAll($queryBuilder->getSql());

        /*echo 'to rented';
        var_dump($queryBuilder->getSql());
        echo'values';
        var_dump ($areRented);//*/

        foreach($areRented as $rented){
            $queryBuilder = $this->db->createQueryBuilder();
            $q = $queryBuilder->update('tools', 't')
                ->set('t.status', $queryBuilder->expr()->literal('Rented'))
                ->set('t.date', 'CURRENT_DATE')
                ->where('t.id = ' . $queryBuilder->expr()->literal($rented['tool_id']))
                ->getSql();
            $this->db->executeQuery($q );
        }

        // set status to available
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('*')
            ->from($this->getTableName(), 'r')
            ->where('r.status =' . $queryBuilder->expr()->literal('accepted' ));

        $queryBuilder->andWhere($queryBuilder->expr()->gt('CURRENT_DATE', 'r.end_date'));
        $areAvailible = $this->db->fetchAll($queryBuilder->getSql());

        foreach($areAvailible as $available){
            $queryBuilder = $this->db->createQueryBuilder();
            $q2 = $queryBuilder->update('tools', 't')
                ->set('t.status', $queryBuilder->expr()->literal('Available'))
                ->set('t.date', 'CURRENT_DATE')
                ->where('t.id = ' . $queryBuilder->expr()->literal($available['tool_id']))
                ->getSql();
            $this->db->executeQuery($q2 );
        }


        // delete reservations that passed
        $queryBuilder = $this->db->createQueryBuilder()
            ->delete ($this->getTableName());

        $queryBuilder->andWhere($queryBuilder->expr()->lt('end_date', 'CURRENT_DATE'));

        $this->db->executeQuery($queryBuilder->getSQL() );

        /*
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('*')
            ->from('tools', 't')
            ->where($queryBuilder->expr()->eq('t.status', $queryBuilder->expr()->literal('rented')));

        $results = $this->db->fetchAll($queryBuilder->getSql());*/
    }


}