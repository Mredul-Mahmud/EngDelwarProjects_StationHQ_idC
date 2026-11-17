<?php

namespace App\Http\Controllers;

use App\Models\ExSso;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExSsoController extends Controller
{
    public function add(Request $request)
    {
        return view('exsso.add');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            // "name" => "required",
            "image" => "nullable|mimes:jpg,jpeg,png",
            "position" => "required",
        ]);
        $data = $request->all();
        $path = 'assets/images/exsso';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data['image'] = hash('sha256', $image->getClientOriginalName() . time() . Str::random(8)) . "." .  $image->getClientOriginalExtension();
            $image->move($path, $data['image']);
        }
        ExSso::create($data);
        return redirect()->route('exsso.list')->with('success', 'Ex SSO Officer added successfully.');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // "name" => "required",
            "image" => "nullable|mimes:jpg,jpeg,png",
            "position" => "required",
        ]);
        $major = ExSso::where('id', $id)->first();
        $data = $request->all();
        $path = 'assets/images/exsso';
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
        return redirect()->route('exsso.list')->with('success', 'Ex SSO information updated successfully.');
    }

    public function edit(Request $request, $id)
    {
        $exsso = ExSso::where('id', $id)->first();
        return view('exsso.edit', compact('exsso'));
    }

    public function list(Request $request)
    {
        $exsso = ExSso::all();
        return view('exsso.list', compact('exsso'));
    }

    public function delete(Request $request, $id)
    {
        $exsso = ExSso::where('id', $id)->first();
        $path = 'assets/images/exsso';
        if ($exsso) {
            if ($exsso->image) {
                $file = $path . '/' . $exsso->image;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
            $exsso->delete();
        }
        return redirect()->back()->with('success', 'Ex SSO information deleted successfull.');
    }
}
