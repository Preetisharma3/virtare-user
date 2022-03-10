<?php

namespace App\Transformers\Widget;

use League\Fractal\TransformerAbstract;

class WidgetTransformer extends TransformerAbstract
{

	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data)
	{
		return [
            'id' =>$data->id,
            'udid'=>$data->udid,
            'widgetName'=>$data->widgetName,
            'title'=>$data->title,
            'dataEndPoint'=>$data->dataEndPoint,
            'rows'=>$data->rows,
            'columns'=>$data->columns,
            'widgetType'=>[
                'id' =>@$data->widgetType->id,
                'udid'=>@$data->widgetType->udid,
                'widgetType'=>@$data->widgetType->name,
            ],
		];
	}
}
