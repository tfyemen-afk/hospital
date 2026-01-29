<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-billpayment"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="<?=site_url('billpayment/index/'.$displayID.'/'.$displayuhID)?>"><?=$this->lang->line('menu_billpayment')?></a></li>
                    <li class="breadcrumb-item active"><?=$this->lang->line('billpayment_edit')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?=($this->session->flashdata('billpaymentedit')) ? '' : 'active'?>" id="list_tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true"><i class="fa fa-table"></i> <?=$this->lang->line('panel_list')?></a>
                    </li>
                    <?php if(permissionChecker('billpayment_edit')) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?=($this->session->flashdata('billpaymentedit')) ? 'active' : ''?>" id="edit_tab" data-toggle="tab" href="#edit" role="tab" aria-controls="edit" aria-selected="false"><i class="fa fa fa-edit"></i> <?=$this->lang->line('panel_edit')?></a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade bg-color <?=($this->session->flashdata('billpaymentedit')) ? '' : 'show active'?>" id="list" role="tabpanel" aria-labelledby="list_tab">
                        <?php $this->load->view('billpayment/tableedit');?>
                    </div>
                    <?php if(permissionChecker('billpayment_edit')) { ?>
                        <div class="tab-pane fade <?=($this->session->flashdata('billpaymentedit')) ? 'show active' : ''?>" id="edit" role="tabpanel" aria-labelledby="edit_tab">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card card-custom">
                                        <div class="card-header">
                                            <div class="header-block">
                                                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('billpayment_filter_data')?></p>
                                            </div>
                                        </div>
                                        <div class="card-block">
                                            <?php $this->load->view('billpayment/itemtableedit'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</article>