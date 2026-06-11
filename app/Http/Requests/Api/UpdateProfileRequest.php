<?php

namespace App\Http\Requests\Api;

use App\Enums\Role;
use App\Enums\StartupStage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->user();

        if ($user->role === Role::Incubator) {
            return [
                'organization_name' => ['sometimes', 'string', 'max:255'],
                'description' => ['sometimes', 'string'],
                'website' => ['nullable', 'url'],
                'country' => ['sometimes', 'string', 'max:100'],
                'city' => ['sometimes', 'string', 'max:100'],
                'focus_sectors' => ['sometimes', 'array'],
                'supported_stages' => ['sometimes', 'array'],
                'capacity' => ['sometimes', 'integer', 'min:1'],
                'founded_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            ];
        }

        return [
            'company_name' => ['sometimes', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'website' => ['nullable', 'url'],
            'country' => ['sometimes', 'string', 'max:100'],
            'city' => ['sometimes', 'string', 'max:100'],
            'sector' => ['sometimes', 'string'],
            'stage' => ['sometimes', Rule::enum(StartupStage::class)],
            'founded_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'team_size' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
