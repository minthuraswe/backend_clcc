<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Activity;
use App\Category;


class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $act = Activity::paginate(10);
        return view('activity.index', compact('act'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cat = Category::orderBy('cat_name')->get();
        return view('activity.create', compact('cat'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateData($request);

        //getting images of activity
        $activity_image = request('memory');
        $ary = array();

        foreach ($activity_image as $data) {
            $filename = uniqid() . '.' . $data->getClientOriginalExtension();
            array_push($ary, $filename);
            $path = imagePath();
            $data->move($path, $filename);
        }

        Activity::create([
            'act_name' => request('name'),
            'cat_id' => request('category'),
            'act_memory' => serialize($ary),
        ]);

        return redirect('activity');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $act = Activity::find($id);
        $cat = Category::all();
        return view('activity.show', compact('act', 'cat'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $act = Activity::find($id);
        // dd($act);
        $cat = Category::all();
        return view('activity.edit', compact('act', 'cat'));
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
        //getting images of activity
        $activity_image = request('memory');
        $ary = array();

        foreach ($activity_image as $data) {
            $filename = uniqid() . '.' . $data->getClientOriginalExtension();
            array_push($ary, $filename);
            $path = imagePath();
            $data->move($path, $filename);
        }

        $act = Activity::find($id);
        $act->act_name = $request->act_name;
        $act->cat_id = $request->cat_id;
        $act->act_memory = serialize($ary);

        $act->save();
        return redirect('activity');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Activity::find($id)->delete();
        return redirect('activity');
    }

    private function validateData($request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'category' => 'required',
            'memory' => 'required',
            'memory.*' => 'required|mimes:jpeg, jpg, png',
        ]);
    }
}
