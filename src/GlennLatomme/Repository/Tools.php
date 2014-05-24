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
    public function find($id) {
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('t.*', 'u.username')
            ->from($this->getTableName(), 't')
            ->innerJoin('t', 'users', 'u', 't.user_id = u.id')
            ->where('t.id = ?');

        return $this->db->fetchAssoc($queryBuilder->getSql(), array($id));
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
            ->select ('t.id','t.title', 't.date', 't.status', 't.price', 't.user_id')
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
            ->innerJoin('t', 'users', 'u', 't.user_id = u.id')
            ->groupBy('t.id');


        // filter
        if($params){
            if (array_key_exists('userId', $params)){
                $queryBuilder->andWhere($queryBuilder->expr()->neq('t.user_id', $queryBuilder->expr()->literal($params['userId'])));
            }
            if (array_key_exists('exclude', $params)){
                $queryBuilder->andWhere($queryBuilder->expr()->neq('t.id', $queryBuilder->expr()->literal($params['exclude'])));
            }
            if (array_key_exists('zip', $params)){
                $queryBuilder->andWhere($queryBuilder->expr()->eq('u.zipPostal', $queryBuilder->expr()->literal($params['zip'])));
            }
            if (array_key_exists('free', $params)){
                $queryBuilder->andWhere('t.price is null');
            }
        }
        // keys
        for($i=0; $i < sizeof($tags); $i++){
            $queryBuilder->andWhere($queryBuilder->expr()->like('k.key', $queryBuilder->expr()->literal('%' . $tags[$i] . '%')));
        }

        $resultSet = $this->db->fetchAll($queryBuilder->getSql( ) );

        if($params){
            if (array_key_exists('end_date', $params) || array_key_exists('start_date', $params)){
                $reservations = $this->reservationsInPeriod(array_key_exists('start_date', $params)?$params['start_date']:null, array_key_exists('end_date', $params)?$params['end_date']:null);
                $unset = [];
                foreach($reservations as $res){
                    for ($index = 0; $index < (sizeof($resultSet)-1); $index++){
                        if ($resultSet[$index]['id'] == $res['tool_id']){
                            // echo 'Resultset: ' . $resultSet[$index]['id'] . '<br />';
                            // echo 'Reservation: ' . $res['tool_id'] . '<br />';
                            array_push($unset, $index);
                        }
                    }
                }
                foreach($unset as $u){
                    unset($resultSet[$u]);
                }
                $resultSet = array_values($resultSet);
            }
        }
        return $resultSet;
    }

    public function countTools(){
        $queryBuilder = $this->db->createQueryBuilder()
            ->select ('count(*)')
            ->from($this->getTableName(), 't');

        return $this->db->fetchAssoc($queryBuilder->getSql());

    }

    public function reservationsInPeriod($start, $end, $forTool = -1){

        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select('*')
            ->from('tools','t')
            ->innerJoin('t','reservations','r', 'r.tool_id = t.id')
            ->where($queryBuilder->expr()->eq('r.status', $queryBuilder->expr()->literal('accepted')));

        if ($forTool != -1){
            $queryBuilder->where('t.id = '.$queryBuilder->expr()->literal($forTool));
        }
        if ($start){
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->gte('r.end_date', $queryBuilder->expr()->literal($start)),
                        $queryBuilder->expr()->lt('r.start_date', $queryBuilder->expr()->literal($start))
                    ),
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->lte('r.start_date', $queryBuilder->expr()->literal($end)),
                        $queryBuilder->expr()->gt('r.end_date', $queryBuilder->expr()->literal($end)))
                )
            );
        }

        return $this->db->fetchAll($queryBuilder->getSql( ) );
    }
}