<?php namespace Fryiee\RepeaterValidators;

use Anomaly\RepeaterFieldType\RepeaterFieldType;
use Anomaly\RepeaterFieldType\Command\GetMultiformFromPost;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class ValidateFieldValueMaximumAmount
 *
 * @author        Craig Berry <hi@craigberry.co>
 */
class ValidateFieldValueMaximumAmount
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
        if (!isset($arguments[0], $arguments[1], $arguments[2]))
            return false;
        
        $mainCount = 0;
        $attribute = $arguments[0];
        $boolValue = filter_var($arguments[1], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $valueToCheckFor = (!is_null($boolValue) ? $boolValue : $arguments[1]);
        $occurrences = intval($arguments[2]);
        
        $errorMessage = 'The ' . $streamFieldName . ' repeater must contain a maximum ' . $occurrences . ' ' . (str_plural('row', $occurrences)) . ' of the ' . $attribute . ' equal to ' . $valueToCheckFor . '.';
        
        $repeaterFormBuilder = $this->dispatch(new GetMultiformFromPost($fieldType));
        
        if(is_null($repeaterFormBuilder)) {
            $parentFormBuilder->addFormError($streamFieldName, $errorMessage);
            return false;
        }
        
        $repeaterFormBuilder->post();
        
        foreach ($repeaterFormBuilder->getForms() as $form) {
            foreach ($form->getFormFields() as $field) {
                if ($field->field == $attribute && $field->value == $valueToCheckFor) {
                    $mainCount++;    
                }
            }
        }
        
        if ($mainCount > $occurrences) {
            $parentFormBuilder->addFormError($streamFieldName, $errorMessage);
            return false;    
        } else {
            return true;    
        }
    }
}
