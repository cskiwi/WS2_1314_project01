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
    public function find($id) {
        return $this->db->fetchAssoc('SELECT '. $this->getTableName(). '.* from '. $this->getTableName(). ' WHERE '. $this->getTableName(). '.id = ?', array($id));
    }

    public function findForUser($id, $userId) {
        return $this->db->fetchAssoc('SELECT '. $this->getTableName(). '.* from '. $this->getTableName(). ' WHERE '. $this->getTableName(). '.id = ? AND author_id = ?', array($id, $userId));
    }

    public function findAll() {
        return $this->db->fetchAll('SELECT '. $this->getTableName(). '.*, users.name from '. $this->getTableName(). ' INNER JOIN users ON '. $this->getTableName(). '.user_id = users.id');
    }

    public function findAllForUser($userId) {
        return $this->db->fetchAll('SELECT '. $this->getTableName(). '.*, users.name from '. $this->getTableName(). ' INNER JOIN users ON '. $this->getTableName(). '.user_id = users.id WHERE user_id = ? ORDER BY id DESC', array($userId));
    }

    public function lastID(){
        return $this->db->lastInsertId();
    }
    public function search($tags){
        $query = 'SELECT DISTINCT tools.*' .
            'FROM keywords ' .
            'INNER JOIN key_for_tools on keywords.id = key_for_tools.key_id ' .
            'INNER JOIN tools on key_for_tools.tools_id = tools.id ' .
            'WHERE';

        $size = sizeof($tags);
        for($i=0; $i < $size; $i++){
            $query .= ' `key` LIKE \'%'.$tags[$i] .'%\'';
            if ($i < $size-1) {
                $query .= ' OR';
            } else {
                $query .= ';';
            }
        }
        $resultSet =  $this->db->fetchAll($query);
        $orderd = [[]];

        foreach($resultSet as $result){
            $id = $this->multidimensional_search($orderd, ['id' => $result['user_id']]);

            if ($id){
                array_push($orderd[$id]['tools'], $result);
            }
            else {
                $user = $this->db->fetchAssoc('SELECT * FROM users WHERE id = ?', array($result['user_id']));
                $id = array_push($orderd, $user);
                $orderd[$id-1]['tools']=[$result];
            }
        }
        return $orderd;
    }

    function multidimensional_search($parents, $searched) {
        if (empty($searched) || empty($parents)) {
            return false;
        }

        foreach ($parents as $key => $value) {
            $exists = true;
            foreach ($searched as $skey => $svalue) {
                $exists = ($exists && IsSet($parents[$key][$skey]) && $parents[$key][$skey] == $svalue);
            }
            if($exists){ return $key; }
        }

        return false;
    }
}