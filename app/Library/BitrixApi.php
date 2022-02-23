<?php
namespace App\Library;
require_once('crest/src/crest.php');

class BitrixApi extends CRest{
    function getDeal($id){
        if($id){
            $getDeal = CRest::call(
               'crm.deal.get',
               [
                'id' => $id,
               ]
           );
            return json_encode($getDeal);
        }else{
            return false;
        }
    }
}