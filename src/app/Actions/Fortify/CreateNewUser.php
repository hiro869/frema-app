<?php

namespace App\Actions\Fortify;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input): User
    {
        // ← FormRequest の rules()/messages() をそのまま利用
        $request   = app(RegisterRequest::class);
        $validator = Validator::make(
            $input,
            $request->rules(),
            $request->messages(),
            method_exists($request, 'attributes') ? $request->attributes() : []
        );
        $validator->validate();

        return User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
