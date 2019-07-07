<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ActionTypeRequest as StoreRequest;
use App\Http\Requests\ActionTypeRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ActionTypeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ActionTypeCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ActionType');
        $this->crud->setRoute("admin/action-type");
        $this->crud->setEntityNameStrings('actiontype', 'action_types');

        $this->crud->setColumns(['name', 'action_type']);
        $this->crud->addField([
            'name' => 'name',
            'type' => 'text',
            'label' => "Name"
        ]);
        $this->crud->addField([
            'name' => 'action_type',
            'type' => 'text',
            'label' => "Action type"
        ]);
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }
}
