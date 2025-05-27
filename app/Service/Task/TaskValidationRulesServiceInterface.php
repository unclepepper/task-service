<?php

namespace App\Service\Task;

interface TaskValidationRulesServiceInterface
{
    /**
     * @param array $data
     * @return array<string, mixed>
     */
    public function getEditRules(array $data): array;
}
