<?php
if($_POST['PositionData']){
	$PositionData = $_POST['PositionData'];		
	}
?>
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'spline'
            },
            title: {
                text: 'Статистика <?php echo"$site";?>'
            },
            subtitle: {
                text: '<?php echo"$seacher";?>'
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: { // don't display the dummy year
                    month: '%e. %b',
                    year: '%b'
                }
            },
            yAxis: {
                title: {
                    text: 'Позиция'
                },
                min: 0
            },
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        Highcharts.dateFormat('%e. %b', this.x) +': '+ this.y;
                }
            },
            legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
            series: [<?php
				foreach($PositionData as $index=>$value){
					echo"{name: '$value[0]',";
					if($index==0){
						echo"color: '#AB5858',";
					}
					echo"data: [";
					if(count($value[2])!=0){
					foreach($value[2] as $key=>$date){
						$position =  $value[1][$key];
                                                $url =  $value[3][$key];//попытка показать найденную страницу сайта
						if($position==NULL) $position="null";
						if($key==count($value[2])-1){
							echo"[Date.UTC($date[0],  $date[1], $date[2],  $date[3],  $date[4],  $date[5]), $position]";
						}
						else{
							echo"[Date.UTC($date[0],  $date[1], $date[2],  $date[3],  $date[4],  $date[5]), $position],";
						}
					}
					}
					if($index==count($PositionData)-1){
						echo" ]}";
					}
					else{
						echo" ]},";
					}
				}
				?>]
        });
    });
    
});
</script>
