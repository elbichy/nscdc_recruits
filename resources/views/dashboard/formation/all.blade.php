@extends('administration.layouts.app', ['title' => 'All Redeployments Records'])

@section('content')
    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SALES HEADING --}}
                <h6 class="center sectionHeading">FORMATIONS MANAGEMENT</h6>

                {{-- SALES TABLE --}}
                <div class="sectionGridWrap z-depth-1">
                    <ul class="grid-collection">
                        @foreach($formations as $formation)
                            @if($formation->type == 'state')
                                <li class="grid-collection-item">
                                    <i class="fas fa-building fa-2x"></i> 
                                    <a href="{{ route('formation_manage', $formation->formation) }}" style="margin: 0 0 0 10px;">{{ $formation->formation }} State Command </a>
                                    <span class="new badge red darken-3" style="font-weight: bold;" data-badge-caption="">{{ $formation->users_count }}</span>
                                    <div class="item-btns">
                                        <a style="#">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a style="#">
                                            <i class="far fa-trash"></i>
                                        </a>
                                    </div>
                                </li>
                            @elseif($formation->type == 'fct')
                                <li class="grid-collection-item">
                                    <i class="fas fa-building fa-2x"></i> 
                                    <a href="{{ route('formation_manage', $formation->formation) }}" style="margin: 0 0 0 10px;">{{ $formation->formation }} Command </a>
                                    <span class="new badge red darken-3" style="font-weight: bold;" data-badge-caption="">{{ $formation->users_count }}</span>
                                    <div class="item-btns">
                                        <a style="#">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a style="#">
                                            <i class="far fa-trash"></i>
                                        </a>
                                    </div>
                                </li>
                            @elseif($formation->type == 'zone')
                                <li class="grid-collection-item">
                                    <i class="fas fa-building fa-2x"></i> 
                                    <a href="{{ route('formation_manage', $formation->formation) }}" style="margin: 0 0 0 10px;">{{ $formation->formation }} HQ </a>
                                    <span class="new badge red darken-3" style="font-weight: bold;" data-badge-caption="">{{ $formation->users_count }}</span>
                                    <div class="item-btns">
                                        <a style="#">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a style="#">
                                            <i class="far fa-trash"></i>
                                        </a>
                                    </div>
                                </li>
                            @else
                                <li class="grid-collection-item">
                                    <i class="fas fa-building fa-2x"></i> 
                                    <a href="{{ route('formation_manage', $formation->formation) }}" style="margin: 0 0 0 10px;">{{ $formation->formation }} </a>
                                    <span class="new badge red darken-3" style="font-weight: bold;" data-badge-caption="">{{ $formation->users_count }}</span>
                                    <div  class="item-btns">
                                        <a style="#">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a style="#">
                                            <i class="far fa-trash"></i>
                                        </a>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer z-depth-1">
            <p>&copy; Nigeria Security & Civil Defence Corps</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/datatable/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/datatable/jszip.min.js') }}"></script>
    <script src="{{ asset('js/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/datatable/buttons.print.min.js') }}"></script>
    <script>
        $(function() {
            $('.collapsible').collapsible();
            $('#users-table').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    'csv', 'excel', 'pdf'
                ],
                "lengthMenu": [[50, 100, 150, 250, 300, -1], [50, 100, 150, 250, 300, "All"]],
                processing: true,
                serverSide: true,
                // ajax:  `{!! route('personnel_get_all') !!}`,
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                    {
                        "data": "id",
                        "title": "SN",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }, "orderable": false, "searchable": false
                    },
                    { data: 'name', name: 'name' },
                    { data: 'service_number', name: 'service_number'},
                    { data: 'sex', name: 'sex'},
                    { data: 'dob', name: 'dob'},
                    { data: 'dofa', name: 'dofa'},
                    { data: 'dopa', name: 'dopa'},
                    { data: 'rank_short', name: 'rank_short'},
                    { data: 'current_formation', name: 'current_formation'}
                ],
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var input = document.createElement("input");
                        $(input).attr('placeholder', 'Search');
                        $(input).appendTo($(column.footer()).empty())
                        .on('keyup', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                    });
                }
            });
            $('.dataTables_length > label > select').addClass('browser-default');
        });
    </script>

@endpush