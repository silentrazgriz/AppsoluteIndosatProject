<table id="{{ $data['id'] }}" class="table table-striped table-bordered">
    <thead>
    <tr>
        @foreach($data['columns'] as $column)
            <th>{{ ucfirst($column) }}</th>
        @endforeach
        @if($data['actions'])
            <th style="width:180px;">Actions</th>
        @endif
    </tr>
    </thead>
    <tfoot>
    <tr>
        @foreach($data['columns'] as $column)
            <th>{{ ucfirst($column) }}</th>
        @endforeach
        @if($data['actions'])
            <th>Actions</th>
        @endif
    </tr>
    </tfoot>
    <tbody>
    </tbody>
</table>

@section('scripts')
    <script>
        let data = {!! $data['values'] !!};

        $(function(){
            $('#{{ $data['id'] }}').dataTable({
                'data': data,
                'deferRender': true,
                'scroller': true,
                'scrollY': '350px',
                'scrollCollapse': true
            });
        });
    </script>
@append

@section('styles')
    <style>
        div.dataTables_paginate, div.dataTables_length {
            display:none;
        }
    </style>
@append