<?php namespace ProjectManagement\ValidationRequests;
use Illuminate\Validation\Rule;
use ProjectManagement\Abstracts\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $clientId = $this->route('clientId');

        return [
            'business_name' => 'string|max:255',
            'contact_person' => 'string|max:255',
            'email' => [
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($clientId),
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'referance' => 'nullable|string|max:255',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'city_id' => 'nullable|exists:cities,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'business_name.required' => 'The business name is required.',
            'contact_person.required' => 'The contact person is required.',
            'email.required' => 'The email address is required.',
            'email.unique' => 'The email address has already been taken.',
            'created_by_id.exists' => 'The selected user does not exist.',
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            'business_name' => $request['business_name'],
            'contact_person' => $request['contact_person'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'address' => $request['address'],
            'referance' => $request['referance'],
            'country_id' => $request['country_id'] ?? null,
            'state_id' => $request['state_id'] ?? null,
            'city_id' => $request['city_id'] ?? null
        ];
    }
}
