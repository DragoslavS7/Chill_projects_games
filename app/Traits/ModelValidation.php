<?php
namespace App\Traits;

trait ModelValidation
{
    protected $errors;

    public function validate($data, $rules=null)
    {
        if(!$rules) {
            $rules = property_exists($this, 'rules') ? $this->rules : [];
        }

        $attributeNames = property_exists($this,'attributeNames' ) ? $this->attributeNames : [];

        $messages = property_exists($this,'validationMessages' ) ? $this->validationMessages : [];

        $validator = \Validator::make($data, $rules, $messages);
        $validator->setAttributeNames($attributeNames);

        if ($validator->fails())
        {
            $this->errors = $validator->errors();
            return false;
        }

        return true;
    }

    public function errors()
    {
        return $this->errors;
    }
}