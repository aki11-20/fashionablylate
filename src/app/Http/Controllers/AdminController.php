<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();

        if($request->filled('keyword')){
            $query->where('name', 'like', "%{$request->keyword}%")
            ->orWhere('email', 'like', "%{$request->keyword}%");
        }
        if($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        if($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $contacts = $query->paginate(7);

        return view('admin.index', compact('contacts'));
    }
    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        if (request()->ajax()){
            return response()->json($content);
        }

        return view('admin.show', compact('contact'));
    }
    public function export()
    {
        $contacts = Contact::all();
        $csvFileName = 'contact.csv';
        $headers = [
            'Contact-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$csvFileName",
        ];
        $callback = function() use ($contacts) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['名前', '性別', 'メールアドレス', 'お問い合わせ種類', '登録日']);

            foreach ($contacts as $contact) {
                fputcsv($handle, [
                    $contact->name,
                    $contact->gender == 1 ? '男性' : ($contact->gender == 2 ? '女性' : 'その他'),
                    $contact->email,
                    $contact->category,
                    $contact->created_at->format('Y-m-d'),
                ]);
            }
            fclose($handle);
        };
        return Response::stream($callback, 200, $headers);
    }
}
