<?php

namespace App\Http\Controllers\Dashboard\Contacts;

use App\Exports\ContactsExport;
use App\Http\Controllers\Controller;
// Request
use Illuminate\Http\Request;
// Models
use App\Models\Contact;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ContactsController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            'title' =>  __("Messages Lists"),
            'items' =>  [
                [
                    'title' =>  __("Messages Lists"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        $lists = Contact::search(request()->all())->latest()->paginate();
        return view('admin.pages.contacts.index',[
            'breadcrumb' => $breadcrumb,
            'lists'      => $lists,
        ]);
    }

    public function show(Contact $contact)
    {
        if($contact->seen == 0) {
            $contact->update([
                'seen'    => 1
            ]);
        }
        $breadcrumb = [
            'title' =>  __("Show message"),
            'items' =>  [
                [
                    'title' =>  __("Messages Lists"),
                    'url'   =>  route('admin.contacts.index'),
                ],
                [
                    'title' =>  __("Show message"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        return view('admin.pages.contacts.show',[
            'breadcrumb'=>$breadcrumb,
            'contact'=>$contact,
        ]);
    }

    public function destroy(Request $request,Contact $contact) {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', __(":item has been deleted.",['item' => __('Message')]) );
    }

    public function exportPdf(Request $request) {
        $lists = Contact::search($request->all())->latest()->get();
        $html = view('admin.pages.contacts.export', compact('lists'));
        $mpdf = new \Mpdf\Mpdf(['autoArabic' => true]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->autoArabic = true;
        $mpdf->writeHtml($html);
        $mpdf->SetDirectionality('rtl');
        return $mpdf->Output("{$this->fileName()}.pdf",'D');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new ContactsExport($request->all()), "{$this->fileName()}.xlsx");
    }

    private function fileName() {
        return "messages_".Carbon::now()->format("Y-m-d");
    }
}
