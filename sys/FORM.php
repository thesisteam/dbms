<?php
/**
 * Renders bootstrap-oriented forms
 */
final class FORM {
    
    /**
     * 
     * @param String $name
     * @param String $method
     * @param String $action
     * @param String $class The class for this form's parent container
     * @param String $align Alignment of the form
     */
    public function __construct($name, $method, $action, $class, $align="left") {
        /*
        do {
            echo strtolower(str_replace(" ", "", key($options))) . '="' . current($options) . '" ';
        } while (next($options));
        echo '>' . $label . '</label>';
        if ($this->is_bootstrap) echo '</div>';
         */
        echo '<div class="container-fluid '.$class.'" align="'.$align.'">';
        echo '<form name="'.$name.'" method="'.$method.'" action="' . $action . '" class="form" role="form">';
    }
    
    /**
     * 
     * @param String $label Label field text
     * @param String $name Input name
     * @param String $type Input type
     * @param Array $options Input's additional HTML properties
     * @param String $scheme Input scheme (Default or Required)
     * @param String $tooltip Hint text for this field
     * @param String $input_class Input's additional CSS class
     */
    public function AddInput($label, $name, $type, $options=array(), $scheme="DEFAULT", $tooltip=null, $input_class = "") {
        $input_id = strtolower(str_replace(" ", "", $name));
        $scheme = strtoupper($scheme);
        $label_class = ($scheme==="REQUIRED" ? "label-primary" : "label-default");
        echo '<label class="label ' . $label_class . '" for="'.$input_id.'">' . $label . '</label>';
        if ($tooltip !== null) {
            echo '<label class="label label-warning">'.$tooltip.'</label>';
        }
        echo '<input type="' . $type . '" class="form-control ' . $input_class . '" id="' . $input_id . '" ';
        do {
            echo strtolower(str_replace(" ", "", key($options))) . '="' . current($options) . '" ';
        } while (next($options));
        echo '><br>';
    }
    
    public function AddHidden($name, $value) {
        echo '<input type="hidden" name="'.$name.'" value="' . $value . '">';
    }
    
    public function RenderSubmitButton($caption) {
        echo ' <input type="submit" value="' . $caption . '" class="btn btn-primary btn-sm"> ';
    }
    
    public function RenderCancelButton($caption) {
        echo ' <input type="button" value="'  . $caption . '" class="btn btn-warning btn-sm"> ';
    }
    
    public function EndForm() {
        echo '</form></div>';
    }
    
}
