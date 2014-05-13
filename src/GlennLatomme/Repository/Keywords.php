<?php

namespace GlennLatomme\Repository;
class Keywords extends \Knp\Repository {

    public function getTableName() {
        return 'keywords';
    }
    public function insertKey($key, $tool){
// check if keyword exists if not insert
        $dbKey = $this->findByKey('key', $key);
        if ($dbKey == null){
            $this->insert(array(
                '`key`' => $key
            ));

            $dbKey = $this->findByKey('key', $key);
        }

        // link keyword to tool
        if ($this->db->fetchAll('SELECT * FROM key_for_tools WHERE tools_id = ? AND key_id = ?', array($tool,$dbKey[0]['id'])) == null){
            $this->db->insert('key_for_tools', array('tools_id' => $tool, 'key_id' => $dbKey[0]['id']));
        }
    }

    public function findByKey($key, $item){
        return $this->db->fetchAll('SELECT * FROM '. $this->getTableName() .' WHERE `'.$key.'` = ?', array($item));
    }
    public function findKeywords($toolID){
        return $this->db->fetchAll('SELECT * FROM keywords inner join key_for_tools on keywords.id = key_for_tools.key_id where tools_id = ?', array($toolID));
    }
}