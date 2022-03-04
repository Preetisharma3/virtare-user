<?php

namespace App\Transformers\Widget;

use League\Fractal\TransformerAbstract;

class WidgetAccessTransformer extends TransformerAbstract
{

	protected $defaultIncludes = [];

	protected $availableIncludes = [];

	public function transform($data)
	{
		return [
               'id' => $data->widget->id,
               'udid'=> $data->widget->udid,
               'name'=> $data->widget->widgetName,
               'title'=> $data->widget->title,              
		];
	}
}
