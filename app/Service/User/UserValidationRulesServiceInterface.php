<?php

namespace App\Service\User;

use Illuminate\Http\Request;

interface UserValidationRulesServiceInterface
{
    public function getEditRules(array $data): array;
}
