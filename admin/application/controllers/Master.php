<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{
    public $sessionArray;
    public $sessionData;
    public $perPage;

    public function __construct()
    {
        parent::__construct();

       
        $this->sessionData = $this->session->userdata("company1");
        $this->load->model('Master_model', 'master', true);
        $this->load->library('Ajax_pagination');

 
        $user_group_id = null;
        if (is_array($this->sessionData) && isset($this->sessionData['user_group_id'])) {
            $user_group_id = $this->sessionData['user_group_id'];
        }

     
        $this->sessionArray = $this->master->getUserAuthentication($user_group_id);

       
        if (isset($this->sessionData['is_admin']) && $this->sessionData['is_admin'] == true) {
            $this->sessionArray['is_admin'] = true;
            $menus = $this->db->get('menu_master')->result();
            foreach ($menus as $menu) {
                $obj = new stdClass();
                $obj->ur_view   = 1;
                $obj->ur_add    = 1;
                $obj->ur_edit   = 1;
                $obj->ur_delete = 1;
                $obj->ur_status = 1;
                $this->sessionArray[$menu->mm_session_number] = $obj;
            }
        }

        $this->perPage = 2;
    }

    public function index()
    {
        $this->load->view('login');
    }

    /* -------------------------- Helpers -------------------- */

    /**
     * Return mm_session_number for a menu by name (or null).
     * This avoids hardcoding session numbers in controllers/views.
     */
    private function getMenuSessionNumberByName($menuName)
    {
        if (!$menuName) return null;
        $row = $this->db->select('mm_session_number')->from('menu_master')->where('mm_name', $menuName)->limit(1)->get()->row();
        return $row ? $row->mm_session_number : null;
    }

    private function hasPermission($sessionNumber, $action = 'view')
    {
        if ($this->isSuperAdmin()) return true;
        if (empty($sessionNumber)) return false;
        if (!isset($this->sessionArray[$sessionNumber])) return false;

        $perm = $this->sessionArray[$sessionNumber];
        switch (strtolower($action)) {
            case 'add': return isset($perm->ur_add) && $perm->ur_add == 1;
            case 'edit': return isset($perm->ur_edit) && $perm->ur_edit == 1;
            case 'delete': return isset($perm->ur_delete) && $perm->ur_delete == 1;
            default: return isset($perm->ur_view) && $perm->ur_view == 1;
        }
    }

    private function isSuperAdmin()
    {
        return isset($this->sessionArray['is_admin']) && $this->sessionArray['is_admin'] == true;
    }

    /* --------------------------Grid System-------------------- */
    public function home()
    {
        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Master Menu List";
        $this->load->view('templates/header', $data);

        $this->load->view('master/menu');
        $this->load->view('templates/footer');
    }

    /* -------------------------- Company -------------------- */
    public function company()
    {
        $this->db->where('status', 0);
        $activeCompanies = $this->db->get('company')->result();

        $data['activeCompanies'] = $activeCompanies;
        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Master Menu List";
        $this->load->view('templates/header', $data);

        $this->load->view('master/company');
        $this->load->view('templates/footer');
    }

    public function companyadd()
    {
        $menuSession = $this->getMenuSessionNumberByName('Company'); // adjust name if different
        if (!$this->hasPermission($menuSession, 'add')) {
            redirect('master/home?msg=no_permission');
        }

        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Master Menu List";
        $this->load->view('templates/header', $data);

        $this->load->library("form_validation");
        $this->form_validation->set_rules("company", "Name", "required");
        if ($this->form_validation->run() == false) {
            $this->load->view('master/companyadd');
        } else {
            $result = $this->master->insert_company();
            if ($result) {
                redirect("master/company?success=1");
            }
        }
        $this->load->view('templates/footer');
    }

    public function profile()
    {
        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Company Profile";

        $companyId = 1;
        $data['profile'] = $this->master->get_company_user_profile($companyId);

        $this->load->view('templates/header', $data);
        $this->load->view('profile/profile', $data);
        $this->load->view('templates/footer');
    }

    public function update_profile()
    {
        $companyId = 1;
        $result = $this->master->update_company_user_profile($companyId);

        switch ($result) {
            case 1:
                $this->session->set_flashdata('success', 'Profile updated successfully.');
                break;
            case 2:
                $this->session->set_flashdata('warning', 'Company name already exists.');
                break;
            case 3:
                $this->session->set_flashdata('error', 'Passwords do not match.');
                break;
            default:
                $this->session->set_flashdata('error', 'Database update failed. Please try again.');
                break;
        }
        redirect('master/profile');
    }

    /**
     * Backwards-compatible alias for older route 'master/user'
     * Delegates to usermaster() to keep logic centralized and avoid duplication.
     */
    public function user()
    {
        return $this->usermaster();
    }

    /* -------------------------- User Group -------------------- */
    public function usergroup()
    {
        // $menuSession = $this->getMenuSessionNumberByName('User Group'); // adjust if mm_name differs
        // if (!$this->hasPermission($menuSession, 'view')) {
        //     redirect('master/home?msg=no_permission');
        //     return;
        // }

        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "User Group";
        $data['session'] = $this->sessionData;

        $this->load->view('templates/header', $data);
        $this->load->view('user_master/usergroup', $data);
        $this->load->view('templates/footer');
    }

    /**
     * AJAX JSON for DataTables
     */
    public function usergroupjson()
    {
        $fetch_data = $this->usergroupquery();

        $data = array();
        $sno = 1;
        foreach ($fetch_data->result() as $row) {
            $sub_array = array();
            $sub_array[] = $sno++;
            $sub_array[] = $row->name;
            $sub_array[] = $row->status;
            $sub_array[] = '<a href="' . base_url() . 'master/usergroupedit/' . $row->id . '" class="btn btn-secondary edit-icon"><i class="mdi mdi-pencil-outline"></i></a>';
            $data[] = $sub_array;
        }
        $output = array(
            "draw" => intval($_POST['draw'] ?? 0),
            "recordsTotal" => $this->usergroupquery(1),
            "recordsFiltered" => $this->usergroupquery(1),
            "data" => $data
        );
        echo json_encode($output);
    }

    /**
     * Data query for user groups (used by datatable)
     */
    public function usergroupquery($id = 0)
    {
        $table = "user_group as ug";
        $select_column = array(
            'id',
            'name',
            'short_name'
        );
        $order_column = array(
            null,
            'name',
            'status',
            ''
        );

        $this->db->select("ug.ug_id as id,ug.ug_name as name,case when ug.ug_status=1 then 'Active' else 'InActive' END as status");
        $this->db->from($table);
        $this->db->group_by('ug_id');
        if (isset($_POST['search']['value']) && $_POST['search']['value'] !== '') {
            $this->db->having("name like '%" . $this->db->escape_like_str($_POST['search']['value']) . "%'");
            $this->db->or_having("status like '%" . $this->db->escape_like_str($_POST['search']['value']) . "%'");
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by("id", "DESC");
        }

        if ($id == 0) {
            if (isset($_POST['length']) && $_POST['length'] != -1) {
                $this->db->limit($_POST['length'], $_POST['start'] ?? 0);
            }
            return $this->db->get();
        } else {
            return $this->db->count_all_results();
        }
    }

    /**
     * Add user group (Super Admin bypass)
     */
    public function usergroupadd()
    {
        // $menuSession = $this->getMenuSessionNumberByName('User Group');
        // if (!$this->hasPermission($menuSession, 'add')) {
        //     redirect('master/home?msg=no_permission');
        //     return;
        // }

        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Master Menu List";
        $this->load->view('templates/header', $data);

        $this->load->library("form_validation");
        $this->form_validation->set_rules("name", "Name", "required");
        if ($this->form_validation->run() == false) {
            $this->load->view('user_master/usergroupadd');
        } else {
            $result = $this->master->usergroupadd();
            if ($result) {
                redirect("master/usergroup?success=1");
            } else {
                // handle duplicate or failure (same as your model returns)
                $this->session->set_flashdata('error', 'Unable to add user group.');
                $this->load->view('user_master/usergroupadd', $data);
            }
        }

        $this->load->view('templates/footer');
    }

    /**
     * Edit user group (Super Admin bypass)
     */
    public function usergroupedit($id)
    {
        // $menuSession = $this->getMenuSessionNumberByName('User Group');
        // if (!$this->hasPermission($menuSession, 'edit')) {
        //     redirect('master/home?msg=no_permission');
        //     return;
        // }

        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['id'] = "$id";
        $data['title'] = "Master Menu List";
        $this->load->view('templates/header', $data);

        $this->load->library("form_validation");
        $this->form_validation->set_rules("name", "Name", "required");
        if ($this->form_validation->run() == false) {
            $this->load->view('user_master/usergroupedit');
        } else {
            $result = $this->master->usergroupedit($id);
            if ($result) {
                redirect("master/usergroup?success=1");
            } else {
                $this->session->set_flashdata('error', 'Unable to update user group.');
                $this->load->view('user_master/usergroupedit', $data);
            }
        }

        $this->load->view('templates/footer');
    }

    /**
     * Delete a user group - removes related rights and clears users' group assignment.
     * Performs a transactional delete. Protected groups (Super Admin / Admin) are prevented.
     */
    public function usergroupdelete($id = null)
    {
        // $menuSession = $this->getMenuSessionNumberByName('User Group');
        // if (!$this->hasPermission($menuSession, 'delete')) {
        //     $this->session->set_flashdata('error', 'You do not have permission to delete user groups.');
        //     redirect('master/usergroup');
        //     return;
        // }

        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid user group id.');
            redirect('master/usergroup');
            return;
        }
        $groupId = (int)$id;

        $groupRow = $this->db->get_where('user_group', ['ug_id' => $groupId])->row();
        if (!$groupRow) {
            $this->session->set_flashdata('error', 'User group not found.');
            redirect('master/usergroup');
            return;
        }

        // Protect essential groups (adjust names if needed)
        $protectedNames = ['Super Admin', 'Admin'];
        if (in_array(trim($groupRow->ug_name), $protectedNames)) {
            $this->session->set_flashdata('error', 'This user group cannot be deleted.');
            redirect('master/usergroup');
            return;
        }

        $this->db->trans_start();
            // delete user_rights for this group
            $this->db->where('ur_user_group_id', $groupId)->delete('user_rights');

            // detach group from users (set to NULL)
            $this->db->where('um_user_group_id', $groupId)->update('user_master', ['um_user_group_id' => null]);

            // delete the group row
            $this->db->where('ug_id', $groupId)->delete('user_group');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $dbErr = $this->db->error();
            $msg = 'Failed to delete user group. DB error: ' . (isset($dbErr['message']) ? $dbErr['message'] : 'unknown');
            log_message('error', '[usergroupdelete] ' . $msg);
            $this->session->set_flashdata('error', 'Failed to delete user group. See logs for details.');
        } else {
            $this->session->set_flashdata('success', 'User group deleted successfully.');
        }

        redirect('master/usergroup');
    }

    /* -------------------------- User Master -------------------- */
    public function usermaster()
    {
        $menuSession = $this->getMenuSessionNumberByName('User Master'); // adjust name if different
        if (!$this->hasPermission($menuSession, 'view')) {
            redirect('master/home?msg=');
        }

        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Master Menu List";
        $data['session'] = $this->sessionData;
        $this->load->view('templates/header', $data);

        $this->load->view('user_master/usermaster');
        $this->load->view('templates/footer');
    }

    public function usermasterjson()
    {
        $fetch_data = $this->usermasterquery();

        $data = array();
        $sno = 1;
        foreach ($fetch_data->result() as $row) {
            $sub_array = array();
            $sub_array[] = $sno++;
            $sub_array[] = $row->name;
            $sub_array[] = $row->employee;
            $sub_array[] = $row->usergroup;
            $sub_array[] = $row->branch;
            $sub_array[] = $row->status;
            $sub_array[] = '<a href="' . base_url() . 'master/usermasteredit/' . $row->id . '" class="btn btn-secondary edit-icon"><i class="mdi mdi-pencil-outline"></i></a>';
            $data[] = $sub_array;
        }
        $output = array(
            "draw" => intval($_POST['draw'] ?? 0),
            "recordsTotal" => $this->usermasterquery(1),
            "recordsFiltered" => $this->usermasterquery(1),
            "data" => $data
        );
        echo json_encode($output);
    }

    public function usermasterquery($id = 0)
    {
        $table = "user_master as um";
        $select_column = array(
            'id',
            'name',
            'short_name'
        );
        $order_column = array(
            null,
            'name',
            'employee',
            'usergroup',
            'branch',
            'status',
            ''
        );

        $this->db->select("um.um_id as id,um.um_username as name,ug.ug_name as usergroup,e.name as employee,b.name as branch,case when um.um_status=1 then 'Active' else 'InActive' END as status");
        $this->db->from($table);
        $this->db->join("user_group as ug", "um.um_user_group_id=ug.ug_id", "left");
        $this->db->join("employee as e", "um.um_employee_id=e.id", "left");
        $this->db->join("branch as b", "e.branch_id=b.id", "left");
        $this->db->group_by("um_id");
        if (isset($_POST['search']['value']) && $_POST['search']['value'] !== '') {
            $this->db->having("name like '%" . $this->db->escape_like_str($_POST['search']['value']) . "%'");
            $this->db->or_having("employee like '%" . $this->db->escape_like_str($_POST['search']['value']) . "%'");
            $this->db->or_having("usergroup like '%" . $this->db->escape_like_str($_POST['search']['value']) . "%'");
            $this->db->or_having("branch like '%" . $this->db->escape_like_str($_POST['search']['value']) . "%'");
            $this->db->or_having("status like '%" . $this->db->escape_like_str($_POST['search']['value']) . "%'");
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by("um_username", "ASC");
        }

        if ($id == 0) {
            if (isset($_POST['length']) && $_POST['length'] != -1) {
                $this->db->limit($_POST['length'], $_POST['start'] ?? 0);
            }
            return $this->db->get();
        } else {
            return $this->db->count_all_results();
        }
    }

    public function usermasteradd()
    {
        $menuSession = $this->getMenuSessionNumberByName('User Master');
        if (!$this->hasPermission($menuSession, 'add')) {
            redirect('master/home?msg=');
        }

        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Create User";
        $data['session'] = $this->sessionData;

        // load header
        $this->load->view('templates/header', $data);

        // Handle POST
        $this->load->library("form_validation");
        $this->form_validation->set_rules("usergroup", "User Group", "required");
        $this->form_validation->set_rules("username", "Username", "required");
        $this->form_validation->set_rules("password1", "Password", "required");
        $this->form_validation->set_rules("password2", "Confirm Password", "required|matches[password1]");

        if ($this->form_validation->run() == FALSE) {
            // NOTE: load the view from user_master directory (correct path)
            $this->load->view('user_master/usermasteradd', $data);
        } else {
            // Use the model alias $this->master (loaded in constructor)
            $result = $this->master->usermasteradd();
            if ($result) {
                redirect("master/usermaster?success=1");
            } else {
                // fallback: reload form with error message
                $this->session->set_flashdata('error', 'Unable to create user. Username may already exist.');
                $this->load->view('user_master/usermasteradd', $data);
            }
        }

        // footer
        $this->load->view('templates/footer');
    }

    public function usermasteredit($id)
    {
        $menuSession = $this->getMenuSessionNumberByName('User Master');
        if (!$this->hasPermission($menuSession, 'edit')) {
            redirect('master/home?msg=');
        }

        $data['id'] = $id;
        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Edit User";
        $data['session'] = $this->sessionData;

        // load header
        $this->load->view('templates/header', $data);

        // Form handling
        $this->load->library("form_validation");
        $this->form_validation->set_rules("usergroup", "User Group", "required");
        $this->form_validation->set_rules("username", "Username", "required");
        // password fields optional on edit; only validate if present
        if ($this->input->post('password1') || $this->input->post('password2')) {
            $this->form_validation->set_rules("password1", "Password", "required");
            $this->form_validation->set_rules("password2", "Confirm Password", "required|matches[password1]");
        }

        if ($this->form_validation->run() == FALSE) {
            // load edit view (correct path)
            $this->load->view('user_master/usermasteredit', $data);
        } else {
            $result = $this->master->usermasteredit($id);
            if ($result) {
                redirect("master/usermaster?success=1");
            } else {
                $this->session->set_flashdata('error', 'Unable to update user. Username may already exist.');
                $this->load->view('user_master/usermasteredit', $data);
            }
        }

        // footer
        $this->load->view('templates/footer');
    }

    /**
     * Deactivate (soft-delete) user
     */
    public function usermasterdelete($id = null)
{
    $menuSession = $this->getMenuSessionNumberByName('User Master');
    if (!$this->hasPermission($menuSession, 'delete')) {
        $this->session->set_flashdata('error', 'You do not have permission to delete users.');
        redirect('master/usermaster');
        return;
    }

    if (empty($id) || !is_numeric($id)) {
        $this->session->set_flashdata('error', 'Invalid user ID.');
        redirect('master/usermaster');
        return;
    }

    // Get user details
    $user = $this->db->get_where('user_master', ['um_id' => $id])->row();
    if (!$user) {
        $this->session->set_flashdata('error', 'User not found.');
        redirect('master/usermaster');
        return;
    }

    // Check if this is the first user (lowest ID) - typically superadmin
    $firstUser = $this->db->select('um_id, um_username')
                         ->from('user_master')
                         ->order_by('um_id', 'asc')
                         ->limit(1)
                         ->get()
                         ->row();
    
    if ($firstUser && $firstUser->um_id == $id) {
        $this->session->set_flashdata('error', 'The primary system user cannot be deleted.');
        redirect('master/usermaster');
        return;
    }

    // Check if this is a system user (admin, superadmin, administrator)
    $username = strtolower(trim($user->um_username));
    if (in_array($username, ['admin', 'superadmin', 'administrator'])) {
        $this->session->set_flashdata('error', 'System users (admin, superadmin, administrator) cannot be deleted.');
        redirect('master/usermaster');
        return;
    }

    // Check if user is trying to delete themselves
    $session = $this->session->userdata('company1') ?? [];
    $currentUserId = $session['user_id'] ?? null;
    
    if ($currentUserId && $currentUserId == $id) {
        $this->session->set_flashdata('error', 'You cannot delete your own account.');
        redirect('master/usermaster');
        return;
    }

    // Check what columns exist in your user_master table
    $tableFields = $this->db->list_fields('user_master');
    
    // Prepare update data based on available columns
    $updateData = [
        'um_status' => 0
    ];
    
    // Add timestamp field if it exists (check common column names)
    if (in_array('updated_at', $tableFields)) {
        $updateData['updated_at'] = date('Y-m-d H:i:s');
    } elseif (in_array('modified_at', $tableFields)) {
        $updateData['modified_at'] = date('Y-m-d H:i:s');
    } elseif (in_array('um_updated_at', $tableFields)) {
        $updateData['um_updated_at'] = date('Y-m-d H:i:s');
    } elseif (in_array('um_modified_at', $tableFields)) {
        $updateData['um_modified_at'] = date('Y-m-d H:i:s');
    } elseif (in_array('last_modified', $tableFields)) {
        $updateData['last_modified'] = date('Y-m-d H:i:s');
    }
    
    $this->db->where('um_id', $id);
    $updated = $this->db->update('user_master', $updateData);

    if ($updated) {
        $this->session->set_flashdata('success', 'User has been deactivated successfully.');
        
        // Log the activity if activity_logs table exists
        if ($this->db->table_exists('activity_logs')) {
            $logData = [
                'log_user_id' => $currentUserId,
                'log_action' => 'delete',
                'log_table' => 'user_master',
                'log_record_id' => $id,
                'log_description' => 'Deactivated user: ' . $user->um_username,
                'log_ip' => $this->input->ip_address(),
                'log_date' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('activity_logs', $logData);
        }
        
    } else {
        $this->session->set_flashdata('error', 'Failed to deactivate user. Please try again.');
    }

    redirect(base_url('master/usermaster'));
}

    /* -------------------------- User Rights -------------------- */
    public function userrights($group_id = null)
    {
        $menuSession = $this->getMenuSessionNumberByName('User Rights'); // adjust mm_name
        if (!$this->hasPermission($menuSession, 'view')) {
            $this->session->set_flashdata('error', 'You do not have permission to access User Rights.');
            redirect('master/home');
            return;
        }

        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "User Rights";
        $data['session'] = $this->sessionData;
        $data['group_id'] = $group_id;

        // POST: save rights
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $posted_group_id = (int)$this->input->post('group_id');
            if (!$posted_group_id) {
                $this->session->set_flashdata('error','Invalid user group selected.');
                redirect('master/userrights');
                return;
            }

            // permission to add/edit rights
            if (!$this->hasPermission($menuSession, 'edit') && !$this->hasPermission($menuSession, 'add')) {
                $this->session->set_flashdata('error','You do not have permission to modify user rights.');
                redirect('master/userrights/' . $posted_group_id);
                return;
            }

            // your model method expects POST('items')
            $res = $this->master->userrightsedit($posted_group_id);
            if ($res) {
                // if superadmin, ensure that group gets full rights (optional)
                if ($this->isSuperAdmin()) {
                    $menus = $this->db->select('mm_id')->from('menu_master')->where('mm_status', 1)->get()->result();
                    foreach ($menus as $m) {
                        $menuId = (int)$m->mm_id;
                        $existing = $this->db->from('user_rights')
                                             ->where('ur_user_group_id', $posted_group_id)
                                             ->where('ur_menu_master_id', $menuId)
                                             ->limit(1)
                                             ->get()
                                             ->row();

                        $full = [
                            'ur_view' => 1,
                            'ur_add'  => 1,
                            'ur_edit' => 1,
                            'ur_status' => 1,
                        ];
                        if ($existing) {
                            $this->db->where('ur_id', $existing->ur_id)->update('user_rights', $full);
                        } else {
                            $ins = [
                                'ur_user_group_id'   => $posted_group_id,
                                'ur_menu_master_id'  => $menuId,
                                'ur_view'            => 1,
                                'ur_add'             => 1,
                                'ur_edit'            => 1,
                                'ur_status'          => 1,
                            ];
                            $this->db->insert('user_rights', $ins);
                        }
                    }
                }

                $this->session->set_flashdata('success','User rights saved successfully.');
            } else {
                $this->session->set_flashdata('error','Unable to save user rights.');
            }
            redirect('master/userrights/' . $posted_group_id);
            return;
        }

        // GET: load data
        $data['user_groups'] = $this->db->order_by('ug_name','ASC')->get_where('user_group', ['ug_status' => 1])->result();
        $this->db->order_by('mm_session_number','ASC');
        $data['menus'] = $this->db->get_where('menu_master', ['mm_status' => 1])->result();

        $data['user_rights'] = [];
        if ($group_id) {
            $rights_rows = $this->db->get_where('user_rights', ['ur_user_group_id' => (int)$group_id])->result();
            foreach ($rights_rows as $r) {
                $data['user_rights'][$r->ur_menu_master_id] = $r;
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('user_master/userrights', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Show edit form on GET (master/usersrightsedit/{group_id}) and save on POST.
     */
    public function usersrightsedit($group_id = null)
    {
        $this->load->model('Master_model', 'master', true);
        $sessionCompany = $this->session->userdata('company1');
        $isSuperAdminUser = (isset($sessionCompany['is_admin']) && $sessionCompany['is_admin'] == true)
                            || (isset($sessionCompany['user_group']) && strtolower($sessionCompany['user_group']) === 'super admin');

        // Handle POST -> save rights
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $posted_group = (int)$this->input->post('group_id');
            $group_id = $posted_group ? $posted_group : ($group_id ? (int)$group_id : 0);

            if ($group_id <= 0) {
                $this->session->set_flashdata('error', 'Please select a valid user group.');
                redirect('master/userrights');
                return;
            }

            // Permission guard
            $menuSession = $this->getMenuSessionNumberByName('User Rights');
            if (!$isSuperAdminUser && !$this->hasPermission($menuSession, 'edit')) {
                $this->session->set_flashdata('error', 'You do not have permission to modify user rights.');
                redirect('master/userrights/' . $group_id);
                return;
            }

            $items = $this->input->post('items');
            if (!is_array($items)) $items = [];

            // Convert posted items into the rights array expected by the model save_rights_for_group
            $rights = [];
            foreach ($items as $menuId => $actions) {
                $mid = isset($actions['menuId']) ? (int)$actions['menuId'] : (int)$menuId;
                if ($mid <= 0) continue;
                $rights[] = [
                    'mm_id'    => $mid,
                    'ur_view'  => isset($actions['view']) ? 1 : 0,
                    'ur_add'   => isset($actions['add'])  ? 1 : 0,
                    'ur_edit'  => isset($actions['edit']) ? 1 : 0,
                    'ur_delete'=> isset($actions['delete']) ? 1 : 0
                ];
            }

            // Save using model helper (transaction inside)
            $saved = $this->master->save_rights_for_group($group_id, $rights);

            if ($saved) {
                if (method_exists($this->master, 'clear_cached_rights_for_group')) {
                    $this->master->clear_cached_rights_for_group($group_id);
                }

                if (is_array($sessionCompany) && isset($sessionCompany['user_group_id']) && (int)$sessionCompany['user_group_id'] === (int)$group_id) {
                    $updatedAuth = $this->master->getUserAuthentication((int)$group_id);
                    $sessionCompany['user_rights'] = $updatedAuth;
                    $this->session->set_userdata('company1', $sessionCompany);
                }

                $this->session->set_flashdata('success', 'User rights saved successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to save user rights. Please try again.');
            }

            redirect('master/userrights/' . $group_id);
            return;
        }

        // === GET: show edit form ===
        $group_id = (int)$group_id;
        if ($group_id <= 0) {
            $this->session->set_flashdata('error', 'Invalid user group.');
            redirect('master/userrights');
            return;
        }

        // Permission: normal users must have view permission to see the edit page
        $authForCurrent = $this->master->getUserAuthentication($sessionCompany['user_group_id'] ?? null);
        $menuSession = $this->getMenuSessionNumberByName('User Rights');
        if (!$isSuperAdminUser && !$this->hasPermission($menuSession, 'view')) {
            $this->session->set_flashdata('error', 'You do not have permission to view user rights.');
            redirect('master/home');
            return;
        }

        // prepare data for view
        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Edit User Rights";
        $data['session'] = $this->session->userdata('company1');
        $data['group_id'] = $group_id;
        $data['user_groups'] = $this->db->order_by('ug_name', 'ASC')->get_where('user_group', ['ug_status' => 1])->result();
        $this->db->order_by('mm_session_number', 'ASC');
        $data['menus'] = $this->db->get_where('menu_master', ['mm_status' => 1])->result();

        // rights for this group indexed by mm_id
        $rights_rows = $this->db->get_where('user_rights', ['ur_user_group_id' => $group_id])->result();
        $data['user_rights'] = [];
        foreach ($rights_rows as $r) {
            $data['user_rights'][(int)$r->ur_menu_master_id] = $r;
        }

        $this->load->view('templates/header', $data);
        $this->load->view('user_master/usersrightsedit', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Delete user rights for a group or a specific menu
     * - $group_id required
     * - optional $menu_id deletes a single right; without it deletes all rights for the group
     */
    public function userrightsdelete($group_id = null, $menu_id = null)
    {
        $menuSession = $this->getMenuSessionNumberByName('User Rights');
        if (!$this->hasPermission($menuSession, 'delete')) {
            $this->session->set_flashdata('error', 'You do not have permission to delete user rights.');
            redirect('master/userrights');
            return;
        }

        $group_id = (int)$group_id;
        if ($group_id <= 0) {
            $this->session->set_flashdata('error', 'Invalid user group.');
            redirect('master/userrights');
            return;
        }

        if ($menu_id && is_numeric($menu_id)) {
            // delete specific right row
            $this->db->where('ur_user_group_id', $group_id)->where('ur_menu_master_id', (int)$menu_id)->delete('user_rights');
        } else {
            // delete all rights for the group
            $this->db->where('ur_user_group_id', $group_id)->delete('user_rights');
        }

        if ($this->db->affected_rows() >= 0) {
            $this->session->set_flashdata('success', 'User rights deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user rights.');
        }

        redirect('master/userrights/' . $group_id);
    }

    /* -------------------------- Role, Branch, Country, University, Intake Year -------------------- */

    public function role()
    {
        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Master Menu List";
        $this->load->view('templates/header', $data);

        $this->load->view('user_master/role_view');
        $this->load->view('templates/footer');
    }

    public function create_role()
    {
        $viewData['parent_level'] = "master/home";
        $viewData['mainMenu'] = "master";
        $viewData['title'] = "Master Menu List";

        if ($this->input->method(true) === 'POST') {
            $post = $this->input->post();

            if (empty(trim($post['role_name'] ?? ''))) {
                $this->session->set_flashdata('error', 'Role name is required');
                redirect(base_url('master/create_role'));
                return;
            }

            $insert_id = $this->master->insert_role($post);

            if ($insert_id) {
                $this->session->set_flashdata('success', 'Role created successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to create role');
            }

            redirect(base_url('master/role'));
            return;
        }

        $this->load->view('templates/header', $viewData);
        $this->load->view('user_master/role_create', $viewData);
        $this->load->view('templates/footer');
    }

    public function edit_role($id = null)
    {
        if (!$id) {
            $this->session->set_flashdata('error', 'Invalid role id.');
            redirect(base_url('master/role'));
        }

        $viewData['parent_level'] = "master/home";
        $viewData['mainMenu'] = "master";
        $viewData['title'] = "Edit Role";
        $viewData['role_id'] = $id;

        if ($this->input->method(true) === 'POST') {
            $post = $this->input->post();

            if (empty(trim($post['role_name'] ?? ''))) {
                $this->session->set_flashdata('error', 'Role name is required');
                redirect(base_url('master/edit_role/' . $id));
                return;
            }

            $this->db->where('role_name', $post['role_name']);
            $this->db->where('id !=', $id);
            $existing_role = $this->db->get('roles')->row();
            if ($existing_role) {
                $this->session->set_flashdata('error', 'Role name already exists');
                redirect(base_url('master/edit_role/' . $id));
                return;
            }

            $updateData = [
                'role_name'  => isset($post['role_name']) ? trim($post['role_name']) : '',
                'status'     => isset($post['status']) ? (int)$post['status'] : 1,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->db->where('id', $id);
            $updated = $this->db->update('roles', $updateData);

            if ($updated) {
                $this->session->set_flashdata('success', 'Role updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update role');
            }

            redirect(base_url('master/role'));
            return;
        }

        $this->db->where('id', $id);
        $role = $this->db->get('roles')->row();

        if (!$role) {
            $this->session->set_flashdata('error', 'Role not found.');
            redirect(base_url('master/role'));
        }

        $viewData['role'] = $role;

        $this->load->view('templates/header', $viewData);
        $this->load->view('user_master/role_edit', $viewData);
        $this->load->view('templates/footer');
    }

    public function delete_role($id = null)
    {
        if ($id && is_numeric($id)) {
            $this->db->where('id', $id)->delete('roles');
        }
        redirect(base_url('master/role'));
    }

    public function branch()
    {
        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Master Menu List";
        $this->load->view('templates/header', $data);

        $this->load->view('user_master/branch_view');
        $this->load->view('templates/footer');
    }

    // ---------- Branch CRUD with permission checks ----------
    public function create_branch()
    {
        $menuSession = $this->getMenuSessionNumberByName('Branch'); // adjust mm_name to actual
        if (!$this->hasPermission($menuSession, 'add')) {
            $this->session->set_flashdata('error', 'You do not have permission to add a branch.');
            redirect('master/branch');
            return;
        }

        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Master Menu List";
        $this->load->view('templates/header', $data);

        $this->load->view('user_master/branch_create');
        $this->load->view('templates/footer');
    }

    public function store_branch()
    {
        $menuSession = $this->getMenuSessionNumberByName('Branch'); // adjust mm_name to actual
        if (!$this->hasPermission($menuSession, 'add')) {
            $this->session->set_flashdata('error', 'You do not have permission to create a branch.');
            redirect('master/branch');
            return;
        }

        $this->form_validation->set_rules('name', 'Branch Name', 'required|trim');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

        if ($this->form_validation->run() == FALSE) {
            $this->create_branch();
        } else {
            $branch_data = [
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'status' => $this->input->post('status')
            ];

            $branch_id = $this->master->insert_branch($branch_data);

            if ($branch_id) {
                $this->session->set_flashdata('success', 'Branch created successfully!');
                redirect('master/branch');
            } else {
                $this->session->set_flashdata('error', 'Failed to create branch. Please try again.');
                redirect('master/create_branch');
            }
        }
    }

    public function edit_branch($id)
    {
        $menuSession = $this->getMenuSessionNumberByName('Branch'); // adjust mm_name to actual
        if (!$this->hasPermission($menuSession, 'edit')) {
            $this->session->set_flashdata('error', 'You do not have permission to edit branch details.');
            redirect('master/branch');
            return;
        }

        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Edit Branch";

        $data['branch'] = $this->master->get_branch_by_id($id);

        if (!$data['branch']) {
            show_404();
        }

        $this->load->view('templates/header', $data);
        $this->load->view('user_master/branch_edit', $data);
        $this->load->view('templates/footer');
    }

    public function update_branch($id)
    {
        $menuSession = $this->getMenuSessionNumberByName('Branch'); // adjust mm_name to actual
        if (!$this->hasPermission($menuSession, 'edit')) {
            $this->session->set_flashdata('error', 'You do not have permission to update branch details.');
            redirect('master/branch');
            return;
        }

        $this->form_validation->set_rules('name', 'Branch Name', 'required|trim');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

        if ($this->form_validation->run() == FALSE) {
            $this->edit_branch($id);
        } else {
            $branch_data = [
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $result = $this->master->update_branch($id, $branch_data);

            if ($result) {
                $this->session->set_flashdata('success', 'Branch updated successfully!');
                redirect('master/branch');
            } else {
                $this->session->set_flashdata('error', 'Failed to update branch. Please try again.');
                redirect('master/edit_branch/' . $id);
            }
        }
    }

    public function delete_branch($id)
    {
        $menuSession = $this->getMenuSessionNumberByName('Branch'); // adjust mm_name to actual
        if (!$this->hasPermission($menuSession, 'delete')) {
            $this->session->set_flashdata('error', 'You do not have permission to delete branches.');
            redirect('master/branch');
            return;
        }

        // Check if branch exists
        $branch = $this->db->get_where('branches', ['id' => $id])->row();

        if (!$branch) {
            $this->session->set_flashdata('error', 'Branch not found!');
            redirect('master/branch');
        }

        // Option A: hard delete (existing behaviour)
        $this->db->where('id', $id);
        $result = $this->db->delete('branches');

        // Option B (recommended): soft delete
        // $result = $this->db->where('id', $id)->update('branches', ['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);

        if ($result) {
            $this->session->set_flashdata('success', 'Branch deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete branch. Please try again.');
        }

        redirect('master/branch');
    }

    public function invoice_type()
    {
        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Master Menu List";
        $this->load->view('templates/header', $data);

        $this->load->view('user_master/invoice_type_view');
        $this->load->view('templates/footer');
    }

    public function create_invoice_type()
    {
        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Master Menu List";
        $this->load->view('templates/header', $data);

        $this->load->view('user_master/invoice_type_create');
        $this->load->view('templates/footer');
    }

    public function edit_invoice_type()
    {
        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Master Menu List";
        $this->load->view('templates/header', $data);

        $this->load->view('user_master/invoice_type_edit');
        $this->load->view('templates/footer');
    }

    public function country()
    {
        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Master Menu List";
        $this->load->view('templates/header', $data);

        $this->load->view('user_master/country_view');
        $this->load->view('templates/footer');
    }

    public function create_country()
    {
        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Create Country";

        if ($this->input->method(true) === 'POST') {
            $post = $this->input->post();

            if (empty(trim($post['name'] ?? '')) || empty(trim($post['country_code'] ?? ''))) {
                redirect(base_url('master/create_country'));
                return;
            }

            $this->master->insert_country($post);
            redirect(base_url('master/country'));
            return;
        }

        $this->load->view('templates/header', $data);
        $this->load->view('user_master/country_create', $data);
        $this->load->view('templates/footer');
    }

    public function edit_country($id = null)
    {
        if (!$id) {
            $this->session->set_flashdata('error', 'Invalid country id.');
            redirect(base_url('master/country'));
        }

        $this->db->where('id', $id);
        $country = $this->db->get('country')->row();

        if (!$country) {
            $this->session->set_flashdata('error', 'Country not found.');
            redirect(base_url('master/country'));
        }

        $viewData['parent_level'] = "master/home";
        $viewData['mainMenu'] = "master";
        $viewData['title'] = "Edit Country";
        $viewData['country'] = $country;

        if ($this->input->method(true) === 'POST') {
            $post = $this->input->post();

            if (empty(trim($post['name'] ?? ''))) {
                $this->session->set_flashdata('error', 'Country name is required');
                redirect(base_url('master/edit_country/' . $id));
            }

            if (empty(trim($post['country_code'] ?? ''))) {
                $this->session->set_flashdata('error', 'Country code is required');
                redirect(base_url('master/edit_country/' . $id));
            }

            $this->db->where('name', trim($post['name']));
            $this->db->where('id !=', $id);
            if ($this->db->get('country')->num_rows() > 0) {
                $this->session->set_flashdata('error', 'Country name already exists');
                redirect(base_url('master/edit_country/' . $id));
            }

            $this->db->where('country_code', strtoupper(trim($post['country_code'])));
            $this->db->where('id !=', $id);
            if ($this->db->get('country')->num_rows() > 0) {
                $this->session->set_flashdata('error', 'Country code already exists');
                redirect(base_url('master/edit_country/' . $id));
            }

            $updateData = [
                'name' => trim($post['name']),
                'country_code' => strtoupper(trim($post['country_code'])),
                'status' => isset($post['status']) ? (int)$post['status'] : 1,
            ];

            $this->db->where('id', $id);
            $updated = $this->db->update('country', $updateData);

            if ($updated) {
                $this->session->set_flashdata('success', 'Country updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update country');
            }

            redirect(base_url('master/country'));
        }

        $this->load->view('templates/header', $viewData);
        $this->load->view('user_master/country_edit', $viewData);
        $this->load->view('templates/footer');
    }

    public function delete_country($id = null)
    {
        if ($id && is_numeric($id)) {
            $this->db->where('id', $id)->delete('country');
        }
        redirect(base_url('master/country'));
    }

    public function university()
    {
        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Master Menu List";
        $this->load->view('templates/header', $data);

        $this->load->view('user_master/university_view');
        $this->load->view('templates/footer');
    }

    public function store_university()
    {
        if ($this->input->method(true) !== 'POST') {
            redirect(base_url('master/university'));
            return;
        }

        $post = $this->input->post();

        if (empty($post['country_id']) || empty(trim($post['name']))) {
            redirect(base_url('master/create_university'));
            return;
        }

        $payload = [
            'country_id' => (int)$post['country_id'],
            'name' => trim($post['name']),
            'status' => $post['status'] ?? 'active'
        ];

        $this->master->insert_university($payload);

        redirect(base_url('master/university'));
    }

    public function create_university()
    {
        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Master Menu List";
        $this->load->view('templates/header', $data);

        $this->load->view('user_master/university_create');
        $this->load->view('templates/footer');
    }

    public function edit_university($id)
    {
        $data['parent_level'] = "master/home";
        $data['mainMenu'] = "master";
        $data['title'] = "Edit University";

        $data['university'] = $this->master->get_university_by_id($id);

        if (!$data['university']) {
            $this->session->set_flashdata('error', 'University not found!');
            redirect('master/university');
        }

        $data['countries'] = $this->master->get_all_countries();

        $this->load->view('templates/header', $data);
        $this->load->view('user_master/university_edit', $data);
        $this->load->view('templates/footer');
    }

    public function update_university($id)
    {
        $this->form_validation->set_rules('country_id', 'Country', 'required|trim');
        $this->form_validation->set_rules('name', 'University Name', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

        if ($this->form_validation->run() == FALSE) {
            $this->edit_university($id);
        } else {
            $university_data = [
                'country_id' => $this->input->post('country_id'),
                'name' => $this->input->post('name'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $result = $this->master->update_university($id, $university_data);

            if ($result) {
                $this->session->set_flashdata('success', 'University updated successfully!');
                redirect('master/university');
            } else {
                $this->session->set_flashdata('error', 'Failed to update university. Please try again.');
                redirect('master/edit_university/' . $id);
            }
        }
    }

    public function delete_university($id)
    {
        $university = $this->db->get_where('university', ['id' => $id])->row();

        if (!$university) {
            $this->session->set_flashdata('error', 'University not found!');
            redirect('master/university');
        }

        $this->db->where('id', $id);
        $result = $this->db->delete('university');

        if ($result) {
            $this->session->set_flashdata('success', 'University deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete university. Please try again.');
        }

        redirect('master/university');
    }

public function intake_year()
{
    // permission: view
    // $menuNames = ['Intake Year','IntakeYear','intake_year'];
    // if (!$this->_has_permission_by_menu_name($menuNames, 'view')) {
    //     $this->session->set_flashdata('error', 'You do not have permission to view Intake Years.');
    //     redirect('master/home');
    //     return;
    // }

    $data['parent_level'] = "master/home";
    $data['mainMenu'] = "master";
    $data['title'] = "Intake Year";
    $this->load->view('templates/header', $data);

    // the view will itself read data, but it's fine to pass rows
    $data['intake_years'] = $this->db->order_by('created_at', 'DESC')->get('intake_year');
    $this->load->view('user_master/in_take_year_view', $data);
    $this->load->view('templates/footer');
}

/** Create */
public function create_intake_year()
{
    // $menuNames = ['Intake Year','IntakeYear','intake_year'];
    // if (!$this->_has_permission_by_menu_name($menuNames, 'add')) {
    //     $this->session->set_flashdata('error', 'You do not have permission to add Intake Year.');
    //     redirect('master/intake_year');
    //     return;
    // }

    $data['parent_level'] = "master/home";
    $data['mainMenu'] = "master";
    $data['title'] = "Create Intake Year";

    if ($this->input->method(TRUE) === 'POST') {
        $post = $this->input->post();
        $val = trim($post['intake_year'] ?? '');
        if ($val === '') {
            $this->session->set_flashdata('error', 'Intake year is required.');
            redirect(base_url('master/create_intake_year'));
            return;
        }

        // duplicate check (case-insensitive)
        $this->db->where('LOWER(intake_year)', strtolower($val));
        if ($this->db->get('intake_year')->num_rows() > 0) {
            $this->session->set_flashdata('error', 'Intake year already exists.');
            redirect(base_url('master/create_intake_year'));
            return;
        }

        $payload = [
            'intake_year' => $val,
            'status' => isset($post['status']) ? $post['status'] : 'Active',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $inserted = $this->db->insert('intake_year', $payload);
        if ($inserted) {
            $this->session->set_flashdata('success', 'Intake year created successfully.');
            redirect(base_url('master/intake_year'));
            return;
        } else {
            $this->session->set_flashdata('error', 'Failed to create intake year.');
            redirect(base_url('master/create_intake_year'));
            return;
        }
    }

    $this->load->view('templates/header', $data);
    $this->load->view('user_master/in_take_year_create', $data);
    $this->load->view('templates/footer');
}

/** Edit */
public function edit_intake_year($id = null)
{
    // $menuNames = ['Intake Year','IntakeYear','intake_year'];
    // if (!$this->_has_permission_by_menu_name($menuNames, 'edit')) {
    //     $this->session->set_flashdata('error', 'You do not have permission to edit Intake Year.');
    //     redirect('master/intake_year');
    //     return;
    // }

    if (!$id || !is_numeric($id)) {
        $this->session->set_flashdata('error', 'Invalid intake year id.');
        redirect(base_url('master/intake_year'));
        return;
    }

    $intake_year = $this->db->get_where('intake_year', ['id' => (int)$id])->row();
    if (!$intake_year) {
        $this->session->set_flashdata('error', 'Intake year not found.');
        redirect(base_url('master/intake_year'));
        return;
    }

    $viewData['parent_level'] = "master/home";
    $viewData['mainMenu'] = "master";
    $viewData['title'] = "Edit Intake Year";
    $viewData['intake_year'] = $intake_year;

    if ($this->input->method(true) === 'POST') {
        $post = $this->input->post();
        $val = trim($post['intake_year'] ?? '');
        if ($val === '') {
            $this->session->set_flashdata('error', 'Intake year is required');
            redirect(base_url('master/edit_intake_year/' . $id));
            return;
        }

        // duplicate check
        $this->db->where('LOWER(intake_year)', strtolower($val));
        $this->db->where('id !=', $id);
        if ($this->db->get('intake_year')->num_rows() > 0) {
            $this->session->set_flashdata('error', 'Intake year already exists');
            redirect(base_url('master/edit_intake_year/' . $id));
            return;
        }

        $update = [
            'intake_year' => $val,
            'status'      => in_array($post['status'], ['Active','Pending','Inactive']) ? $post['status'] : 'Active',
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $id);
        $updated = $this->db->update('intake_year', $update);

        if ($updated) {
            $this->session->set_flashdata('success', 'Intake year updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to update intake year');
        }

        redirect(base_url('master/intake_year'));
        return;
    }

    $this->load->view('templates/header', $viewData);
    $this->load->view('user_master/in_take_year_edit', $viewData);
    $this->load->view('templates/footer');
}

/** Delete (soft-delete) */
public function delete_intake_year($id = null)
{
    // $menuNames = ['Intake Year','IntakeYear','intake_year'];
    // if (!$this->_has_permission_by_menu_name($menuNames, 'delete')) {
    //     $this->session->set_flashdata('error', 'You do not have permission to delete Intake Year.');
    //     redirect('master/intake_year');
    //     return;
    // }

    if (!$id || !is_numeric($id)) {
        $this->session->set_flashdata('error', 'Invalid intake year id.');
        redirect(base_url('master/intake_year'));
        return;
    }

    // Prefer soft-delete: mark status inactive and record deleted_at
    $update = [
        'status' => 'Inactive',
        'deleted_at' => date('Y-m-d H:i:s')
    ];
    $this->db->where('id', (int)$id);
    $updated = $this->db->update('intake_year', $update);

    if ($updated) {
        $this->session->set_flashdata('success', 'Intake year deleted successfully.');
    } else {
        $this->session->set_flashdata('error', 'Failed to delete intake year.');
    }

    redirect(base_url('master/intake_year'));
}


}
