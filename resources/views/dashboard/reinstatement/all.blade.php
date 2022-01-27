@extends('administration.layouts.app', ['title' => 'All Reinstatement Records'])

@section('content')

    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SALES HEADING --}}
                <h6 class="center sectionHeading">REINSTATEMENT - All RECORDS</h6>

                {{-- SALES TABLE --}}
                <div class="sectionTableWrap z-depth-1">
                    <div class="topMenuWrap" style="display: flex; justify-content:space-between; margin-bottom: 20px;">
                        <div class="left">
                            <button id="addCandidate" class="orangeBtn btn btn-small  orange darken-3 left">
                                <i class="fas fa-plus-square right"></i></i> ADD NEW RECORD
                            </button>
                            <a href="{{ route('import_reinstatement_data') }}" class="greenBtn btn btn-small green darken-2 left">
                                <i class="fas fa-file-excel right"></i></i> IMPORT FROM EXCELL
                            </a>
                        </div>
                        
                        <button id="enlistBtn" class="enlistBtn btn btn-small"><i class="fas fa-file-word right"></i> GENERATE REINSTATEMENT LETTER</button>
                    </div>
                    <table class="table centered table-bordered striped highlight" id="users-table">
                        <thead>
                            <tr>
                                <th><input type='checkbox' class='browser-default selectAll'></th>
                                <th>#</th>
                                <th>SN</th>
                                <th>Fullname</th>
                                <th>DOB</th>
                                <th>SOO</th>
                                <th>Command</th>
                                <th>Qual.</th>
                                <th>DOFA</th>
                                <th>DOPA</th>
                                <th>Entry rank</th>
                                <th>Present rank</th>
                                <th>Reinstatement rank</th>
                                <th>Effective date</th>
                                <th>Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>SN</th>
                                <th>Fullname</th>
                                <th>DOB</th>
                                <th>SOO</th>
                                <th>Command</th>
                                <th>Qual.</th>
                                <th>DOFA</th>
                                <th>DOPA</th>
                                <th>Entry rank</th>
                                <th>Present rank</th>
                                <th>Reinstatement rank</th>
                                <th>Effective date</th>
                                <th>Created</th>
                                {{-- <th></th> --}}
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

    @if ($errors->any())
    <script>
        $(function() {
            $('.reinstatementModal').modal('open');
        });
    </script>
    @endif

    <script>
        
        // EDIT CONVERSION
        function editConversion(e){
            e.preventDefault();
            
            let id = e.currentTarget.dataset.conv_id;
            axios.get(`http://admindb.test/administration/dashboard/progression/reinstatement/manage/junior/get/${id}`)
            .then(function (response) {
               if(response.status == 200){
                    $('#id').val(id);
                    $('#update-svc_no').val(response.data.svc_no);
                    $('#update-name').val(response.data.name);
                    $('#update-dob').val(response.data.dob);
                    $(`#update-formation option[value="${response.data.command}"]`).prop("selected", "selected");
                    $(`#update-entry_rank option[value="${response.data.entry_rank}"]`).prop("selected", "selected");
                    $(`#update-present_rank option[value="${response.data.present_rank_full}"]`).prop("selected", "selected");
                    $(`#update-new_rank option[value="${response.data.reinstatement_rank_full}"]`).prop("selected", "selected");
                    $('.update-soo').val(response.data.soo);
                    $('.update-additional_qual').val(response.data.additional_qual);
                    $('.update-qual_year').val(response.data.qual_year);
                    $('#update-dofa').val(response.data.dofa);
                    $('#update-dopa').val(response.data.dopa);
                    $('#update-effective_date').val(response.data.effective_date);
                    $('.reinstatementEditModal').modal('open');
               }
            });
        }

        $(function() {

            // GENERATE BULK REINSTATEMENT LETTER
            $(document).on('click', '#enlistBtn', function() {
                let id = [];
                if (confirm('Are you sure you want to generate letters for the selected personnel(s)?')) {
                    $('.personnelCheckbox:checked').each(function() {
                        id.push($(this).val())
                    });
                    if (id.length > 0) {
                        $('.enlistBtn').prop('disabled', true).html('PROCESSING...');
                        axios.post(`{!! route('generate_bulk_reinstatement_letter') !!}`, { candidates: id }, {responseType: 'blob'})
                            .then(function(response) {
                                if(response.status == 200){
                                    $('.enlistBtn').prop('disabled', false).html(`<i class="material-icons right">format_list_bulleted</i> GENERATE REINSTATEMENT LETTER`);
                                    const url = window.URL.createObjectURL(new Blob([response.data]));
                                    const link = document.createElement('a');
                                    link.href = url;
                                    link.setAttribute('download', 'reinstatement_letter.docx');
                                    document.body.appendChild(link);
                                    link.click();
                                    $('#users-table th input:checked'). prop("checked", false);
                                    $('#users-table').DataTable().ajax.reload();
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
                ajax:  `{!! route('reinstatement_get_list') !!}`,
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                    {
                        "data": "id",
                        "title": "#",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }, "orderable": false, "searchable": false
                    },
                    { data: 'serial_no', name: 'serial_no'},
                    { data: 'name', name: 'name' },
                    { data: 'dob', name: 'dob' },
                    { data: 'soo', name: 'soo' },
                    { data: 'command', name: 'command'},
                    { data: 'additional_qual', name: 'additional_qual' },
                    { data: 'dofa', name: 'dofa' },
                    { data: 'dopa', name: 'dofa' },
                    { data: 'entry_rank', name: 'entry_rank' },
                    { data: 'present_rank_short', name: 'present_rank_short'},
                    { data: 'promotion_rank_short', name: 'promotion_rank_short'},
                    { data: 'effective_date', name: 'effective_date'},
                    { data: 'updated_at', name: 'updated_at'}
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