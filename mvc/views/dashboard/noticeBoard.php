<div class="card-block no-padding">
    <div class="card-title-block margin">
        <h3 class="title"><?=$this->lang->line('dashboard_notice')?></h3>
    </div>
    <section class="section">
        <table class="table table-hover">
            <tbody>
            <?php
            if(inicompute($notices)) {
                $i=0;
                foreach($notices as $notice) { $i++;
                    if($i>5) break;
                    ?>
                    <tr>
                        <td class="profile-padding"><?=$i?></td>
                        <td class="profile-padding"><?=namesorting($notice->title, 25)?></td>
                        <td class="profile-padding"><?=namesorting($notice->notice, 50)?></td>
                        <td class="profile-padding">
                            <?=btn_custom('notice_view', site_url('notice/view/'.$notice->noticeID.'/'.'4'), $this->lang->line('dashboard_view'), 'fa fa-check-square-o', 'bg-maroon-light');?>
                        </td>
                    </tr>
                <?php } } ?>
            </tbody>
        </table>
    </section>
</div>