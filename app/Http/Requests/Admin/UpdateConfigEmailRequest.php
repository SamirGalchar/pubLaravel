<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateConfigEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user()->role == 'admin'):
            return true;
        else:
            return false;
        endif;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fromName'=>'required',
            'smtpHost'=>'required',
            'smtpPort'=>'required',
            'smtpEmail'=>['required','email'],
            'smtpPass'=>'required',
            'adminEmail'=>['required','email'],
        ];
    }
}
