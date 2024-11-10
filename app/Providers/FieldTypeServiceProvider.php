<?php

namespace App\Providers;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\ServiceProvider;

class FieldTypeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Registra el nuevo tipo de campo 'custom_checklist'
        CRUD::addFieldType('custom_checklist', function ($field) {
            // Aquí debes especificar la ruta correcta a la vista
            return view('resource.vendor.backpack.fields.custom_checklist', compact('field'));
        });
    }

    public function register()
    {
        //
    }
}