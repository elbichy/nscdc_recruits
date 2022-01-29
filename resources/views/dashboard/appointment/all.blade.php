@extends('layouts.app', ['title' => 'Appointment Records'])

@section('content')
    <!-- Modal Structure for excel upload -->
    <div id="appointment_modal" class="modal">
        <div class="modal-content">
            <h4>Upload Redeployment</h4>
            <p>Select an excel file containing redeployment table and submit</p>
            <form style="margin-top: 15px; margin-bottom: 0px; padding: 10px;" action="{{ route('store_imported_appointment') }}" method="post" enctype="multipart/form-data" id="importData" class="row">
                @csrf
                <input type="file" name="import_file" class="left"/>
                <button class="importBtn btn waves-effect waves-light green darken-2 right">Import File</button>
            </form>
        </div>
        {{-- <div class="modal-footer">
            <a href="#!" id="modal-close" class="modal-close waves-effect waves-green btn-flat">Close</a>
        </div> --}}
    </div>
 
    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SALES HEADING --}}
                <h6 class="center sectionHeading">PERSONNEL APPOINTMENT - {{ $year }} LIST</h6>

                {{-- SALES TABLE --}}
                <div class="sectionTableWrap z-depth-1">
                    <div class="topMenuWrap" style="display: flex; justify-content:space-between; margin-bottom: 20px;">
                        <div class="left">
                            <button id="upload_excel_modal" class="greenBtn btn btn-small green darken-2 left">
                                <i class="fas fa-file-excel right"></i></i> IMPORT FROM EXCELL
                            </button>
                        </div>
                        
                        <button id="enlistBtn" class="enlistBtn btn btn-small"><i class="fas fa-file-word right"></i> GENERATE APPOINTMENT LETTER</button>
                    </div>
                    <table class="table centered table-bordered striped highlight" id="users-table">
                        <thead>
                            <tr>
                                <th><input type='checkbox' class='browser-default selectAll'></th>
                                <th>SN</th>
                                <th>Fullname</th>
                                <th>Gender</th>
                                <th>DOB</th>
                                <th>SOO</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Rank</th>
                                <th>ID NO.</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>SN</th>
                                <th>Fullname</th>
                                <th>Gender</th>
                                <th>DOB</th>
                                <th>SOO</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Rank</th>
                                <th>ID NO.</th>
                                <th></th>
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

    <script>

        $(function() {

            $('.dob_datepicker').datepicker({
				format: 'yyyy-mm-dd',
                container: 'body',
				yearRange: [1930, 1997]
			});
			$('.dofa_datepicker').datepicker({
				format: 'yyyy-mm-dd',
                container: 'body',
				yearRange: [2004, 2015]
			});
			$('.dopa_datepicker').datepicker({
				format: 'yyyy-mm-dd',
                container: 'body',
				yearRange: [2010, 2018]
			});
            $('.modal').modal();

            $(document).on('click', '#addCandidate', function() {
                // $(this).prop('disabled', true).html('Adding record...');
                $('.promotionModal').modal('open');
            });
            $(document).on('click', '#upload_excel_modal', function(){
                // alert('Yeah!');
                $('#appointment_modal').modal('open');
            });

            // GENERATE BULK LETTERS
            $(document).on('click', '#enlistBtn', function() {
                let id = [];
                if (confirm('Are you sure you want to generate letters for the selected personnel(s)?')) {
                    $('.personnelCheckbox:checked').each(function() {
                        id.push($(this).val())
                    });
                    if (id.length > 0) {
                        $('.enlistBtn').prop('disabled', true).html('PROCESSING...');
                        axios.post(`{!! route('generate_bulk_appointment_letter') !!}`, { candidates: id }, {responseType: 'blob'})
                            .then(function(response) {
                                if(response.status == 200){
                                    $('.enlistBtn').prop('disabled', false).html(`<i class="material-icons right">format_list_bulleted</i> GENERATE PROMOTION LETTERS`);
                                    const url = window.URL.createObjectURL(new Blob([response.data]));
                                    const link = document.createElement('a');
                                    link.href = url;
                                    link.setAttribute('download', 'appointment_letter.docx');
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
                ajax:  `{!! route('appointment_get_list', $year) !!}`,
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
                    { data: 'gender', name: 'gender'},
                    { data: 'date_of_birth', name: 'date_of_birth' },
                    { data: 'state', name: 'state' },
                    { data: 'email', name: 'email' },
                    { data: 'mobile_number', name: 'mobile_number' },
                    { data: 'position', name: 'position'},
                    { data: 'id_number', name: 'id_number'},
                    { data: 'view', name: 'view', "orderable": false, "searchable": false}
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