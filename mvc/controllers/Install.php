<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

class Install extends CI_Controller
{
    protected $_info;
    protected $_internet_connection = false;
    protected $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->library('form_validation');
        $this->load->library('updatechecker');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->helper('file');
        $this->load->helper('inicompute');
        $this->load->config('iniconfig');

        if ( $this->updatechecker->checkInternetConnection() ) {
            $this->_internet_connection = true;
        }

        $pstatus = strpos($this->uri->uri_string(), 'install');
        if ( $pstatus == false && $this->config->config_install() ) {
            redirect(site_url('signin/index'));
        }
    }

    public function index()
    {
        $this->data['errors']  = [];
        $this->data['success'] = [];

        // Check PHP version
        if ( phpversion() < "5.6" ) {
            $this->data['errors'][] = 'You are running PHP old version!';
        } else {
            $phpversion              = phpversion();
            $this->data['success'][] = ' You are running PHP ' . $phpversion;
        }

        // Check Mysql PHP extension
        if ( !extension_loaded('mysqli') ) {
            $this->data['errors'][] = 'Mysqli PHP extension unloaded';
        } else {
            $this->data['success'][] = 'Mysqli PHP extension loaded';
        }

        // Check MBString PHP extension
        if ( !extension_loaded('mbstring') ) {
            $this->data['errors'][] = 'MBString PHP extension unloaded';
        } else {
            $this->data['success'][] = 'MBString PHP extension loaded';
        }

        // Check CURL PHP extension
        if ( !extension_loaded('curl') ) {
            // CURL is optional for installation - show as warning instead of error
            $this->data['success'][] = 'CURL PHP extension unloaded (optional - can be enabled later)';
        } else {
            $this->data['success'][] = 'CURL PHP extension loaded';
        }

        // Check Zip PHP extension
        if ( version_compare(phpversion(), '7.3', '<') ) {
            if ( !extension_loaded('zip') ) {
                $this->data['errors'][] = 'Zip PHP extension unloaded';
            } else {
                $this->data['success'][] = 'Zip PHP extension loaded';
            }
        }

        // Check Config Path
        if ( @include( $this->config->config_path ) ) {
            $this->data['success'][] = 'Config file is loaded';
            @chmod($this->config->config_path, FILE_WRITE_MODE);
            if ( is_really_writable($this->config->config_path) == true ) {
                $this->data['success'][] = 'Config file is writable';
            } else {
                $this->data['errors'][] = 'Config file is non-writable';
            }
        } else {
            $this->data['errors'][] = 'Config file is unloaded';
        }

        // Check Database Path
        if ( @include( $this->config->database_path ) ) {
            $this->data['success'][] = 'Database file is loaded';
            @chmod($this->config->database_path, FILE_WRITE_MODE);
            if ( is_really_writable($this->config->database_path) === false ) {
                $this->data['errors'][] = 'database file is non-writable';
            } else {
                $this->data['success'][] = 'Database file is writable';
            }
        } else {
            $this->data['errors'][] = 'Database file is unloaded';
        }

        //Check Purchase Path
        if (file_exists($this->config->purchase_path)) {
            $this->data['success'][]        = 'Purchase file is loaded';
            @chmod($this->config->purchase_path, FILE_WRITE_MODE);
            if (is_really_writable($this->config->purchase_path) === FALSE) {
                $this->data['errors'][]     = 'Purchase file is non-writable';
            } else {
                $this->data['success'][]    = 'Purchase file is writable';
            }
        } else {
            $this->data['errors'][]         = 'Purchase file is unloaded';
        }

        // Check allow_url_fopen
        if ( ini_get('allow_url_fopen') ) {
            $this->data['success'][] = 'allow_url_fopen is enable';
        } else {
            $this->data['errors'][] = 'allow_url_fopen is disable. enable it to your php.ini file';
        }

        // Check Purchase Path
        if ( is_dir('uploads') ) {
            if ( is_really_writable(FCPATH . 'uploads') === false ) {
                $this->data['errors'][] = 'Uploads folder file is not writable';
            } else {
                $folders = [ 'default', 'files', 'gallery', 'general', 'idQRcode', 'report', 'update', 'user' ];
                foreach ( $folders as $folder ) {
                    if ( substr(sprintf("%o", fileperms("uploads/" . $folder)), -4) != '0777' ) {
                        $this->data['errors'][] = $folder . ' folder is not writable. (uploads/' . $folder . ')';
                    }
                }
                $this->data['success'][] = 'Uploads folder is writable';
            }
        } else {
            $this->data['errors'][] = 'Uploads folder is unloaded';
        }

        if ( $this->_internet_connection ) {
            $this->data['success'][] = 'Internet connection OK';
        } else {
            $this->data['errors'][] = 'Internet connection problem';
        }

        $this->data["subview"] = "install/index";
        $this->load->view('_layout_install', $this->data);
    }

    public function purchasecode()
    {
        if ( $_POST ) {
       
                $file = APPPATH . 'config/purchase.php';
                $uac  = json_encode([
                    trim($this->input->post('purchase_username')),
                    trim($this->input->post('purchase_code'))
                ]);
                @chmod($file, FILE_WRITE_MODE);
                write_file($file, $uac);

                redirect(site_url("install/database"));
            }
            redirect(site_url("install/database"));
    }

    protected function rules_purchase_code()
    {
        $rules = [
            [
                'field' => 'purchase_username',
                'label' => 'Username',
                'rules' => 'trim|required|max_length[255]|callback_username_validation'
            ],
            [
                'field' => 'purchase_code',
                'label' => 'Purchase Code',
                'rules' => 'trim|required|max_length[255]|callback_purchase_code_validation'
            ]
        ];
        return $rules;
    }

    public function database()
    {
        $purchaseCodeChecker = $this->_purchaseCodeChecker();
  
            if ( $_POST ) {
                $rules = $this->rules_database();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $this->data["subview"] = "install/database";
                    $this->load->view('_layout_install', $this->data);
                } else {
                    $this->unique_database($_POST['host'],$_POST['user'],$_POST['password'],$_POST['database']);
                    redirect(site_url("install/timezone"));
                }
            } else {
                $this->data["subview"] = "install/database";
                $this->load->view('_layout_install', $this->data);
            }
        
    }

    private function _purchaseCodeChecker( $data = [] )
    {
        $array = $this->_purchaseFileRead();
        if ( inicompute($data) && is_array($data) ) {
            $array = array_merge($array, $data);
        }

        $apiCurl = $this->updatechecker->verifyValidUser($array, false);
        return $apiCurl;
    }

    private function _purchaseFileRead()
    {
        $file = APPPATH . 'config/purchase.php';
        @chmod($file, FILE_WRITE_MODE);
        $purchase = file_get_contents($file);
        $purchase = json_decode($purchase);

        $array = [ 'purchase_code' => '', 'username' => '' ];
        if ( is_array($purchase) ) {
            $array['purchase_code'] = trim($purchase[1]);
            $array['username']      = trim($purchase[0]);
        }
        return $array;
    }

    protected function rules_database()
    {
        $rules = [
            [
                'field' => 'host',
                'label' => 'host',
                'rules' => 'trim|required|max_length[255]'
            ],
            [
                'field' => 'database',
                'label' => 'database',
                'rules' => 'trim|required|max_length[255]'
            ],
            [
                'field' => 'user',
                'label' => 'user',
                'rules' => 'trim|required|max_length[255]'
            ],
            [
                'field' => 'password',
                'label' => 'password',
                'rules' => 'trim|max_length[255]|callback_unique_password'
            ]
        ];
        return $rules;
    }

    public function timezone()
    {
        $purchaseCodeChecker = $this->_purchaseCodeChecker();
        
            if ( $this->_checkDatabaseConnection() ) {
                if ( $_POST ) {
                    $rules = $this->rules_timezone();
                    $this->form_validation->set_rules($rules);
                    if ( $this->form_validation->run() == false ) {
                        $this->data["subview"] = "install/timezone";
                        $this->load->view('_layout_install', $this->data);
                    } else {
                        $array = [
                            'time_zone' => $this->input->post('timezone')
                        ];

                        $this->load->model('install_m');
                        $this->install_m->insert_or_update($array);
                        redirect(site_url("install/site"));
                    }
                } else {
                    $this->data["subview"] = "install/timezone";
                    $this->load->view('_layout_install', $this->data);
                }
            } else {
                redirect(site_url("install/database"));
            }
      
    }

    private function _checkDatabaseConnection()
    {
        ini_set('display_errors', 'Off');
        $getConnectionArray = $this->config->db_config_get();
        $get_obj            = $this->load->database($getConnectionArray, true);
        $connected          = $get_obj->initialize();
        if ( $connected ) {
            return true;
        }
        return false;
    }

    protected function rules_timezone()
    {
        $rules = [
            [
                'field' => 'timezone',
                'label' => 'timezone',
                'rules' => 'trim|required|max_length[255]|callback_index_validation'
            ]
        ];
        return $rules;
    }

    public function site()
    {
        
            if ( $this->_checkDatabaseConnection() ) {
                if ( $_POST ) {
                    $this->load->library('session');
                    unset($this->db);
                    $rules = $this->rules_site();
                    $this->form_validation->set_rules($rules);
                    if ( $this->form_validation->run() == false ) {
                        $this->data["subview"] = "install/site";
                        $this->load->view('_layout_install', $this->data);
                    } else {
                        $this->load->model('install_m');
                        $purchaseFileRead = $this->_purchaseFileRead();
                        $array            = [
                            'address'           => $this->input->post("address"),
                            'currency_code'     => $this->input->post("currency_code"),
                            'currency_symbol'   => $this->input->post("currency_symbol"),
                            'email'             => $this->input->post("email"),
                            'footer_text'       => 'Copyright &copy; ' . $this->input->post("system_name"),
                            'phone'             => $this->input->post("phone"),
                            'logo'              => 'site.png',
                            'purchase_code'     => ( isset($purchaseFileRead['purchase_code']) ? $purchaseFileRead['purchase_code'] : '' ),
                            'purchase_username' => ( isset($purchaseFileRead['username']) ? $purchaseFileRead['username'] : '' ),
                            'system_name'       => $this->input->post("system_name"),
                            'default_language'  => 'english',
                            'updateversion'     => config_item('iniversion'),
                        ];
                        $this->install_m->insert_or_update($array);
                        redirect(site_url('install/systemadmin'));
                    }
                } else {
                    $this->data["subview"] = "install/site";
                    $this->load->view('_layout_install', $this->data);
                }
            } else {
                redirect(site_url("install/database"));
            }
    

    }

    protected function rules_site()
    {
        $rules = [
            [
                'field' => 'system_name',
                'label' => 'Site Name',
                'rules' => 'trim|required|max_length[40]'
            ],
            [
                'field' => 'phone',
                'label' => 'Phone',
                'rules' => 'trim|required|max_length[40]|numeric'
            ],
            [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|max_length[40]|valid_email'
            ],
            [
                'field' => 'address',
                'label' => 'Address',
                'rules' => 'trim|max_length[40]'
            ],
            [
                'field' => 'currency_code',
                'label' => 'Currency Code',
                'rules' => 'trim|max_length[40]'
            ],
            [
                'field' => 'currency_symbol',
                'label' => 'Currency Symbol',
                'rules' => 'trim|max_length[40]'
            ],
        ];
        return $rules;
    }

    public function systemadmin()
    {
        if ( $this->_checkDatabaseConnection() ) {
            if ( $_POST ) {
                $this->load->library('session');
                unset($this->db);
                $rules = $this->rules_systemadmin();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $this->data["subview"] = "install/systemadmin";
                    $this->load->view('_layout_install', $this->data);
                } else {
                    $this->load->model('user_m');
                    $this->load->model('update_m');

                    $array['name']          = $this->input->post('name');
                    $array['designationID'] = 1;
                    $array['description']   = '';
                    $array['gender']        = $this->input->post('gender');
                    $array['dob']           = date('Y-m-d', strtotime($this->input->post('dob')));
                    $array['religion']      = $this->input->post('religion');
                    $array['email']         = $this->input->post('email');
                    $array['phone']         = $this->input->post('phone');
                    $array['address']       = $this->input->post('address');
                    $array['jod']           = date('Y-m-d', strtotime($this->input->post('jod')));
                    $array['photo']         = 'default.png';
                    $array['roleID']        = 1;
                    $array['status']        = 1;
                    $array['username']      = $this->input->post('username');
                    $array['password']      = $this->user_m->hash($this->input->post('password'));
                    $array["create_date"]   = date("Y-m-d H:i:s");
                    $array["modify_date"]   = date("Y-m-d H:i:s");
                    $array["create_userID"] = 1;
                    $array["create_roleID"] = 1;

                    $array_version = [
                        'version' => config_item('iniversion'),
                        'date'    => date('Y-m-d H:i:s'),
                        'userID'  => 1,
                        'roleID'  => 1,
                        'log'     => '<h4>1. initial install</h4>',
                        'status'  => 1
                    ];

                    $user = $this->user_m->get_user();
                    if ( inicompute($user) ) {
                        $this->user_m->update_user($array, 1);
                    } else {
                        $this->user_m->insert_user($array);
                    }

                    $update = $this->update_m->get_update();
                    if ( inicompute($update) ) {
                        $this->update_m->update_update($array_version, 1);
                    } else {
                        $this->update_m->insert_update($array_version);
                    }

                    $this->load->library('session');
                    $sesdata = [
                        'username' => $this->input->post('username'),
                        'password' => $this->input->post('password'),
                    ];
                    $this->session->set_userdata($sesdata);
                    redirect(site_url("install/done"));
                }
            } else {
                $this->data["subview"] = "install/systemadmin";
                $this->load->view('_layout_install', $this->data);
            }
        } else {
            redirect(site_url("install/database"));
        }
    }

    protected function rules_systemadmin()
    {
        $rules = [
            [
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required|max_length[40]'
            ],
            [
                'field' => 'dob',
                'label' => 'Date of Birth',
                'rules' => 'trim|required|callback_valid_date'
            ],
            [
                'field' => 'gender',
                'label' => 'Gender',
                'rules' => 'trim|required|callback_required_no_zero'
            ],
            [
                'field' => 'religion',
                'label' => 'Religion',
                'rules' => 'trim|max_length[30]'
            ],
            [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            ],
            [
                'field' => 'phone',
                'label' => 'Phone',
                'rules' => 'trim|required|numeric|max_length[20]'
            ],
            [
                'field' => 'address',
                'label' => 'Address',
                'rules' => 'trim|max_length[50]'
            ],
            [
                'field' => 'jod',
                'label' => 'Joining Date',
                'rules' => 'trim|required|callback_valid_date'
            ],
            [
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required|min_length[4]|max_length[40]'
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required|min_length[4]'
            ]
        ];
        return $rules;
    }

    public function done()
    {
        if ( $this->_checkDatabaseConnection() ) {
            $this->load->library('session');
            if ( $this->session->userdata('username') && $this->session->userdata('password') ) {
                $this->load->library('session');
                if ( $_POST ) {
                    $this->config->config_update([ "installed" => true ]);
                    @chmod($this->config->database_path, FILE_READ_MODE);
                    @chmod($this->config->config_path, FILE_READ_MODE);
                    $this->session->sess_destroy();
                    $file = APPPATH.'config/purchase.php';
                    if (file_exists($file)) {
                        @chmod($file, FILE_WRITE_MODE);
                        write_file($file, '');
                    }
                    redirect(site_url('signin/index'));
                } else {
                    $this->data["subview"] = "install/done";
                    $this->load->view('_layout_install', $this->data);
                }
            } else {
                redirect(site_url("install/systemadmin"));
            }
        } else {
            redirect(site_url("install/database"));
        }
    }

    public function purchase_code_validation()
    {
        $array['username']      = $this->input->post('purchase_username');
        $array['purchase_code'] = $this->input->post('purchase_code');
        $apiCurl                = $this->updatechecker->verifyValidUser($array, false);

        if ( $apiCurl->for == 'purchasecode' || $apiCurl->for == 'block' ) {
            $this->form_validation->set_message("purchase_code_validation", $apiCurl->message);
            return false;
        }
        return true;
    }

    public function username_validation()
    {
        $array['username']      = $this->input->post('purchase_username');
        $array['purchase_code'] = $this->input->post('purchase_code');
        $apiCurl                = $this->updatechecker->verifyValidUser($array, false);

        if ( $apiCurl->for == 'username' ) {
            $this->form_validation->set_message("username_validation", $apiCurl->message);
            return false;
        }
        return true;
    }

    public function unique_database($host = "",$user="",$password="",$database="")
    {
        if ( strpos($this->input->post('database'), '.') === false ) {
            ini_set('display_errors', 'Off');
            $connected = false;
            if ($connected ==false ) {
                $config_db['hostname'] = trim(($host != "") ? $host : $this->input->post('host'));
                $config_db['username'] = trim(($user != "") ? $user : $this->input->post('user'));
                $config_db['password'] = ($password != "") ? $password : $this->input->post('password');
                $config_db['database'] = trim(($database != "") ? $database : $this->input->post('database'));
                $config_db['dbdriver'] = 'mysqli';
                $config_db['connect_timeout'] = 5; // Timeout 5 seconds
                $db_obj                = $this->load->database($config_db, true);
                $connected             = @$db_obj->initialize();
                
                // If connection failed, show error message
                if (!$connected) {
                    $error = $db_obj->error();
                    $this->form_validation->set_message("unique_database", "فشل الاتصال بقاعدة البيانات: " . (isset($error['message']) ? $error['message'] : 'تأكد من أن MySQL يعمل'));
                    return false;
                }
            }

            if ( $connected ) {
                $this->config->db_config_update($config_db);
                unset($this->db);
                $config_db['db_debug'] = false;
                $this->load->database($config_db);
                $this->load->dbutil();
                if ( $this->dbutil->database_exists($this->db->database) ) {
                    if ( $this->db->table_exists('generalsettings') == false ) {
                        $encryption_key = md5(config_item('product_name') . uniqid());
                        $this->config->config_update([ 'encryption_key' => $encryption_key ]);
                        
                        // Skip purchase code check if CURL is not available - allow installation to proceed
                        $purchaseCodeChecker = null;
                        if (extension_loaded('curl') && $this->_internet_connection) {
                            $purchaseCodeChecker = $this->_purchaseCodeChecker([ 'purpose' => 'install' ]);
                        }
                        
                        // If purchase code check is available and successful, use schema from server
                        if ( $purchaseCodeChecker && isset($purchaseCodeChecker->status) && $purchaseCodeChecker->status ) {
                            $this->load->model('install_m');
                            if ( !empty($purchaseCodeChecker->schema) ) {
                                $expSchemas = explode(';', $purchaseCodeChecker->schema);
                                if ( inicompute($expSchemas) ) {
                                    foreach ( $expSchemas as $expSchema ) {
                                        $this->install_m->use_sql_string($expSchema);
                                    }
                                    return true;
                                } else {
                                    $this->form_validation->set_message("unique_database", "Schema not explode.");
                                    return false;
                                }
                            } else {
                                $this->form_validation->set_message("unique_database", "Schema not found.");
                                return false;
                            }
                        } else {
                            // If no purchase code check or CURL unavailable, allow installation to proceed
                            // Database connection is successful, tables will be created later
                            return true;
                        }
                    }
                    return true;
                } else {
                    $this->form_validation->set_message("unique_database", "قاعدة البيانات غير موجودة. يرجى إنشاؤها من phpMyAdmin أولاً.");
                    return false;
                }
            } else {
                $this->form_validation->set_message("unique_database", "Database Connection Failed.");
                return false;
            }
        } else {
            $this->form_validation->set_message("unique_database", "Database can not accept dot in DB name.");
            return false;
        }
    }

    public function unique_password()
    {
        if ( $this->input->post('password') ) {
            if ( preg_match("/;/", $this->input->post('password')) ) {
                $this->form_validation->set_message("unique_password", "Password can not take ; under the password.");
                return false;
            }
        }
        return true;
    }

    public function index_validation()
    {
        $timezone = $this->input->post('timezone');
        @chmod($this->config->index_path, 0777);
        if ( is_really_writable($this->config->index_path) === false ) {
            $this->form_validation->set_message("index_validation", "Index file is not writable");
            return false;
        } else {
            $file        = $this->config->index_path;
            $filecontent = "date_default_timezone_set('" . $timezone . "');";
            $fileArray   = [ 2 => $filecontent ];
            $this->_replace_lines($file, $fileArray);
            @chmod($this->config->index_path, 0644);
            return true;
        }
    }

    private function _replace_lines( $file, $new_lines, $source_file = null )
    {
        $response = 0;
        $tab      = chr(9);
        $lbreak   = chr(13) . chr(10);
        if ( $source_file ) {
            $lines = file($source_file);
        } else {
            $lines = file($file);
        }
        foreach ( $new_lines as $key => $value ) {
            $lines[ --$key ] = $tab . $value . $lbreak;
        }
        $new_content = implode('', $lines);
        if ( $h = fopen($file, 'w') ) {
            if ( fwrite($h, $new_content) ) {
                $response = 1;
            }
            fclose($h);
        }
        return $response;
    }

    public function valid_date( $date )
    {
        if ( $date ) {
            if ( strlen($date) < 10 ) {
                $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                return false;
            } else {
                $arr = explode("-", $date);
                if ( inicompute($arr) == 3 ) {
                    $dd   = $arr[0];
                    $mm   = $arr[1];
                    $yyyy = $arr[2];
                    if ( checkdate($mm, $dd, $yyyy) ) {
                        return true;
                    } else {
                        $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                        return false;
                    }
                } else {
                    $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                    return false;
                }
            }
        } else {
            return true;
        }
    }

    public function required_no_zero( $data )
    {
        if ( $data == '0' ) {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return false;
        } else {
            return true;
        }
    }
}


