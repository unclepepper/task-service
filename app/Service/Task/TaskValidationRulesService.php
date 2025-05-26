<?php

declare(strict_types=1);

namespace App\Service\Task;

class TaskValidationRulesService implements TaskValidationRulesServiceInterface
{
    public function getEditRules(array $data): array
    {
        $rules = [];

        if (isset($data['title'])) {
            $rules['title'] = 'string|max:255';
        }

        if (isset($data['description'])) {
            $rules['description'] = 'nullable|string';
        }

        if (isset($data['status'])) {
            $rules['status'] = 'in:pending,in_progress,completed';
        }

        return $rules;
    }

}
