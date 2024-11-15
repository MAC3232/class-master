<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormModalComponent extends Component
{
    public $actionUrl;
    public $fields;
    public $idField;

    /**
     * Create a new component instance.
     *
     * @param string $actionUrl
     * @param array $fields
     */
    public function __construct($actionUrl, $fields = [], $idField=[])
    {
        $this->actionUrl = $actionUrl;
        $this->fields = $fields;
        $this->idField = $idField;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-modal-component');
    }
}
