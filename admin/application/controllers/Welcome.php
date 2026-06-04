<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }

    public function index()
    {
        $this->load->library("form_validation");
        $this->form_validation->set_rules("username", "Username", "required");
        $this->form_validation->set_rules("password", "Password", "required");

        if ($this->form_validation->run() == false) {
            $this->load->view('login');
            return;
        }

        $username = trim($this->input->post("username"));
        $password = trim($this->input->post("password"));

        $this->db->select('ug.*, um.*, cus.customer_id, c.name as company, um.um_company_id as company_id');
        $this->db->from("user_master as um");
        $this->db->join("user_group as ug", "um.um_user_group_id = ug.ug_id", "left");
        $this->db->join("company as c", "um.um_company_id = c.company_id", "left");
        $this->db->join("customer as cus", "um.um_customer_id = cus.customer_id", "left");
        $this->db->where("um.um_username", $username);
        $this->db->where("um.um_password", md5($password)); // using md5 for legacy compatibility
        $this->db->where("um.um_status", 1); // ✅ ensure only active users can login
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();

            // ✅ Normalize and check for Super Admin
            $group_name = strtolower(trim($row->ug_name));
            $isSuperAdmin = ($group_name === 'super admin');

            $sess = array(
                "storage"       => 1,
                "storage_id"    => 1,
                "company"       => $row->company,
                "company_id"    => $row->company_id,
                "customer_id"   => $row->customer_id,
                "user_id"       => $row->um_id,
                "user_group_id" => $row->ug_id,
                "user_group"    => $row->ug_name,
                "is_customer"   => !$isSuperAdmin,
                "is_admin"      => $isSuperAdmin,  // ✅ Always TRUE for Super Admin
                "fin_year_id"   => 1,
                "number"        => $row->um_username,
                "username"      => $row->um_username,
                "mobile"        => $row->mobile_number ?? '',
                "email"         => $row->um_username,
                "mdb"           => 'bluestoneocs',
                "tdb"           => 'bluestoneocs',
                "logged_in"     => true
            );

            // --- Load group rights and save into session so permissions reflect immediately ---
            // Master_model::getUserAuthentication($group_id) returns rights for the group
            $this->load->model('Master_model', 'master', true);
            $group_id = (int)$row->ug_id;
            $group_rights = [];
            if (method_exists($this->master, 'getUserAuthentication')) {
                $group_rights = $this->master->getUserAuthentication($group_id);
            } else {
                // fallback: attempt to fetch from user_rights table manually
                $this->db->select('ur_menu_master_id, ur_view, ur_add, ur_edit, ur_status');
                $this->db->from('user_rights');
                $this->db->where('ur_user_group_id', $group_id);
                $rights_rows = $this->db->get()->result();
                // index by menu id for session convenience
                foreach ($rights_rows as $r) {
                    $menuId = (int)$r->ur_menu_master_id;
                    $group_rights[$menuId] = [
                        'view' => (int)$r->ur_view,
                        'add'  => (int)$r->ur_add,
                        'edit' => (int)$r->ur_edit,
                        'status' => (int)$r->ur_status
                    ];
                }
            }

            // attach rights into company1 session array
            $sess['user_rights'] = $group_rights;

            // ✅ Save session
            $this->session->set_userdata('company1', $sess);

            // ✅ Optional: write debug info
            // log_message('debug', 'Login Session: ' . print_r($sess, true));

            redirect('Dashboard');
        } else {
            $this->session->set_flashdata('error', 'Invalid username or password.');
            redirect('');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('company1');
        $this->session->sess_destroy();
        redirect(base_url());
    }

    public function exitLogout()
    {
        $this->session->unset_userdata('company1');
        $this->session->sess_destroy();
        redirect('https://www.google.com');
    }

    public function home()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/side_menu');
        $this->load->view('dashboard');
        $this->load->view('templates/footer');
    }
}
