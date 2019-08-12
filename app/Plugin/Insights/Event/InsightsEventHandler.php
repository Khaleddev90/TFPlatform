<?php
/**
 * TJ Platform
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    TJPlatform
 * @subpackage Core
 * @author     Nicolas <nicodele8@gmail.com>
 * @copyright  2018 Nicolas 
 * 
 * 
 */
class InsightsEventHandler extends Object implements CakeEventListener
{
    /**
     * implementedEvents
     *
     * @return array
     */
    public function implementedEvents() 
    {
        return array(
            'View.UserProfile.additionalFields' => array(
                'callable' => 'onUserProfileEdit',
            )
        );
    }
    public function onUserProfileEdit($event) 
    {
        $obj = $event->subject();
        App::import('Model', 'Insights.Education');
        $this->Education = new Education();
        $Educations = $this->Education->find('list', array(
            'conditions' => array(
                'Education.is_active' => 1
            ) ,
            'fields' => array(
                'Education.education'
            ) ,
            'recursive' => -1
        ));
        App::import('Model', 'Insights.Employment');
        $this->Employment = new Employment();
        $Employments = $this->Employment->find('list', array(
            'conditions' => array(
                'Employment.is_active' => 1
            ) ,
            'fields' => array(
                'Employment.employment'
            ) ,
            'recursive' => -1
        ));
        App::import('Model', 'Insights.IncomeRange');
        $this->IncomeRange = new IncomeRange();
        $Incomeranges = $this->IncomeRange->find('list', array(
            'conditions' => array(
                'IncomeRange.is_active' => 1
            ) ,
            'fields' => array(
                'IncomeRange.income'
            ) ,
            'recursive' => -1
        ));
        App::import('Model', 'Insights.Relationship');
        $this->Relationship = new Relationship();
        $Relationships = $this->Relationship->find('list', array(
            'conditions' => array(
                'Relationship.is_active' => 1
            ) ,
            'fields' => array(
                'Relationship.relationship'
            ) ,
            'recursive' => -1
        ));
        $return = '<fieldset  class="form-block"><legend>' . __l('Demographics') . '</legend><div class="clearfix user-profile-block">';
        $return.= $obj->Form->input('UserProfile.education_id', array(
            'empty' => __l('Please Select') ,
            'options' => $Educations,
            'label' => __l('Education')
        ));
        $return.= $obj->Form->input('UserProfile.employment_id', array(
            'empty' => __l('Please Select') ,
            'options' => $Employments,
            'label' => __l('Employment Status')
        ));
        $return.= $obj->Form->input('UserProfile.income_range_id', array(
            'empty' => __l('Please Select') ,
            'options' => $Incomeranges,
            'label' => sprintf(__l('Income range (%s)') , Configure::read('site.currency'))
        ));
        $return.= $obj->Form->input('UserProfile.relationship_id', array(
            'empty' => __l('Please Select') ,
            'options' => $Relationships,
            'label' => __l('Relationship status')
        ));
        $return.= '</div></fieldset>';
        $event->data['content'] = $return;
    }
}
