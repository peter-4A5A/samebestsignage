<?php
/**
 * Created by PhpStorm.
 * User: Jordi
 * Date: 18-1-2018
 * Time: 10:00
 */

class Pages extends CI_Controller
{
    public $data;

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url_helper');
        $this->load->helper('main_helper');

        if (! $this->session->userdata('DX_logged_in')){
            $this->session->sess_destroy();
            redirect('auth/login');
        }

        $data['this_user'] = $this->session->userdata();
        $this->load->database();

        $this->load->model('ticket');
        $this->load->model('clients');
        $this->load->model('user');
        $this->load->model('category');
        $this->load->model('status');
        $this->load->model('importance');
        $this->load->model('alert');

        $this->data['clients'] = $this->clients->get_all_entries();
        $this->data['users'] = $this->user->get_all_entries();
        $this->data['categorys'] = $this->category->get_all_entries();
        $this->data['statuses'] = $this->status->get_all_entries();
        $this->data['importances'] = $this->importance->get_all_entries();
        $this->data['alerts'] = $this->alert->get_all_entries_user($this->session->userdata('DX_user_id'));
    }

    private function _check_auth(){
        if(!$this->session->userdata('is_admin')){
            $this->session->sess_destroy();
            redirect('login');
        }
    }

    public function view($page = 'home', $id = null)
    {
        switch ($page){
            case('completed'):
                $this->completed();
                break;
            case('overview'):
                $this->overview();
                break;
            case('mytickets'):
                $this->myTickets();
                break;
            default:
                $page = 'home';
                $this->ticket();
                break;
        }

        if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php'))
        {
            // Whoops, we don't have a page for that!
            show_404();
        }
        $data = $this->data;
        $data['title'] = ucfirst($page); // Capitalize the first letter
        $data['this_user'] = $this->session->userdata();

        $this->load->view('templates/header', $data);
        $this->load->view('pages/'.$page, $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * @param mixed $data
     */
    public function setData($key, $data)
    {
        $this->data[$key] = $data;
    }

    public function ticket(){
        $data = $this->ticket->get_pending_entries();
        $this->setData('tickets', $data);
    }

    public function completed($id = null){
        $data = $this->ticket->get_completed_entries();
        $this->setData('tickets', $data);
    }

    public function overview(){
        $this->load->library('table');
        $this->setData('table', $this->table->generate($this->ticket->get_all_entries()));
    }

    public function myTickets(){
        $this->load->library('table');
        $this->setData('table', $this->table->generate($this->ticket->get_my_entries()));
    }
}