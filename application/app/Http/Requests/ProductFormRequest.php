<?php namespace App\Http\Requests;

Use Input;

class ProductFormRequest extends Request
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
            //
            'productName' => 'required',
            'location' => 'required',
            'amount' => 'numeric',
            'expirationDate' => 'date',
            'reorderAmount'=>'numeric',
            'productImage'=>'string'
        ];
    }



}
