<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestAdvertisement extends FormRequest
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
            'title'                 => 'required|max:255|min:5',
            'ds_advertisement'      => 'required|max:255|min:5',
            'price'                 => 'required',
            'cd_category'           => 'required'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {

        return [
            'title'                 => 'Titulo',
            'ds_advertisement'      => 'Descrição do Anúncio',
            'price'                 => 'Preço',
            'cd_category'           => 'Código da Categoria'
        ];
    }
}
