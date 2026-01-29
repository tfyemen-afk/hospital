<script type="application/javascript">
    $(function() {
        $('#calendar').fullCalendar({
            theme: true,
            customButtons: {
                reload: {
                    text: 'Reload',
                    click: function() {
                    }
                }
            },
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listMonth'
            },
            navLinks: true,
            editable: false,
            eventLimit: true,
            events: [
                <?php
                    if(inicompute($notices)) {
                        foreach ($notices as $notice) {
                            echo '{';
                                echo "title: '".str_replace("'", "\'", $notice->title)."', ";
                                echo "start: '".$notice->date."', ";
                                echo "end: '".$notice->date."', ";
                                echo "url:'".site_url('notice/view/'.$notice->noticeID.'/4')."', ";
                                echo "color  : '#EF2E04'";
                            echo '},';
                        }
                    }
                    if(inicompute($events)) {
                        foreach ($events as $event) {
                            echo '{';
                                echo "title: '".str_replace("'", "\'", $event->title)."', ";
                                echo "start: '".$event->fdate."T".$event->ftime."', ";
                                echo "end: '".$event->tdate."T".$event->ttime."', ";
                                echo "url:'".site_url('event/view/'.$event->eventID.'/4')."', ";
                                echo "color  : '#5C6BC0'";
                            echo '},';
                        }
                    }
                ?>
            ]
        });
    });
</script>
