<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isStartup();
    }

    public function rules(): array
    {
        return [
            'incubator_id' => [
                'required',
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $already = \App\Models\Application::where('startup_id', $this->user()->id)
                        ->where('incubator_id', $value)
                        ->exists();
                    if ($already) {
                        $fail('You have already applied to this incubator.');
                    }
                },
            ],
            'motivation' => ['required', 'string', 'min:100'],
            'pitch_deck_url' => ['nullable', 'url', 'max:500'],
        ];
    }
}
