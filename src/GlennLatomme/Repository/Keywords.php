<?php

namespace GlennLatomme\Repository;
class Keywords extends \Knp\Repository {

    public function getTableName() {
        return 'keywords';
    }
    public function insertKey($key, $tool){

        // check if exists
        $dbKey = $this->findKey($key);
        if ($dbKey == null){
            $this->insert(array(
                '`key`' => $key
            ));

            // fetch the new key
            $dbKey = $this->findKey($key);

        }

        // link keyword to tool
        if ($this->db->fetchAll('SELECT * FROM key_for_tools WHERE tools_id = ? AND key_id = ?', array($tool,$dbKey[0]['id'])) == null){
            $this->db->insert('key_for_tools', array('tools_id' => $tool, 'key_id' => $dbKey[0]['id']));
        }
    }

    public function findKey($item){
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('k.*')
            ->from($this->getTableName(), 'k')
            ->where('k.key = ?');
        return $this->db->fetchAll($queryBuilder->getSql(), array($item));
    }

    public function findKeywords($toolID){
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('k.*')
            ->from($this->getTableName(), 'k')
            ->innerJoin('k', 'key_for_tools', 'kft', 'k.id = kft.key_id')
            ->where('kft.tools_id = ?');

        return $this->db->fetchAll($queryBuilder->getSql(), array($toolID));
    }


}