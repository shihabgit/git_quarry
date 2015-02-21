<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Index extends My_controller
{

    function __construct()
    {
        parent::__construct();
        // $this->load->library('ion_auth'); // auto-load set
        // $this->load->library('form_validation');// auto-load set
        //$this->load->helper('url');
        // Load MongoDB library instead of native db driver if required
        $this->config->item('use_mongodb', 'ion_auth') ?
                        $this->load->library('mongo_db') :
                        $this->load->database();

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));


        $this->lang->load('auth');
        $this->load->helper('language');
    }


    function index()
    {   
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('index/login', 'refresh');
            return;
        }
        
        // Checking is the current task is enabled for the user
        $this->isAllowedTask($this->cls);
        
        // Checking is the user permited to go forwad?
        $this->isAllowed();


        $this->data['message'] = $this->session->flashdata('message');
        $this->data['message_level'] = $this->session->flashdata('message_level');
        $this->data['title'] = "The Quary";


        $show_shortcut_menu = TRUE;
        $this->data['workcentres'] = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);
        
        if(!$this->data['workcentres'])
            $show_shortcut_menu = FALSE;
        
        #------- Checking all settings in Tbl:settings are running correctly. ---------------#
        $this->load->model('settings_model', 'settings');
        // Getting all firms
        $firms = $this->firms->get_firms_options('', '');
        $this->data['settings_errors'] = $this->settings->checkSettings($firms);
        #-------------------------------------------------------------------------------------#

        //print_r($this->data['workcentres']);
        $this->_render_page($this->clsfunc, $this->data,FALSE,$show_shortcut_menu);
    }
/*
    //redirect if needed, otherwise display the user list
    function employee()
    {   // Checking is the user permited to go forwad?
        $this->isAllowed();

        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('index/login', 'refresh'); //redirect them to the login page
            return;
        }
//        elseif (!$this->ion_auth->is_admin()) //remove this elseif if you want to enable this for non-admins
//        {
//            //redirect them to the home page because they must be an administrator to view this
//            return show_error('You must be an administrator to view this page.');
//        }
        else
        {
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->data['message_level'] = (validation_errors()) ? 2 : $this->session->flashdata('message_level');
            $this->data['title'] = "Employees";

            //list the users
            $this->data['users'] = $this->ion_auth->users()->result();
            foreach ($this->data['users'] as $k => $user)
            {
                $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
            }


            $this->data['offset'] = 0;

//            if ($this->uri->segment(3) == 'paging')
//            {
//                $offset = $this->uri->segment(4) ? : 0;
//                $input = $this->admin_entry->getHolder($this->clsfunc);
//                $input['offset'] = $offset;
//                $this->admin_entry->setHolder($this->clsfunc, $input);
//                $_POST = $input;
//            }
//            else
//            {
//                $offset = 0;
//                $input = $this->get_inputs();
//                $this->admin_entry->setHolder($this->clsfunc, $input);
//            }

            $this->per_page = $_POST ? ifSet('PER_PAGE') : $this->per_page;
            $limit = $this->per_page;

            $this->data['msg_level'] = $this->session->set_flashdata('msg_level');
            $this->data['msg_value'] = $this->session->set_flashdata('msg_value');


            $this->data['num_rows'] = count($this->data['users']);
            $this->data['table'] = $this->ion_auth->search(NULL);
            foreach ($this->data['table'] as $k => $user)
            {
                $this->data['table'][$k]['groups'] = $this->ion_auth->get_users_groups($user['id'])->result();
            }

//            $this->data['offset'] = $offset;
            $this->data['page_count'] = $this->per_page ? ceil($this->data['num_rows'] / $this->per_page) : 1;

            $this->load->library('pagination');
            $config = get_pagination_configurations();
            $config['per_page'] = $this->per_page;
            $config['base_url'] = site_url($this->clsfunc . '/paging');
            $config['total_rows'] = $this->data['num_rows'];
            $this->pagination->initialize($config);
            if (!$this->form_validation->run() && $_POST)
            {
                $this->data['msg_value'] = 'Some Errors Occured !';
                $this->data['msg_level'] = 2;
                $this->data['num_rows'] = 0;
                $this->data['num_copy'] = 0;
                $this->data['table'] = array();
                $this->data['offset'] = 0;
                $this->data['page_count'] = 0;
                $config['total_rows'] = 0;
            }
            $this->_render_page($this->clsfunc, $this->data);
        }
    }
*/
    //log the user in
    function login()
    {   
        $this->data['title'] = "Login";

        //validate form input
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true)
        {
            //check to see if the user is logging in
            //check for "remember me"
            $remember = (bool) $this->input->post('remember');
            $this->form_validation->set_error_delimiters('<div class="alert-box error"><span>Error: </span>', '</div>');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
            {
                //if the login is successful
                
                // Added by shihab to checking and doing, if anything have to do automatically on userlogin.
                $this->auto_run();
                
                //redirect them to the firm login page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->session->set_flashdata('message_level', 1); // Success
                redirect('firms/login', 'refresh');
            }
            else
            {
                //if the login was un-successful
                //redirect them back to the login page
                $this->session->set_flashdata('message_level', 2); // Error
                $this->session->set_flashdata('message', $this->ion_auth->errors());

                redirect('index/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
                //by shihab (To give my own style for incorrect user/pwd message. otherwise it will display in ion-auth style)
                //redirect('index/login/incorrect', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        }
        else
        {
            //the user is not logging in so display the login page
            //set the flash data error message if there is one
            //Hided by shihab START (because validation errors will be displayed individually with its fields. no need it to display on message box.
            //$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            //$this->data['message_level'] = (validation_errors()) ? 2 : $this->session->flashdata('message_level');
            // End shihab hide
            //Added by shihab START
            $this->data['message'] = $this->session->flashdata('message');
            $this->data['message_level'] = $this->session->flashdata('message_level');
            // End shihab add

            $this->data['identity'] = array('name' => 'identity',
                'autocomplete' => 'off',
                'id' => 'identity',
                'type' => 'text',
                //Commented by shihab
                /* 'value' => $this->form_validation->set_value('identity') */

                //Added by shihab
                'value' => 'administrator',
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                //Added by shihab
                'value' => 'password',
            );

            //$this->_render_page('index/login', $this->data);
            $this->load->view('index/login', $this->data);
        }
    }

    //log the user out
    function logout()
    {
        $this->data['title'] = "Logout";
        
        // If any message from previous page
        $msg = $this->session->flashdata('message');
        $lvl = $this->session->flashdata('message_level');

        //log the user out
        $logout = $this->ion_auth->logout();
        
        //Setting flash message.
        if($msg && $lvl)
        {
            $this->session->set_flashdata('message', $msg);
            $this->session->set_flashdata('message_level', $lvl); 
        }
        else
        {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            $this->session->set_flashdata('message_level', 1); // Success
        }
        
        // Clearing firm data.
        $this->session->set_userdata('firm_id', NULL);

        //redirect them to the login page
        redirect('index/login', 'refresh');
    }
/*
//change password
    function change_password()
    {
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

        if (!$this->ion_auth->logged_in())
        {
            redirect('index/login', 'refresh');
        }

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() == false)
        {
            //display the form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
            $this->data['old_password'] = array(
                'name' => 'old',
                'id' => 'old',
                'type' => 'password',
            );
            $this->data['new_password'] = array(
                'name' => 'new',
                'id' => 'new',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            );
            $this->data['new_password_confirm'] = array(
                'name' => 'new_confirm',
                'id' => 'new_confirm',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            );
            $this->data['user_id'] = array(
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $user->id,
            );

            //render
            $this->_render_page('index/change_password', $this->data);
        }
        else
        {
            $identity = $this->session->userdata('identity');

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change)
            {
                //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->logout();
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('index/change_password', 'refresh');
            }
        }
    }
*/
    /*
//forgot password
    function forgot_password()
    {
        $this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
        if ($this->form_validation->run() == false)
        {
            //setup the input
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
            );

            if ($this->config->item('identity', 'ion_auth') == 'username')
            {
                $this->data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');
            }
            else
            {
                $this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
            }

            //set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->_render_page('index/forgot_password', $this->data);
        }
        else
        {
            // get identity from username or email
            if ($this->config->item('identity', 'ion_auth') == 'username')
            {
                $identity = $this->ion_auth->where('username', strtolower($this->input->post('email')))->users()->row();
            }
            else
            {
                $identity = $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->row();
            }
            if (empty($identity))
            {
                $this->ion_auth->set_message('forgot_password_email_not_found');
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("index/forgot_password", 'refresh');
            }

            //run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

            if ($forgotten)
            {
                //if there were no errors
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("index/login", 'refresh'); //we should display a confirmation page here instead of the login page
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("index/forgot_password", 'refresh');
            }
        }
    }
*/
/*    
    //reset password - final step for forgotten password
    public function reset_password($code = NULL)
    {
        if (!$code)
        {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user)
        {
            //if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() == false)
            {
                //display the form
                //set the flash data error message if there is one
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;

                //render
                $this->_render_page('index/reset_password', $this->data);
            }
            else
            {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
                {

                    //something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($code);

                    show_error($this->lang->line('error_csrf'));
                }
                else
                {
                    // finally change the password
                    $identity = $user->{$this->config->item('identity', 'ion_auth')};

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change)
                    {
                        //if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        $this->logout();
                    }
                    else
                    {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('index/reset_password/' . $code, 'refresh');
                    }
                }
            }
        }
        else
        {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("index/forgot_password", 'refresh');
        }
    }
*/
/*    
    //activate the user
    function activate($id, $code = false)
    {
        if ($code !== false)
        {
            $activation = $this->ion_auth->activate($id, $code);
        }
        else if ($this->ion_auth->is_admin())
        {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation)
        {
            //redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("index", 'refresh');
        }
        else
        {
            //redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("index/forgot_password", 'refresh');
        }
    }
*/
/*    
    //deactivate the user
    function deactivate($id = NULL)
    {
        $id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');
        $this->data['title'] = "User Deativation";
        if ($this->form_validation->run() == FALSE)
        {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['user'] = $this->ion_auth->user($id)->row();

            $this->_render_page('index/deactivate_user', $this->data);
        }
        else
        {
            // do we really want to deactivate?
            if ($this->input->post('confirm') == 'yes')
            {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
                {
                    show_error($this->lang->line('error_csrf'));
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
                {
                    $this->ion_auth->deactivate($id);
                }
            }

            //redirect them back to the auth page
            redirect('index', 'refresh');
        }
    }
*/  
/*
    //create a new user
    function create_user()
    {
        $this->data['title'] = "Create User";

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('index', 'refresh');
        }

        $tables = $this->config->item('tables', 'ion_auth');

        //validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|xss_clean');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'required|xss_clean');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true)
        {
            $username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data))
        {
            //check to see if we are creating the user
            //redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("index", 'refresh');
        }
        else
        {
            //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));





            $this->data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['company'] = array(
                'name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['phone'] = array(
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            );



            $this->data['firms'] = $this->firms->get_active_option();
            $select = array('wcntr_id', 'wcntr_fk_firms', 'wcntr_name');
            $where = array('wcntr_status' => 1);
            $this->data['workcentres'] = $this->workcentres->get_data($select, $where);



            $this->_render_page('index/create_user', $this->data);
        }
    }
*/
/*    
    //edit a user
    function edit_user($id)
    {
        $this->data['title'] = "Edit User";

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
        {
            redirect('index', 'refresh');
        }

        $user = $this->ion_auth->user($id)->row();
        $groups = $this->ion_auth->groups()->result_array();
        $currentGroups = $this->ion_auth->get_users_groups($id)->result();

        //validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required|xss_clean');
        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required|xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required|xss_clean');
        $this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required|xss_clean');
        $this->form_validation->set_rules('groups', $this->lang->line('edit_user_validation_groups_label'), 'xss_clean');

        if (isset($_POST) && !empty($_POST))
        {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
            {
                show_error($this->lang->line('error_csrf'));
            }

            $data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),
            );

            // Only allow updating groups if user is admin
            if ($this->ion_auth->is_admin())
            {
                //Update the groups user belongs to
                $groupData = $this->input->post('groups');

                if (isset($groupData) && !empty($groupData))
                {

                    $this->ion_auth->remove_from_group('', $id);

                    foreach ($groupData as $grp)
                    {
                        $this->ion_auth->add_to_group($grp, $id);
                    }
                }
            }

            //update the password if it was posted
            if ($this->input->post('password'))
            {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

                $data['password'] = $this->input->post('password');
            }

            if ($this->form_validation->run() === TRUE)
            {
                $this->ion_auth->update($user->id, $data);

                //check to see if we are creating the user
                //redirect them back to the admin page
                $this->session->set_flashdata('message', "User Saved");
                if ($this->ion_auth->is_admin())
                {
                    redirect('index', 'refresh');
                }
                else
                {
                    redirect('/', 'refresh');
                }
            }
        }

        //display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        //set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        //pass the user to the view
        $this->data['user'] = $user;
        $this->data['groups'] = $groups;
        $this->data['currentGroups'] = $currentGroups;

        $this->data['first_name'] = array(
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
        );
        $this->data['last_name'] = array(
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
        );
        $this->data['company'] = array(
            'name' => 'company',
            'id' => 'company',
            'type' => 'text',
            'value' => $this->form_validation->set_value('company', $user->company),
        );
        $this->data['phone'] = array(
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone', $user->phone),
        );
        $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'type' => 'password'
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'type' => 'password'
        );

        $this->_render_page('index/edit_user', $this->data);
    }
*/
    // create a new group
    function create_group()
    {
        $this->data['title'] = $this->lang->line('create_group_title');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('index', 'refresh');
        }

        //validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash|xss_clean');
        $this->form_validation->set_rules('description', $this->lang->line('create_group_validation_desc_label'), 'xss_clean');

        if ($this->form_validation->run() == TRUE)
        {
            $new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
            if ($new_group_id)
            {
                // check to see if we are creating the group
                // redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("index", 'refresh');
            }
        }
        else
        {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['group_name'] = array(
                'name' => 'group_name',
                'id' => 'group_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('group_name'),
            );
            $this->data['description'] = array(
                'name' => 'description',
                'id' => 'description',
                'type' => 'text',
                'value' => $this->form_validation->set_value('description'),
            );

            $this->_render_page('index/create_group', $this->data);
        }
    }

    //edit a group
    function edit_group($id)
    {
        // bail if no group id given
        if (!$id || empty($id))
        {
            redirect('index', 'refresh');
        }

        $this->data['title'] = $this->lang->line('edit_group_title');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('index', 'refresh');
        }

        $group = $this->ion_auth->group($id)->row();

        //validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash|xss_clean');
        $this->form_validation->set_rules('group_description', $this->lang->line('edit_group_validation_desc_label'), 'xss_clean');

        if (isset($_POST) && !empty($_POST))
        {
            if ($this->form_validation->run() === TRUE)
            {
                $group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

                if ($group_update)
                {
                    $this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
                }
                else
                {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("index", 'refresh');
            }
        }

        //set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        //pass the user to the view
        $this->data['group'] = $group;

        $this->data['group_name'] = array(
            'name' => 'group_name',
            'id' => 'group_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('group_name', $group->name),
        );
        $this->data['group_description'] = array(
            'name' => 'group_description',
            'id' => 'group_description',
            'type' => 'text',
            'value' => $this->form_validation->set_value('group_description', $group->description),
        );

        $this->_render_page('index/edit_group', $this->data);
    }

    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
                $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /*
      function _render_page($view, $data=null, $render=false)
      {

      $this->viewdata = (empty($data)) ? $this->data: $data;

      $view_html = $this->load->view($view, $this->viewdata, $render);

      if (!$render) return $view_html;
      } */
}
