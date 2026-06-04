<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
    public $mdb='';
    public $tdb='';
    public $emp_id='';
    public $branch_id='';
    public $user_group_id='';
    public $storage_id='';
    public $company_id='';
    public $user_id='';
    public $sessionArray;
    public $sessionData;
    
    function __construct() {
        parent::__construct();
        if($this->session->userdata("company1")=='')
        {
            redirect(base_url());
        }
        ini_set('max_input_vars','10000' );
        $this->sessionData=$this->session->userdata("company1");
        $this->mdb=$this->sessionData['mdb'];
        $this->tdb=$this->sessionData['tdb'];
        $this->trdb=$this->load->database('tdb',true);
        $this->company_id=$this->sessionData['company_id'];
        $this->storage_id=$this->sessionData['storage_id'];
        $this->user_group_id=$this->sessionData['user_group_id'];
        $this->user_id=$this->sessionData['user_id'];
        $this->fin_year_id=$this->sessionData['fin_year_id'];
        
        $this->load->model('Master_model','master');
        $this->sessionArray=$this->Master_model->getUserAuthentication();
        $this->acc_id=$this->master->get_account($this->branch_id);
    }

    public function index()
    {
        $data['parent_level']="master/home";
        $data['mainMenu']="transaction";
        $data['title']="Master Menu List";
        $data['session'] = $this->sessionData;
        $this->db->where('status', 0);
        $activeCompanies = $this->db->get('company')->result();
        
        $data['activeCompanies'] = $activeCompanies;
        $this->load->view('templates/header',$data);
        $this->load->view('templates/dashboards');
        $this->load->view('templates/footer');
    }




}
?>