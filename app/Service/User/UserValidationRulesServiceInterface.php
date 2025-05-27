<?php

namespace App\Service\User;

interface UserValidationRulesServiceInterface
{
    /**
     * @param array $data
     * @return array<string, mixed>
     */
    public function getEditRules(array $data): array;
}
