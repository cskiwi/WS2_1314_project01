<?php

namespace GlennLatomme\Repository;

class Messaging extends \Knp\Repository {

    public function getTableName() {
        return 'messages';
    }

    public function findInbox($userId, $amount = PHP_INT_MAX){
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('m.*','u.username', 'u.id as userId')
            ->from($this->getTableName(), 'm')
            ->innerJoin('m', 'users', 'u', 'm.to_user = u.id')
            ->where('m.to_user = ?')
            ->orderBy('m.date_send', 'DESC')
            ->setMaxResults($amount);

        return $this->db->fetchAll($queryBuilder->getSql(),array($userId));
    }

    public function countUnread($userId){
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('count(*)')
            ->from($this->getTableName(), 'm')
            ->where('m.to_user = ?')
            ->andWhere('message_read = 0');

        return $this->db->fetchAssoc($queryBuilder->getSql(),array($userId));
    }
}