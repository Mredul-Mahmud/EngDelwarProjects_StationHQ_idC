<?php

namespace App\Http\Controllers;

use App\Models\ETypeStickerRegisterNumber;
use App\Http\Requests\CreateETypeRegistrationNumberRequest;
use App\Models\StickerCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddETypeRegistrationNumber extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['reg_numbers'] = ETypeStickerRegisterNumber::with('stickerCategory')->latest()->get();
        return view('e-type-registration-number.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['sticker_types'] = StickerCategory::get();
        return view('e-type-registration-number.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateETypeRegistrationNumberRequest $request)
    {
        $e_type_register_number = new ETypeStickerRegisterNumber();
        $e_type_register_number->sticker_category_id = $request->e_type_sticker;
        $e_type_register_number->reg_number = $request->reg_number;
        $e_type_register_number->created_by = auth()->user()->name;
        $e_type_register_number->save();

        return redirect()->route('registration-number.index')->with('message', 'Create successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!$id) {
            return back();
        }

        $data['sticker_types'] = StickerCategory::get();
        $data['e_reg_number'] = ETypeStickerRegisterNumber::find($id);
        return view('e-type-registration-number.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateETypeRegistrationNumberRequest $request, $id)
    {
        if (!$id) {
            return back();
        }
        $e_type_register_number = ETypeStickerRegisterNumber::find($id);
        $e_type_register_number->sticker_category_id = $request->e_type_sticker;
        $e_type_register_number->reg_number = $request->reg_number;
        $e_type_register_number->updated_by = auth()->user()->name;
        $e_type_register_number->save();
        return redirect()->route('registration-number.index')->with('message', 'Create successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$id) {
            return back();
        }

        $registrationNumber = ETypeStickerRegisterNumber::findOrFail($id);

        $registrationNumber->delete();

        return redirect()->route('registration-number.index')->with('success', 'Registration number deleted successfully.');
    }
}
