<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-discharge"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="<?=site_url('discharge/index/'.$displayID)?>"><?=$this->lang->line('menu_discharge')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('discharge_edit')?></li>
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
                            <p class="title"><i class="fa fa-edit"></i> &nbsp;<?=$this->lang->line('panel_edit')?></p>
                        </div>
                    </div>
                    <form role="form" method="POST">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group <?=form_error('uhid') ? 'text-danger' : ''?>">
                                <label for="uhid"><?=$this->lang->line('discharge_uhid')?> <span class="text-danger">*</span></label>
                                <?php
                                $patientArray['0'] = '— '.$this->lang->line('discharge_please_select').' —';
                                if(inicompute($editpatient)) {
                                    $patientArray[$editpatient->patientID] = $editpatient->patientID.' - '.$editpatient->name;
                                }
                                if(inicompute($patients)) {
                                    foreach ($patients as $patient) {
                                        $patientArray[$patient->patientID] = $patient->patientID.' - '.$patient->name;
                                    }
                                }
                                $errorClass = form_error('uhid') ? 'is-invalid' : '';
                                echo form_dropdown('uhid', $patientArray,  set_value('uhid', $discharge->patientID), 'id="uhid" class="form-control select2 '.$errorClass.'"');
                                ?>
                                <span><?=form_error('uhid')?></span>
                            </div>

                            <div class="form-group <?=form_error('conditionofdischarge') ? 'text-danger' : ''?>">
                                <label for="conditionofdischarge"><?=$this->lang->line('discharge_condition_of_discharge')?> <span class="text-danger">*</span></label>
                                <?php
                                $conditionofdischarge['0'] = '— '.$this->lang->line('discharge_please_select').' —';
                                if(inicompute($conditions)) {
                                    foreach ($conditions as $conditionKey =>  $condition) {
                                        $conditionofdischarge[$conditionKey] = $condition;
                                    }
                                }
                                $errorClass = form_error('conditionofdischarge') ? 'is-invalid' : '';
                                echo form_dropdown('conditionofdischarge', $conditionofdischarge,  set_value('conditionofdischarge', $discharge->conditionofdischarge), 'id="conditionofdischarge" class="form-control select2 '.$errorClass.'"');
                                ?>
                                <span><?=form_error('conditionofdischarge')?></span>
                            </div>

                            <div class="form-group <?=form_error('date') ? 'text-danger' : ''?>">
                                <label for="date"><?=$this->lang->line('discharge_date')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control datepicker <?=form_error('date') ? 'is-invalid' : ''?>" name="date" id="date" autocomplete="off" value="<?=set_value('date', date('d-m-Y h:i A', strtotime($discharge->date)))?>"/>
                                <span><?=form_error('date')?></span>
                            </div>

                            <div class="form-group <?=form_error('note') ? 'text-danger' : ''?>">
                                <label for="note"><?=$this->lang->line('discharge_note')?></label>
                                <textarea id="note" name="note" class="form-control <?=form_error('note') ? 'is-invalid' : ''?>"><?=set_value('note', $discharge->note)?></textarea>
                                <span><?=form_error('note')?></span>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('discharge_update')?></button>
                        </div>
                    </form>
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
                        <?php $this->load->view('discharge/table', ['displayViewType' => 'edit']); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>