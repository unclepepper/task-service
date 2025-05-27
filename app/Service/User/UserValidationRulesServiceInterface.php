<?php

namespace App\Service\User;

use Illuminate\Http\Request;

interface UserValidationRulesServiceInterface
{
    /**
     * @param array $data
     * @return array<string, mixed>
     */
    public function getEditRules(array $data): array;
}
