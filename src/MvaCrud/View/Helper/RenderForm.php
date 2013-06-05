<?php

namespace MvaCrud\View\Helper;

use Zend\View\Helper\AbstractHelper;

class RenderForm extends AbstractHelper {
    
    private $s_pre;
    private $s_post;

    public function __invoke($I_form,$s_title) {
        
        $this->setPre($s_title);
        $this->setPost();
        
        $out = $this->s_pre;
        
       $I_form->setAttribute('class', 'form-horizontal bordered');
        $I_form->prepare();
        
        $submit = '<div class="form-actions">';
        $out .= '<div class="portlet-body form">';

        $out .= $this->view->form()->openTag($I_form)."\n";
        $elements = $I_form->getElements();
        foreach ($elements as $element) {
            if ( $this->view->formElementErrors($element) ) {
                $s_classError = ' error';
            }
            else {
                $s_classError = '';
            }
            
            // Stampo gli elementi dopo averne analizzato il tipo
            switch ( get_class($element) ){
                case 'Zend\Form\Element\Hidden':
                    $out .= "\t".$this->view->formElement($element)."\n";
                    break;
                case 'Zend\Form\Element\Submit':
                case 'Zend\Form\Element\Button':
                    $submit .= '<button type="submit" class="btn red"><i class="icon-ok"></i> '.$element->getLabel().'</button>';
                    break;
                case 'Zend\Form\Element\MultiCheckbox':
                    $element->setLabelAttributes(array('class'=>'checkbox-line'));
                default:
                    $out .= $this->view->RenderDefaultElement($element);
            }
        }
        $submit .= '</div>';
        $out .= $submit;
        $out .= $this->view->form()->closeTag($I_form)."\n";
        $out .= '</div>';
        
        
        $out .= $this->s_post;
        return $out;
    }
    
    /**
     * Set form title and html code to prepend to form
     * @param type $s_title
     */
    private function setPre($s_title){
        $this->s_pre = <<<EOS
        <div class="row-fluid">
            <div class="span12">
                <div class="portlet box grey">
                    <div class="portlet-title">
                       <h4>
                          <i class="icon-reorder hidden-320"></i>
                            <span class="hidden-480">
EOS;
        $this->s_pre .= $s_title;
        $this->s_pre .= <<< EOS
                          </span>
                       </h4>
                    </div>
EOS;
    }
    
    /**
     * Html code to appendo to form
     */
    private function setPost(){
        $this->s_post = <<<EOS
                </div>
            </div>
        </div>
EOS;
    }

    
}