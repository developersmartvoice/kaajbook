<?php

namespace Modules\ToDo\Models\Attribute;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * Trait ToDoAttribute
 *
 * The Attribute is Defined For ToDo.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\ToDo
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait ToDoAttribute
{
    /**
     * This mutator automatically set due date format.
     *
     * @param Date $value [Due date]
     *
     * @return Date
     */
    public function setDueDateAttribute($value)
    {
        if($value){
            $this->attributes['due_date'] = (new Carbon($value))->format('Y-m-d');
        }
    }
}
