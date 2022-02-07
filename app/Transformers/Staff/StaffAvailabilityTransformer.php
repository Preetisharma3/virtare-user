<?php

namespace App\Transformers\Staff;


use App\Transformers\Appointment\AppointmentDataTransformer;
use League\Fractal\TransformerAbstract;
use App\Transformers\Role\RoleTransformer;
use App\Transformers\Staff\StaffNoteTransformer;
use App\Transformers\Staff\BookappointmentValueTransformer;



class StaffAvailabilityTransformer extends TransformerAbstract
{



    protected $showData;

    public function __construct($showData = true)
    {
        $this->showData = $showData;
    }

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
                'staffId'=>$data->staffId,
                'startTime'=>$data->startTime,
                'endTime'=>$data->endTime
            ];

    }
}
