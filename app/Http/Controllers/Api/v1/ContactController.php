<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\ContactService;
use App\Http\Requests\Contact\ContactRequest;
use App\Http\Requests\Contact\ContactTextRequest;
use App\Http\Requests\Contact\ContactEmailRequest;

class ContactController extends Controller
{
    public function index(ContactRequest $request)
    {
        return (new ContactService)->requestCall($request);
    }
    public function contactMessage(ContactTextRequest $request)
    {
        return (new ContactService)->contactMessage($request);
    }
    public function contactEmail(ContactEmailRequest $request)
    {
        return (new ContactService)->contactEmail($request);
    }
}
