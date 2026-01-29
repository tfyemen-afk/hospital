<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

class Update extends Admin_Controller
{
    protected $_memoryLimit = '1024M';
    protected $_versionCheckingUrl = 'http://demo.inilabs.net/autoupdate/update/index';
    protected $_updateFileUrl = 'http://demo.inilabs.net/autoupdate/updatefiles/hospital/';
    protected $_successUrl = 'http://demo.inilabs.net/autoupdate/update/success';
    protected $_downloadPath = FCPATH . 'uploads/update';
    protected $_downloadFileWithPath = '';
    protected $_downloadExtractPath = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->model("update_m");
        $this->load->model("signin_m");

        $language = $this->session->userdata('lang');
        $this->lang->load('update', $language);
        if ( config_item('demo') ) {
            $this->session->set_flashdata('error', 'In demo update module is disable!');
            redirect(site_url('dashboard/index'));
        }
    }

    public function index()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/update/index.js'
            ]
        ];

        ini_set('memory_limit', $this->_memoryLimit);
        if ( isset($_FILES["file"]['name']) && $_FILES["file"]['name'] != '' ) {
            $this->_htmlDesign('none', false);
            $browseFileUpload = $this->_browseFileUpload($_FILES);
            if ( $browseFileUpload->status ) {
                if ( file_exists($this->_downloadFileWithPath) ) {
                    $fileUnZip = $this->_fileUnZip();
                    if ( $fileUnZip->status ) {
                        $manageFile = $this->_manageFile($browseFileUpload);
                        if ( $manageFile->status ) {
                            $databaseUpdate = $this->_databaseUpdate();
                            if ( $databaseUpdate->status ) {
                                if ( $databaseUpdate->version != 'none' ) {
                                    $array = [
                                        'version' => $databaseUpdate->version,
                                        'date'    => date('Y-m-d H:i:s'),
                                        'userID'  => $this->session->userdata('loginuserID'),
                                        'roleID'  => $this->session->userdata('roleID'),
                                        'status'  => 1,
                                        'log'     => $this->_updateLog(),
                                    ];
                                    $this->update_m->insert_update($array);
                                    $this->_deleteZipAndFile($this->_downloadFileWithPath);
                                    $this->signin_m->signout();
                                    redirect(base_url("signin/index"));
                                } else {
                                    $this->_deleteZipAndFile($this->_downloadFileWithPath);
                                    $this->signin_m->signout();
                                    redirect(base_url("signin/index"));
                                }
                            } else {
                                $this->_deleteZipAndFile($this->_downloadFileWithPath);
                                $this->signin_m->signout();
                                redirect(base_url("signin/index"));
                            }
                        } else {
                            $this->session->set_flashdata('error', 'File distribution failed');
                            redirect(base_url('update/index'));
                        }
                    } else {
                        $this->session->set_flashdata('error', 'File extract failed');
                        redirect(base_url('update/index'));
                    }
                } else {
                    $this->session->set_flashdata('error', 'Upload file does not exist');
                    redirect(base_url('update/index'));
                }
            } else {
                $this->session->set_flashdata('error', $browseFileUpload->message);
                redirect(base_url('update/index'));
            }
        } else {
            $this->data['updates'] = $this->update_m->get_update();
            $this->data["subview"] = "update/index";
            $this->load->view('_layout_main', $this->data);
        }
    }

    private function _htmlDesign( $versionChecking, $versionShow = true )
    {
        $this->load->config('iniconfig');
        echo '<html>';
        echo '<head>';
        echo '<title>' . $this->lang->line('panel_title') . '</title>';
        echo '<link rel="SHORTCUT ICON" href="' . imagelink($this->data['generalsettings']->logo,
                'uploads/general') . '" />';
        echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">';
        echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>';
        echo '<style type="text/css">.progress { margin: 10px;max-width: 100%; } .content { padding : 20px; }</style>';
        echo '</head>';
        echo '<body style="font-family:none">';
        echo '<div class="content">';
        echo '<div class="row">';
        echo '<div class="col-sm-offset-2 col-sm-8">';
        echo '<div class="jumbotron">';
        echo '<center><p style="font-size:20px"><img style="widht:50px;height:50px" src="' . imagelink($this->data['generalsettings']->logo,
                'uploads/general') . '"></p></center>';
        echo '<center><p style="font-size:20px;color:#1A2229">' . $this->data['generalsettings']->system_name . '</p></center>';
        echo '<center><p style="font-size:14px;color:#1A2229">' . $this->data['generalsettings']->address . '</p></center>';
        if ( $versionShow ) {
            echo '<center><p style="font-size:12px" class="text-green">Your system is updating ' . config_item('iniversion') . ' to ' . $versionChecking->version . '</p></center>';
        }
        echo '<center><p style="font-size:12px">-! Please wait some minutes !-</p></center>';

        echo '<div class="progress">';
        echo '<div id="dynamic" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">';
        echo '<span id="current-progress"></span>';
        echo '</div>';
        echo '</div>';

        echo '<p style="font-size:12px;padding-top:10px;padding-left:15px;"> 1. Don\'t close this page</p>';
        echo '<p style="font-size:12px;padding-left:15px;"> 2. Don\'t reload this page</p>';
        echo '<p style="font-size:12px;padding-left:15px;"> 3. Don\'t open another tab of your system</p>';
        echo '<p style="font-size:12px;padding-left:15px;"> 4. When the update process will be complete it will redirect to the sign-in page</p>';
        echo '<script type="text/javascript">';
        echo '$(function() {
									  	var current_progress = 0;
									  	var interval = setInterval(function() {
									    	current_progress += 1;
									    	$("#dynamic")
									    	.css("width", current_progress + "%")
									    	.attr("aria-valuenow", current_progress)
									    	.text(current_progress + "% Complete");
									    	if (current_progress >= 100)
									        	clearInterval(interval);
									  	}, 18000);
									});';
        echo '</script>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</body>';
        echo '</html>';
    }

    private function _browseFileUpload( $file )
    {
        $returnArray['status'] = false;;
        $returnArray['version'] = 'none';
        $returnArray['message'] = 'File not found';

        if ( isset($file['file']) ) {
            $fileName = $file['file']['name'];
            $fileSize = $file['file']['size'];
            $fileTmp  = $file['file']['tmp_name'];
            $endArray = explode('.', $file['file']['name']);
            $fileExt  = strtolower(end($endArray));

            $extensions  = [ "zip" ];
            $maxFileSize = 1073741824;

            if ( in_array($fileExt, $extensions) ) {
                if ( $fileSize <= $maxFileSize ) {
                    move_uploaded_file($fileTmp, $this->_downloadPath . '/' . $fileName);
                    $this->_downloadFileWithPath = $this->_downloadPath . '/' . $fileName;
                    $returnArray['status']       = true;
                    $returnArray['version']      = str_replace('.zip', '', $fileName);
                    $returnArray['message']      = 'Success';
                } else {
                    $returnArray['message'] = "You max file size is 1 GB";
                }
            } else {
                $returnArray['message'] = "Please choose a zip file";
            }
        }

        return (object) $returnArray;
    }

    private function _fileUnZip()
    {
        $returnArray['status']  = false;
        $returnArray['message'] = 'Error';
        $zip                    = new ZipArchive;
        if ( $zip->open($this->_downloadFileWithPath) === true ) {
            $zip->extractTo($this->_downloadPath);
            $zip->close();
            $returnArray['status']  = true;
            $returnArray['message'] = 'Success';
        } else {
            $returnArray['message'] = 'The update zip does not found';
        }

        return (object) $returnArray;
    }

    private function _manageFile( $versionChecking )
    {
        $returnArray['status']      = false;
        $returnArray['message']     = 'File distribution fail';
        $destination                = FCPATH;
        $destination                = rtrim($destination, '/');
        $this->_downloadExtractPath = $this->_downloadPath . '/' . $versionChecking->version . '/';
        if ( $this->_smartCopy($this->_downloadExtractPath, $destination) ) {
            $returnArray['status']  = true;
            $returnArray['message'] = 'Success';
        }

        return (object) $returnArray;
    }

    private function _smartCopy( $source, $dest, $options = [ 'folderPermission' => 0777, 'filePermission' => 0777 ] )
    {
        $result = false;

        if ( is_file($source) ) {
            if ( $dest[ strlen($dest) - 1 ] == '/' ) {
                if ( !file_exists($dest) ) {
                    cmfcDirectory::makeAll($dest, $options['folderPermission'], true);
                }
                $__dest = $dest . "/" . basename($source);
            } else {
                $__dest = $dest;
            }
            $result = copy($source, $__dest);
            @chmod($__dest, $options['filePermission']);
        } elseif ( is_dir($source) ) {
            if ( $dest[ strlen($dest) - 1 ] == '/' ) {
                if ( $source[ strlen($source) - 1 ] == '/' ) {
                    //Copy only contents
                } else {
                    //Change parent itself and its contents
                    $dest = $dest . basename($source);
                    @mkdir($dest);
                    @chmod($dest, $options['filePermission']);
                }
            } else {
                if ( $source[ strlen($source) - 1 ] == '/' ) {
                    //Copy parent directory with new name and all its content
                    @mkdir($dest, $options['folderPermission']);
                    @chmod($dest, $options['filePermission']);
                } else {
                    //Copy parent directory with new name and all its content
                    @mkdir($dest, $options['folderPermission']);
                    @chmod($dest, $options['filePermission']);
                }
            }

            $dirHandle = opendir($source);
            while ( $file = readdir($dirHandle) ) {
                if ( $file != "." && $file != ".." ) {
                    if ( !is_dir($source . "/" . $file) ) {
                        $__dest = $dest . "/" . $file;
                    } else {
                        $__dest = $dest . "/" . $file;
                    }
                    $result = $this->_smartCopy($source . "/" . $file, $__dest, $options);
                }
            }
            closedir($dirHandle);
        } else {
            $result = false;
        }
        return $result;
    }

    private function _databaseUpdate()
    {
        $returnArray['status']  = false;
        $returnArray['version'] = 'none';
        $returnArray['message'] = 'Unknown version';

        if ( file_exists($this->_downloadExtractPath . 'inilabs.json') ) {
            $inilabsString = file_get_contents($this->_downloadExtractPath . 'inilabs.json');
            if ( !empty($inilabsString) ) {
                $inilabsJsonArray = json_decode($inilabsString, true);
                if ( isset($inilabsJsonArray['database']['status']) && strtolower($inilabsJsonArray['database']['status']) != 'no' ) {
                    if ( isset($inilabsJsonArray['filename']) && !empty($inilabsJsonArray['filename']) ) {
                        $this->_sqlGenerator($inilabsJsonArray['filename']);
                    }
                }

                if ( isset($inilabsJsonArray['version']) && !empty($inilabsJsonArray['version']) ) {
                    $returnArray['status']  = true;
                    $returnArray['version'] = $inilabsJsonArray['version'];
                    $returnArray['message'] = 'Success';
                }
            } else {
                $returnArray['message'] = 'inilabs.json content is empty';
            }
        } else {
            $returnArray['message'] = 'inilabs.json file not found';
        }
        return (object) $returnArray;
    }

    private function _sqlGenerator( $filename )
    {
        if ( !empty($filename) ) {
            $file = APPPATH . 'libraries/upgrade/' . $filename . '.php';
            if ( file_exists($file) && is_file($file) ) {
                @include_once( $file );
            }
        }
    }

    private function _updateLog()
    {
        $inilabsString = file_get_contents($this->_downloadExtractPath . 'inilabs.log');
        if ( !empty($inilabsString) ) {
            return $inilabsString;
        }
        return '';
    }

    private function _deleteZipAndFile( $filePathAndName )
    {
        $returnArray['status']  = false;
        $returnArray['message'] = 'Error';

        try {

            if ( file_exists($filePathAndName) ) {
                unlink($filePathAndName);
                $filePathAndName = str_replace(".zip", "", $filePathAndName);
                $this->_rmdirRecursive($filePathAndName);
                $this->_emptyFolder(APPPATH . 'libraries/upgrade/');
            }

            $returnArray['status']  = true;
            $returnArray['message'] = 'Success';
        } catch ( Exception $e ) {
            $returnArray['message'] = 'File delete permission problem';
        }

        return (object) $returnArray;
    }

    private function _rmdirRecursive( $dir )
    {
        if ( !file_exists($dir) ) {
            return true;
        }

        if ( !is_dir($dir) ) {
            return unlink($dir);
        }

        foreach ( scandir($dir) as $item ) {
            if ( $item == '.' || $item == '..' ) {
                continue;
            }

            if ( !$this->_rmdirRecursive($dir . DIRECTORY_SEPARATOR . $item) ) {
                return false;
            }
        }

        return rmdir($dir);
    }

    private function _emptyFolder( $dir )
    {
        foreach ( scandir($dir) as $item ) {
            if ( $item == '.' || $item == '..' ) {
                continue;
            }
            unlink($dir . $item);
        }
        return true;
    }

    public function autoupdate()
    {
        ini_set('memory_limit', $this->_memoryLimit);
        if ( $this->session->userdata('roleID') == 1 && $this->session->userdata('loginuserID') == 1 ) {
            if ( $this->session->userdata('updatestatus') ) {
                if ( inicompute($postDatas = @$this->_postData()) ) {
                    $versionChecking = $this->_versionChecking($postDatas);
                    if ( $versionChecking->status ) {
                        if ( $versionChecking->version != 'none' ) {
                            $this->_htmlDesign($versionChecking);
                            $fileDownload = $this->_fileDownload($versionChecking);
                            if ( !empty($fileDownload) ) {
                                $filePush = $this->filePush($versionChecking, $fileDownload);
                                if ( $filePush->status ) {
                                    if ( file_exists($this->_downloadFileWithPath) ) {
                                        $fileUnZip = $this->_fileUnZip();
                                        if ( $fileUnZip->status ) {
                                            $manageFile = $this->_manageFile($versionChecking);
                                            if ( $manageFile->status ) {
                                                $this->_databaseUpdate();
                                                $array = [
                                                    'version' => $versionChecking->version,
                                                    'date'    => date('Y-m-d H:i:s'),
                                                    'userID'  => $this->session->userdata('loginuserID'),
                                                    'roleID'  => $this->session->userdata('roleID'),
                                                    'status'  => 1,
                                                    'log'     => $this->_updateLog(),
                                                ];
                                                $this->update_m->insert_update($array);
                                                $this->_deleteZipAndFile($this->_downloadFileWithPath);

                                                if ( inicompute($postDatas) ) {
                                                    $postDatas['updateversion'] = $versionChecking->version;
                                                    $this->_successProvider($postDatas);
                                                }
                                                $this->signin_m->signout();
                                                redirect(base_url("signin/index"));
                                            } else {
                                                $this->session->set_flashdata('error', 'File distribution failed');
                                                redirect(base_url('dashboard/index'));
                                            }
                                        } else {
                                            $this->session->set_flashdata('error', 'File extract failed');
                                            redirect(base_url('dashboard/index'));
                                        }
                                    } else {
                                        $this->session->set_flashdata('error', 'Download file does not exist');
                                        redirect(base_url('dashboard/index'));
                                    }
                                } else {
                                    $this->session->set_flashdata('error', $filePush->message);
                                    redirect(base_url('dashboard/index'));
                                }
                            } else {
                                $this->session->set_flashdata('error', 'File downloading failed');
                                redirect(base_url('dashboard/index'));
                            }
                        } else {
                            $this->session->set_flashdata('success', 'You are using the latest version');
                            redirect(base_url('dashboard/index'));
                        }
                    } else {
                        $this->session->set_flashdata('error', 'Sync update failed');
                        redirect(base_url('dashboard/index'));
                    }
                } else {
                    $this->session->set_flashdata('error', 'Post data does not found');
                    redirect(base_url('dashboard/index'));
                }
            } else {
                $this->session->set_flashdata('error', 'Only the main system admin can update this system');
                redirect(base_url('dashboard/index'));
            }
        } else {
            $this->session->set_flashdata('error', 'Please login via the main system admin');
            redirect(base_url('dashboard/index'));
        }
    }

    private function _postData()
    {
        $postDatas = [];
        $updates   = $this->update_m->get_max_update();
        if ( count($updates) ) {
            $postDatas = [
                'username'       => count($this->data['generalsettings']) ? $this->data['generalsettings']->purchase_username : '',
                'purchasekey'    => count($this->data['generalsettings']) ? $this->data['generalsettings']->purchase_code : '',
                'domainname'     => site_url(),
                'email'          => count($this->data['generalsettings']) ? $this->data['generalsettings']->email : '',
                'currentversion' => $updates->version,
                'projectname'    => 'hospital',
            ];
        }
        return $postDatas;
    }

    private function _versionChecking( $data )
    {
        $result = [
            'status'  => false,
            'message' => 'Error',
            'version' => 'none'
        ];

        $postDataStrings = json_encode($data);
        $ch              = curl_init($this->_versionCheckingUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataStrings);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postDataStrings)
            ]
        );

        $getResult = curl_exec($ch);
        curl_close($ch);
        if ( inicompute($getResult) ) {
            $result = json_decode($getResult, true);
        }
        return (object) $result;
    }

    private function _fileDownload( $result )
    {
        ini_set('memory_limit', $this->_memoryLimit);
        $this->_updateFileUrl = $this->_updateFileUrl . $result->version . '.zip';
        $curlCh               = curl_init();
        curl_setopt($curlCh, CURLOPT_URL, $this->_updateFileUrl);
        curl_setopt($curlCh, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlCh, CURLOPT_SSLVERSION, 3);
        $curlData = curl_exec($curlCh);
        curl_close($curlCh);
        return $curlData;
    }

    private function filePush( $result, $curlData )
    {
        $returnArray['status']  = false;
        $returnArray['message'] = 'Error';
        $downloadPath           = FCPATH . 'uploads/update/' . $result->version . '.zip';
        $permissionCheckingPath = FCPATH . 'uploads/update/index.html';
        if ( file_exists($permissionCheckingPath) ) {
            try {
                if ( $file = @fopen($downloadPath, 'w+') ) {
                    fputs($file, $curlData);
                    fclose($file);

                    $this->_downloadFileWithPath = $downloadPath;
                    $returnArray['status']       = true;
                    $returnArray['message']      = 'Success';
                } else {
                    $returnArray['message'] = 'Uploads folder permission has the decline';
                }
            } catch ( Exception $e ) {
                $returnArray['message'] = 'Uploads folder permission has the decline';
            }
        } else {
            $returnArray['message'] = 'Uploads folder permission has the decline';
        }

        return (object) $returnArray;
    }

    private function _successProvider( $data )
    {
        $result = [
            'status'  => false,
            'message' => 'Error'
        ];

        $postDataStrings = json_encode($data);
        $ch              = curl_init($this->_successUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataStrings);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postDataStrings)
            ]
        );

        $getResult = curl_exec($ch);
        curl_close($ch);
        if ( inicompute($getResult) ) {
            $result = json_decode($getResult, true);
        }
        return (object) $result;
    }

    public function getloginfo()
    {
        $text     = '';
        $updateID = $this->input->post('updateID');
        $update   = $this->update_m->get_single_update([ 'updateID' => $updateID ]);
        if ( inicompute($update) ) {
            $text = $update->log;
        }

        echo $text;
    }
}
