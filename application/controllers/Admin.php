<?php
/**
 * Created by PhpStorm.
 * User: Jordi
 * Date: 30-1-2018
 * Time: 10:23
 */

class Admin extends CI_Controller
{
    /**
     * @var array
     */
    private $data = array();

    /**
     * Admin constructor.
     */
    public function __construct()
    {
        parent::__construct();

        if (! $this->session->userdata('DX_logged_in') ){
            $this->session->sess_destroy();
            redirect('/login');
        }

        $data['this_user'] = $this->session->userdata();
        $this->load->database();

        $this->load->model('ticket');
        $this->load->model('clients');
        $this->load->model('user');
        $this->load->model('category');
        $this->load->model('status');
        $this->load->model('importance');
        $this->load->model('mail');
        $this->load->model('templates');
        $this->load->model('roles');
    }

    /**
     * @param string $page
     */
    public function view($page = 'dashboard')
    {
        //Right check located in libraries
        $this->rights->validate_rights($page);

        if ( ! file_exists(APPPATH.'views/admin/pages/'.$page.'.php'))
        {
            // Whoops, we don't have a page for that!
            show_404();
        }

        switch ($page){
            case ('category'):
                $data['array'] = $this->category->get_all_entries();
                break;
            case ('status'):
                $data['array'] = $this->status->get_all_entries();
                break;
            case ('importance'):
                $data['array'] = $this->importance->get_all_entries();
                break;
            case ('users'):
                $data['array'] = $this->user->get_all_entries_table();
                break;
            case ('rights'):
                $data['array'] = $this->roles->get_all_entries();
                break;
            case ('mail'):
                $data['array'] = $this->mail->get_all_entries();
                break;
            case ('templates'):
                $data['array'] = $this->templates->get_all_entries();
                break;
            case ('clients'):
                $data['array'] = $this->clients->get_all_entries_full();
                break;
            case ('log'):
                $data['array'] = $this->logs->get_all_entries();
                break;
            case ('pages'):
                $data['array'] = $this->page->get_all_entries();
                break;
            default:
                break;
        }

        $data['title'] = ucfirst($page);
        $data["company"] = '';

        $data['this_user'] = $this->session->userdata();

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/pages/'.$page, $data);
        $this->load->view('admin/templates/footer', $data);
    }

    /**
     * @param string $page
     * @param $id
     */
    public function viewSingle($page = 'user', $id)
    {
        //Right check located in libraries
        $this->rights->validate_rights($page);

        if ( ! file_exists(APPPATH.'views/admin/pages/view/'.$page.'.php'))
        {
            // Whoops, we don't have a page for that!
            show_404();
        }

        switch ($page){
            case ('user'):
                $this->data['roles'] = $this->roles->get_all_entries();
                $this->data['user'] = $this->user->get_single_entry($id);
                //$this->data['login'] = $this->user->get_last_user_login($id);
                break;
            default:
                break;
        }

        $data = $this->data;

        $data['title'] = ucfirst($page);
        $data["company"] = '';

        $data['this_user'] = $this->session->userdata();

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/pages/view/'.$page, $data);
        $this->load->view('admin/templates/footer', $data);
    }

    /**
     * @param string $page
     */
    public function add($page = 'dashboard')
    {
        //Right check located in libraries
        $this->rights->validate_rights($page);

        if ( ! file_exists(APPPATH.'views/admin/pages/add/'.$page.'.php'))
        {
            // Whoops, we don't have a page for that!
            show_404();
        }

        switch ($page){
            case ('category'):
                break;
            case ('status'):
                $data['array'] = $this->status->get_enum();
                break;
            case ('importance'):
                $data['array'] = $this->importance->get_enum();
                break;
            case ('user'):
                $data['roles'] = $this->roles->get_all_entries();
                break;
            case ('client'):
                $data['roles'] = $this->roles->get_all_entries();
                break;
            case ('right'):
                $data['roles'] = $this->roles->get_all_entries();
                break;
            case ('page'):
                $data['page'] = $this->page->get_all_entries();
                $data['types'] = $this->page->get_enum();
                $data['roles'] = $this->roles->get_all_entries();
                break;
            case ('template'):
                break;
            default:
                redirect('/home');
                break;
        }

        $data['title'] = ucfirst($page);
        $data["company"] = '';

        $data['this_user'] = $this->session->userdata();

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/pages/add/'.$page, $data);
        $this->load->view('admin/templates/footer', $data);
    }

    /**
     * @param string $page
     * @param $id
     */
    public function edit($page = 'dashboard', $id)
    {
        //Right check located in libraries
        $this->rights->validate_rights($page);

        if ( ! file_exists(APPPATH.'views/admin/pages/edit/'.$page.'.php'))
        {
            // Whoops, we don't have a page for that!
            show_404();
        }

        switch ($page){
            case ('category'):
                $this->data['array'] = $this->category->get_single_entry($id);
                break;
            case ('status'):
                $this->data['levels'] = $this->status->get_enum();
                $this->data['status'] = $this->status->get_single_entry($id);
                break;
            case ('importance'):
                $this->data['levels'] = $this->importance->get_enum();
                $this->data['importance'] = $this->importance->get_single_entry($id);
                break;
            case ('user'):
                $this->data['roles'] = $this->roles->get_all_entries();
                $this->data['user'] = $this->user->get_single_entry($id);
                break;
            case ('template'):
                $this->data['array'] = $this->templates->get_single_entry($id);
                break;
            case ('client'):
                $this->data['client'] = $this->clients->get_single_entry($id);
                break;
            case ('right'):
                $this->data['role'] = $this->roles->get_entry($id);
                break;
            case ('page'):
                $this->data['page'] = $this->page->get_entry($id);
                $this->data['types'] = $this->page->get_enum();
                $this->data['roles'] = $this->roles->get_all_entries();
                $this->data['roles_selected'] = json_decode($this->data['page']['page_level'], true);
                break;
            default:
                break;
        }

        $data = $this->data;

        $data['title'] = ucfirst($page);
        $data["company"] = '';

        $data["id"] = $id;

        $data['this_user'] = $this->session->userdata();


        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/pages/edit/'.$page, $data);
        $this->load->view('admin/templates/footer', $data);
    }
}