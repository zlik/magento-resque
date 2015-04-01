<?php

require __DIR__ . '/../../app/Mage.php';

class Job
{
    /**
     * @var Mage_Core_Model_Resource_Setup
     */
    public $db;

    public function __construct()
    {
        Mage::app('admin', 'store');
        $this->db = new Mage_Core_Model_Resource_Setup('core_setup');
    }

    public function perform()
    {
        $this->db->run($this->args['q']);
    }
}
