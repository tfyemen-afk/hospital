<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-calculator"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pull-right themebreadcrumb">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i><?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_salarytemplate')?></li>
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
                            <a class="nav-link active" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true"><i class="fa fa-table"></i> <?=$this->lang->line('salarytemplate_list')?></a>
                        </li>
                        <?php if(permissionChecker('salarytemplate_add')) { ?>
                          <li class="nav-item">
                            <a class="nav-link" id="add-tab" data-toggle="tab" href="#add" role="tab" aria-controls="add" aria-selected="true"><i class="fa fa-plus-square-o"></i> <?=$this->lang->line('salarytemplate_add')?></a>
                          </li>
                        <?php } ?>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade bg-color show active" id="list" role="tabpanel" aria-labelledby="list-tab">
                            <?php $this->load->view('salarytemplate/table'); ?>
                        </div>
                        <?php if(permissionChecker('salarytemplate_add')) { ?>
                            <div class="tab-pane fade bg-color" id="add" role="tabpanel" aria-labelledby="add-tab">
                                <?php $this->load->view('salarytemplate/add_tab'); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
                