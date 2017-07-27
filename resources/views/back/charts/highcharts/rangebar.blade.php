<script type="text/javascript">
	$(function () {
		var {{ $model->id }} = new Highcharts.Chart({
			colors: [
				@foreach($model->colors as $c)
					"{{ $c }}",
				@endforeach
			],
			chart: {
				renderTo:  "{{ $model->id }}",
				@include('charts::_partials.dimension.js2')
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'columnrange',
				inverted: true
			},
			@if($model->title)
			title: {
				text:  "{!! $model->title !!}"
			},
			@endif
					@if(!$model->credits)
			credits: {
				enabled: false
			},
			@endif
			plotOptions: {
				series: {
					colorByPoint: true,
				},
				columnrange: {
					shadow: true,
				}
			},
			xAxis: {
				title: {
					text: "{!! $model->x_axis_title !!}"
				},
				categories: [
					@foreach($model->labels as $label)
						"{!! $label !!}",
					@endforeach
				]
			},
			yAxis: {
				title: {
					text: "{!! $model->y_axis_title === null ? $model->element_label : $model->y_axis_title !!}"
				},
				plotLines: [{
					color: 'red', // Color value
					dashStyle: 'solid', // Style of the plot line. Default to solid
					value: Date.UTC('{{ \Carbon\Carbon::now()->year }}', '{{ \Carbon\Carbon::now()->month }}', '{{ \Carbon\Carbon::now()->day }}'), // Value of where the line will appear
					width: 1 ,// Width of the line
					zIndex: 5
				}],
				type: 'datetime',
				labels: {
					formatter: function() {
						return Highcharts.dateFormat('%e/%m/%y',new Date(this.value));
					}
				}
			},
			legend: {
				@if(!$model->legend)
				enabled: false,
				@endif
			},
			tooltip: {
				shared: true,
				valueSuffix: '',
				formatter: function() {
					var each = Highcharts.each,
						points = this.points || Highcharts.splat(this),
						txt = '';
					each(points, function(p, i){
						txt += '<b>'+ p.x + '</b><br/>start: ' + Highcharts.dateFormat('%e %B %Y',
							new Date(p.point.low)) + '<br/> end: ' + Highcharts.dateFormat('%e %B %Y',
							new Date(p.point.high));
					});
					return txt;
				}
			},
			series: [{
				name: "{!! $model->element_label !!}",
				data: [
						@foreach($model->values as $dta)
							{
								low: Date.UTC('{{ $dta[0]->year }}', '{{ $dta[0]->month }}', '{{ $dta[0]->day }}'),
								high: Date.UTC('{{ $dta[1]->year }}', '{{ $dta[1]->month }}', '{{ $dta[1]->day }}'),
								color: '{{ $dta['color'] }}',
								@if(isset($dta['link']))
									link: '{{ $dta['link'] }}'
								@endif
							},
						@endforeach
				],
				cursor: 'pointer',
				point: {
					events: {
						click: function () {
							window.location.href = this.link;
						}
					}
				}
			}]
		})
	});
</script>
@if(!$model->customId)
	@include('charts::_partials.container.div')
@endif