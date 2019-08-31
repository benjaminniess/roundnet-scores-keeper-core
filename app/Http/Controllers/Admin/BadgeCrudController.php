<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\BadgeType;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BadgeRequest as StoreRequest;
use App\Http\Requests\BadgeRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class BadgeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BadgeCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Badge');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/badge');
        $this->crud->setEntityNameStrings('badge', 'badges');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // Fields shown when creating or updating a badge
        $this->crud->addField([ 
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text'
        ]);
        $this->crud->addField([ 
            'name' => 'description',
            'label' => 'Description',
            'type' => 'textarea'
        ]);
        $this->crud->addField([ 
            'name' => 'action_count',
            'label' => 'Number of actions before unlocking the badge',
            'type' => 'number'
        ]);
        $this->crud->addField([
           'label' => "Badge type",
           'type' => 'select',
           'name' => 'badges_types_id', // the db column for the foreign key
           'entity' => 'badge_type', // the method that defines the relationship in your Model
           'attribute' => 'name', // foreign key attribute that is shown to user
           'model' => "App\Models\Badge_type"
        ]);

        // Columns shown in badges list
        $this->crud->addColumn([ 
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text'
        ]);
        $this->crud->addColumn([ 
            'name' => 'description',
            'label' => 'Description',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
           'label' => "Badge type",
           'type' => 'select',
           'name' => 'badges_types_id', // the db column for the foreign key
           'entity' => 'badge_type', // the method that defines the relationship in your Model
           'attribute' => 'name', // foreign key attribute that is shown to user
           'model' => "App\Models\Badge"
        ]);

        // add asterisk for fields that are required in BadgeRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
