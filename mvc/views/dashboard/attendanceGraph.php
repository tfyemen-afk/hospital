
<script type="application/javascript">
    $(function() {
        LoadRoleWiseAttendance();
        function LoadRoleWiseAttendance() {
            $( '#top_right_graph_title').html("Role Attendence Lang");
            $( "#top_right_graph_back_btn" ).hide();
            $('#attendanceGraph').highcharts({
                chart: {
                    type: 'column',
                    height: 320
                },
                title: {
                    text: "<?=$this->lang->line("dashboard_today_attendance")?>"
                },
                subtitle: {
                    text: '<?=$this->lang->line("dashboard_today_attendance_subtitle")?>'
                },
                xAxis: {
                    categories: [
                        <?php
                            echo implode(',', pluck_bind($roles, 'role', "'", "'"));
                        ?>
                    ],
                    title: {
                        text: '<?=$this->lang->line("dashboard_role")?>',
                        align: 'low'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '<?=$this->lang->line("dashboard_attendance")?>',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y}</b>'
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true
                        }
                    },
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function (e) {
                                    LoadDayWiseAttendance(this.type, this.roleID, this.dayWiseAttendance);
                                }
                            }
                        }
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'left',
                    verticalAlign: 'top',
                    x: 5,
                    y: -10,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                    shadow: true
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: '<?=$this->lang->line("dashboard_present")?>',
                    data: [
                        <?php
                            foreach ($roles as $role) {
                                if(isset($today_attendance[$role->roleID])) {
                                    echo "{y:".$today_attendance[$role->roleID]['P'].", roleID:'".$role->roleID."', 'dayWiseAttendance': '".json_encode($role_wise_attendance[$role->roleID])."', 'type': 'P'},";
                                } else {
                                     echo "{y:0},";
                                }
                            }
                        ?>
                    ],
                    color: 'rgb(128,213,244)'
                },{
                    name: '<?=$this->lang->line("dashboard_absent")?>',
                    data: [
                        <?php
                            foreach ($roles as $role) {
                                if(isset($today_attendance[$role->roleID])) {
                                    echo "{y:".$today_attendance[$role->roleID]['A'].", roleID:'".$role->roleID."', 'dayWiseAttendance': '".json_encode($role_wise_attendance[$role->roleID])."', 'type': 'A'},";
                                } else {
                                    echo "{y:0},";
                                }
                            }
                        ?>
                    ],
                    color: 'rgb(225,83,135)'
                }]
            });
        }

        function LoadDayWiseAttendance(type, roleID, dayWiseAttendance)
        {
            $( "#top_right_graph_back_btn" ).show();
            $( "#top_right_graph_back_btn" ).unbind( "click" );
            $( "#top_right_graph_back_btn" ).on( "click",  function() {
                LoadRoleWiseAttendance();
            });
            var categories = [];
            var series = [];
            var attendance = [];
            var color = '#000';
            var attendanceTitle = '';
            if(type == 'P') {
                color = 'rgb(128,213,244)';
                attendanceTitle = '<?=$this->lang->line("dashboard_present")?>';
            } else {
                color = 'rgb(225,83,135)';
                attendanceTitle = '<?=$this->lang->line("dashboard_absent")?>';
            }

            csrfName = '<?=$this->security->get_csrf_token_name()?>';
            csrfHash = '<?=$this->security->get_csrf_hash()?>';

            $.ajax({
                type: 'POST',
                url: "<?=site_url('dashboard/getDayWiseAttendance')?>",
                data: {"dayWiseAttendance" : dayWiseAttendance, 'type': type, [csrfName] : csrfHash},
                dataType: "html",
                success: function(data) {
                    data = $.parseJSON(data);
                    $.each(data, function (i, value) {
                        categories.push('<?=$this->lang->line("dashboard_day")?> '+i);
                        attendance.push(value);
                    });
                    $('#attendanceGraph').highcharts({
                        chart: {
                            type: 'column',
                            height: 320
                        },
                        title: {
                            text: '<?=$this->lang->line("dashboard_this_month_daywise_attendance")?>'
                        },
                        subtitle: {
                            text: ''
                        },
                        xAxis: {
                            categories: categories,
                            title: {
                                text: null
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: '<?=$this->lang->line("dashboard_attendance")?>',
                                align: 'high'
                            },
                            labels: {
                                overflow: 'justify'
                            }
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.y}</b>'
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                }
                            },
                            series: {
                                cursor: 'pointer',
                                point: {
                                    events: {
                                        click: function (e) {
                                        }
                                    }
                                }
                            }
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'left',
                            verticalAlign: 'top',
                            x: 5,
                            y: -10,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                            shadow: true
                        },
                        credits: {
                            enabled: false
                        },
                        exporting: {
                            buttons: {
                                customButton: {
                                    text: "<< Back",
                                    x: -40,
                                    onclick: function () {
                                       LoadRoleWiseAttendance();
                                    }
                                }
                            }
                        },
                        series: [{
                            name: attendanceTitle,
                            data: attendance,
                            color: color
                        }]
                    });
                }
            });
        }

        $.extend(Highcharts.Renderer.prototype.symbols, {
            anX: function (a,b,c,d){return["M",a,b,"L",a+c,b+d,"M",a+c,b,"L",a,b+d]},
            triangle: function (a,b,c,d){return["M",a,b,"L",a+c,b+c,a+c/2,d,"Z"]},
            exportIcon: function (a,b,c,d){return y(["M",a,b+c,"L",a+c,b+d,a+c,b+d*0.8,a,b+d*0.8,"Z","M",a+c*0.5,b+d*0.8,"L",a+c*0.8,b+d*0.4,a+c*0.4,b+d*0.4,a+c*0.4,b,a+c*0.6,b,a+c*0.6,b+d*0.4,a+c*0.2,b+d*0.4,"Z"])}
        });
    });
</script>