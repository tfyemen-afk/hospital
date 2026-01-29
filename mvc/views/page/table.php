<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
        <tr>
            <th><?=$this->lang->line('page_slno')?></th>
            <th><?=$this->lang->line('page_title')?></th>
            <th><?=$this->lang->line('page_template')?></th>
            <th><?=$this->lang->line('page_publish_date')?></th>
            <?php if(permissionChecker('page_edit') || permissionChecker('page_delete')) { ?>
                <th><?=$this->lang->line('page_action')?></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php $i = 0; if(inicompute($pages)) { foreach($pages as $page) { $i++; ?>
            <tr>
                <td data-title="<?=$this->lang->line('page_slno')?>"><?=$i?></td>
                <td data-title="<?=$this->lang->line('page_title')?>">
                    <?=namesorting($page->title,40)?>
                    <?php if($page->status == 2 || $page->status == 4) {
                            echo '<b>--'. ucfirst(pageStatus($page->status, FALSE)).'</b>';
                        }
                    ?>
                </td>
                <td data-title="<?=$this->lang->line('page_template')?>"><?=ucfirst($page->template)?></td>
                <td data-title="<?=$this->lang->line('page_publish_date')?>"><?=app_datetime($page->publish_date)?></td>
                <?php if(permissionChecker('page_edit') || permissionChecker('page_delete')) { ?>
                    <td data-title="<?=$this->lang->line('page_action')?>">
                        <?=btn_edit('page/edit/'.$page->pageID, $this->lang->line('page_edit'))?>
                        <?=btn_delete('page/delete/'.$page->pageID, $this->lang->line('page_delete'))?>
                    </td>
                <?php } ?>
            </tr>
        <?php } } ?>
        </tbody>
    </table>
</div>