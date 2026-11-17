<?php

namespace App\Http\Controllers;

use App\Models\HomePage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomePageComtroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $homePages = HomePage::get();

        return view('homepage.list', compact('homePages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('homepage.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => "required",
            "image" => "nullable|mimes:jpg,jpeg,png",
            "background_image" => "nullable|mimes:jpg,jpeg,png"
        ]);
        $data = $request->all();
        $path = 'assets/images/homePage';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data['image'] = hash('sha256', $image->getClientOriginalName() . time() . Str::random(8)) . "." .  $image->getClientOriginalExtension();
            $image->move($path, $data['image']);
        }
        if ($request->hasFile('background_image')) {
            $image = $request->file('background_image');
            $data['background_image'] = hash('sha256', $image->getClientOriginalName() . time() . Str::random(8)) . "." .  $image->getClientOriginalExtension();
            $image->move($path, $data['background_image']);
        }
        HomePage::create($data);
        return redirect()->back()->with('success', 'Home Page added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $homePage = HomePage::find($id);
        return view('homepage.edit', compact('homePage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "name" => "required",
            "image" => "nullable|mimes:jpg,jpeg,png",
            "background_image" => "nullable|mimes:jpg,jpeg,png"

        ]);
        $homePage = HomePage::where('id', $id)->first();
        $data = $request->all();
        $path = 'assets/images/homePage';
        // if ($request->hasFile('image')) {
        //     if ($homePage->image) {
        //         $file = $path . '/' . $homePage->image;
        //         if (file_exists($file)) {
        //             unlink($file);
        //         }
        //     }
        //     $image = $request->file('image');
        //     $data['image'] = hash('sha256', $image->getClientOriginalName() . time() . Str::random(8)) . "." .  $image->getClientOriginalExtension();
        //     $image->move($path, $data['image']);
        // }

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($homePage->image) {
                $oldImagePath = $path . '/' . $homePage->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $image = $request->file('image');
            $data['image'] = hash('sha256', $image->getClientOriginalName() . time() . Str::random(8)) . "." .  $image->getClientOriginalExtension();
            $image->move($path, $data['image']);
        }

        // if ($request->hasFile('background_image')) {
        //     if ($homePage->image) {
        //         $file = $path . '/' . $homePage->image;
        //         if (file_exists($file)) {
        //             unlink($file);
        //         }
        //     }
        //     $image = $request->file('background_image');
        //     $data['background_image'] = hash('sha256', $image->getClientOriginalName() . time() . Str::random(8)) . "." .  $image->getClientOriginalExtension();
        //     $image->move($path, $data['image']);
        // }

        if ($request->hasFile('background_image')) {
            // Delete old background image if exists
            if ($homePage->background_image) {
                $oldBackgroundImagePath = $path . '/' . $homePage->background_image;
                if (file_exists($oldBackgroundImagePath)) {
                    unlink($oldBackgroundImagePath);
                }
            }
            $backgroundImage = $request->file('background_image');
            $data['background_image'] = hash('sha256', $backgroundImage->getClientOriginalName() . time() . Str::random(8)) . "." .  $backgroundImage->getClientOriginalExtension();
            $backgroundImage->move($path, $data['background_image']);
        }
        $homePage->update($data);
        return redirect()->back()->with('success', 'Home Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
