<?php

declare(strict_types=1);

namespace App\Service\User;

class UserValidationRulesService implements UserValidationRulesServiceInterface
{
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
