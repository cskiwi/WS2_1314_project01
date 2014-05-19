<?php

namespace GlennLatomme\Repository;

class Messaging extends \Knp\Repository {

    public function getTableName() {
        return 'messages';
    }

    public function findInbox($userId){
        return $this->db->fetchAll('SELECT messages.*, users.username, users.id as userId FROM '. $this->getTableName() . ' INNER JOIN users ON messages.to_user = users.id  WHERE to_user = ? ORDER BY date_send DESC', array($userId));
    }

    public function countUnread($userId){
        return $this->db->fetchAssoc('SELECT count(*) FROM '. $this->getTableName() . ' WHERE message_read = 0 AND to_user = ?', array($userId));
    }
}