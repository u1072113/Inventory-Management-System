<?php namespace App\Http\Requests;

use Inventory\Repository\Product\ProductInterface;
use Input;
use Validator;

class DispatchFormRequest extends Request
{

    public function __construct(ProductInterface $product)
    {
        $this->product = $product;
    }

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
        Validator::extend('greater_than', function ($attribute, $value, $parameters) {

            if (doubleval($value) <= doubleval($parameters[0]) and doubleval($value) > 0) {
                return true;
            }
            return false;
        });
        $product = $this->product->getProductById(Input::get('dispatchedItem'));
        $max = 'greater_than:' . round($product->amount) . '|required';
        return [
            //
            'amount' => $max
        ];
    }

}
