<?php

namespace Modules\Team\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Emails;
use App\Rules\EmailsExists;

class ImportTeamRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'team_name' => 'required|max:30|unique:'.config('core.acl.teams').',team_name,NULL,id,deleted_at,NULL',
            'members' => ['required', new Emails, new EmailsExists],
            'team_leader' => 'required|email|exists:'.config('core.acl.users_table').',email,deleted_at,NULL'
        ];
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
}
