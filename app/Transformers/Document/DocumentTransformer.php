<?php

namespace App\Transformers\Document;

use League\Fractal\TransformerAbstract;
use App\Transformers\Tag\TagTransformer;
use App\Transformers\GlobalCode\GlobalCodeTransformer;


class DocumentTransformer extends TransformerAbstract
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
    public function transform($data): array
    {
        return [
            'id'=>$data->id,
			'name'=>$data->name,
            'type'=>$data->documentType->name,
            'patient'=>$data->referanceId,
            'document'=>$data->filePath,
            'entity'=>$data->entityType,
            'tags'=>fractal()->collection($data->tag)->transformWith(new TagTransformer())->toArray()
		];
    }
}
