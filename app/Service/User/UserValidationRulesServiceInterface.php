<?php

namespace App\Service\User;

use Illuminate\Http\Request;

interface UserValidationRulesServiceInterface
{
    public function getRegisterRules(Request $request): array;

    public function getLoginRules(Request $request): array;

    public function getEditRules(array $data): array;
}
