<?php

namespace App\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class profileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required','string','email:rfc,dns','max:255',Rule::unique('users')->ignore(Auth::user()->id)],
            //'phone' => ['required', 'string', 'max:25',Rule::unique('users')->ignore(Auth::user()->id) ],
        ];
    }
}
