<?php

namespace App\Transformers\Template;

use League\Fractal\TransformerAbstract;
 

class TemplateTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($data)
    {
        return[ 
            'id' => $data->id,
            'udid' => $data->udid,
            'name' => $data->name,
            'dataType' => $data->dataType,
            'templateType' =>$data->templateType,
            'isActive'  => $data->isActive,
        ];
      
    }
}
