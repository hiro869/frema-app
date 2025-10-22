<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Http\Requests\Auth\RegisterRequest;

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input): User
    {
        $request = new RegisterRequest();
        $validated = validator($input, $request->rules(), $request->messages(), $request->attributes())->validate();

        return User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
    }
}
