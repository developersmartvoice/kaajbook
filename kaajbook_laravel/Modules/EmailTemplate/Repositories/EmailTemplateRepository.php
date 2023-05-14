<?php
namespace Modules\EmailTemplate\Repositories;

use Modules\EmailGroup\Models\EmailGroup;
use Modules\EmailTemplate\Models\EmailTemplate;

/**
 * Class EmailTemplateRepository
 *
 * Email template insert, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\EmailTemplate
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class EmailTemplateRepository
{

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        return EmailGroup::with('templates')->get();
    }

    /**
     * Display the specified resource in storage.
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function findById($id)
    {
        return EmailTemplate::find($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create email template]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $emailtemplate = new EmailTemplate;
        $emailtemplate->fill($input);
        $emailtemplate->type = strtolower(
            preg_replace('/\s+/', '_', $input['template_name'])
        );
        if ($emailtemplate->save()) {
            //--
            // Add activity
            createUserActivity(
                EmailTemplate::MODULE_NAME,
                $emailtemplate->id,
                $request->method(),
                $emailtemplate->template_name,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update email template]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $emailtemplate = EmailTemplate::findOrFail($id);
        $emailtemplate->type = strtolower(
            preg_replace('/\s+/', '_', $input['template_name'])
        );
        if ($emailtemplate->fill($input)->save()) {
            //--
            // Add activity
            createUserActivity(
                EmailTemplate::MODULE_NAME,
                $emailtemplate->id,
                $request->method(),
                $emailtemplate->template_name,
                $request->ip()
            );
            if (isset($input['is_setting_emailtemplate'])) {
                return true;
            }
            return true;
        }
        return false;
    }
}
