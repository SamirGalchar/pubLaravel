<?php

namespace App\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardExpirationMonth;
use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardNumber;

class subscribeNowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        if(Auth::user()->role == 'user'):
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
            'plan_price' => 'required',
            'plan_id' => 'required',
            'card_number' => ['required'/*,new CardNumber*/],
            'card_name' => 'required',
            'expiry_month' => ['required'/*, new CardExpirationMonth($this->get('expiry_month'))*/],
            'expiry_year' => ['required'/*, new CardExpirationYear($this->get('expiry_year'))*/],
            'cvv' => ['required'/*, new CardCvc($this->get('cvv'))*/],
        ];
    }
}
