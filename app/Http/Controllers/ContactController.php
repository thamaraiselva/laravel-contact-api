<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request){

        $contacts = Contact::select('id', 'first_name', 'last_name', 'email', 'created_at', 'updated_at')->get();


        return response()->json([
            'data' => $contacts,
            'token' => $request->user()->currentAccessToken()
        ]);
    }

    public function store(Request $request){
        // logger('bool', [$request->user()->tokenCan('can-write')]);
        
        if(! $request->user()->tokenCan('server:write')) {
            return   response()->json('err');
        }

        
        $contact = new Contact();
        $contact->first_name = $request->first_name;
        $contact->last_name = $request->last_name;
        $contact->email = $request->email;
        $contact->save();
        
        return response()->json('Ok');
    }

    public function update(Request $request, $id){


        if(! $request->user()->tokenCan('server:write')) {
            return   response()->json('err');
        }

        $contact = Contact::find($id);
        $contact->first_name = $request->first_name;
        $contact->last_name = $request->last_name;
        // $contact->email = $request->email;
        $contact->save();

        return response()->json('Ok');
    }

    public function destroy($id){

        $contact = Contact::find($id);
        $contact->delete();

        return response()->json('Ok');
    }
}
