# pyro-repeater-validators
Additional repeaters for PyroCMS 3 Repeater Field Type.

## Usage
Require using composer: `composer require fryiee/pyro-repeater-validators`

Add use statement in your own FormBuilder:
```php
use Fryiee\RepeaterValidators\ValidateFieldValueMinimumAmount;
use Fryiee\RepeaterValidators\ValidateFieldValueMaximumAmount;
use Fryiee\RepeaterValidators\ValidateFieldValueAmount;
use Fryiee\RepeaterValidators\ValidateFieldWithLaravelRules;
```

Add 'rules' and 'validators' array with handler to the fields in your FormBuilder:
e.g.
```php
'phones' => [
  'validators' => [
    'validate_max_number_of_field' => [
      'handler' => ValidateFieldValueMaximumAmount::class,
      'message' => false,
    ],
  ],
  'rules' => [
    'validate_max_number_of_field:main,true,1'  
  ],
],
```

```php
'phones' => [
  'validators' => [
    'validate_min_number_of_field' => [
      'handler' => ValidateFieldValueMinimumAmount::class,
      'message' => false,
    ],
  ],
  'rules' => [
    'validate_min_number_of_field:main,true,1'  
  ],
],
```

```php
'phones' => [
  'validators' => [
    'validate_number_of_field' => [
      'handler' => ValidateFieldValueAmount::class,
      'message' => false,
    ],
  ],
  'rules' => [
    'validate_number_of_field:main,true,1'  
  ],
],
```

```php
'logs' => [
  'validators' => [
    'validate_with_laravel_rules' => [
      'handler' => ValidateFieldWithLaravelRules::class,
      'message' => false,
    ],
  ],
  'rules' => [
    'validate_with_laravel_rules:ip_address,ip,'`',string'
  ],
]
```

## Arguments
### ValidateFieldValueAmount
```{field},{value},{amount}```

e.g ```phone_number,12345678,3``` would mean that exactly 3 of the phone_number fields in the repeater form would have to be equal to 12345678.

### ValidateFieldValueMaximumAmount
```{field},{value},{amount}```

e.g ```phone_number,12345678,3``` would mean that at most 3 of the phone_number fields in the repeater form would have to be equal to 12345678.

### ValidateFieldValueMaximumAmount
```{field},{value},{amount}```

e.g ```phone_number,12345678,3``` would mean that at least 3 of the phone_number fields in the repeater form would have to be equal to 12345678.

### ValidateFieldWithLaravelRules
```{field},{amount},`,{laravel_rule2},`,{laravel_rule3}```

e.g ```phone_number,string,`,numeric,`,in:1,2,3,4,5,6``` would mean that phone_number would need to be string, numeric and be within the given array of values.

## License
This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
