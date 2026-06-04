<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Chat_presence extends CI_Controller
{
    public $sessionArray;
    public $sessionData;
    public $perPage;

    public function __construct()
    {
        parent::__construct();

        // Check session
        if ($this->session->userdata("company1") == '') {
            redirect(base_url('login'));
        }

        // Store session data
        $this->sessionData = $this->session->userdata("company1");

        // Load Master_model (for authentication)
        $this->load->model('Master_model', 'master', true);

        // Load Blog_model
        $this->load->model('Blog_model', 'blog', true);


        // Load Ajax pagination library
        $this->load->library('Ajax_pagination');

        // Get user authentication details from Master_model
        $this->sessionArray = $this->master->getUserAuthentication();

        // Default pagination size
        $this->perPage = 10;
    }
    // helper to get admin id from session (supports different session keys)
private function _get_admin_id()
{
    // If you store full user-array in company1 (as in your constructor), use that:
    $company = $this->session->userdata('company1');
    if (!empty($company) && is_array($company) && !empty($company['id'])) {
        return $company['id'];
    }

    // fallback: legacy admin_id session key
    $admin_id = $this->session->userdata('admin_id');
    if (!empty($admin_id)) return $admin_id;

    return null;
}

  public function chat_presence() {
        $data['title'] = "Chat";
        $this->load->view('templates/header', $data);
        $this->load->view('chatbot/chatbot', $data);
        $this->load->view('templates/footer');
    }
    /* --------------------------------------------
     * BLOG MODULE — Handles blog-related views
     * -------------------------------------------- */
// Returns only threads that have messages
public function get_chats()
{
    header('Content-Type: application/json');
    $this->load->model('Chat_model','chat');

    $threads = $this->db->order_by('last_at', 'DESC')->get('chat_threads')->result_array();

    $out = [];
    foreach ($threads as $t) {
        $out[] = [
            'id' => (int)$t['id'],
            'name' => $t['visitor_name'] ?: 'Visitor',
            'avatar' => base_url('images/avatar/2.jpg'),
            'last_msg' => $t['last_message'] ?? '',
            'time' => $t['last_at'] ? date('g:ia', strtotime($t['last_at'])) : '',
            'unread' => (int)($t['unread_admin'] ?? 0),
            'status' => ($t['last_at'] && (time() - strtotime($t['last_at']) < 300)) ? 'online' : 'offline'
        ];
    }

    echo json_encode(['ok'=>true,'chats'=>$out]);
}


// Return messages for a thread
public function get_messages($chat_id = 0)
{
    header('Content-Type: application/json');

    $chat_id = (int)$chat_id;
    if ($chat_id <= 0) { echo json_encode(['ok'=>true,'messages'=>[],'meta'=>[]]); return; }

    $this->load->model('Chat_model','chat');

    // fetch messages from model
    $messages = $this->chat->get_messages($chat_id);

    // Build meta (thread info)
    $thread = $this->db->get_where('chat_threads', ['id' => $chat_id])->row_array();
    $meta = [
        'id' => $chat_id,
        'name' => $thread ? ($thread['visitor_name'] ?: 'Visitor') : 'Visitor',
        'avatar' => base_url('images/avatar/2.jpg'),
        'status' => ($thread && isset($thread['last_at']) && (time() - strtotime($thread['last_at']) < 300)) ? 'online' : 'offline',
        'last_seen' => $thread && isset($thread['last_at']) ? $thread['last_at'] : null
    ];

    // Normalize messages shape for client
    $out = [];
    foreach ($messages as $m) {
        $out[] = [
            'id' => (int)$m['id'],
            'sender_type' => $m['sender_type'], // 'visitor' or 'admin'
            'sender_id' => $m['sender_id'],
            'text' => $m['text'],
            'created_at' => $m['created_at'],
            // also add simple 'from' used by the view
            'from' => ($m['sender_type'] === 'visitor') ? 'other' : 'me',
            'time' => date('H:i', strtotime($m['created_at']))
        ];
    }

    // Optionally mark messages read for admin
    // $this->chat->mark_read_admin($chat_id);   // if you want marking behavior, enable

    echo json_encode(['ok'=>true,'messages'=>$out,'meta'=>$meta]);
}

// in Chat_presence controller

// Website: create or return thread for visitor (POST)
public function website_get_thread()
{
    header('Content-Type: application/json');
    $this->load->model('Chat_model','chat');

    $visitor_token = $this->input->post('visitor_token') ?: get_cookie('visitor_token');
    $name = $this->input->post('name');
    $mobile = $this->input->post('mobile');
    $email = $this->input->post('email');

    if (!$visitor_token) {
        $visitor_token = bin2hex(random_bytes(12));
        set_cookie(['name'=>'visitor_token','value'=>$visitor_token,'expire'=>31536000,'path'=>'/']);
    }

    $thread = $this->chat->get_or_create_thread_for_visitor($visitor_token, ['name'=>$name,'mobile'=>$mobile,'email'=>$email]);

    echo json_encode(['ok'=>true,'visitor_token'=>$visitor_token,'thread'=>$thread]);
}

// Website: post message by visitor (POST)
public function website_post_message()
{
    header('Content-Type: application/json');
    $this->load->model('Chat_model','chat');

    $visitor_token = $this->input->post('visitor_token') ?: get_cookie('visitor_token');
    $thread_id = (int)$this->input->post('thread_id');
    $text = trim($this->input->post('text'));
    $name = $this->input->post('name');
    $mobile = $this->input->post('mobile');
    $email = $this->input->post('email');

    if (!$text) { http_response_code(400); echo json_encode(['error'=>'empty_text']); return; }

    // If no thread_id, create/get one using email/mobile to avoid duplicates
    if (!$thread_id) {
        $thread = $this->chat->get_or_create_thread_for_visitor($visitor_token, ['name'=>$name,'mobile'=>$mobile,'email'=>$email,'initial_message'=>$text]);
        $thread_id = $thread['id'];
    } else {
        // optional: you could validate thread belongs to visitor_token/email
    }

    $message = $this->chat->post_message($thread_id, 'visitor', null, $text);

    echo json_encode(['ok'=>true,'message'=>$message,'thread_id'=>$thread_id]);
}

// Website: Check agent presence (simple)
public function check_status()
{
    header('Content-Type: application/json');
    // We will return status: online or offline. You can check user_master table for any user with is_online=1
    $q = $this->db->select('COUNT(*) as cnt')->where('is_online',1)->get('user_master')->row_array();
    $online = (isset($q['cnt']) && $q['cnt'] > 0);
    echo json_encode(['status'=> $online ? 'online' : 'offline', 'online' => $online]);
}





// Website: get messages for visitor thread (GET or POST)
public function website_get_messages($thread_id = 0)
{
    header('Content-Type: application/json');
    $this->load->model('Chat_model','chat');

    $thread_id = (int)$thread_id;
    if (!$thread_id) { echo json_encode(['messages'=>[]]); return; }

    // optional: validate visitor token belongs to thread (skip if not required)
    $messages = $this->chat->get_messages($thread_id);
    // mark visitor read (we'll set unread_visitor = 0)
    $this->db->where('id', $thread_id);
    $this->db->update('chat_threads', ['unread_visitor' => 0]);

    echo json_encode(['ok'=>true,'messages'=>$messages]);
}

//
// Admin endpoints (for admin panel to call via AJAX)
//

public function admin_get_threads()
{
    header('Content-Type: application/json');
    $admin_id = $this->_get_admin_id();
    if (!$admin_id) { http_response_code(403); echo json_encode(['error'=>'not_auth']); return; }
    $this->load->model('Chat_model','chat');
    $threads = $this->chat->get_threads_for_admin(200, 0);
    echo json_encode(['ok'=>true,'threads'=>$threads]);
}

public function admin_get_messages($thread_id = 0)
{
    header('Content-Type: application/json');
    $admin_id = $this->_get_admin_id();
    if (!$admin_id) { http_response_code(403); echo json_encode(['error'=>'not_auth']); return; }

    $this->load->model('Chat_model','chat');
    $thread_id = (int)$thread_id;
    if (!$thread_id) { echo json_encode(['messages'=>[]]); return; }
    $this->chat->mark_read_admin($thread_id);
    $messages = $this->chat->get_messages($thread_id);
    echo json_encode(['ok'=>true,'messages'=>$messages]);
}


public function admin_post_message()
{
    header('Content-Type: application/json');

    $admin_id = $this->_get_admin_id();
    if (!$admin_id) {
        http_response_code(403);
        echo json_encode(['error'=>'not_auth']);
        return;
    }

    $this->load->model('Chat_model','chat');
    $thread_id = (int)$this->input->post('thread_id');
    $text = trim($this->input->post('text'));
    if (!$thread_id || !$text) { http_response_code(400); echo json_encode(['error'=>'invalid']); return; }

    $msg = $this->chat->post_message($thread_id, 'admin', $admin_id, $text);
    echo json_encode(['ok'=>true,'message'=>$msg]);
}

public function check_agent_status()
{
    header('Content-Type: application/json');

    $q = $this->db->select('COUNT(*) as online_count')
        ->where('is_online', 1)
        ->get('user_master')
        ->row_array();

    echo json_encode(['online' => ($q['online_count'] > 0)]);
}
public function admin_unread_count()
{
    header('Content-Type: application/json');

    // require admin session
    if (!$this->session->userdata('admin_id')) {
        http_response_code(403);
        echo json_encode(['error' => 'not_auth']);
        return;
    }

    $this->load->model('Chat_model','chat');
    $count = $this->chat->get_unread_count_for_admin();
    echo json_encode(['ok'=>true,'count'=> (int)$count]);
}




}
?>