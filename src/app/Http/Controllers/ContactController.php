<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index(){
        return view('index');
    }

    public function confirm(Request $request)
    {
        $contact = $request->only(['first_name', 'last_name', 'gender', 'email', 'tel1', 'tel2', 'tel3', 'address', 'building', 'category', 'content']);
        
        $contact['name'] = $contact['first_name'] . ' ' . $contact['last_name'];
        $contact['tel'] = $contact['tel1'] . $contact['tel2'] . $contact['tel3'];

        return view('confirm', compact('contact'));
    }

    public function store(Request $request)
    {
        $contact = $request->only(['name', 'gender', 'email', 'tel', 'address', 'building', 'category', 'content']);
        Contact::create($contact);

        return view('thanks');
    }
}
