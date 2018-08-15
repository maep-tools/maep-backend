<?php

namespace App\Http\Requests\API;

use App\Models\storage_config;
use InfyOm\Generator\Request\APIRequest;

class Updatestorage_configAPIRequest extends APIRequest
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
        return storage_config::$rules;
    }
}
