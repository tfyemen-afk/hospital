<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-bedmanager"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8">
            <nav class="breadcrumb-position" aria-label="breadcrumb">
                <ol class="breadcrumb themebreadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_bedmanager')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-4">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('bedmanager_filter')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="list-group bedmanager-list" role="tablist" aria-orientation="vertical">
                            <?php $j=1; if(inicompute($wards)) { foreach($wards as $ward) { ?>
                                <a class="list-group-item <?=($j==1) ? 'active': ''?>" data-toggle="pill" href="#tab-<?=$ward->wardID?>" role="tab"><?=$ward->name?> - <?=isset($rooms[$ward->roomID]) ? $rooms[$ward->roomID] : ''?></a>
                            <?php $j++; } } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> <i class="fa fa-table"></i> &nbsp;<?=$this->lang->line('bedmanager_filter_data')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="tab-content">
                            <?php $j=1; if(inicompute($wards)) { foreach($wards as $ward) { ?>
                                <div class="tab-pane fade show bedmanager-tab-pane <?=($j==1) ? 'active': ''?>" id="tab-<?=$ward->wardID?>" role="tabpanel">
                                    <div id="hide-table">
                                        <table class="table table-striped table-bordered table-hover example">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?=$this->lang->line('bedmanager_bed_name')?></th>
                                                    <th><?=$this->lang->line('bedmanager_bedtype')?></th>
                                                    <th><?=$this->lang->line('bedmanager_status')?></th>
                                                    <th><?=$this->lang->line('bedmanager_action')?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 0; if(isset($beds[$ward->wardID]) && inicompute($beds[$ward->wardID])) {foreach($beds[$ward->wardID] as $bed) { $i++; ?>
                                                    <tr>
                                                        <td data-title="#">
                                                            <?=$i;?>
                                                        </td>
                                                        <td data-title="<?=$this->lang->line('bedmanager_bed_name')?>">
                                                            <?=$bed->name;?>
                                                        </td>
                                                        <td data-title="<?=$this->lang->line('bedmanager_bedtype')?>">
                                                            <?=isset($bedtypes[$bed->bedtypeID]) ? $bedtypes[$bed->bedtypeID] : "&nbsp"?>
                                                        </td>
                                                        <td data-title="<?=$this->lang->line('bedmanager_status')?>">
                                                            <?=($bed->status) ? "<span class='text-danger'>".$this->lang->line('bedmanager_not_available')."</span>" : "<span class='text-success'>".$this->lang->line('bedmanager_available')."</span>"?>
                                                        </td>
                                                        <td data-title="<?=$this->lang->line('bedmanager_action')?>">
                                                            <?=($bed->status) ? "<button data-placement='top' data-toggle='tooltip' title='".$this->lang->line('bedmanager_view')."' id='".$bed->patientID."'  class='btn btn-success btn-custom mrg viewModalBtn'><span class='fa fa-check-square-o' data-toggle='modal' data-target='#viewModal'></span></button>" : "&nbsp"?>
                                                        </td>
                                                    </tr>
                                                <?php } } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php $j++; } } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>                

<?php if(inicompute($wards)) { if(permissionChecker('bedmanager')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('bedmanager_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('bedmanager_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } } ?>