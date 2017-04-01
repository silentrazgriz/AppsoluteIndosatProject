@extends('admin.app')

@section('content')
	<h2>In Development</h2>
	<hr/>
	<div>
		Temporary report data<br>
		Total report : {{ $data['success_answer'] + $data['failed_answer'] }}<br>
		Success report : {{ $data['success_answer'] }}<br>
		Failed report : {{ $data['failed_answer'] }}
	</div>
	<hr/>
	<div>
		Temporary sales data<br>
		Total sales : {{ count($data['sales']) }}<br>
		Total report per sales :<br>
		<table frame="box" rules="all">
			<tr>
				<th>Email</th>
				<th>Name</th>
				<th>Success report</th>
				<th>Failed report</th>
			</tr>
			@foreach($data['sales'] as $sales)
				<tr>
					<td>{{ $sales['email'] }}</td>
					<td>{{ $sales['name'] }}</td>
					<td>{{ $sales['success_count'] }}</td>
					<td>{{ $sales['failed_count'] }}</td>
				</tr>
			@endforeach
		</table>
	</div>
@endsection
@section('styles')
	<style>
		table tr td, table tr th {
			padding: 5px;
		}
	</style>
@append