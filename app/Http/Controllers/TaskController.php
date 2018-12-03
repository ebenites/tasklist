<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tasks = $request->user()->tasks()
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return $tasks;
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
            'name' => 'required|max:255',
            'description' => 'required|max:255',
        ]);
        
        $task = $request->user()->tasks()->create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        
        return $task;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $task = $request->user()->tasks()->where('id', $id)->firstOrFail();
        
        return $task;
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
        $task = $request->user()->tasks()->where('id', $id)->firstOrFail();
        
        if($request->has('name')){
            
            $this->validate($request, [
                'name' => 'max:255',
            ]);
            
            $task->update([
                'name' => $request->name
            ]);
            
        }
        
        if($request->has('description')){
            
            $this->validate($request, [
                'description' => 'max:255',
            ]);
            
            $task->update([
                'description' => $request->description
            ]);
            
        }
        
        return $task;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $task = $request->user()->tasks()->where('id', $id)->firstOrFail();
        $task->delete();
        
        return $task;
    }
}
