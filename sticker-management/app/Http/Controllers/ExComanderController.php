<?php

namespace App\Http\Controllers;

use App\Models\ExComander;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExComanderController extends Controller
{
    public function add(Request $request)
    {
        return view('excomd.add');
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
        $path = 'assets/images/excomd';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data['image'] = hash('sha256', $image->getClientOriginalName() . time() . Str::random(8)) . "." .  $image->getClientOriginalExtension();
            $image->move($path, $data['image']);
        }
        ExComander::create($data);
        return redirect()->back()->with('success', 'Major added successfully.');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // "name" => "required",
            "image" => "nullable|mimes:jpg,jpeg,png",
            "position" => "required",
        ]);
        $major = ExComander::where('id', $id)->first();
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
        return redirect()->back()->with('success', 'Major updated successfully.');
    }

    public function edit(Request $request, $id)
    {
        $major = ExComander::where('id', $id)->first();
        return view('excomd.edit', compact('major'));
    }

    public function list(Request $request)
    {
        $excomds = ExComander::all();
        return view('excomd.list', compact('excomds'));
    }

    public function delete(Request $request, $id)
    {
        $major = ExComander::where('id', $id)->first();
        $path = 'assets/images/excomd';
        if ($major) {
            if ($major->image) {
                $file = $path . '/' . $major->image;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
            $major->delete();
        }
        return redirect()->back()->with('success', 'MILL OFFRS deleted successfull.');
    }
}
