<hr/>
<div class="row">
	<div class="col-xs-12"><h4>Your summary for today</h4></div>
</div>
<ul>
	@foreach ($kpis as $kpi)
		<li>
			<span>{{ $kpi['text'] }}</span>
			<div class="pull-right">
				<span class="{{ ($kpi['result'] < $kpi['goal']) ? 'text-danger' : 'text-success' }}">{{ number_format($kpi['result']) }}</span>
				<span>/ {{ number_format($kpi['goal']) }} {{ $kpi['unit'] }}</span>
			</div>
		</li>
	@endforeach
</ul>
<hr/>