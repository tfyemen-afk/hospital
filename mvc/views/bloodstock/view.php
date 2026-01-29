<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-bloodstock"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pull-right themebreadcrumb">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item"><a href="<?=site_url('bloodstock/index')?>"><?=$this->lang->line('menu_bloodstock')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('bloodstock_view')?></li>
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
                            <p class="title"><?=$this->lang->line('bloodstock_blood_details')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item list-group-item-background">
                                <b><?=$this->lang->line('bloodstock_blood_group')?></b>
                                <a class="pull-right"><?=inicompute($bloodgroup) ? $bloodgroup->bloodgroup : ''?></a>
                            </li>
                            <li class="list-group-item list-group-item-background">
                                <b><?=$this->lang->line('bloodstock_total')?></b>
                                <a class="pull-right"><?=isset($bloodstocks[$bloodgroupID]) ? $bloodstocks[$bloodgroupID]['total'] : '0'?></a>
                            </li>
                            <li class="list-group-item list-group-item-background">
                                <b><?=$this->lang->line('bloodstock_reserves')?></b>
                                <a class="pull-right"><?=isset($bloodstocks[$bloodgroupID]) ? $bloodstocks[$bloodgroupID]['reserve'] : '0'?></a>
                            </li>
                            <li class="list-group-item list-group-item-background">
                                <b><?=$this->lang->line('bloodstock_release')?></b>
                                <a class="pull-right"><?=isset($bloodstocks[$bloodgroupID]) ? $bloodstocks[$bloodgroupID]['release'] : '0'?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> <i class="fa fa-table"></i> &nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('bloodstock/table');?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>                