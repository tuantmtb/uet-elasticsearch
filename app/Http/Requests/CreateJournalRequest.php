<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateJournalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /**
         * @var User $user
         */
        $user = $this->user();
        return $user->hasPermissionTo('edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|required|unique:journals',
            'name_en' => 'string',
            'website' => 'string',
            'address' => 'string',
            'description' => 'string',
        ];
    }
}
