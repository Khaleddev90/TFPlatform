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
class GoogleAnalytic extends AppModel
{
    public $useTable = false;
    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
		$this->filterOptions = array(
            ConstFilterOptions::Loggedin => __l('Loggedin Users') ,
            ConstFilterOptions::Refferred => __l('Refferred Users'),
			ConstFilterOptions::Favorited => __l('Favorited Users') ,
			ConstFilterOptions::Voted => __l('Voted Users') ,
			ConstFilterOptions::Commented => __l('Commented Users') ,
			ConstFilterOptions::Booked => __l('Booked Amount Value') ,
			ConstFilterOptions::JobPosted => sprintf(__l('%s Posted Amount Value'), jobAlternateName(ConstJobAlternateName::Singular,ConstJobAlternateName::FirstLeterCaps))
        );
    }
}
?>