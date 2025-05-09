<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AmoMainService extends AmoAuthService
{

    public function addNoteToEntity($entityType, int $entityId, $noteText, $noteType = 'common')
    {
        $accessToken = $this->getCurrentAccessToken()->access_token;

        $response = Http::withoutVerifying()->withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => 'application/json',
        ])->post("$this->domain/api/v4/{$entityType}/notes", [
            [
                'entity_id' => $entityId,
                'note_type' => $noteType,
                'params' => [
                    'text' => $noteText
                ]
            ]
        ]);

    }

    public function handleLeadCreation($lead): void
    {
        $noteText = "Сделка {$lead['name']} создана. Ответственный: {$lead['responsible_user_id']}, Время: " . now()->format('d.m.Y H:i:s');
        $this->addNoteToEntity('leads', $lead['id'], $noteText);
    }

    public function handleLeadUpdate($lead): void
    {
        $oldValues = $lead['old_values'] ?? [];
        $newValues = $lead['new_values'] ?? [];
        if (empty($oldValues) || empty($newValues)) {
            return;
        }
        $noteText = "Сделка {$lead['name']} изменена. Ответственный: {$lead['responsible_user_id']}, Время: " . now()->format('d.m.Y H:i:s');

        foreach ($oldValues as $field => $oldValue) {
            $newValue = $newValues[$field] ?? '';
            $noteText .= "▸ {$this->getFieldName($field)}: {$oldValue} → {$newValue}\n";
        }

        $this->addNoteToEntity('leads', $lead['id'], $noteText);
    }

    public function handleContactCreation($contact): void
    {
        $noteText = "Контакт {$contact['name']} создан. Ответственный: {$contact['responsible_user_id']} Время" . now()->format('d.m.Y H:i:s');
        $this->addNoteToEntity('contacts', $contact['id'], $noteText);
    }

    public function handleContactUpdate($contact): void
    {
        $oldValues = $contact['old_values'] ?? [];
        $newValues = $contact['new_values'] ?? [];
        if (empty($oldValues) || empty($newValues)) {
            return;
        }
        $noteText = "Контакт {$contact['name']} изменена. Ответственный: {$contact['responsible_user_id']} Время" . now()->format('d.m.Y H:i:s');

        foreach ($oldValues as $field => $oldValue) {
            $newValue = $newValues[$field] ?? '';
            $noteText .= "▸ {$this->getFieldName($field)}: {$oldValue} → {$newValue}\n";
        }

        foreach ($oldValues as $field => $oldValue) {
            $newValue = $newValues[$field] ?? '';
            $noteText .= "▸ {$this->getFieldName($field)}: {$oldValue} → {$newValue}\n";
        }

        $this->addNoteToEntity('contacts', $contact['id'], $noteText);

    }
    protected function getFieldName(string $field): string
    {
        $fieldNames = [
            'name' => 'Название',
            'price' => 'Сумма',
            'status_id' => 'Статус',
            'responsible_user_id' => 'Ответственный',
            'custom_fields' => 'Доп. поля',
            // Добавьте другие поля по необходимости
        ];

        return $fieldNames[$field] ?? $field;
    }

}
