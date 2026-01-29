<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-calculator"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i><?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="<?=site_url('salarytemplate/index')?>"><?=$this->lang->line('menu_salarytemplate')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('salarytemplate_edit')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-12">
                <div class="nav_custom_tabs">
                    <ul class="nav nav-tabs" id="custom_tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true"><i class="fa fa-plus-square-o"></i> <?=$this->lang->line('salarytemplate_list')?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="edit-tab" data-toggle="tab" href="#edit" role="tab" aria-controls="edit" aria-selected="true"><i class="fa fa-edit"></i> <?=$this->lang->line('salarytemplate_edit')?></a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade bg-color" id="list" role="tabpanel" aria-labelledby="list-tab">
                            <?php $this->load->view('salarytemplate/table'); ?>
                        </div>
                        <div class="tab-pane fade bg-color show active" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                            <?php $this->load->view('salarytemplate/edit_tab'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
                