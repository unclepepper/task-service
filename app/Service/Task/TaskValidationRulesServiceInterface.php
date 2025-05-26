<?php

namespace App\Service\Task;

interface TaskValidationRulesServiceInterface
{
    public function getEditRules(array $data): array;
}
