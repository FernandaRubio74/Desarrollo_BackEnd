<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\StoreUserRequest;



class UserController extends Controller
{
   
    public function index()
    {
        $users = User::when(request()->has('username'), function ($query) {
            $query->where('username','like','%'.request()->input('username').'%')->get();
        }
        )->when(request()->has('email'), function ($query) {
            $query->where('email','like','%'.request()->input('email').'%')->get();
        })
        ->paginate(request()->per_page);

        return UserResource::collection($users);
    }

   
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Str::random(8); 
        
        $user = User::create($data);

        return response()->json(UserResource::make($user), 201);

    }

    
    public function show(User $user)
    {
        return response()->json(UserResource::make($user));
    }

   
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update($data);

        return response()->json(UserResource::make($user),0);
    }

       public function destroy(User $user)
    {
        //
    }


}
