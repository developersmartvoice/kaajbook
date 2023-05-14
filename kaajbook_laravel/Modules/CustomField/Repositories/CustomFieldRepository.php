<?php

namespace Modules\CustomField\Repositories;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Modules\CustomField\Models\CustomField;
use Modules\CustomField\Models\Form;

use Auth;

/**
 * Class CustomFieldRepository
 *
 * CustomField CRUD functionality.
 *
 * CustomField create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\CustomField
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CustomFieldRepository
{

    /**
     * Display a listing of the resource.
     *
     * @return object
     */
    public function findAll()
    {
        return CustomField::with('forms')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Params for get custom field]
     *
     * @return array
     */
    public function getAllCustomFields($request)
    {
        $CustomField = CustomField::with('forms');

        $totalData = $CustomField->count();

        $columns = array(
            0 =>'field_label',
            1 =>'name',
            2 =>'field_type',
            3 =>'status'
        );

        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            // $CustomField = $CustomField->where(
            //     function ($query) use ($search) {
            //         $query->where('field_label', 'LIKE', "%{$search}%")
            //             ->orWhere('field_type', 'LIKE', "%{$search}%")
            //             ->orWhere('status', 'LIKE', "%{$search}%");
            //     }
            // );

            $CustomField = CustomField::with(
                ['forms' => function ($query) use ($search) {
                    $query->orWhere('name', 'LIKE', "%{$search}%");
                }]
            )
            ->where(
                function ($query) use ($search) {
                    $query->where('field_label', 'LIKE', "%{$search}%")
                        ->orWhere('field_type', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                }
            )
            ->orWhereHas(
                'forms',
                function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                }
            );

            $totalFiltered = $CustomField->count();
        }

        $CustomField = $CustomField->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $CustomField
        );

        return $json_data;
    }

    /**
     * Display the specified resource in storage.
     *
     * @param int $id [Row id]
     *
     * @return object
     */
    public function findById($id)
    {
        return CustomField::with('forms')->where('id', $id)->first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Params for create custom field]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $CustomField = new CustomField;
        $input = $request->all();

        // --
        // Decode default value
        if ($input['field_type'] == 'dropdown') {
            $input['default_value'] = json_encode($input['default_value']);
        }
        $input['field_column'] = strtolower(
            preg_replace('/\s+/', '_', $input['field_label'])
        );
        $CustomField->fill($input);
        if ($CustomField->save()) {
            // --
            // Add activities
            createUserActivity(
                CustomField::MODULE_NAME,
                $CustomField->id,
                $request->method(),
                $CustomField->field_label,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Params for update custom field]
     * @param int     $id      [Row id]
     *
     * @return object
     */
    public function update($request, $id)
    {
        $CustomField = CustomField::findOrFail($id);
        // --
        // Rename column.
        $input = $request->all();
        if ($input['field_label'] != $CustomField->field_label) {
            $form = Form::where('id', $CustomField->form_id)
                ->where('status', 1)
                ->first();

            if (Schema::hasColumn($form->table_name, $CustomField->field_column)) {
                $input['field_column'] = strtolower(
                    preg_replace('/\s+/', '_', $input['field_label'])
                );
                Schema::table(
                    $form->table_name,
                    function (Blueprint $table) use ($CustomField,$input) {
                        $table->renameColumn(
                            $CustomField->field_column,
                            $input['field_column']
                        );
                    }
                );
            }
        }
        // --
        // Decode default value
        if ($input['field_type'] == 'dropdown') {
            $input['default_value'] = json_encode($input['default_value']);
        }
        if ($CustomField->fill($input)->save()) {
            // --
            // Add activities
            createUserActivity(
                CustomField::MODULE_NAME,
                $CustomField->id,
                $request->method(),
                $CustomField->field_label,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Params for delete custom field]
     * @param int     $id      [Row id]
     *
     * @return boolean
     */
    public function delete($request, $id)
    {
        $CustomField = CustomField::findOrFail($id);
        if (!empty($CustomField)) {
            $forms = Form::get();
            $name = strtolower(
                preg_replace(
                    '/\s+/',
                    '_',
                    $CustomField->field_label
                )
            );
            foreach ($forms as $key => $value) {
                if (Schema::hasColumn($value->table_name, $name)) {
                    Schema::table(
                        $value->table_name,
                        function (Blueprint $table) use ($name) {
                            $table->dropColumn($name);
                        }
                    );
                }
            }
            if ($CustomField->delete()) {
                // --
                // Add activities
                createUserActivity(
                    CustomField::MODULE_NAME,
                    $CustomField->id,
                    $request->method(),
                    $CustomField->field_label,
                    $request->ip()
                );
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    /**
     * Post change custom field status
     *
     * @param Request $request [Params for change custom field status]
     * @param int     $id      [Row id]
     *
     * @return Boolean
     */
    public function changeCustomFieldStatus($request, $id)
    {
        $status = $request->get('status');
        $CustomField = CustomField::findOrFail($id);
        $CustomField->status = $status;

        if ($CustomField->save()) {
            // --
            // Add activities
            createUserActivity(
                CustomField::MODULE_NAME,
                $CustomField->id,
                $request->method(),
                $CustomField->field_label,
                $request->ip(),
                null,
                true
            );
            return true;
        }
    }

    /**
     * Get form tables for custom fields.
     *
     * @return Response
     */
    public function getFormTables()
    {
        return \DB::table(config('core.acl.form_table'))->get();
    }

    /**
     * Get particular module custom field detail.
     *
     * @param Int     $form_id [Form id]
     * @param Boolean $is_view [Show on details]
     *
     * @return Object
     */
    public function getCustomFieldByForm($form_id, $is_view)
    {
        $customField = CustomField::where('form_id', $form_id)
            ->where('status', 1);
            
        if ($is_view) {
            $customField->where('show_on_details', 1);
        }

        return $customField->get();
    }
}
