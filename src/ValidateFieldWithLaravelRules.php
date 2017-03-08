<?php namespace Fryiee\RepeaterValidators;

use Anomaly\RepeaterFieldType\RepeaterFieldType;
use Anomaly\RepeaterFieldType\Command\GetMultiformFromPost;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Validator;

/**
 * Class ValidateFieldWithLaravelRules
 *
 * @author        Craig Berry <hi@craigberry.co>
 */
class ValidateFieldWithLaravelRules
{
    use DispatchesJobs;

    /**
     * Handle the validation.
     *
     * @param             $value
     * @return bool
     */
    public function handle(RepeaterFieldType $fieldType, $streamFieldName, $parentFormAssignments, $arguments, $parentFormBuilder)
    {
        // check that its a repeater field type and that it has the required arguments
        if (!isset($arguments[0], $arguments[1]))
            return false;
        
        $mainCount = 0;
        $attribute = $arguments[0];
        $rule = $arguments[1];
        $ruleArgumentString = (count($arguments) > 2 ? implode(',', array_slice($arguments, 1)) : false);
        
        // if backtick is present, it means multiple rules
        if (strpos($ruleArgumentString, '`') !== false)
            $ruleArgumentString = str_replace(',`,', '|', $ruleArgumentString);
        
        $repeaterFormBuilder = $this->dispatch(new GetMultiformFromPost($fieldType));
        
        if(is_null($repeaterFormBuilder))
            return true;
        
        $repeaterFormBuilder->post();
        
        foreach ($repeaterFormBuilder->getForms() as $form) {
            foreach ($form->getFormFields() as $field) {
                if ($field->field == $attribute) {
                    $validationFields[$field->field][] = $field->value;
                }
            }
        }
        
        $niceNames = [$attribute . '.*' => $streamFieldName . ' repeater ' . $attribute . ' attribute'];
        
        $validator = Validator::make($validationFields, [
            $attribute . '.*' => $ruleArgumentString,    
        ]);
        
        $validator->setAttributeNames($niceNames);
        
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $parentFormBuilder->addFormError($streamFieldName, $error);        
            }
            return false;
        } else {
            return true;    
        }
    }
}
