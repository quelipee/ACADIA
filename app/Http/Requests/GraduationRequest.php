<?php

namespace App\Http\Requests;

use App\Concerns\ProfileValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class GraduationRequest extends FormRequest
{
    use ProfileValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => $this->idRules(),
            'idCourse' => $this->idRules(),
            'cIdCourse' => $this->idRules(),
            'idUserCourse' => $this->idRules(),
            'name' => $this->nameRules(),
            'class' => $this->nameRules(),
            'courseLevel' => $this->nameRules()
        ];
    }
}