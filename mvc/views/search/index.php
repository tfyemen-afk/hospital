<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-search"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pull-right themebreadcrumb">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_search')?></li>
              </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-table"></i>&nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <div id="hide-table">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?=$this->lang->line('search_slno')?></th>
                                        <th><?=$this->lang->line('search_name')?></th>
                                        <th><?=$this->lang->line('search_email')?></th>
                                        <th><?=$this->lang->line('search_phone')?></th>
                                        <th><?=$this->lang->line('search_designation')?></th>
                                        <th><?=$this->lang->line('search_role')?></th>
                                        <th><?=$this->lang->line('search_action')?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0; if(inicompute($searchs)) {foreach($searchs as $search) { $i++; 
                                        if($search->userID == 1) {
                                            continue;
                                        } ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('search_slno')?>">
                                                <?=$i;?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('search_name')?>">
                                                <?=$search->name;?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('search_email')?>">
                                                <?=$search->email;?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('search_phone')?>">
                                                <?=($search->phone) ? $search->phone : '&nbsp;'?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('search_designation')?>">
                                                <?=isset($designations[$search->designationID]) ? $designations[$search->designationID] : '&nbsp;'?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('search_role')?>">
                                                <?=isset($roles[$search->roleID]) ? $roles[$search->roleID] : '&nbsp;'?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('search_action')?>">
                                                <?php 
                                                    $sv_url = "#";
                                                    if((int)$search->patientID) {
                                                        $sv_url = site_url('patient/view/'.$search->patientID.'/4');
                                                    } else {
                                                        $sv_url = site_url('user/view/'.$search->userID.'/4');
                                                    }
                                                ?>
                                                <a href="<?=$sv_url?>" class="btn btn-success btn-custom mrg" data-placement="top" data-toggle="tooltip" data-original-title="<?=$this->lang->line('search_view')?>" aria-describedby="tooltip675453"><i class="fa fa-check-square-o"></i></a>
                                            </td>
                                        </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>