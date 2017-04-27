@extends('admin.app')

@section('content')
	<div class="container">
		<form role="form" method="POST" action="{{ route('put-area', ['id' => $data['id']]) }}">
			<input type="hidden" name="_method" value="PUT">
			{{ csrf_field() }}
			<h3>Ubah Area</h3>
			@include('fields.text', [
				'field' => [
					'key' => 'description',
					'text' => 'Sales Area',
					'required' => true,
					'value' => $data['description']
				]
			])
			<div class="form-group text-center">
				<button type="reset" class="btn btn-danger border-round">Batal</button>
				<button type="submit" class="btn btn-success border-round">Ubah Area</button>
			</div>
		</form>
	</div>
@endsection