<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function __construct()
    {
        // $this->middleware();
        // $this->authorize('');
    }


    public function show(User $user)
    {

        return view('users.show',compact('user'));

    }

    public function edit(User $user)
    {
        return view('users.edit',compact('user'));
    }

    public function update()
    {

    }


}