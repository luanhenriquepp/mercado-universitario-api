<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestUser extends FormRequest
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
        switch ($this->route()->getActionMethod()) {
            case 'authenticate': {
                return  [
                    'registration'      => 'required',
                    'password'          => 'required'
                ];
            }
            case 'register': {
                return [
                    'name'              => 'required|max:255|min:3',
                    'registration'      => 'required|unique:tb_user|max:32|min:4',
                    'cpf'               => 'required|max:14|min:11|unique:tb_user',
                    'rg'                => 'required|max:14|min:5|unique:tb_user',
                    'birth'             => 'required',
                    'email'             => 'required|email|unique:tb_user',
                    'password'          => 'required|max:32|min:8|regex: ^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$^|confirmed',
                    'public_place'      => 'required|max:255|min:5',
                    'number'            => 'required',
                    'complement'        => 'max:255',
                    'neighborhood'      => 'required|max:255|min:5',
                    'cep'               => 'required|max:20|min:5',
                    'university_name'   => 'required|max:255|min:3',
                    'course'            => 'required|max:255|min:2',
                    'semester'          => 'required|max:100|min:1'
                ];
            }
            case  'update': {
                return [
                    'name'                  => 'required|max:255|min:3',
                    'public_place'          => 'required|max:255|min:10',
                    'number'                => 'required',
                    'complement'            => 'max:255',
                    'neighborhood'          => 'required|max:255|min:5',
                    'cep'                   => 'required|max:20|min:5',
                    'university_name'       => 'required|max:255|min:3',
                    'course'                => 'required|max:255|min:2',
                    'semester'              => 'required|max:100|min:2',
                    'cd_city'               => 'required'
                ];
            }
        }
        return [

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
            'name'              => 'Nome',
            'registration'      => 'Matricula',
            'cpf'               => 'CPF',
            'rg'                => 'RG',
            'birth'             => 'Data de Nascimento',
            'email'             => 'E-Mail',
            'password'          => 'Password',
            'public_place'      => 'Logradouro',
            'number'            => 'Numero',
            'complement'        => 'Complemento',
            'neighborhood'      => 'Bairro',
            'cep'               => 'CEP',
            'university_name'   => 'Faculdade',
            'course'            => 'Curso',
            'semester'          => 'Semestre',
        ];
    }
}
