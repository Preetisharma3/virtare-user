<?php

namespace App\Services\Api;

use Exception;
use App\Models\Faq\Faq;
use App\Transformers\Faq\FaqTransformer;
use Illuminate\Support\Facades\App;

class FaqService
{
    public function list($request)
    {
        try {
            $faq = Faq::where('language',app('translator')->getLocale())->get();
            return fractal()->collection($faq)->transformWith(new FaqTransformer)->toArray();
        } catch (Exception $e) {
            return response()->
            json(['message' => $e->getMessage()],  500);
        }
    }
}
