<?php

declare(strict_types=1);

namespace App\Service\User;

use Illuminate\Http\Request;

class UserValidationRulesService implements UserValidationRulesServiceInterface
{
    public function getRegisterRules(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|required|max:50',
            'email' => 'required|string|email|required|max:50|unique:users',
            'password' => 'required|string|required|min:3',
        ]);
    }

    public function getLoginRules(Request $request): array
    {
        return $request->validate([
            'email' => 'required|string|email|max:50|',
            'password' => 'required|string|required|min:3',
        ]);
    }

    public function getEditRules(array $data): array
    {
        $rules = [];

        if (isset($data['email'])) {
            $rules['email'] = 'required|string|email';
        }

        if (isset($data['name'])) {
            $rules['name'] = 'required|string';
        }

        if (isset($data['password'])) {
            $rules['password'] = 'required|string';
        }

        return $rules;
    }

}
