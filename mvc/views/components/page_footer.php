                <footer class="footer">
                    <div class="footer-block author">
                        <ul>
                            <li><?=$generalsettings->footer_text?></li>
                        </ul>
                    </div>
                    <span class="version-text">v <?=$this->config->item('iniversion')?></span>
                </footer>
            </div>
        </div>
        <!-- Reference block for JS -->
        <div class="ref" id="ref">
            <div class="color-primary"></div>
            <div class="chart">
                <div class="color-primary"></div>
                <div class="color-secondary"></div>
            </div>
        </div>
        <script src="<?=base_url('assets/js/app.js')?>"></script>
        <script src="<?=base_url('assets/datatable/js/jquery.dataTables.min.js')?>"></script>
        <script src="<?=base_url('assets/datatable/js/dataTables.bootstrap4.min.js')?>"></script>
        <script src="<?=base_url('assets/toastr/toastr.min.js')?>"></script>
        <script src="<?=base_url('assets/inilabs/footer.js')?>"></script>

        <?php
            if(isset($footerassets)) {
                foreach ($footerassets as $assetstype => $footerasset) {
                    if($assetstype == 'css') {
                        if(inicompute($footerasset)) {
                            foreach ($footerasset as $keycss => $css) {
                                echo '<link rel="stylesheet" href="'.base_url($css).'">'."\n";
                            }
                        }
                    } elseif($assetstype == 'cdn') {
                        if(inicompute($footerasset)) {
                            foreach ($footerasset as $keycdn => $cdn) {
                                echo '<script type="text/javascript" src="'.$cdn.'"></script>'."\n";
                            }
                        }
                    } elseif($assetstype == 'js') {
                        if(inicompute($footerasset)) {
                            foreach ($footerasset as $keyjs => $js) {
                                echo '<script type="text/javascript" src="'.base_url($js).'"></script>'."\n";
                            }
                        }
                    }
                }
            }
        ?>

        <?php if ($this->session->flashdata('success')): ?>
            <script type="text/javascript">
                toastr["success"]("<?=$this->session->flashdata('success');?>");
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "500",
                    "hideDuration": "500",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            </script>
        <?php endif ?>
        <?php if ($this->session->flashdata('error')): ?>
           <script type="text/javascript">
                toastr["error"]("<?=$this->session->flashdata('error');?>");
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "500",
                    "hideDuration": "500",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            </script>
        <?php endif ?>
    </body>
</html>