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
     * Render an input element
     * @param String $label Label field text
     * @param String $name Input name
     * @param String $type Input type
     * @param Array $options Input's additional HTML properties
     * @param String $scheme Input scheme (Default or Required)
     * @param String $tooltip Hint text for this field
     * @param Boolean $is_fullwidth Set if this element's width should be 100%
     * @param String $input_class Input's additional CSS class
     */
    public function AddInput($label, $name, $type, $options=array(), $scheme="DEFAULT", $tooltip=null, $is_fullwidth=false, $input_class = "") {
        # <input> ID, SCHEME, <label> initializations
        $input_id = strtolower(str_replace(" ", "", $name));
        $scheme = strtoupper($scheme);
        $label_class = ($scheme==="REQUIRED" ? "label-primary" : "label-default");
        
        # <label> printing
        echo '<label class="label ' . $label_class . '" for="'.$input_id.'">' . $label . '</label>';
        if ($tooltip !== null || strlen($tooltip) > 0) {
            echo '<label class="label label-warning">'.$tooltip.'</label>';
        }
        # <input> printing
        echo '<input type="' . $type . '" name="' . $name . '" class="form-control' . (!$is_fullwidth ? '-free ':' ') . $input_class . '" id="' . $input_id . '" ';
        # -- check for existing POST data and set value if exist
        if(Index::__HasPostData($name) /* Check if password field */ && trim(strtolower($type))!='password') {
            echo 'value="' . DATA::__getPOST($name, false, true) . '" ';
        }
        # -- check for optional attributes ($options) and add each if exist
        do {
            echo strtolower(str_replace(" ", "", key($options))) . '="' . current($options) . '" ';
        } while (next($options));
        echo '><br>';
    }
    
    /**
     * Adds a dropdown HTML element
     * @param String $label
     * @param String  $name
     * @param Array[][] $choices
     * @param Array[][] $options
     * @param String $scheme
     * @param String $tooltip
     * @param Boolean $is_fullwidth
     * @param type $input_class
     */
    public function AddDropdown($label, $name, $choices=array(), $options=array(), $scheme="DEFAULT", $tooltip=null, $is_fullwidth=false, $input_class=null) {
        $input_id = strtolower(str_replace(" ", "", $name));
        $scheme = strtoupper($scheme);
        $label_class = ($scheme==="REQUIRED" ? "label-primary" : "label-default");
        
        echo '<label class="label ' . $label_class . '" for="'.$input_id.'">' . $label . '</label>';
        if ($tooltip !== null || strlen($tooltip) > 0) {
            echo '<label class="label label-warning">'.$tooltip.'</label>';
        }
        echo '<select name="' . $name . '" class="form-control' . (!$is_fullwidth ? '-free ':' ') . $input_class . '" id="' . $input_id . '" ';
        // Check and apply `$options` array parameter
        if (count($options) > 0) {
            do {
                echo strtolower(str_replace(" ", "", key($options))) . '="' . current($options) . '" ';
            } while (next($options));
        }
        echo '>';
        
        // Check and apply `$choices` array parameter
        //  for this dropdown element
        do {
            echo '<option value="' . current($choices) . '" '
                    . (Index::__HasPostData($name) ? 
                        ( DATA::__getPOST($name) == current($choices) ? 'selected' : '' 
                            ) : '') 
                    . '>' . key($choices) . '</option>';
        } while(next($choices));
        echo '</select><br>';
        
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
