<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function confirm(ContactRequest $request)
    {
        $data = $request->validated();

        
        $data['name'] = $data['first_name'] . ' ' . $data['last_name'];
        $data['tel'] = $data['tel1'] . $data['tel2'] . $data['tel3'];

        return view('confirm', ['contact' => $data]);
    }

    public function store(ContactRequest $request)
    {
        $data = $request->only([
            'name', 'gender', 'email', 'tel', 'address', 'building', 'category', 'content'
        ]);
        Contact::create($data);

        return view('thanks');
    }
}
