<?php

namespace MvaCrud\View\Helper;

use Zend\View\Helper\AbstractHelper;

class RenderDefaultElement extends AbstractHelper {

    public function __invoke($element) {

        if ( $this->view->formElementErrors($element) ) {
            $s_classError = ' error';
        }
        else {
            $s_classError = '';
        }

        $out = '<div class="control-group'.$s_classError.'">'."\n";
        // Print label
        $out .= "\t".'<label class="control-label">'.$element->getLabel().'</label>'."\n";
        $out .= "\t".'<div class="controls">'.$this->view->formElement($element);
        // Print form errors
        if ( $this->view->formElementErrors($element) ) {
            $out .= $this->view->formElementErrors()
                ->setMessageOpenFormat("\t".'<span class="help-inline">')
                ->setMessageSeparatorString('<br>')
                ->setMessageCloseString('</span>'."\n")
                ->render($element);
        }
        // Print tooltip options
        if ( count( $element->getOptions() ) ){
            $out .= '<span class="help-block">';
            $out .= $element->getOption('tooltip');
            $out .= '</span>';
        }
        $out .= '</div>'."\n";
        $out .= '</div>'."\n";
        return $out;
    }
}