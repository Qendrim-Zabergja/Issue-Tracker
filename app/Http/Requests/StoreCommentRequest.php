<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'issue_id'    => ['required', 'string', 'exists:issues,uuid'],
            'author_name' => ['required', 'string', 'max:100'],
            'body'        => ['required', 'string', 'max:5000'],
        ];
    }
}
