<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UrlSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'urls' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $urls = array_filter(explode("\n", str_replace("\r", "", trim($value))));
                    foreach ($urls as $url) {
                        $url = trim($url);
                        if (!filter_var($url, FILTER_VALIDATE_URL)) {
                            $fail("$url is not a valid URL.");
                        }
                    }
                }
            ],
        ];
    }
}
