<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
        <tr>
            <th><?=$this->lang->line('post_slno')?></th>
            <th><?=$this->lang->line('post_title')?></th>
            <th><?=$this->lang->line('post_category')?></th>
            <th><?=$this->lang->line('post_date')?></th>
            <?php if(permissionChecker('post_edit') || permissionChecker('post_delete')) { ?>
                <th><?=$this->lang->line('post_action')?></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php $i = 0; if(inicompute($posts)) { foreach($posts as $post) { $i++; ?>
            <tr>
                <td data-title="<?=$this->lang->line('post_slno')?>"><?=$i?></td>
                <td data-title="<?=$this->lang->line('post_title')?>">
                    <?=namesorting($post->title,40)?>
                    <?php if($post->status == 2 || $post->status == 4) {
                        echo '<b>--'. ucfirst(pageStatus($post->status, FALSE)).'</b>';
                    }
                    ?>
                </td>
                <td data-title="<?=$this->lang->line('post_category')?>">
                    <?php
                    if(isset($postcategorys[$post->postID])) {
                        $j = 1;
                        foreach ($postcategorys[$post->postID] as $category) {
                            if(isset($postcategories[$category])) {
                                if($j > 1) {
                                    echo ', '.$postcategories[$category];
                                } else {
                                    echo $postcategories[$category];
                                }
                                $j++;
                            }
                        }
                    }
                    ?>
                </td>
                <td data-title="<?=$this->lang->line('post_date')?>"><?=app_datetime($post->publish_date)?></td>
                <?php if(permissionChecker('post_edit') || permissionChecker('post_delete')) { ?>
                    <td data-title="<?=$this->lang->line('post_action')?>">
                        <?=btn_edit('post/edit/'.$post->postID, $this->lang->line('post_edit'))?>
                        <?=btn_delete('post/delete/'.$post->postID, $this->lang->line('post_delete'))?>
                    </td>
                <?php } ?>
            </tr>
        <?php } } ?>
        </tbody>
    </table>
</div>