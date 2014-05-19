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
        return $this->db->fetchAssoc('SELECT '. $this->getTableName(). '.* from '. $this->getTableName(). ' WHERE '. $this->getTableName(). '.id = ? AND user_id = ?', array($id, $userId));
    }

    public function findAll() {
        return $this->db->fetchAll('SELECT '. $this->getTableName(). '.*, users.name from '. $this->getTableName(). ' INNER JOIN users ON '. $this->getTableName(). '.user_id = users.id');
    }

    public function findAllForUser($userId, $amount = PHP_INT_MAX) {
        return $this->db->fetchAll('
        SELECT '. $this->getTableName(). '.*, users.name
        from '. $this->getTableName(). '
        INNER JOIN users ON '. $this->getTableName(). '.user_id = users.id
        WHERE user_id = ?
        ORDER BY date DESC
        LIMIT ' . $amount,
            array($userId)
        );
    }

    public function lastID(){
        return $this->db->lastInsertId();
    }
    public function search($tags){
        $query =
            'SELECT DISTINCT tools.id, tools.content, tools.title, tools.price, users.username FROM tools ' .
            'INNER JOIN key_for_tools on key_for_tools.tools_id = tools.id ' .
            'INNER JOIN keywords on key_for_tools.key_id = keywords.id ' .
            'INNER JOIN users on users.id = tools.user_id ' .
            'WHERE';

        $size = sizeof($tags);
        for($i=0; $i < $size; $i++){
            $query .= ' `key` LIKE \'%'.$tags[$i] .'%\'';
            if ($i < $size-1) {
                $query .= ' OR';
            }
        }
        $query .= ' ;';

        $resultSet =  $this->db->fetchAll($query);
        /*var_dump($query);
        var_dump($resultSet);
        die();//*/
        /*$orderd = [[]];

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
        }*/
        return $resultSet;
    }

    public function countTools(){
        return $this->db->fetchAssoc('SELECT count(*) FROM '. $this->getTableName());

    }
}