<h2><?=$this->lang->line('post_create_video_playlist')?></h2>
<div class="nav_custom_tabs">
    <ul class="nav nav-tabs" id="custom_tab" role="tablist">
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#createVideoPlaylistMediaUpload"><?=$this->lang->line('post_upload_files')?></a></li>
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#createVideoPlaylistMediaUploadLibrary"><?=$this->lang->line('post_media_library')?></a></li>
    </ul>

    <div class="tab-content no-padd">
        <div id="createVideoPlaylistMediaUpload" class="tab-pane">
            <div class="drop--files">
                <form id="create_video_playlist-fileupload" class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
                    <input type="text" name="media_gallery_type" value="4" style="display: none">
                    <input type="text" name="focus_id" value="createVideoPlaylist" style="display: none">
                    <div class="fileupload-input-group">
                        <span class="fileupload-input-group-btn">
                            <div class="fileupload-image-preview-input">
                                <?=$this->lang->line('post_select_file')?>
                                <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept="video/mp4,video/x-m4v,video/*" name="file"/>
                            </div>
                        </span>
                    </div>
                </form>
                <div class="post--upload--info">
                    <p><?=$this->lang->line('post_maximum_upload_file_size')?></p>
                </div>
            </div>
        </div>

        <div id="createVideoPlaylistMediaUploadLibrary" class="tab-pane clearfix active">
            <div class="media-library-left pos-rel pull-left">
                <?php 
                    if(inicompute($media_gallerys_videos)) {
                        echo '<div class="attached-preview">';
                            echo '<ul class="create_video_playlist_type">';
                        foreach ($media_gallerys_videos as $media_gallerys_video_key => $media_gallerys_video) {
                ?>       
                            <li class="create_video_playlist_video" onclick="getFileInfo(this, 'create_video_playlist_type', 'video', 'multi', 'create_video_playlist_hidden_field', false, 'create_video_playlist');" id ="<?=$media_gallerys_video->media_galleryID?>">
                                <div class="thumb">
                                    <i class="fa fa-file-video-o"></i>
                                </div>
                                <div class="video-title">
                                    <p><?=namesorting($media_gallerys_video->file_original_name, 50)?></p>
                                </div>
                            </li>
                <?php 
                        }
                            echo '</ul>';  
                        echo '</div>';
                        echo '<input type="text" id="create_video_playlist_hidden_field" style="display:none">';

                    } else { ?>
                    <div class="drop--files">
                        <form id="create_video_playlist-fileupload-list" class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
                            <input type="text" name="media_gallery_type" value="4" style="display: none">
                            <input type="text" name="focus_id" value="createVideoPlaylist" style="display: none">
                            <div class="fileupload-input-group">
                                <span class="fileupload-input-group-btn">
                                    <div class="fileupload-image-preview-input">
                                        <?=$this->lang->line('post_select_file')?>
                                        <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept="video/mp4,video/x-m4v,video/*" name="file"/>
                                    </div>
                                </span>
                            </div>
                        </form>
                        <div class="post--upload--info">
                            <p><?=$this->lang->line('post_maximum_upload_file_size')?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="media-library-right pull-left">
                <div class="attached-details" id="create_video_playlist_type">

                </div>
            </div>
        </div>

        <div class="footer--upload">
            <a data-dismiss="modal" id="create_a_new_video_playlist" onclick="setFileToEditor(this, 'create_video_playlist_hidden_field', 'create_video_playlist_type');" href="#"><?=$this->lang->line('post_create_a_new_video_playlist')?></a>
        </div>
    </div>
</div>