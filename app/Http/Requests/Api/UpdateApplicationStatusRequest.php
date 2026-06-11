<?php

namespace App\Http\Requests\Api;

use App\Enums\ApplicationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isIncubator();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(ApplicationStatus::class)],
            'reviewer_notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
