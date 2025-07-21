<?php

namespace App\View\Helper;

use Cake\View\Helper\FormHelper as CakeFormHelper;
use Cake\Utility\Hash;
use Cake\View\View;
use Cake\Utility\Inflector;

class FormHelper extends CakeFormHelper {

    private $templates = [
        // Used for button elements in button().
        // 'button' => '<button{{attrs}}>{{text}}</button>',
        // Used for checkboxes in checkbox() and multiCheckbox().
        // Input group wrapper for checkboxes created via control().
        // Widget ordering for date/time/datetime pickers.
        'dateWidget' => '<div class="form-group">{{label}} {{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}</div>',
        'error' => '<span class="invalid-feedback">{{content}}</span>',
        // 'errorList' => '<ul>{{content}}</ul>',
        // 'errorItem' => '<li>{{text}}</li>',
        // 'file' => '<input type="file" name="{{name}}"{{attrs}}>',
        // 'fieldset' => '<fieldset{{attrs}}>{{content}}</fieldset>',
        // 'formStart' => '<form{{attrs}}>',
        // 'formEnd' => '</form>',
        'formGroup' => '{{input}}{{label}}',
        // 'hiddenBlock' => '<div style="display:none;">{{content}}</div>',
        'control' => '<input type="{{type}}" name="{{name}}"{{attrs}}/>',
        // 'input' => '<input type="{{type}}" name="{{name}}"{{attrs}}/>',
        // 'inputSubmit' => '<input type="{{type}}"{{attrs}}/>',
        'inputContainer' => '<div class="form-label-group input {{type}}{{required}}">{{content}}</div>',
        'inputContainerError' => '<div class="form-label-group input {{type}}{{required}} validated">{{content}}{{error}}</div>',
        'label' => '<label class="form-control-label" {{attrs}}>{{text}}</label>',
        // 'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
        // 'legend' => '<legend>{{text}}</legend>',
        // 'multicheckboxTitle' => '<legend>{{text}}</legend>',
        // 'multicheckboxWrapper' => '<fieldset{{attrs}}>{{content}}</fieldset>',
        // 'option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>',
        // 'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
        // 'select' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',
        // 'selectMultiple' => '<select name="{{name}}[]" multiple="multiple"{{attrs}}>{{content}}</select>',
        'radio' => '<input type="radio" class="form-check-input" name="{{name}}" value="{{value}}"{{attrs}}>',
        'radioWrapper' => '<div class="form-check">{{label}}</div>',
        // 'textarea' => '<textarea name="{{name}}"{{attrs}}>{{value}}</textarea>',
        // 'submitContainer' => '<div class="box-footer {{required}}">{{content}}</div>',
        'checkbox' => '<input type="checkbox" class="form-check-input" name="{{name}}" value="{{value}}"{{attrs}}>',
        'checkboxLabel' => '<label class="form-check-label" {{attrs}}>{{text}}</label>',
        'checkboxContainer' => '<div class="form-check">{{content}}</div>',
    ];

    public function __construct(View $View, array $config = [])
    {
        $this->_defaultConfig['templates'] = array_merge($this->_defaultConfig['templates'], $this->templates);
        
        $this->_defaultConfig['errorClass'] = 'is-invalid';
        parent::__construct($View, $config);
    }

    public function create($context = null, array $options = [])
    {
        $options += ['role' => 'form'];
        return parent::create($context, $options);
    }

    public function button($title, array $options = array())
    {
        $options += ['escape' => false, 'secure' => false, 'class' => 'btn btn-primary'];
        $options['text'] = $title;
        return $this->widget('button', $options);
    }

    public function submit($caption = null, array $options = array())
    {
        $options += ['class' => 'btn btn-primary'];
        return parent::submit($caption, $options);
    }

    /**
     * 
     * {@inheritDoc}
     * @see \Cake\View\Helper\FormHelper::input()
     * @deprecated 1.1.1 Use FormHelper::control() instead, due to \Cake\View\Helper\FormHelper::input() deprecation 
     */
    public function input($fieldName, array $options = [])
    {

        return $this->control($fieldName, $options);
    }
	public function control($fieldName, array $options = [])
	{

        $_options = [];
        $options = $this->_parseOptions($fieldName, $options);

		switch($options['type']) {
			case 'checkbox':
			case 'radio':
            case 'date':
				break;
			default:
				$_options = ['class' => 'form-control'];
				break;

		}

		$options += $_options;

		return parent::control($fieldName, $options);
    }
}