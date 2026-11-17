<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function add(Request $request)
    {
        return view('contact.add');
    }

    public function store(Request $request)
    {
       
        $data = $request->all();      
       
        Contact::create($data);
        return redirect()->route('contact.list')->with('success', 'Contact added successfully.');
    }

    public function update(Request $request, $id)
    {
       
        $major = Contact::where('id', $id)->first();
        $data = $request->all();
        $path = 'assets/images/excomd';
        if ($request->hasFile('image')) {
            if ($major->image) {
                $file = $path . '/' . $major->image;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
            $image = $request->file('image');
            $data['image'] = hash('sha256', $image->getClientOriginalName() . time() . Str::random(8)) . "." .  $image->getClientOriginalExtension();
            $image->move($path, $data['image']);
        }
        $major->update($data);
        return redirect()->back()->with('success', 'Contact updated successfully.');
    }

    public function edit(Request $request, $id)
    {
        $contact = Contact::where('id', $id)->first();
        return view('contact.edit', compact('contact'));
    }

    public function list(Request $request)
    {
        $contacts = Contact::all();
        return view('contact.list', compact('contacts'));
    }

    public function delete(Request $request, $id)
    {
        $contact = Contact::where('id', $id)->first();
        $contact->delete();
       
        return redirect()->back()->with('success', 'Contact deleted successfull.');
    }
}
