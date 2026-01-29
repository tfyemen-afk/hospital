<div class="card-block no-padding">
    <section class="panel">
        <div class="profile-db-head bg-maroon-light">
            <a href="<?=site_url('profile/index')?>">
                <img src="<?=imagelink($profileinfouser->photo, '/uploads/user/') ?>" alt="">
            </a>
            <h1><?=$profileinfouser->name?></h1>
            <p><?=inicompute($profileinfouserdesignation) ? $profileinfouserdesignation->designation :  ''?></p>
        </div>
        <table class="table table-hover">
            <tbody>
            <tr>
                <td class="profile-padding"><i class="fa fa-user text-maroon-light"></i></td>
                <td class="profile-padding"><?=$this->lang->line('dashboard_username')?></td>
                <td class="profile-padding"><?=$profileinfouser->username?></td>
            </tr>
            <tr>
                <td class="profile-padding"><i class="fa fa-envelope text-maroon-light"></i></td>
                <td class="profile-padding"><?=$this->lang->line('dashboard_email')?></td>
                <td class="profile-padding"><?=$profileinfouser->email?></td>
            </tr>
            <tr>
                <td class="profile-padding"><i class="fa fa-phone text-maroon-light"></i></td>
                <td class="profile-padding"><?=$this->lang->line('dashboard_phone')?></td>
                <td class="profile-padding"><?=$profileinfouser->phone?></td>
            </tr>
            <tr>
                <td class="profile-padding"><i class=" fa fa-globe text-maroon-light"></i></td>
                <td class="profile-padding"><?=$this->lang->line('dashboard_address')?></td>
                <td class="profile-padding"><?=$profileinfouser->address?></td>
            </tr>
            </tbody>
        </table>
    </section>
</div>