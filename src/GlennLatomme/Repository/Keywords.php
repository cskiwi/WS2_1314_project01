<?php

namespace GlennLatomme\Repository;
class Keywords extends \Knp\Repository {

    public function getTableName() {
        return 'keywords';
    }

    public function deleteKey($key, $toolid){
        $key = $this->findKey($key);
        $this->db->delete('key_for_tools', ['tools_id' => $toolid, 'key_id' => $key['id']]);
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
        if ($this->db->fetchAll('SELECT * FROM key_for_tools WHERE tools_id = ? AND key_id = ?', array($tool,$dbKey['id'])) == null){
            $this->db->insert('key_for_tools', array('tools_id' => $tool, 'key_id' => $dbKey['id']));
        }
    }

    public function deleteUnusedKeys(){
        $queryBuilder = $this->db->createQueryBuilder();

        $queryBuilder
            ->select( 'k.*')
            ->from('keywords', 'k')
            ->leftJoin('k', 'key_for_tools','kft','k.id = kft.key_id')
            ->where('kft.key_id is NULL')
        ;
        //var_dump($queryBuilder->getSQL());//->execute();

        $results = $this->db->fetchAll($queryBuilder->getSQL());

        foreach ($results as $result) {
            //var_dump($result);


            $this->db->delete('keywords', ['id' => $this->db->quote($result['id'])]);
        }
/*
 DELETE k FROM keywords AS k LEFT OUTER JOIN key_for_tools AS kft ON k.id= kft.key_id WHERE kft.key_id IS NULL;
SELECT k.* FROM keywords AS k LEFT OUTER JOIN key_for_tools AS kft ON k.id= kft.key_id WHERE kft.key_id IS NULL;

 */
    }

    public function findKey($item){
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('k.*')
            ->from($this->getTableName(), 'k')
            ->where('k.key = ?');
        return $this->db->fetchAssoc($queryBuilder->getSql(), array($item));
    }

    public function findKeywords($toolID){
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('k.key')
            ->from($this->getTableName(), 'k')
            ->innerJoin('k', 'key_for_tools', 'kft', 'k.id = kft.key_id')
            ->where('kft.tools_id = ?');

        return $this->db->fetchAll($queryBuilder->getSql(), array($toolID));
    }


}