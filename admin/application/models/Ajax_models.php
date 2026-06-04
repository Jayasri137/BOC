<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_models extends CI_Model
{
     public function __construct()
    {
        parent::__construct();
		//$this->tdb=$this->sessionData['tdb'];
    }
	
	// GET AJAX TODO 
	public function get_db($data)
	{
		$db = array(
			'dsn'	=> '',
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '',
			'database' => 'goldce_ahz',
			'dbdriver' => 'mysqli',
			'dbprefix' => '',
			'pconnect' => FALSE,
			'db_debug' => (ENVIRONMENT !== 'production'),
			'cache_on' => TRUE,
			'cachedir' => 'application/cache/db',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'encrypt' => FALSE,
			'compress' => FALSE,
			'stricton' => FALSE,
			'failover' => array(),
			'save_queries' => TRUE
		);
		$db=$this->load->database($db,true);
		return $db;
	}
	
	
}