<?php

namespace GlennLatomme\Repository;

class Messaging extends \Knp\Repository {

    public function getTableName() {
        return 'messages';
    }

    public function findInbox($userId, $amount = PHP_INT_MAX){
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('m.*','tu.username as to_username', 'fu.username as from_username', 'fu.id as userId')
            ->from($this->getTableName(), 'm')
            ->innerJoin('m', 'users', 'fu', 'm.from_user = fu.id')
            ->innerJoin('m', 'users', 'tu', 'm.to_user = tu.id')
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