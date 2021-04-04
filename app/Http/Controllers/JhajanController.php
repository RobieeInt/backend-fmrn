<?php

namespace App\Http\Controllers;

use App\Http\Requests\JhajanRequest;
use App\Models\Jhajan;
use Illuminate\Http\Request;

class JhajanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $jhajan = Jhajan::paginate(10);

        return view('jhajan.index',[
            'jhajan' => $jhajan
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('jhajan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JhajanRequest $request)
    {
        $data = $request->all();

        $data['picturePath'] = $request->file('picturePath')->store('assets/jhajan', 'public');

        Jhajan::create($data);

        return redirect()->route('jhajan.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Jhajan $jhajan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Jhajan $jhajan)
    {
        return view('jhajan.edit', [
            'item' => $jhajan
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(JhajanRequest $request, Jhajan $jhajan)
    {
         $data = $request->all();

        if($request->file('picturePath'))
        {
            $data['picturePath'] = $request->file('picturePath')->store('assets/jhajan', 'public');
        }

        $jhajan->update($data);

        return redirect()->route('jhajan.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jhajan $jhajan)
    {
         $jhajan->delete();

        return redirect()->route('jhajan.index');
    }
}
