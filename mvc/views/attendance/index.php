<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-user-secret"></i> <?= $this->lang->line('panel_title') ?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('dashboard/index') ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?>
                        </a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line('menu_attendance') ?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?= ( $activetab ) ? 'active' : '' ?>" id="list_tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true"><i class="fa fa-table"></i> <?= $this->lang->line('panel_list') ?>
                        </a>
                    </li>
                    <?php if ( permissionChecker('attendance_add') ) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?= ( $activetab ) ? '' : 'active' ?>" id="add_tab" data-toggle="tab" href="#add" role="tab" aria-controls="add" aria-selected="false"><i class="fa fa-plus-square-o"></i> <?= $this->lang->line('panel_add') ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade bg-color <?= ( $activetab ) ? 'show active' : '' ?>" id="list" role="tabpanel" aria-labelledby="list_tab">
                        <div id="hide-table">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                <tr>
                                    <th><?= $this->lang->line('attendance_slno') ?></th>
                                    <th><?= $this->lang->line('attendance_photo') ?></th>
                                    <th><?= $this->lang->line('attendance_name') ?></th>
                                    <th><?= $this->lang->line('attendance_designation') ?></th>
                                    <th><?= $this->lang->line('attendance_email') ?></th>
                                    <?php if ( permissionChecker('attendance_view') ) { ?>
                                        <th><?= $this->lang->line('attendance_action') ?></th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0;
                                    if ( inicompute($users) ) {
                                        foreach ( $users as $user ) {
                                            $i++; ?>
                                            <tr>
                                                <td data-title="<?= $this->lang->line('attendance_slno') ?>"><?= $i ?></td>
                                                <td data-title="<?= $this->lang->line('attendance_photo') ?>">
                                                    <img class="img-responsive" style="height: 28px; width: 28px" src="<?= imagelink($user->photo,
                                                        '/uploads/user/') ?>"/>
                                                </td>
                                                <td data-title="<?= $this->lang->line('attendance_name') ?>"><?= namesorting($user->name,
                                                        40) ?></td>
                                                <td data-title="<?= $this->lang->line('attendance_designation') ?>"><?= isset($designations[ $user->designationID ]) ? $designations[ $user->designationID ] : '' ?></td>
                                                <td data-title="<?= $this->lang->line('attendance_email') ?>"><?= namesorting($user->email,
                                                        40) ?></td>
                                                <?php if ( permissionChecker('attendance_view') ) { ?>
                                                    <td data-title="<?= $this->lang->line('attendance_action') ?>">
                                                        <?= btn_view('attendance/view/' . $user->userID,
                                                            $this->lang->line('attendance_view')) ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if ( permissionChecker('attendance_add') ) { ?>
                        <div class="tab-pane fade <?= ( $activetab ) ? '' : 'show active' ?>" id="add" role="tabpanel" aria-labelledby="add_tab">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="card card-custom">
                                        <div class="card-header">
                                            <div class="header-block">
                                                <p class="title">
                                                    <i class="fa fa-sliders"></i> &nbsp;<?= $this->lang->line('attendance_filter') ?>
                                                </p>
                                            </div>
                                        </div>

                                        <form method="POST">
                                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                                            <div class="card-block">
                                                <div class="form-group <?= form_error('date') ? 'text-danger' : '' ?>">
                                                    <label for="date"><?= $this->lang->line('attendance_date') ?>
                                                        <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control datepicker <?= form_error('date') ? 'is-invalid' : '' ?>" name="date" id="date" value="<?= set_value('date',
                                                        $attendance_date) ?>"/>
                                                    <span><?= form_error('date') ?></span>
                                                </div>
                                            </div>

                                            <?php if ( !empty($attendance_date) && $poststatus === true ) { ?>
                                                <div class="card-footer">
                                                    <p class="footertitle"><?= $this->lang->line('attendance_attendance_details') ?></p>
                                                    <ul class="list-group list-group-unbordered">
                                                        <li class="list-group-item" style="background-color: #FFF">
                                                            <b><?= $this->lang->line('attendance_day') ?></b>
                                                            <a class="pull-right"><?= date('l',
                                                                    strtotime($attendance_date)) ?></a>
                                                        </li>
                                                        <li class="list-group-item" style="background-color: #FFF">
                                                            <b><?= $this->lang->line('attendance_date') ?></b>
                                                            <a class="pull-right"><?= app_date($attendance_date) ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            <?php } ?>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-primary"><?= $this->lang->line('attendance_filter') ?></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="col-sm-8">
                                    <div class="card card-custom">
                                        <div class="card-header">
                                            <div class="header-block">
                                                <p class="title">
                                                    <i class="fa fa-braille"></i> &nbsp;<?= $this->lang->line('attendance_filter_data') ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="card-block">
                                            <div id="hide-table">
                                                <table class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th><?= $this->lang->line('attendance_slno') ?></th>
                                                        <th><?= $this->lang->line('attendance_photo') ?></th>
                                                        <th><?= $this->lang->line('attendance_name') ?></th>
                                                        <th><?= $this->lang->line('attendance_designation') ?></th>
                                                        <th><?= $this->lang->line('attendance_attendance') ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if ( inicompute($attendances) ) {
                                                        $i = 0;
                                                        if ( inicompute($users) ) {
                                                            foreach ( $users as $user ) {
                                                                $i++; ?>
                                                                <tr>
                                                                    <td data-title="<?= $this->lang->line('attendance_slno') ?>"><?= $i ?></td>
                                                                    <td data-title="<?= $this->lang->line('attendance_photo') ?>">
                                                                        <img class="img-responsive" style="height: 28px; width: 28px" src="<?= imagelink($user->photo,
                                                                            '/uploads/user/') ?>"/>
                                                                    </td>
                                                                    <td data-title="<?= $this->lang->line('attendance_name') ?>"><?= namesorting($user->name,
                                                                            40) ?></td>
                                                                    <td data-title="<?= $this->lang->line('attendance_designation') ?>"><?= isset($designations[ $user->designationID ]) ? $designations[ $user->designationID ] : '' ?>
                                                                    </td>

                                                                    <td data-title="<?= $this->lang->line('attendance_attendance') ?>">
                                                                        <?php
                                                                            $pmethod  = '';
                                                                            $lemethod = '';
                                                                            $lmethod  = '';
                                                                            $amethod  = '';

                                                                            if ( $attendances[ $user->userID ]->$colday == "P" ) {
                                                                                $pmethod = "checked";
                                                                            } elseif ( $attendances[ $user->userID ]->$colday == "LE" ) {
                                                                                $lemethod = "checked";
                                                                            } elseif ( $attendances[ $user->userID ]->$colday == "L" ) {
                                                                                $lmethod = "checked";
                                                                            } elseif ( $attendances[ $user->userID ]->$colday == "A" ) {
                                                                                $amethod = "checked";
                                                                            }

                                                                            $userattendanceID = isset($attendances[ $user->userID ]) ? 'attendance-' . $attendances[ $user->userID ]->attendanceID : 0;

                                                                            echo btn_attendance_radio($user->userID . '-1',
                                                                                $pmethod, "attendance present",
                                                                                $userattendanceID,
                                                                                $this->lang->line('attendance_present'),
                                                                                'P');

                                                                            echo btn_attendance_radio($user->userID . '-2',
                                                                                $lemethod, "attendance lateexcuse",
                                                                                $userattendanceID,
                                                                                $this->lang->line('attendance_late_excuse'),
                                                                                'LE');

                                                                            echo btn_attendance_radio($user->userID . '-3',
                                                                                $lmethod, "attendance late",
                                                                                $userattendanceID,
                                                                                $this->lang->line('attendance_late_present'),
                                                                                'L');

                                                                            echo btn_attendance_radio($user->userID . '-4',
                                                                                $amethod, "attendance absent",
                                                                                $userattendanceID,
                                                                                $this->lang->line('attendance_absent'),
                                                                                'A');
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            <?php }
                                                        }
                                                    } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <?php if ( !empty($attendance_date) && $poststatus === true ) { ?>
                                            <div class="card-footer">
                                                <button class="btn btn-primary saveattendance"><?= $this->lang->line('attendance_save') ?></button>
                                            </div>
                                        <?php } ?>
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