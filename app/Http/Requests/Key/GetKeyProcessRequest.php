<?php namespace App\Http\Requests\Key;

use App\Http\Requests\Request;

class GetKeyProcessRequest extends Request {

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
            'phone' => ['required', 'regex:/^[78][\d]{10}$/i'],
		];
	}

}
