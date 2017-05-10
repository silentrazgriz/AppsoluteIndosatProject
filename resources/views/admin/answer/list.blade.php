@extends('admin.app')

@section('content')
	<div class="container">
		<form method="GET" action="">
			<div class="row">
				<div class="col-xs-4">
					@include('fields.dropdown', [
						'field' => [
							'key' => 'sales_area_id',
							'text' => 'Area',
							'values' => $data['salesAreas'],
							'value' => $data['form']['sales_area_id'] ?? $data['salesAreas'][0]['key'],
							'placeholder' => 'Area'
						]
					])
				</div>
				<div class="col-xs-4">
					@include('fields.dropdown', [
						'field' => [
							'key' => 'user_id',
							'text' => 'Buddies',
							'values' => $data['users'],
							'value' => $data['form']['user_id'] ?? $data['users'][0]['key'],
							'placeholder' => 'Buddies'
						]
					])
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="form-group text-center">
						<button type="submit" class="btn btn-primary border-round">Kirim</button>
					</div>
				</div>
			</div>
		</form>
		<h3>Hasil Survey {{ $data['summary']['name'] }}</h3>
		@include('admin.component.table', ['data' => $data])
		<div class="text-center">
			{{ $data['pages']->links() }}
		</div>
	</div>
@endsection