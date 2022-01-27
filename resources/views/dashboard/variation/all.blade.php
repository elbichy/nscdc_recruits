@extends('administration.layouts.app', ['title' => 'All variation Records'])

@section('content')

    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SALES HEADING --}}
                <h6 class="center sectionHeading">All PERSONNEL - (CONPASS)</h6>

                {{-- SALES TABLE --}}
                <div class="sectionTableWrap z-depth-1">
                    <div class="topMenuWrap" style="display: flex; justify-content:space-between; margin-bottom: 20px;">
                        <div class="left">
                            {{-- <button id="addCandidate" class="orangeBtn btn btn-small  orange darken-3 left">
                                <i class="fas fa-plus-square right"></i></i> ADD NEW RECORD
                            </button> --}}
                            <a href="{{ route('import_variation_data') }}" class="greenBtn btn btn-small green darken-2 left">
                                <i class="fas fa-file-excel right"></i></i> IMPORT FROM EXCELL
                            </a>
                        </div>
                        
                        <button id="enlistBtn" class="enlistBtn btn btn-small"><i class="fas fa-file-word right"></i> GENERATE BULK VARIATION SLIP</button>
                    </div>
                    <table class="table centered table-bordered striped highlight" id="users-table">
                        <thead>
                            <tr>
                                <th><input type='checkbox' class='browser-default selectAll'></th>
                                <th>#</th>
                                <th>Service No.</th>
                                <th>IPPIS No.</th>
                                <th>Fullname</th>
                                <th>Rank</th>
                                <th>Date of 1st Appt.</th>
                                {{-- <th>Old rank</th>
                                <th>Old GL</th>
                                <th>Old Step</th>
                                <th>Old Salary Per Annum</th>
                                <th>New rank</th>
                                <th>New GL</th>
                                <th>New Step</th>
                                <th>New Salary Per Annum</th>
                                <th>Effective</th>
                                <th>Placed</th>
                                <th>Months Owed</th>
                                <th>Variation</th>
                                <th>Arrears</th>
                                <th>Remark</th> --}}
                                <th>Progressions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                {{-- <th>#</th> --}}
                                <th>Service No.</th>
                                <th>IPPIS No.</th>
                                <th>Fullname</th>
                                <th>Rank</th>
                                <th>Date of 1st Appt.</th>
                                {{-- <th>Old rank</th>
                                <th>Old GL</th>
                                <th>Old Step</th>
                                <th>Old Salary Per Annum</th>
                                <th>New rank</th>
                                <th>New GL</th>
                                <th>New Step</th>
                                <th>New Salary Per Annum</th>
                                <th>Effective</th>
                                <th>Placed</th>
                                <th>Months Owed</th>
                                <th>Variation</th>
                                <th>Arrears</th>
                                <th>Remark</th> --}}
                                <th>Progressions</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
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
            // GENERATE BULK variation LETTER
            $(document).on('click', '#enlistBtn', function() {
                let id = [];
                if (confirm('Are you sure you want to generate variation advice for the selected personnel(s)?')) {
                    $('.personnelCheckbox:checked').each(function() {
                        id.push($(this).val())
                    });
                    if (id.length > 0) {
                        $('.enlistBtn').prop('disabled', true).html('PROCESSING...');
                        axios.post(`{!! route('generate_bulk_variation_slip') !!}`, { candidates: id }, {responseType: 'blob'})
                            .then(function(response) {
                                if(response.status == 200){
                                    if(response.data.size == 0){
                                        alert('The selected personnel have no single progression record')
                                        $('.enlistBtn').prop('disabled', false).html(`<i class="material-icons right">format_list_bulleted</i> GENERATE VARIATION ADVICE`);
                                        $('#users-table th input:checked'). prop("checked", false);
                                        $('#users-table').DataTable().ajax.reload();
                                    }else{
                                        $('.enlistBtn').prop('disabled', false).html(`<i class="material-icons right">format_list_bulleted</i> GENERATE VARIATION ADVICE`);
                                        const url = window.URL.createObjectURL(new Blob([response.data]));
                                        const link = document.createElement('a');
                                        link.href = url;
                                        link.setAttribute('download', 'bulk_variation_advice.docx');
                                        document.body.appendChild(link);
                                        link.click();
                                        $('#users-table th input:checked'). prop("checked", false);
                                        $('#users-table').DataTable().ajax.reload();
                                    }
                                }
                            });
                    } else {
                        alert('You must select at least one personnel!');
                    }
                }
            });

            $(document).on('change', '.selectAll', function() {
                if (this.checked) {
                    $('.personnelCheckbox').attr('checked', true);
                } else {
                    $('.personnelCheckbox').attr('checked', false);
                }
            });

            $('#users-table').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    'csv', 'excel', 'pdf'
                ],
                "lengthMenu": [[10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100, -1], [10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100, "All"]],
                processing: true,
                serverSide: true,
                ajax:  `{!! route('variation_get_list') !!}`,
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                    {
                        "data": "id",
                        "title": "#",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }, "orderable": false, "searchable": false
                    },
                    { data: 'svc_no', name: 'svc_no'},
                    { data: 'ippis_no', name: 'ippis_no'},
                    { data: 'name', name: 'name' },
                    { data: 'rank', name: 'rank' },
                    { data: 'dofa', name: 'dofa' },
                    // { data: 'old_rank', name: 'old_rank'},
                    // { data: 'old_gl', name: 'old_gl'},
                    // { data: 'old_step', name: 'old_step'},
                    // { data: 'old_salary_per_annum', name: 'old_salary_per_annum'},
                    // { data: 'new_rank', name: 'new_rank'},
                    // { data: 'new_gl', name: 'new_gl'},
                    // { data: 'new_step', name: 'new_step'},
                    // { data: 'new_salary_per_annum', name: 'new_salary_per_annum'},
                    // { data: 'effective', name: 'effective'},
                    // { data: 'placed', name: 'placed'},
                    // { data: 'months_owed', name: 'months_owed'},
                    // { data: 'variation_amount', name: 'variation_amount'},
                    // { data: 'arrears', name: 'arrears'},
                    { data: 'variations_count', name: 'variations_count', "orderable": false, "searchable": false},
                    { data: 'view', name: 'view', "orderable": false, "searchable": false},
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