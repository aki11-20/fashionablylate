<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $q = Contact::query();

        if ($kw = $request->keyword) {
            $q->where(function ($qq) use ($kw) {
                $qq->where('name', 'like', "%{$kw}%")
                    ->orWhere('email', 'like', "%{$kw}%");
            });
        }
        if ($g = $request->gender) {
            $q->where('gender', $g);
        }
        if ($cat = $request->category) {
            $q->where('category', $cat);
        }
        if ($d = $request->date) {
            $q->whereDate('created_at', $d);
        }

        $contacts = $q->orderByDesc('created_at')->paginate(10);

        return view('admin.index', compact('contacts'));
    }
    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        if (request()->ajax()) {
            return response()->json($contact);
        }

        return view('admin.show', compact('contact'));
    }
    public function export(Request $request): StreamedResponse
    {
        $q = Contact::query();
        if ($kw = $request->keyword) {
            $q->where(function ($qq) use ($kw) {
                $qq->where('name', 'like', "%{$kw}%")
                    ->orWhere('email', 'like', "%{$kw}%");
            });
        }
        if ($g = $request->gender) $q->where('gender', $g);
        if ($cat = $request->category) $q->where('category', $cat);
        if ($d = $request->date) $q->whereDate('created_at', $d);

        $filename = 'contacts_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($q) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['ID', '名前', '性別', 'メール', '電話', '住所', '建物', '種類', '内容', '作成日時']);

            $q->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $r) {
                    fputcsv($out, [
                        $r->id,
                        $r->name,
                        $r->gender == 1 ? '男性' : ($r->gender == 2 ? '女性' : 'その他'),
                        $r->email,
                        $r->tel,
                        $r->address,
                        $r->building,
                        $r->category,
                        $r->content,
                        $r->created_at,
                    ]);
                }
            });
            fclose($out);
        }, 200, $headers);
    }
}
