<?php
/**
 * Form Request
 *
 * @module  NsPrintAdapter
 *
 * @since  4.7.0
**/

namespace Modules\NsPrintAdapter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrinterSetupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return  bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return  array
     */
    public function rules()
    {
        return [
            'setup_id'  =>  'required',
        ];
    }
}
