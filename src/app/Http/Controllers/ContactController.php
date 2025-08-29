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

        if ($request->input('action')==='back') {
            return redirect()->route('contacts.index')->withInput();
        }

        $data = array_merge([
            'building' => '',
        ], $data);

        $data['name'] = $data['first_name'] . '　' . $data['last_name'];
        $data['tel'] = $data['tel1'] . $data['tel2'] . $data['tel3'];

        return view('confirm', ['contact' => $data]);
    }

    public function store(ContactRequest $request)
    {
        $name = $request->input('name');
        if (!$name) {
            $name = trim(($request->input('first_name', '')) . ' ' . ($request->input('last_name', '')));
        }

        $tel = $request->input('tel');
        if (!$tel) {
            $tel = ($request->input('tel1', '')).($request->input('tel2', '')).($request->input('tel3', ''));
        }
        $data = [
            'name' => $name,
            'gender' => $request->input('gender'),
            'email' => $request->input('email'),
            'tel' => $tel,
            'address' => $request->input('address'),
            'building' => $request->input('building', ''),
            'category' => $request->input('category'),
            'content' => $request->input('content'),
        ];
        Contact::create($data);

        return view('thanks');
    }
}
