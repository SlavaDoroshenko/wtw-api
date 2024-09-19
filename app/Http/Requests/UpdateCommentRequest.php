<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && (Auth::user()->isModerator() || Auth::id() === $this->comment->user_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:10',
            'parent_id' => 'nullable|exists:comments,id',
        ];
    }
}
