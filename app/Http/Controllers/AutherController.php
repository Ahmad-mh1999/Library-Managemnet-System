<?php

namespace App\Http\Controllers;

use App\Models\Auther;
use Illuminate\Http\Request;
use App\Http\Requests\AutherRequest;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\AutherResource;
use App\Http\Requests\UpdateAutherRequest;
use Illuminate\Support\Facades\Cache;

class AutherController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authers =  Cache::remember('authers',120,function(){
            return Auther::all();
        });
        return $this->customeResponse(AutherResource::collection($authers),'Done',200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AutherRequest $request)
    {
        $auther = Auther::create([
            'name' => $request->name,
        ]);
        return $this->customeResponse(new AutherResource($auther), ' Created Successfuly', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Auther $auther)
    {
        return $this->customeResponse(new AutherResource($auther), 'Done', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAutherRequest $request, Auther $auther)
    {
        $auther->name = $request->input('name') ?? $auther->name;
        $auther->save();
        return $this->customeResponse(new AutherResource($auther), 'updated Successfuly', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auther $auther)
    {
        $auther->delete();
        return $this->customeResponse("", ' deleted successfully', 200);
    }
}
