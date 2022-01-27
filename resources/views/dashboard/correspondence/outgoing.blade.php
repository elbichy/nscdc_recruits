@extends('administration.layouts.app', ['title' => 'All Redeployments Records'])

@section('content')
    <!-- Modal Structure for cloud upload -->
    <div id="modal" class="modal">
        <form action="{{ route('correspondence_store_incoming') }}" method="POST" name="create_form" id="create_form">
            <div class="modal-content">
                
                    @csrf
                    <div class="formWrap">
                        <fieldset id="form" class="row">
                            <input type="hidden" name="type" value="incomming">
                            {{-- Type --}}
                            <div class="col s12 l3">
                                <label for="type">Select Type</label>
                                <select id="kind" name="kind" class=" browser-default" required>
                                    <option disabled>Select Type</option>
                                    <option value="mail">Mail</option>
                                    <option value="memo">File</option>
                                </select>
                            </div>
                            {{-- Filename --}}
                            <div class="input-field col s12 l9">
                                <input id="filename" name="filename" type="text" value="{{old('filename')}}" required>
                                @if ($errors->has('filename'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('filename') }}</strong>
                                    </span>
                                @endif
                                <label for="filename">Filename</label>
                            </div>
                            {{-- File No --}}
                            <div class="input-field col s12 l4">
                                <input id="file_number" name="file_number" type="number" value="{{old('file_number')}}" required>
                                @if ($errors->has('file_number'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('file_number') }}</strong>
                                    </span>
                                @endif
                                <label for="file_number">File No.</label>
                            </div>
                            {{-- From Command --}}
                            <div class="input-field col s12 l4">
                                <input name="from" type="text" id="autocomplete-input" class="from autocomplete  z-depth-1" value="{{old('from')}}" required>
                                @if ($errors->has('from'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('from') }}</strong>
                                    </span>
                                @endif
                                <label for="from">From (Formation)</label>
                            </div>
                            {{-- Date --}}
                            <div class="input-field col s12 l4">
                                <input id="date" name="date" type="text" class="datepicker" value="{{old('date')}}" required>
                                @if ($errors->has('date'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                @endif
                                <label for="date">Date</label>
                            </div>
                        </fieldset>
                    </div>
            </div>
            <div class="modal-footer">
                <a href="#!" id="modal-close" class="modal-close waves-effect waves-green btn-flat">Close</a>
                <button class="submit btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>ADD RECORD</button>
            </div>
        </form>
    </div>
    
    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- OUTGOING HEADING --}}
                <h6 class="center sectionHeading">OUTGOING REGISTER</h6>

                {{-- OUTGOING TABLE --}}
                <div class="sectionTableWrap z-depth-1">
                    <div class="row topMenuWrap">
                        {{-- <button id="addIncoming" class="addIncoming btn btn-small green darken-2 white-text"><i class="material-icons right">add</i> ADD NEW RECORD</button> --}} 
                    </div>
                    <table class="table centered table-bordered" id="users-table">
                        <thead>
                            <tr>
                                <th><input type='checkbox' class='browser-default selectAll'></th>
                                <th>SN</th>
                                <th>Filename</th>
                                <th>File No</th>
                                <th>From (Ofiice)</th>
                                <th>Created</th>
                                <th style="width: 90px;"></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>SN</th>
                                <th>Filename</th>
                                <th>File No</th>
                                <th>From (Ofiice)</th>
                                <th>Created</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="footer z-depth-1">
            <p>&copy; NSCDC ICT & Cybersecurity Department</p>
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
    <script type="text/javascript" src="{{ asset('js/datatable/dataTables.checkboxes.min.js') }}"></script>

    <script>
        $(document).ready(function(){

            $('.datepicker').datepicker({
                container: 'body',
                defaultDate: new Date(),
                format: 'yyyy-mm-dd',
                setDefaultDate: true
            });
            $('.timepicker').timepicker({
                defaultTime: 'now'
            });

            $(document).on('change', '.selectAll', function() {
                if (this.checked) {
                    $('.personnelCheckbox').attr('checked', true);
                } else {
                    $('.personnelCheckbox').attr('checked', false);
                }
            });

            $(document).on('click', '#addIncoming', function() {
                // $(this).prop('disabled', true).html('Adding record...');
                $('.incomingModal').modal('open');
            });

            // LOAD DATATABLE
            $(function() {
                $('#users-table').DataTable({
                    dom: 'lBfrtip',
                    buttons: [
                        'csv', 'excel', 'pdf'
                    ],
                    "lengthMenu": [[10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100, -1], [10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100, "All"]],
                    processing: true,
                    serverSide: true,
                    ajax:  `{!! route('correspondence_get_incoming') !!}`,
                    columns: [
                        { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                        {
                            "data": "id",
                            "title": "SN",
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }, "orderable": false, "searchable": false
                        },
                        { data: 'filename', name: 'filename' },
                        { data: 'file_number', name: 'file_number'},
                        { data: 'from', name: 'from'},
                        { data: 'created_at', name: 'created_at'},
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
            
        });


        function edit(e, id) {
            e.preventDefault();
            console.log(id);
            let url = `${base_url}administration/dashboard/correspondence/edit/${id}`;
            axios.get(`${url}`)
                .then(response => {
                    if (response.status == 200) {
                        console.log(response.data);
                        $('#create_form').prop('action', `${base_url}administration/dashboard/correspondence/update/${response.data.id}`);
                        $('#filename').val(`${response.data.filename}`);
                        $('#file_number').val(`${response.data.file_number}`);
                        $('.from').val(`${response.data.from}`);
                        $('#date').val(`${response.data.created_at}`);
                        $('.submit').html(`<i class="material-icons right">send</i>UPDATE RECORD`);

                        $('.modal').modal('open');
                        // $.wnoty({
                        //     type: 'success',
                        //     message: 'Record deleted successfully',
                        //     autohideDelay: 5000
                        // });
                        // console.log($('a[data-row_id=' + response.data.id + ']').parent().parent().hide());
                    }
                });
        }
        
        // function editIncomingRecord(e, id) {
        //     e.preventDefault();
        //     console.log(id);
        //     let url = `${base_url}administration/dashboard/correspondence/delete_incoming/${id}`;
        //     let verify = confirm('Are you sure you want to delete this record?');
        //     if (verify) {
        //         axios.delete(`${url}${id}`)
        //             .then(response => {
        //                 if (response.data.status) {
        //                     $.wnoty({
        //                         type: 'success',
        //                         message: 'Record deleted successfully',
        //                         autohideDelay: 5000
        //                     });
        //                     console.log($('a[data-row_id=' + response.data.id + ']').parent().parent().hide());
        //                 }
        //             });
        //     }
        // }

    </script>

@endpush