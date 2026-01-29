<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends Admin_Controller 
{
   
    public function __construct()
    {
        parent::__construct();
        $this->load->model("page_m");
        $this->load->model("post_m");
        $this->load->model("frontend_template_m");
        $this->load->model("media_gallery_m");
        $this->load->model("slider_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('page', $language);
    }

    protected function rules($visibility)
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("page_title"),
                'rules' => 'trim|required|max_length[128]'
            ),
            array(
                'field' => 'url',
                'label' => $this->lang->line("page_url"),
                'rules' => 'trim|required|max_length[240]|callback_unique_url'
            ),
            array(
                'field' => 'template',
                'label' => $this->lang->line("page_template"),
                'rules' => 'trim|required|max_length[250]'
            ),
            array(
                'field' => 'featured_image',
                'label' => $this->lang->line("page_featured_image"),
                'rules' => 'trim|max_length[11]|numeric'
            ),
            array(
                'field' => 'content',
                'label' => $this->lang->line("page_content"),
                'rules' => 'trim|callback_unique_content'
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line("page_status"),
                'rules' => 'trim|required|max_length[10]'
            ),
            array(
                'field' => 'visibility',
                'label' => $this->lang->line("page_visibility"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'publish_month',
                'label' => 'Month',
                'rules' => 'trim|required|max_length[2]|callback_validateDate'
            ),
            array(
                'field' => 'publish_day',
                'label' => 'Day',
                'rules' => 'trim|required|max_length[2]|callback_validateDate'
            ),
            array(
                'field' => 'publish_year',
                'label' => 'Year',
                'rules' => 'trim|required|max_length[4]|callback_validateDate'
            ),
            array(
                'field' => 'publish_hour',
                'label' => 'Hour',
                'rules' => 'trim|required|max_length[2]|callback_validateDate'
            ),
            array(
                'field' => 'publish_minute',
                'label' => 'Minute',
                'rules' => 'trim|required|max_length[2]|callback_validateDate'
            ),
            array(
                'field' => 'hidden_slider_images',
                'label' => 'Slider Images',
                'rules' => 'trim'
            )
        );

        if($visibility == 2) {
            $rules[] = array(
                'field' => 'protected_password',
                'label' => 'Protected Password',
                'rules' => 'trim|max_length[40]'
            );
        }

        return $rules;
    }

    public function validateDate()
    {
        $status     = FALSE;
        $error      = 0;
        $month      = $this->input->post('publish_month');
        $day        = $this->input->post('publish_day');
        $year       = $this->input->post('publish_year');
        $hour       = $this->input->post('publish_hour');
        $minute     = $this->input->post('publish_minute');

        if(empty($month) || empty($day) || empty($year) || empty($hour) || empty($minute)) {
            $error++;
        }

        if($error > 0) {
            $this->form_validation->set_message("validateDate", "The %s field is required.");
        } else {
            $format     = "Y-m-d H:i:s";
            $date       = $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute.':'.'01';

            if(validateDate($date, $format)) {
                $status = TRUE;
            } else {
                $this->form_validation->set_message("validateDate", "The date & time is invalid.");
            }
        }
        return $status;
    }

    private function _dateCompare($data = NULL)
    {
        if($data == NULL) {
            $month      = $this->input->post('publish_month');
            $day        = $this->input->post('publish_day');
            $year       = $this->input->post('publish_year');
            $hour       = $this->input->post('publish_hour');
            $minute     = $this->input->post('publish_minute');
            $date       = $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute;
            if(strtotime($date) == strtotime(date('Y-m-d H:i'))) {
                return 'same';
            } else {
                return date('M d, Y @ H:i', strtotime($date));
            }
        } else {
            if(strtotime($data) == strtotime(date('Y-m-d H:i:s'))) {
                return 'same';
            } else {
                return date('M d, Y @ H:i', strtotime($data));
            }
        }
    }

    public function index()
    {
        $this->session->unset_userdata('media_gallery_stroge');
        if($this->session->flashdata('pageSubmitType')) {
            $url = site_url('frontend/page/'.$this->session->flashdata('sesPageUrl'));
            redirect($url);
        }

        if($this->session->userdata('sesPageReUrl')) {
            redirect(site_url('page/edit/'.$this->session->userdata('sesPageReUrlEditID')));
        }

        $this->data['headerassets'] = array(
            'css' => array(
                'assets/wp-style/assets/css/style.css',
                'assets/wp-style/assets/css/galleryforeditor.css',
                'assets/summernote/summernote-bs4.css',
                'assets/iniPlaylist/iniplaylist.css',
            )
        );

        $this->data['footerassets'] = array(
            'js' => array(
                'assets/summernote/summernote-bs4.min.js',
                'assets/wp-style/assets/js/wp.js',
                'assets/inilabs/page/index.js',
            )
        );

        $this->data['jsmanager'] = [
            'page_draft'              => $this->lang->line('page_draft'),
            'page_save_draft'         => $this->lang->line('page_save_draft'),
            'page_pending_review'     => $this->lang->line('page_pending_review'),
            'page_save_as_pending'    => $this->lang->line('page_save_as_pending'),
            'page_public'             => $this->lang->line('page_public'),
            'page_password_protected' => $this->lang->line('page_password_protected'),
            'page_private'            => $this->lang->line('page_private'),
            'page_immediately'        => $this->lang->line('page_immediately'),
            'page_immediately'        => $this->lang->line('page_immediately'),
        ];

        $this->data['activetab']                = true;
        $this->data['pages']                    = $this->page_m->get_page();
        $this->data['frontend_templates']       = $this->frontend_template_m->get_frontend_template();
        $this->data['media_gallerys_all']       = $this->media_gallery_m->get_media_gallery();
        $this->data['media_gallerys_images']    = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 1));
        $this->data['media_gallerys_audios']    = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 2));
        $this->data['media_gallerys_videos']    = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 3));

        if($_POST) {
            $this->data['activetab']    = false;
            $rules                      = $this->rules($this->input->post('visibility'));
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["send_status"]      = $this->input->post('status');
                $this->data["send_visibility"]  = $this->input->post('visibility');
                $this->data["send_publish"]     = $this->validateDate();
                if($this->data["send_publish"]) {
                    $this->data['send_date_status'] = $this->_dateCompare();
                }

                if(!empty($this->input->post('featured_image'))) {
                    $this->data['send_featured_image'] = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $this->input->post('featured_image')));
                } else {
                    $this->data['send_featured_image'] = [];
                }

                if(!empty($this->input->post('hidden_slider_images'))) {
                    $imgArray = [];
                    $expImg = explode(',', $this->input->post('hidden_slider_images'));
                    if(inicompute($expImg)) {
                        foreach ($expImg as $expKey => $expValue) {
                            if(!empty($expValue)) {
                                $callImage = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $expValue));
                                if(inicompute($callImage)) {
                                    $imgArray[$expValue] = $callImage->file_name;
                                }

                            }
                        }
                    }
                    $this->data['send_slider_images'] = $imgArray;
                } else {
                    $this->data['send_slider_images'] = [];
                }

                if(!empty($this->input->post('url'))) {
                    $this->data['send_url'] = array('status' => TRUE, 'url' => $this->input->post('url'));
                } else {
                    $this->data['send_url'] = array('status' => FALSE);
                }

                $this->data["subview"] = "page/index";
                $this->load->view('_layout_main', $this->data);
            } else {
                $date = $this->input->post('publish_year').'-'.$this->input->post('publish_month').'-'.$this->input->post('publish_day').' '.$this->input->post('publish_hour').':'.$this->input->post('publish_minute').':01';

                if($this->input->post('submit') == 'Publish') {
                    $status = 1;
                } else {
                    $status = pageStatus($this->input->post('status'));
                }

                $array = array(
                    'title'             => ucwords($this->input->post('title')),
                    'url'               => $this->input->post('url'),
                    'content'           => xssRemove(str_replace('<p><br></p>', '', $_POST['content'])),
                    'status'            => $status,
                    'visibility'        => $this->input->post('visibility'),
                    'publish_date'      => $date,
                    'parentID'          => 0,
                    'pageorder'         => 0,
                    'template'          => $this->input->post('template'),
                    'featured_image'    => $this->input->post('featured_image'),
                    'create_date'       => date("Y-m-d H:i:s"),
                    'modify_date'       => date("Y-m-d H:i:s"),
                    'create_userID'     => $this->session->userdata('loginuserID'),
                    'create_username'   => $this->session->userdata('username'),
                    'create_usertypeID' => $this->session->userdata('usertypeID'),
                );

                if($this->input->post('visibility') == 2) {
                    $array['password'] = $this->input->post('protected_password');
                }

                $this->page_m->insert_page($array);
                $lastID = $this->db->insert_id();

                if(!empty($this->input->post('hidden_slider_images'))) {
                    $expImg = explode(',', $this->input->post('hidden_slider_images'));
                    if(inicompute($expImg)) {
                        foreach ($expImg as $expKey => $expValue) {
                            if(!empty($expValue)) {
                                $callImage = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $expValue));
                                if(inicompute($callImage)) {
                                    $this->slider_m->insert_slider(array('pageID' => $lastID, 'slider' => $expValue));
                                }
                            }
                        }
                    }
                }

                if($this->input->post('submit') == 'preview') {
                    $this->session->set_flashdata('pageSubmitType', 'preview');
                    $this->session->set_flashdata('sesPageUrl', $this->input->post('url'));
                    $this->session->set_userdata('sesPageReUrl', TRUE);
                    $this->session->set_userdata('sesPageReUrlEditID', $lastID);
                    redirect(site_url('page/index'));
                } elseif($this->input->post('submit') == 'draft' || $this->input->post('submit') == 'review') {
                    redirect(site_url('page/edit/'.$lastID));
                } else {
                    $this->session->set_flashdata('success', 'Success');
                    redirect(site_url("page/index"));
                }
            }
        } else {
            $this->data["send_status"]          = "draft";
            $this->data["send_visibility"]      = 1;
            $this->data["send_publish"]         = TRUE;
            $this->data["send_date_status"]     = 'same';
            $this->data['send_featured_image']  = [];
            $this->data['send_slider_images']   = [];
            $this->data['send_url']             = ['status' => FALSE];

            $this->data["subview"] = "page/index";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit()
    {
        $this->session->unset_userdata('media_gallery_stroge');
        $this->session->unset_userdata('sesPageReUrl');
        $this->session->unset_userdata('sesPageReUrlEditID');

        $this->data['headerassets'] = array(
            'css' => array(
                'assets/wp-style/assets/css/style.css',
                'assets/wp-style/assets/css/galleryforeditor.css',
                'assets/summernote/summernote-bs4.css',
                'assets/iniPlaylist/iniplaylist.css',
            )
        );

        $this->data['footerassets'] = array(
            'js' => array(
                'assets/summernote/summernote-bs4.min.js',
                'assets/wp-style/assets/js/wp.js',
                'assets/inilabs/page/index.js',
            )
        );

        $this->data['jsmanager'] = [
            'page_draft'              => $this->lang->line('page_draft'),
            'page_save_draft'         => $this->lang->line('page_save_draft'),
            'page_pending_review'     => $this->lang->line('page_pending_review'),
            'page_save_as_pending'    => $this->lang->line('page_save_as_pending'),
            'page_public'             => $this->lang->line('page_public'),
            'page_password_protected' => $this->lang->line('page_password_protected'),
            'page_private'            => $this->lang->line('page_private'),
            'page_immediately'        => $this->lang->line('page_immediately'),
            'page_immediately'        => $this->lang->line('page_immediately'),
        ];

        $this->data['pages']                    = $this->page_m->get_page();
        $this->data['frontend_templates']       = $this->frontend_template_m->get_frontend_template();
        $this->data['media_gallerys_all']       = $this->media_gallery_m->get_media_gallery();
        $this->data['media_gallerys_images']    = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 1));
        $this->data['media_gallerys_audios']    = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 2));
        $this->data['media_gallerys_videos']    = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 3));

        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['activetab']    = true;
            $this->data['page']         = $this->page_m->get_page($id);
            if($this->data['page']) {
                if($_POST) {
                    $rules = $this->rules($this->input->post('visibility'));
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["send_status"]      = $this->input->post('status');
                        $this->data["send_visibility"]  = $this->input->post('visibility');
                        $this->data["send_publish"]     = $this->validateDate();
                        if($this->data["send_publish"]) {
                            $this->data['send_date_status'] = $this->_dateCompare();
                        }

                        if(!empty($this->input->post('featured_image'))) {
                            $this->data['send_featured_image'] = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $this->input->post('featured_image')));
                        } else {
                            $this->data['send_featured_image'] = [];
                        }

                        if(!empty($this->input->post('hidden_slider_images'))) {
                            $imgArray = [];
                            $expImg = explode(',', $this->input->post('hidden_slider_images'));
                            if(inicompute($expImg)) {
                                foreach ($expImg as $expKey => $expValue) {
                                    if(!empty($expValue)) {
                                        $callImage = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $expValue));
                                        if(inicompute($callImage)) {
                                            $imgArray[$expValue] = $callImage->file_name;
                                        }
                                    }
                                }
                            }

                            $this->data['send_hidden_slider_images'] = $this->input->post('hidden_slider_images');
                            $this->data['send_slider_images'] = $imgArray;
                        } else {
                            $this->data['send_hidden_slider_images'] = $this->input->post('hidden_slider_images');
                            $this->data['send_slider_images'] = [];
                        }

                        if(!empty($this->input->post('url'))) {
                            $this->data['send_url'] = array('status' => TRUE, 'url' => $this->input->post('url'));
                        } else {
                            $this->data['send_url'] = array('status' => FALSE);
                        }

                        $this->data["subview"] = "page/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $date       = $this->input->post('publish_year').'-'.$this->input->post('publish_month').'-'.$this->input->post('publish_day').' '.$this->input->post('publish_hour').':'.$this->input->post('publish_minute').':01';
                        $status     = pageStatus($this->input->post('status'));

                        $array = array(
                            'title'             => ucwords($this->input->post('title')),
                            'url'               => $this->input->post('url'),
                            'content'           =>  xssRemove(str_replace('<p><br></p>', '', $_POST['content'])),
                            'status'            => $status,
                            'visibility'        => $this->input->post('visibility'),
                            'publish_date'      => $date,
                            'parentID'          => 0,
                            'pageorder'         => 0,
                            'template'          => $this->input->post('template'),
                            'featured_image'    => $this->input->post('featured_image'),
                            'modify_date'       => date("Y-m-d H:i:s"),
                        );

                        if($this->input->post('visibility') == 2) {
                            $array['password'] = $this->input->post('protected_password');
                        }

                        $sliders = $this->slider_m->get_order_by_slider(array('pageID' => $id));
                        if(inicompute($sliders)) {
                            foreach($sliders as $slider) {
                                $this->slider_m->delete_slider($slider->sliderID);
                            }
                        }

                        if(!empty($this->input->post('hidden_slider_images'))) {
                            $expImg = explode(',', $this->input->post('hidden_slider_images'));
                            if(inicompute($expImg)) {
                                foreach ($expImg as $expKey => $expValue) {
                                    if(!empty($expValue)) {
                                        $callImage = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $expValue));
                                        if(inicompute($callImage)) {
                                            $this->slider_m->insert_slider(array('pageID' => $id, 'slider' => $expValue));
                                        }
                                    }
                                }
                            }
                        }

                        $this->page_m->update_page($array, $id);
                        if($this->input->post('submit') == 'preview') {
                            $this->session->set_flashdata('pageSubmitType', 'preview');
                            $this->session->set_flashdata('sesPageUrl', $this->input->post('url'));
                            redirect(site_url('page/edit/'.$id));
                        } else {
                            redirect(site_url("page/index"));
                        }
                    }
                } else {
                    $this->data["send_status"]          = pageStatus($this->data['page']->status, FALSE);
                    $this->data["send_visibility"]      = $this->data['page']->visibility;
                    $this->data["send_publish"]         = TRUE;
                    $this->data["send_date_status"]     = $this->_dateCompare($this->data['page']->publish_date);
                    $this->data['send_featured_image']  = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $this->data['page']->featured_image));
                    $this->data['send_slider_images']   = [];

                    $getSliders = $this->slider_m->get_order_by_slider(array('pageID' => $this->data['page']->pageID));
                    if(inicompute($getSliders)) {
                        $imgArray = [];
                        $hidden_slider_images = '';
                        foreach ($getSliders as $getSlidersKey => $getSlider) {
                            $callImage = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $getSlider->slider));
                            if(inicompute($callImage)) {
                                $hidden_slider_images .= ','.$callImage->media_galleryID;
                                $imgArray[$callImage->media_galleryID] = $callImage->file_name;
                            }
                        }

                        $this->data['send_hidden_slider_images']    = $hidden_slider_images;
                        $this->data['send_slider_images']           = $imgArray;
                    } else {
                        $this->data['send_hidden_slider_images']    = '';
                        $this->data['send_slider_images']           = [];
                    }

                    $this->data['send_url'] = array('status' => TRUE, 'url' => $this->data['page']->url );
                    $this->data["subview"] = "page/edit";
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function delete()
    {
        $pageID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$pageID) {
            $this->data['pages'] = $this->page_m->get_single_page(array('pageID' => $pageID));
            if(inicompute($this->data['pages'])) {
                $this->page_m->delete_page($pageID);
                $this->session->set_flashdata('message', 'Success');
                redirect(site_url("page/index"));
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_content() {
        if($this->input->post('template') == 'none') {
            if(empty($this->input->post('content'))) {
                $this->form_validation->set_message("unique_content", "The %s field is required.");
                return FALSE;
            }
            return TRUE;
        }
        return TRUE;
    }

    public function unique_url()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $page = $this->page_m->get_order_by_page(array("url" => $this->input->post("url"), 'pageID !=' => $id));
            $post = $this->post_m->get_order_by_post(array("url" => $this->input->post("url")));
            if(inicompute($page)) {
                $this->form_validation->set_message("unique_url", "The %s is already exists.");
                return FALSE;
            }

            if(inicompute($post)) {
                $this->form_validation->set_message("unique_url", "The %s is already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $page = $this->page_m->get_order_by_page(array("url" => $this->input->post("url")));
            $post = $this->post_m->get_order_by_post(array("url" => $this->input->post("url")));
            if(inicompute($page)) {
                $this->form_validation->set_message("unique_url", "The %s is already exists.");
                return FALSE;
            }

            if(inicompute($post)) {
                $this->form_validation->set_message("unique_url", "The %s is already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }

    public function fileUpload()
    {
        $insert_media_content           = '';
        $create_gallery_content         = '';
        $create_audio_playlist_content  = '';
        $create_video_playlist_content  = '';
        $featured_image_content         = '';
        $set_featured_image_content     = '';
        $set_slider_images_content      = '';
        $status                         = FALSE;
        $focus_id                       = $this->input->post('focus_id');
        $media_gallery_type             = $this->input->post('media_gallery_type');

        if($focus_id && $media_gallery_type) {
            if((int)$media_gallery_type) {
                if($media_gallery_type >= 1 && $media_gallery_type <= 4) {
                    if($media_gallery_type == 1) {
                        $fileType = "gif|jpg|png|mp3|pcm|wav|aac|ogg|wma|mkv|flv|vob|wmv|mpg|avi|webm|ogv|mp4|3gp";
                    } elseif($media_gallery_type == 2) {
                        $fileType = "gif|jpg|png";
                    } elseif($media_gallery_type == 3) {
                        $fileType = 'mp3|pcm|wav|aac|ogg|wma';
                    } elseif($media_gallery_type == 4) {
                        $fileType = 'mkv|flv|vob|wmv|mpg|avi|mp4|webm|ogv|mp4|3gp';
                    } else {
                        $fileType = 'none';
                    }

                    if($fileType != 'none') {
                        if($_FILES["file"]['name'] !="") {
                            $file_name          = $_FILES["file"]['name'];
                            $random             = random19();
                            $makeRandom         = hash('sha512', $random. $_FILES["file"]['name'] . config_item("encryption_key"));
                            $file_name_rename   = $makeRandom;
                            $explode = explode('.', $file_name);
                            if(inicompute($explode) >= 2) {
                                $new_file = $file_name_rename.'.'.end($explode);
                                $config['upload_path']      = "./uploads/gallery";
                                $config['allowed_types']    = $fileType;
                                $config['file_name']        = $new_file;
                                $config['max_size']         = '819200';
                                $config['max_width']        = '500000';
                                $config['max_height']       = '500000';
                                $this->load->library('upload', $config);
                                if(!$this->upload->do_upload("file")) {
                                    $message = str_replace('</p>', '', str_replace('<p>', '', $this->upload->display_errors()));
                                } else {
                                    $media_gallery_type = $this->_checkFileType($_FILES['file']);
                                    $fileArray = array(
                                        'media_gallery_type'    => $media_gallery_type,
                                        'file_type'             => end($explode),
                                        'file_name'             => $new_file,
                                        'file_original_name'    => $file_name,
                                        'file_title'            => pathinfo($file_name, PATHINFO_FILENAME),
                                        'file_size'             => formatSizeUnits($_FILES['file']['size']),
                                        'file_upload_date'      => date("Y-m-d H:i:s"),
                                    );

                                    if($media_gallery_type == 1) {
                                        $image_info                     = getimagesize($_FILES["file"]["tmp_name"]);
                                        $image_width                    = $image_info[0];
                                        $image_height                   = $image_info[1];
                                        $fileArray['file_width_height'] = $image_width . ' x ' .$image_height;
                                    } elseif($media_gallery_type == 2) {
                                        // $fileArray['file_length'] = $_FILES['file']['hours'];
                                    } elseif($media_gallery_type == 3) {
                                        // $fileArray['file_length'] = $_FILES['file']['hours'];
                                        // $getID3 = new getID3;
                                        // $file = $getID3->analyze($_FILES["file"]['name']);
                                    }

                                    $this->media_gallery_m->insert_media_gallery($fileArray);
                                    $status     = TRUE;
                                    $message    = 'Success';

                                    $this->data['media_gallerys_all']       = $this->media_gallery_m->get_media_gallery();
                                    $this->data['media_gallerys_images']    = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 1));
                                    $this->data['media_gallerys_audios']    = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 2));
                                    $this->data['media_gallerys_videos']    = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 3));

                                    $insert_media_content           = $this->load->view('page/content/insert_media', $this->data, true);
                                    $create_gallery_content         = $this->load->view('page/content/create_gallery', $this->data, true);
                                    $create_audio_playlist_content  = $this->load->view('page/content/create_audio_playlist', $this->data, true);
                                    $create_video_playlist_content  = $this->load->view('page/content/create_video_playlist', $this->data, true);
                                    $featured_image_content         = $this->load->view('page/content/featured_image', $this->data, true);
                                    $set_featured_image_content     = $this->load->view('page/content/set_featured_image', $this->data, true);
                                    $set_slider_images_content      = $this->load->view('page/content/set_slider_images', $this->data, true);
                                }
                            } else {
                                $message = 'Invalid file';
                            }
                        } else {
                            $message = 'File does not found';
                        }
                    } else {
                        $message = 'File type is not match';
                    }
                } else {
                    $message = 'Media gallery type id is not match';
                }
            } else {
                $message = 'Media gallery type is not integer';
            }
        } else {
            $message = 'Focus and media gallery type empty';
        }

        $json = array(
            "message" => $message,
            'focus_id' => $focus_id,
            'insert_media_content' => $insert_media_content,
            'create_gallery_content' => $create_gallery_content,
            'create_audio_playlist_content' => $create_audio_playlist_content,
            'create_video_playlist_content' => $create_video_playlist_content,
            'featured_image_content' => $featured_image_content,
            'set_featured_image_content' => $set_featured_image_content,
            'set_slider_images_content' => $set_slider_images_content,
            'status' => $status,
        );
        header("Content-Type: application/json", true);
        echo json_encode($json);
        exit;
    }

    public function getFileInfo()
    {
        $id                             = htmlentities(escapeString($this->input->post('id')));
        $media_type                     = htmlentities(escapeString($this->input->post('media_type')));
        $imageArray['file_status']      = false;
        $imageArray['content']          = '';

        if((int) $id && $media_type) {
            $this->data['image_info'] = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $id));
            if(inicompute($this->data['image_info'])) {
                $imageArray = (array) $this->data['image_info'];
                $imageArray['file_status'] = true;
                if($media_type == 1) {
                    $imageArray['content'] = $this->load->view('page/content/callImageInfo', $this->data, true);
                } elseif($media_type == 2) {
                    $imageArray['content'] = $this->load->view('page/content/callAudioInfo', $this->data, true);
                } elseif($media_type == 3) {
                    $imageArray['content'] = $this->load->view('page/content/callVideoInfo', $this->data, true);
                }
            }
        }

        header("Content-Type: application/json", true);
        echo json_encode($imageArray);
        exit;
    }

    public function deleteFileInfo()
    {
        $id                             = $this->input->post('id');
        $insert_media_content           = '';
        $create_gallery_content         = '';
        $create_audio_playlist_content  = '';
        $create_video_playlist_content  = '';
        $featured_image_content         = '';
        $set_featured_image_content     = '';
        $set_slider_images_content      = '';
        $status                         = false;
        if((int) $id) {
            $image_info = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $id));
            if(inicompute($image_info)) {
                if(config_item('demo') == FALSE) {
                    if(file_exists(FCPATH.'uploads/gallery/'.$image_info->file_name)) {
                        unlink(FCPATH.'uploads/gallery/'.$image_info->file_name);
                    }
                }

                $this->media_gallery_m->delete_media_gallery($id);
                $message = 'Success';
                $status = true;

                $this->data['media_gallerys_all']       = $this->media_gallery_m->get_media_gallery();
                $this->data['media_gallerys_images']    = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 1));
                $this->data['media_gallerys_audios']    = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 2));
                $this->data['media_gallerys_videos']    = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 3));

                $insert_media_content           = $this->load->view('page/content/insert_media', $this->data, true);
                $create_gallery_content         = $this->load->view('page/content/create_gallery', $this->data, true);
                $create_audio_playlist_content  = $this->load->view('page/content/create_audio_playlist', $this->data, true);
                $create_video_playlist_content  = $this->load->view('page/content/create_video_playlist', $this->data, true);
                $featured_image_content         = $this->load->view('page/content/featured_image', $this->data, true);
                $set_featured_image_content     = $this->load->view('page/content/set_featured_image', $this->data, true);
                $set_slider_images_content      = $this->load->view('page/content/set_slider_images', $this->data, true);
            } else {
                $message = 'File does not found';
            }
        } else {
            $message = 'File ID is not integer';
        }

        $json = array(
            "message"                           => $message,
            'insert_media_content'              => $insert_media_content,
            'create_gallery_content'            => $create_gallery_content,
            'create_audio_playlist_content'     => $create_audio_playlist_content,
            'create_video_playlist_content'     => $create_video_playlist_content,
            'featured_image_content'            => $featured_image_content,
            'set_featured_image_content'        => $set_featured_image_content,
            'set_slider_images_content'         => $set_slider_images_content,
            'status'                            => $status
        );

        header("Content-Type: application/json", true);
        echo json_encode($json);
        exit;
    }

    private function _checkFileType($file)
    {
        $filetype = 'none';
        if(isset($file)) {
            $mime = $file['type'];
            if(strstr($mime, "video/")){
                $filetype = 3;
            }else if(strstr($mime, "image/")){
                $filetype = 1;
            }else if(strstr($mime, "audio/")){
                $filetype = 2;
            }
        }
        return $filetype;
    }

    public function setFileToEditor()
    {
        $i = 1;
        $audioStatus        = FALSE;
        $videoStatus        = FALSE;
        $imageStatus        = FALSE;
        $header             = '';
        $footer             = '';
        $this->data['rand'] = '';
        $content            = '';
        $warpContent        = '';
        $message            = 'Success';
        $status             = FALSE;
        $allID              = htmlentities(escapeString($this->input->post('allID')));
        $media_type         = htmlentities(escapeString($this->input->post('media_type')));
        $ulclass_type       = htmlentities(escapeString($this->input->post('ulclass_type')));

        if((int)$media_type && $allID && $ulclass_type) {
            $expID = explode(',', $allID);
            if(inicompute($expID) >= 2) {
                $sessionData = $this->session->userdata('media_gallery_stroge');
                $lastID = end($expID);
                foreach ($expID as $expKey => $expValue) {
                    if(!empty($expValue)) {
                        $get_media_gallery = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $expValue));
                        if(isset($sessionData[$expValue])) {
                            $array = $sessionData[$expValue];
                            if(inicompute($get_media_gallery)) {
                                $this->media_gallery_m->update_media_gallery($array, $expValue);
                            }
                        } else {
                            $message = 'Some session unset';
                        }

                        $get_media_gallery = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $expValue));
                        if(inicompute($get_media_gallery)) {
                            if($get_media_gallery->media_gallery_type == 1) {
                                if($ulclass_type == 'set_slider_images_type') {
                                    $this->data['imageContent'] = $get_media_gallery;
                                    $imageContent = $this->load->view('page/content/image/slider_full_content', $this->data, true);
                                    $warpContent .= $imageContent;
                                    if($lastID == $expValue) {
                                        $content .= $warpContent;
                                    }
                                } elseif($ulclass_type == 'create_gallery_type') {
                                    $this->data['imageContent'] = $get_media_gallery;
                                    if($imageStatus == FALSE) {
                                        $header = $this->load->view('page/content/image/gallery_header', $this->data, true);
                                        $footer = $this->load->view('page/content/image/gallery_footer', $this->data, true);
                                        $imageStatus = TRUE;
                                    }

                                    $imageContent = $this->load->view('page/content/image/gallery_content', $this->data, true);
                                    $warpContent .= $imageContent;
                                    if($lastID == $expValue) {
                                        $content .= $header;
                                        $content .= $warpContent;
                                        $content .= $footer;
                                    }
                                } else {
                                    $warpContent = '<img src="'.base_url('uploads/gallery/'.$get_media_gallery->file_name).'" alt="'.$get_media_gallery->file_alt_text.'">';
                                    $content .= $warpContent;
                                }
                            } elseif($get_media_gallery->media_gallery_type == 2) {
                                $this->data['audioContent'] = $get_media_gallery;
                                if($audioStatus == FALSE) {
                                    $rand = random19();
                                    $this->data['rand'] = $rand;
                                    $header = $this->load->view('page/content/audio/audio_header', $this->data, true);
                                    $footer = $this->load->view('page/content/audio/audio_footer', $this->data, true);
                                }
                                $this->data['i'] = $i;
                                $this->data['audioStatus'] = $audioStatus;
                                $audioContent = $this->load->view('page/content/audio/audio_content', $this->data, true);
                                $audioStatus = TRUE;
                                $i++;

                                $warpContent .= $audioContent;
                                if($lastID == $expValue) {
                                    $content .= $header;
                                    $content .= $warpContent;
                                    $content .= $footer;
                                }
                            } elseif($get_media_gallery->media_gallery_type == 3) {
                                $this->data['videoContent'] = $get_media_gallery;
                                if($videoStatus == FALSE) {
                                    $rand = random19();
                                    $this->data['rand'] = $rand;
                                    $header = $this->load->view('page/content/video/video_header', $this->data, true);
                                    $footer = $this->load->view('page/content/video/video_footer', $this->data, true);
                                }
                                $this->data['i'] = $i;
                                $this->data['videoStatus'] = $videoStatus;
                                $videoContent = $this->load->view('page/content/video/video_content', $this->data, true);
                                $videoStatus = TRUE;
                                $i++;

                                $warpContent .= $videoContent;
                                if($lastID == $expValue) {
                                    $content .= $header;
                                    $content .= $warpContent;
                                    $content .= $footer;
                                }
                            }
                        }
                    } else {
                        $message = 'Empty each value';
                    }
                }
                $status = TRUE;
            } else {
                if((int)$allID) {
                    $sessionData = $this->session->userdata('media_gallery_stroge');
                    if(isset($sessionData[$allID])) {
                        $array = $sessionData[$allID];
                        $this->media_gallery_m->update_media_gallery($array, $allID);
                    } else {
                        $message = 'Some session unset';
                    }

                    $get_media_gallery = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $allID));
                    if(inicompute($get_media_gallery)) {
                        if($get_media_gallery->media_gallery_type == 1) {
                            $content = '<img src="'.base_url('uploads/gallery/'.$get_media_gallery->file_name).'" alt="'.$get_media_gallery->file_alt_text.'">';
                        } elseif($get_media_gallery->media_gallery_type == 2) {
                            $rand = random19();
                            $this->data['rand'] = $rand;
                            $this->data['audioContent'] = $get_media_gallery;
                            $content = $this->load->view('page/content/audio/audio_single', $this->data, true);
                        } elseif($get_media_gallery->media_gallery_type == 3) {
                            $rand = random19();
                            $this->data['rand'] = $rand;
                            $this->data['videoContent'] = $get_media_gallery;
                            $content = $this->load->view('page/content/video/video_single', $this->data, true);
                        }
                    }
                    $status = TRUE;
                } else {
                    $message = 'ID is not int';
                }
            }
        } else {
            $message = 'Some poost data missing';
        }

        $this->data['media_gallerys_all']       = $this->media_gallery_m->get_media_gallery();
        $this->data['media_gallerys_images']    = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 1));
        $this->data['media_gallerys_audios']    = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 2));
        $this->data['media_gallerys_videos']    = $this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 3));

        $insert_media_content           = $this->load->view('page/content/insert_media', $this->data, true);
        $create_gallery_content         = $this->load->view('page/content/create_gallery', $this->data, true);
        $create_audio_playlist_content  = $this->load->view('page/content/create_audio_playlist', $this->data, true);
        $create_video_playlist_content  = $this->load->view('page/content/create_video_playlist', $this->data, true);
        $featured_image_content         = $this->load->view('page/content/featured_image', $this->data, true);
        $set_featured_image_content     = $this->load->view('page/content/set_featured_image', $this->data, true);
        $set_slider_images_content      = $this->load->view('page/content/set_slider_images', $this->data, true);

        header("Content-Type: application/json", true);
        echo json_encode(
            array(
                'message'                           => $message,
                'status'                            => $status,
                'content'                           => $content,
                'insert_media_content'              => $insert_media_content,
                'create_gallery_content'            => $create_gallery_content,
                'create_audio_playlist_content'     => $create_audio_playlist_content,
                'create_video_playlist_content'     => $create_video_playlist_content,
                'featured_image_content'            => $featured_image_content,
                'set_featured_image_content'        => $set_featured_image_content,
                'set_slider_images_content'         => $set_slider_images_content,
            )
        );
        exit;
    }

    public function setFileInfo()
    {
        $media_galleryID = $this->input->post('hidden_id_field');
        if(!empty($media_galleryID)) {
            $file_title         = htmlentities(escapeString($this->input->post('file_title')));
            $file_caption       = htmlentities(escapeString($this->input->post('file_caption')));
            $file_description   = htmlentities(escapeString($this->input->post('file_description')));
            $file_alt_text      = htmlentities(escapeString($this->input->post('file_alt_text')));
            $file_artist        = htmlentities(escapeString($this->input->post('file_artist')));
            $file_album         = htmlentities(escapeString($this->input->post('file_album')));

            if(isset($file_title) && isset($file_caption) && isset($file_description) && isset($file_alt_text) && isset($file_artist) && isset($file_album)) {
                $dData = $this->session->userdata('media_gallery_stroge');
                if($dData == '') {
                    $newdData = array(
                        $media_galleryID => array(
                            'file_title'        => $file_title,
                            'file_caption'      => $file_caption,
                            'file_description'  => $file_description,
                            'file_alt_text'     => $file_alt_text,
                            'file_artist'       => $file_artist,
                            'file_album'        => $file_album,
                        )
                    );
                    $this->session->set_userdata('media_gallery_stroge', $newdData);
                } else {
                    $dData[$media_galleryID] = array(
                        'file_title'        => $file_title,
                        'file_caption'      => $file_caption,
                        'file_description'  => $file_description,
                        'file_alt_text'     => $file_alt_text,
                        'file_artist'       => $file_artist,
                        'file_album'        => $file_album,
                    );
                    $this->session->set_userdata('media_gallery_stroge', $dData);
                }
            }
        }
        echo 'success';
    }
}