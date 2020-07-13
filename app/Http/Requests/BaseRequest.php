<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-13 09:15:25
 * @LastEditTime: 2020-07-13 09:34:26
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Http/Requests/BaseRequest.php
 * @Motto: MMMMMMMM
 */

namespace App\Http\Requests;

use App\Exceptions\ValidatorException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    public $key = 'default';

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
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array|mixed
     */
    public function messages()
    {
        $validation = config('validation');
        $validation = isset($validation[$this->key]) ? array_merge($validation['default'], $validation[$this->key]) : $validation['default'];
        return $validation;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param $validator
     * @return void
     *
     * @throws ValidatorException
     */
    public function failedValidation(Validator $validator)
    {
        if( $validator->failed() ) {
            $code = (int)$validator->errors()->first();
            throw new ValidatorException($code);
        }
        // throw new ValidationException($validator, $this->response(
        //     $this->formatErrors($validator)
        // ));
    }
}
