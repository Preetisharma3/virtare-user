<?php
namespace App\Library;
require_once('crest/src/crest.php');

class BitrixApi extends CRest{
    function getDealById($id){
        if($id){
            $getDeal = CRest::call(
               'crm.deal.get',
               [
                'id' => $id,
               ]
           );
            return $getDeal;
        }else{
            return false;
        }
    }

    function getDealByName($title){
        if($title){
            $getDeal = CRest::call(
                'crm.deal.list',
                   [
                    'filter' => array("%TITLE" => $title)
                   ]
           );
            return $getDeal;
        }else{
            return false;
        }
    }

    function getAllDeal(){
            $getDeal = CRest::call(
                'crm.deal.list',
                   []
               );

            return $getDeal;
    }

    function searchDeals($search_obj){
        if($search_obj){
            $getDeal = CRest::call(
                'crm.deal.list',
                   [
                    'filter' => $search_obj,
                   ]
           );
            return $getDeal;
        }else{
            return false;
        }
    }
}