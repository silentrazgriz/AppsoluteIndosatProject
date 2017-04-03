<table id="{{ $data['id'] }}" class="table table-striped table-bordered">
	<thead>
		<tr>
			@foreach($data['columns'] as $column)
			<th>{{ ucfirst($column) }}</th>
			@endforeach
			@if(isset($data['edit']) || isset($data['destroy']) || isset($data['detail']) || isset($data['popup']))
			<th style="width:180px;">Actions</th>
			@endif
		</tr>
	</thead>
	<tfoot>
		<tr>
			@foreach($data['columns'] as $column)
				<th>{{ ucfirst($column) }}</th>
			@endforeach
			@if(isset($data['edit']) || isset($data['destroy']) || isset($data['detail']) || isset($data['popup']))
			<th>Actions</th>
			@endif
		</tr>
	</tfoot>
	<tbody>
		@foreach ($data['values'] as $value)
		<tr>
			@foreach ($data['columns'] as $column)
			<td>{{ $value[$column] }}</td>
			@endforeach
			@if(isset($data['edit']) || isset($data['destroy']) || isset($data['detail']) || isset($data['popup']))
			<td class="text-center">
				@if(isset($data['popup']))
					@include('admin.component.popup', ['data' => $value, 'column' => $data['summary']['column']])
				@endif
				@if(isset($data['detail']))
					<a href="{{ route($data['detail'], ['id' => $value['id']]) }}" class="btn btn-primary btn-xs"><i class="fa fa-search" aria-hidden="true"></i> Lihat</a>
				@endif
				@if(isset($data['edit']))
					<a href="{{ route($data['edit'], ['id' => $value['id']]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Ubah</a>
				@endif
				@if(isset($data['destroy']))
					<form method="POST" action="{{ route($data['destroy'], ['id' => $value['id']]) }}" class="inline">
						{{ csrf_field() }}
						<input name="_method" type="hidden" value="DELETE">
						<button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-times" aria-hidden="true"></i> Hapus</button>
					</form>
				@endif
			</td>
			@endif
		</tr>
		@endforeach
	</tbody>
</table>

@section('scripts')
	<script>
		$(function(){
			$('#{{ $data['id'] }}').dataTable({
				'scrollY': '500px',
				'scrollCollapse': true,
				'paging': false,
				'responsive': true
			});
		});
	</script>
@append