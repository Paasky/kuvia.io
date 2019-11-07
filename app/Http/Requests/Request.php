<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    abstract public function rules(): array;

    public function allAllowed(): array
    {
        $params = [];
        foreach (array_keys($this->rules()) as $param) {
            if (strpos($param, '.') === false) {
                $params[] = $param;
            } else {
                $params[] = explode('.', $param)[0];
            }
        }

        return $this->only(array_unique($params));
    }
}
