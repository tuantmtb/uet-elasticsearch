<?php

namespace App\Http\Requests;

use App\Models\Journal;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateArticleRequest extends FormRequest
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
        $journal_ids = Journal::pluck('id')->toArray();
        return [
            'title' => 'string|required|min:5|unique:articles',
            'abstract' => 'string',
            'keyword' => 'string',
            'volume' => 'string',
            'number' => 'string',
            'year' => 'string',
            'source' => 'active_url',
            'uri' => 'active_url',
            'journal_id' => [
                'required',
                Rule::in($journal_ids),
            ],
            'language' => [
                Rule::in(array_keys(\VciConstants::LOCALIZE)),
            ],
            'authors' => 'array',
            'authors.*.name' => 'string|required',
            'authors.*.email' => 'email',
            'authors.*.organize_name' => 'string',
            'reference' => 'string',
        ];
    }


}
