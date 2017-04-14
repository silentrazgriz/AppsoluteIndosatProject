@extends('web.app')

@section('navigation')
	<div class="status-bar small">
		<div class="title">
			<a href="{{ route('home') }}" class="btn-back"><i class="fa fa-arrow-circle-left fa-2x"></i></a>
			<h4>Leaderboard</h4>
			<span class="date pull-right">{{ $date }}</span>
		</div>
	</div>
@endsection

@section('content')
	<div class="leaderboard">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Nama</th>
					@foreach ($leaderboard['columns'] as $column)
						<th class="text-center">{{ $column }}</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach ($leaderboard['data'] as $row)
					<tr>
						<td>{{ $row['user']['name'] }}</td>
						@foreach ($row['kpis'] as $kpi)
							<td class="{{ ($kpi['result'] < $kpi['goal']) ? 'text-danger' : (($kpi['result'] > $kpi['goal']) ? 'text-info' : 'text-success') }} text-center">
								{{ number_format($kpi['result']) }}
							</td>
						@endforeach
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection