@extends('administration.layouts.app', ['title' => 'All Redeployments Records'])

@section('content')
    <!-- Modal Structure for selection -->
    <div id="modal" class="modal newPromotionModal">
        <div class="modal-content">
            <h5 class="center" style="margin-bottom: 15px;">AUTO FILL OPTIONS</h5>
            <p class="center">Select the personnel you'd like to redeploy.</p>
            <div class="row card" style="margin: 10px 0; padding: 9px 8px 8px 0;">
                <div class="checkboxes col s12 l9">
                </div>
                <button class="btn green col s12 l3" onclick="populateForm()">SELECT</button>
            </div>
        </div>
    </div>
    <!-- Modal Structure to create new conversion -->
    <div id="modal" class="modal conversionModal">
        <form action="{{ route('conversion_store_junior') }}" method="POST" name="create_form" id="conversion_create_form">
            <div class="modal-content">
                    @csrf
                    <div class="formWrap">
                        <fieldset id="form">
                            <div class="row">
                                {{-- Service No --}}
                                <div class="input-field col s12 l2">
                                    <input id="svc_no" name="svc_no" type="number" value="{{old('svc_no')}}"  onblur="loadAllfromSvcNo(event)" placeholder="e.g 012" required>
                                    @if ($errors->has('svc_no'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('svc_no') }}</strong>
                                        </span>
                                    @endif
                                    <label for="svc_no">Service No.</label>
                                </div>
                                {{-- Fullname --}}
                                <div class="input-field col s12 l4">
                                    <input id="name" name="name" type="text" value="{{old('name')}}" placeholder="e.g Jane Doe" required>
                                    @if ($errors->has('name'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                    <label for="name">Fullname</label>
                                </div>
                                {{-- Date of Birth --}}
                                <div class="input-field col s12 l3">
                                    <input id="dob" name="dob" type="text" class="dob_datepicker" value="{{old('dob')}}" placeholder="yy-mm-dd" required>
                                    @if ($errors->has('dob'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('dob') }}</strong>
                                        </span>
                                    @endif
                                    <label for="dob">Date of Birth</label>
                                </div>
                                {{-- Formation --}}
                                <div class="col s12 l3">
                                    <label for="formation">Serving Formation</label>
                                    <select id="formation" name="formation" class=" browser-default" required>
                                        <option disabled selected>Choose formation</option>
                                        @foreach($formations as $formation)
                                        <option value="{{ $formation->id }}">{{ $formation->formation }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('formation'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('formation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                {{-- State of Origin --}}
                                <div class="input-field col s12 l3">
                                    <input name="soo" type="text" class="soo" value="{{old('soo')}}" placeholder="e.g Kano" required>
                                    @if ($errors->has('soo'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('soo') }}</strong>
                                        </span>
                                    @endif
                                    <label for="soo">State of origin</label>
                                </div>
                                {{-- <div class="col s12 l3">
                                    <label for="soo">State of origin</label>
                                    <select id="soo" name="soo" class=" browser-default" required>
                                        <option disabled selected>Choose state</option>
                                        @foreach($states as $state)
                                        <option value="{{ $state->state_name }}">{{ ucwords($state->state_name) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('soo'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('soo') }}</strong>
                                        </span>
                                    @endif
                                </div> --}}

                                {{-- Additional Qual. --}}
                                <div class="input-field col s12 l4">
                                    <input name="additional_qual" type="text" class="additional_qual" value="{{old('additional_qual')}}" placeholder="e.g ICAN, B.Sc, HND, NCE, ND " required>
                                    @if ($errors->has('additional_qual'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('additional_qual') }}</strong>
                                        </span>
                                    @endif
                                    <label for="additional_qual">Additional Qual.</label>
                                </div>
                                {{-- Additional Qual. Year --}}
                                <div class="input-field col s12 l2">
                                    <input name="qual_year" type="number" class="qual_year" value="{{old('qual_year')}}" placeholder="e.g 2018" required>
                                    @if ($errors->has('qual_year'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('qual_year') }}</strong>
                                        </span>
                                    @endif
                                    <label for="qual_year">Grad. year</label>
                                </div>
                                {{-- Date of First Appt. --}}
                                <div class="input-field col s12 l3">
                                    <input id="dofa" name="dofa" type="text" class="dofa_datepicker" value="{{old('dofa')}}" placeholder="yy-mm-dd" required>
                                    @if ($errors->has('dofa'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('dofa') }}</strong>
                                        </span>
                                    @endif
                                    <label for="dofa">Date of First Appt.</label>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Date of Present Appt. --}}
                                <div class="input-field col s12 l4">
                                    <input id="dopa" name="dopa" type="text" class="dopa_datepicker" value="{{old('dopa')}}" placeholder="yy-mm-dd" required>
                                    @if ($errors->has('dopa'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('dopa') }}</strong>
                                        </span>
                                    @endif
                                    <label for="dopa">Date of Present Appt.</label>
                                </div>
                                {{-- Present Cadre --}}
                                <div class="col s12 l4">
                                    <label for="present_cadre">Present Cadre</label>
                                    <select id="present_cadre" name="present_cadre" class=" browser-default" required>
                                        <option disabled selected>Choose Cadre</option>
                                        <option value="inspectorate">Inspectorate</option>
                                        <option value="assistant">Assistant</option>
                                    </select>
                                    @if ($errors->has('present_cadre'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('present_cadre') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- Entry GL --}}
                                <div class="col s12 l4">
                                    <label for="entry_gl">Entry GL</label>
                                    <select id="entry_gl" name="entry_gl" class=" browser-default" required>
                                        <option disabled selected>Choose Cadre First</option>
                                    </select>
                                    @if ($errors->has('entry_gl'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('entry_gl') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                {{-- Present GL --}}
                                <div class="col s12 l3">
                                    <label for="present_gl">Present GL</label>
                                    <select id="present_gl" name="present_gl" class=" browser-default" required>
                                        <option disabled selected>Choose GL</option>
                                    </select>
                                    @if ($errors->has('present_gl'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('present_gl') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- New Cadre --}}
                                <div class="col s12 l3">
                                    <label for="new_cadre">New Cadre</label>
                                    <select id="new_cadre" name="new_cadre" class=" browser-default" required>
                                        <option disabled selected>Choose cadre</option>
                                        <option value="inspectorate">Inspectorate</option>
                                        <option value="assistant">Assistant</option>
                                    </select>
                                    @if ($errors->has('new_cadre'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('new_cadre') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- New GL --}}
                                <div class="col s12 l3">
                                    <label for="new_gl">New GL</label>
                                    <select id="new_gl" name="new_gl" class=" browser-default" required>
                                        <option disabled selected>Choose GL</option>
                                    </select>
                                    @if ($errors->has('new_gl'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('new_gl') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="submitBtn" style="display: flex; align-items: flex-end; height: 66px;">
                                    <button class="submit btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>ADD RECORD</button>
                                </div>
                            </div>
                        </fieldset>
                    </div>
            </div>
        </form>
    </div>
    
    <!-- Modal Structure to edit conversion -->
    <div id="modal" class="modal conversionEditModal">
        <form action="{{ route('conversion_update_junior') }}" method="POST" name="create_form" id="conversion_edit_form">
            <div class="modal-content">
                    @csrf
                    <div class="formWrap">
                        <fieldset id="form">
                            <div class="row">
                                {{-- ID --}}
                                <input id="id" name="id" type="hidden" value="{{old('id')}}">
                                {{-- Service No --}}
                                <div class="input-field col s12 l2">
                                    <input id="update-svc_no" name="update-svc_no" type="number" value="{{old('update-svc_no')}}" placeholder="e.g 012" required>
                                    @if ($errors->has('update-svc_no'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('update-svc_no') }}</strong>
                                        </span>
                                    @endif
                                    <label for="update-svc_no">Service No.</label>
                                </div>
                                {{-- Fullname --}}
                                <div class="input-field col s12 l4">
                                    <input id="update-name" name="update-name" type="text" value="{{old('update-name')}}" placeholder="e.g Jane Doe" required>
                                    @if ($errors->has('update-name'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('update-name') }}</strong>
                                        </span>
                                    @endif
                                    <label for="update-name">Fullname</label>
                                </div>
                                {{-- Date of Birth --}}
                                <div class="input-field col s12 l3">
                                    <input id="update-dob" name="update-dob" type="text" class="dob_datepicker" value="{{old('update-dob')}}" placeholder="yy-mm-dd" required>
                                    @if ($errors->has('update-dob'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('update-dob') }}</strong>
                                        </span>
                                    @endif
                                    <label for="dob">Date of Birth</label>
                                </div>
                                {{-- Formation --}}
                                <div class="col s12 l3">
                                    <label for="update-formation">Serving Formation</label>
                                    <select id="update-formation" name="update-formation" class=" browser-default" required>
                                        <option disabled selected>Choose formation</option>
                                        @foreach($formations as $formation)
                                        <option value="{{ $formation->formation }}">{{ $formation->formation }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('update-formation'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('update-formation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                {{-- State of Origin --}}
                                <div class="input-field col s12 l3">
                                    <input name="update-soo" type="text" class="update-soo" value="{{old('update-soo')}}" placeholder="e.g Kano" required>
                                    @if ($errors->has('update-soo'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('update-soo') }}</strong>
                                        </span>
                                    @endif
                                    <label for="update-soo">State of origin</label>
                                </div>
                                {{-- <div class="col s12 l3">
                                    <label for="soo">State of origin</label>
                                    <select id="soo" name="soo" class=" browser-default" required>
                                        <option disabled selected>Choose state</option>
                                        @foreach($states as $state)
                                        <option value="{{ $state->state_name }}">{{ ucwords($state->state_name) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('soo'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('soo') }}</strong>
                                        </span>
                                    @endif
                                </div> --}}

                                {{-- Additional Qual. --}}
                                <div class="input-field col s12 l4">
                                    <input name="update-additional_qual" type="text" class="update-additional_qual" value="{{old('update-additional_qual')}}" placeholder="e.g ICAN, B.Sc, HND, NCE, ND " required>
                                    @if ($errors->has('update-additional_qual'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('update-additional_qual') }}</strong>
                                        </span>
                                    @endif
                                    <label for="update-additional_qual">Additional Qual.</label>
                                </div>
                                {{-- Additional Qual. Year --}}
                                <div class="input-field col s12 l2">
                                    <input name="update-qual_year" type="number" class="update-qual_year" value="{{old('update-qual_year')}}" placeholder="e.g 2018" required>
                                    @if ($errors->has('update-qual_year'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('update-qual_year') }}</strong>
                                        </span>
                                    @endif
                                    <label for="update-qual_year">Grad. year</label>
                                </div>
                                {{-- Date of First Appt. --}}
                                <div class="input-field col s12 l3">
                                    <input id="update-dofa" name="update-dofa" type="text" class="dofa_datepicker" value="{{old('update-dofa')}}" placeholder="yy-mm-dd" required>
                                    @if ($errors->has('update-dofa'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('update-dofa') }}</strong>
                                        </span>
                                    @endif
                                    <label for="update-dofa">Date of First Appt.</label>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Date of Present Appt. --}}
                                <div class="input-field col s12 l4">
                                    <input id="update-dopa" name="update-dopa" type="text" class="dopa_datepicker" value="{{old('update-dopa')}}" placeholder="yy-mm-dd" required>
                                    @if ($errors->has('update-dopa'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('update-dopa') }}</strong>
                                        </span>
                                    @endif
                                    <label for="update-dopa">Date of Present Appt.</label>
                                </div>
                                {{-- Efective date --}}
                                <div class="input-field col s12 l4">
                                    <input id="update-effective_date" name="update-effective_date" type="text" class="dopa_datepicker" value="{{old('update-effective_date')}}" placeholder="yy-mm-dd" required>
                                    @if ($errors->has('update-effective_date'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('update-effective_date') }}</strong>
                                        </span>
                                    @endif
                                    <label for="update-effective_date">Effective date</label>
                                </div>
                                {{-- Entry Rank --}}
                                <div class="col s12 l4">
                                    <label for="update-entry_rank">Entry Rank</label>
                                    <select id="update-entry_rank" name="update-entry_rank" class=" browser-default" required>
                                        <option disabled selected>Choose GL</option>
                                        @foreach($ranks as $rank)
                                            @if(strpos($rank, 'General'))
                                            @continue
                                            @endif
                                            @if(strpos($rank, 'Commandant'))
                                                @continue
                                            @endif
                                            @if(strpos($rank, 'Superintendent'))
                                                @continue
                                            @endif
                                            @if(strpos($rank, 'Chief Inspector'))
                                                @continue
                                            @endif
                                            @if(strpos($rank, 'Principal'))
                                                @continue
                                            @endif
                                            @if(strpos($rank, 'Senior Inspector'))
                                                @continue
                                            @endif
                                            <option value="{{ $rank->short_title }}">{{ $rank->full_title }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('update-entry_rank'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('update-entry_rank') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                {{-- Present Rank --}}
                                <div class="col s12 l4">
                                    <label for="update-present_rank">Present Rank</label>
                                    <select id="update-present_rank" name="update-present_rank" class=" browser-default" required>
                                        <option disabled selected>Choose Rank</option>
                                        @foreach($ranks as $rank)
                                            @if(strpos($rank, 'General'))
                                                @continue
                                            @endif
                                            @if(strpos($rank, 'Commandant'))
                                                @continue
                                            @endif
                                            @if(strpos($rank, 'Superintendent'))
                                                @continue
                                            @endif
                                            @if(strpos($rank, 'Chief Inspector'))
                                                @continue
                                            @endif
                                            @if(strpos($rank, 'Principal'))
                                                @continue
                                            @endif
                                            @if(strpos($rank, 'Senior Inspector'))
                                                @continue
                                            @endif
                                            <option value="{{ $rank->full_title }}">{{ $rank->full_title }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('update-present_rank'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('update-present_rank') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- New Rank --}}
                                <div class="col s12 l4">
                                    <label for="update-new_rank">New Rank</label>
                                    <select id="update-new_rank" name="update-new_rank" class=" browser-default" required>
                                        <option disabled>Choose rank</option>
                                        @foreach($ranks as $rank)
                                            @if(strpos($rank, 'General'))
                                            @continue
                                            @endif
                                            @if(strpos($rank, 'Commandant'))
                                                @continue
                                            @endif
                                            @if(strpos($rank, 'Superintendent'))
                                                @continue
                                            @endif
                                            @if(strpos($rank, 'Chief Inspector'))
                                                @continue
                                            @endif
                                            @if(strpos($rank, 'Principal'))
                                                @continue
                                            @endif
                                            @if(strpos($rank, 'Senior Inspector'))
                                                @continue
                                            @endif
                                                <option value="{{ $rank->full_title }}">{{ $rank->full_title }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('update-new_rank'))
                                        <span class="helper-text red-text">
                                            <strong>{{ $errors->first('update-new_rank') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col s12 l4 submitBtn right" style="display: flex; align-items: flex-end; justify-content: flex-end; height: 66px;">
                                    <button class="submit btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>UPDATE RECORD</button>
                                    <button class="delete btn waves-effect waves-light right" type="submit" style="margin-left: 6px;" title="DELETE RECORD" onclick="deleteRecord(event)"><i class="material-icons right" style="margin-left: 0px;">delete</i></button>
                                </div>
                            </div>
                            
                        </fieldset>
                    </div>
            </div>
        </form>
        <form action="{{ route('conversion_delete_junior') }}" method="post" id="delete_form">
            @method('DELETE')
            @csrf
            <input type="hidden" name="delete_id" class="delete_id">
        </form>
    </div>

    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SALES HEADING --}}
                <h6 class="center sectionHeading">CONVERSION/UPGRADING - All RECORDS</h6>

                {{-- SALES TABLE --}}
                <div class="sectionTableWrap z-depth-1">
                    <div class="topMenuWrap" style="display: flex; justify-content:space-between; margin-bottom: 20px;">
                        <div class="left">
                            <button id="addCandidate" class="orangeBtn btn btn-small  orange darken-3 left">
                                <i class="fas fa-plus-square right"></i></i> ADD NEW RECORD
                            </button>
                            <a href="{{ route('import_conversion_data') }}" class="greenBtn btn btn-small green darken-2 left">
                                <i class="fas fa-file-excel right"></i></i> IMPORT FROM EXCELL
                            </a>
                        </div>
                        
                        <button id="enlistBtn" class="enlistBtn btn btn-small"><i class="fas fa-file-word right"></i> GENERATE CONVERSION LETTER</button>
                    </div>
                    <table class="table centered table-bordered striped highlight" id="users-table">
                        <thead>
                            <tr>
                                <th ></th>
                                <th><input type='checkbox' class='browser-default selectAll'></th>
                                <th>SN</th>
                                <th>Svc No.</th>
                                <th>Fullname</th>
                                <th>DOB</th>
                                <th>SOO</th>
                                <th>Command</th>
                                <th>Qual.</th>
                                <th>Qual. Year</th>
                                <th>DOFA</th>
                                <th>DOPA</th>
                                <th>Entry rank</th>
                                <th>Present rank</th>
                                <th>Conversion rank</th>
                                <th>Effective date</th>
                                <th>Type</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>SN</th>
                                <th>Svc No.</th>
                                <th>Fullname</th>
                                <th>DOB</th>
                                <th>SOO</th>
                                <th>Command</th>
                                <th>Qual.</th>
                                <th>Qual. Year</th>
                                <th>DOFA</th>
                                <th>DOPA</th>
                                <th>Entry rank</th>
                                <th>Present rank</th>
                                <th>Conversion rank</th>
                                <th>Effective date</th>
                                <th>Type</th>
                                <th>Created</th>
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
            $('.conversionModal').modal('open');
        });
    </script>
    @endif

    <script>

        $(function() {
            $('.newPromotionModal').modal();
        });
        
        // EDIT CONVERSION
        function editConversion(e){
            e.preventDefault();
            
            let id = e.currentTarget.dataset.conv_id;
            axios.get(`http://admindb.test/administration/dashboard/progression/conversion/junior/manage/get/${id}`)
            .then(function (response) {
               if(response.status == 200){
                    $('#id').val(id);
                    $('#update-svc_no').val(response.data.svc_no);
                    $('#update-name').val(response.data.name);
                    $('#update-dob').val(response.data.dob);
                    $(`#update-formation option[value="${response.data.command}"]`).prop("selected", "selected");
                    $(`#update-entry_rank option[value="${response.data.entry_rank}"]`).prop("selected", "selected");
                    $(`#update-present_rank option[value="${response.data.present_rank_full}"]`).prop("selected", "selected");
                    $(`#update-new_rank option[value="${response.data.conversion_rank_full}"]`).prop("selected", "selected");
                    $('.update-soo').val(response.data.soo);
                    $('.update-additional_qual').val(response.data.additional_qual);
                    $('.update-qual_year').val(response.data.qual_year);
                    $('#update-dofa').val(response.data.dofa);
                    $('#update-dopa').val(response.data.dopa);
                    $('#update-effective_date').val(response.data.effective_date);
                    $('.delete_id').val(response.data.id);
                    $('.conversionEditModal').modal('open');
               }
            });
        }

        // DELETE PROMOTION
        function deleteRecord(e){
			e.preventDefault();
            if (confirm('Are you sure you want to delete this record?')) {
                $('.delete').prop('disabled', true);
                $('#delete_form').submit();
            }
		}
        
        // LOAD EVERYTHING FROM SVC NO.
        function loadAllfromSvcNo(e){
            let value = e.currentTarget.value;
            if (value > 0) {
                axios.post(`{!! route('conversion_store_junior') !!}`, { svc_no_key: value })
                .then(function(response) {
                    if(response.data.status){
                        if(response.data.count > 1){
                            // $.wnoty({
                            //     type: 'error',
                            //     message: `Sorry there is conflict on this service number, please fill manually.`,
                            //     autohideDelay: 5000
                            // });
                            $.each(response.data.record, function name(key, value) {
								$('.checkboxes').append(`
									<label>
										<input class="with-gap" name="prompt_result" type="radio" data-details_array="${[value.name, value.dob, value.formations.length > 0 ? value.formations[0].id : 1, value.soo, value.qualifications.length > 0 ? value.qualifications[0].qualification : 'N/A', value.qualifications.length > 0 ? value.qualifications[0].year_obtained : '2017', value.dofa, value.dopa, value.cadre]}" value="${key}"/>
										<span>${value.name}</span>
									</label>
								`);
							});
							$('.newPromotionModal').modal('open');
                        }
                        else if(response.data.count == 0){
                            $.wnoty({
                                type: 'error',
                                message: `Record not found! please fill manually.`,
                                autohideDelay: 5000
                            });
                        }
                        else{
                            $confirm = confirm(`Does this service number belong to ${response.data.record[0].name}?`)
                            if($confirm){
                                $('#name').val(response.data.record[0].name);
                                $('#dob').val(response.data.record[0].dob);
                                $(`#formation option[value="${response.data.record[0].formations[0].id}"]`).prop("selected", "selected");
                                $('.soo').val(response.data.record[0].soo);
                                $(`#soo option[value="${response.data.record[0].formations[0].id}"]`).prop("selected", "selected");

                                $('.additional_qual').val(response.data.record[0].qualifications[0].qualification);
                                $('.qual_year').val(response.data.record[0].qualifications[0].year_obtained);
                                $('#dofa').val(response.data.record[0].dofa);
                                $('#dopa').val(response.data.record[0].dopa);
                                $(`#present_cadre option[value="${response.data.record[0].cadre}"]`).prop("selected", "selected");
                            }else{
                                $.wnoty({
                                type: 'error',
                                message: `Okay then, fill your form manually.`,
                                autohideDelay: 5000
                            });
                            }
                        }
                    }else{
                        $.wnoty({
                            type: 'error',
                            message: `${response.data.message}.`,
                            autohideDelay: 5000
                        });
                    }
                });
            } else {
                alert('You must type a valid service/file number!');
            }
        }

        function populateForm(e) {
			let selectedData = $("input[name='prompt_result']:checked")[0].dataset.details_array;
			let finalArray = selectedData.split(',');
			console.log(finalArray);
			$('#fullname').val();
			$('#from').val(finalArray[1]);
			$(`#rank option[value="${finalArray[2]}"]`).prop("selected", "selected");

            $('#name').val(finalArray[0]);
            $('#dob').val(finalArray[1]);
            $(`#formation option[value="${finalArray[2]}"]`).prop("selected", "selected");
            $('.soo').val(finalArray[3]);
            $(`#soo option[value="${finalArray[3]}"]`).prop("selected", "selected");

            $('.additional_qual').val(finalArray[4]);
            $('.qual_year').val(finalArray[5]);
            $('#dofa').val(finalArray[6]);
            $('#dopa').val(finalArray[7]);
            $(`#present_cadre option[value="${finalArray[8]}"]`).prop("selected", "selected");


			$('.newPromotionModal').modal('close');
			$('.checkboxes').html('');
		}

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

            // LOAD GL ON PRESENT CADRE SELECTION
            $('#present_cadre').change(function(event) {
                let selected_option = event.currentTarget.value;
                if (selected_option != '') {
                    axios.post(`{!! route('conversion_store_junior') !!}`, { present_key: selected_option })
                        .then(function(response) {
                            if(response.status == 200){
                                let result = response.data;
                                let options = '';
                                result.forEach(function(option){
                                    options+=`<option value='${option.gl}'>${option.gl}<option>`;
                                });
                                $('#entry_gl').html(options);
                                $('#present_gl').html(options);
                                
                            }
                        });
                } else {
                    alert('You must select at least one personnel!');
                }
            });

            // LOAD GL ON NEW CADRE SELECTION
            $('#new_cadre').change(function(event) {
                let selected_option = event.currentTarget.value;
                if (selected_option != '') {
                    axios.post(`{!! route('conversion_store_junior') !!}`, { new_key: selected_option })
                        .then(function(response) {
                            if(response.status == 200){
                                let result = response.data;
                                let options = '';
                                result.forEach(function(option){
                                    if(option.gl <= 7){
                                        options+=`<option value='${option.gl}'>${option.gl}<option>`;
                                    }
                                });
                                $('#new_gl').html(options);
                                
                            }
                        });
                } else {
                    alert('You must select at least one personnel!');
                }
            });

            $(document).on('click', '#addCandidate', function() {
                // $(this).prop('disabled', true).html('Adding record...');
                $('.conversionModal').modal('open');
            });

            // GENERATE LIST OF REDEPLOYMENTS
            $(document).on('click', '#enlistBtn', function() {
                let id = [];
                if (confirm('Are you sure you want to generate letters for the selected personnel(s)?')) {
                    $('.personnelCheckbox:checked').each(function() {
                        id.push($(this).val())
                    });
                    if (id.length > 0) {
                        $('.enlistBtn').prop('disabled', true).html('PROCESSING...');
                        axios.post(`{!! route('generate_bulk_junior_conversion_letter') !!}`, { candidates: id }, {responseType: 'blob'})
                            .then(function(response) {
                                if(response.status == 200){
                                    $('.enlistBtn').prop('disabled', false).html(`<i class="material-icons right">format_list_bulleted</i> GENERATE CONVERSION LETTER`);
                                    const url = window.URL.createObjectURL(new Blob([response.data]));
                                    const link = document.createElement('a');
                                    link.href = url;
                                    link.setAttribute('download', 'junior_conversion_letter.docx');
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
                ajax:  `{!! route('conversion_get_junior_list') !!}`,
                columns: [
                    
                    { data: 'view', name: 'view', "orderable": false, "searchable": false},
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                    {
                        "data": "id",
                        "title": "SN",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }, "orderable": false, "searchable": false
                    },
                    { data: 'svc_no', name: 'svc_no'},
                    { data: 'name', name: 'name' },
                    { data: 'dob', name: 'dob' },
                    { data: 'soo', name: 'soo' },
                    { data: 'command', name: 'command'},
                    { data: 'additional_qual', name: 'additional_qual' },
                    { data: 'qual_year', name: 'qual_year' },
                    { data: 'dofa', name: 'dofa' },
                    { data: 'dopa', name: 'dofa' },
                    { data: 'entry_rank', name: 'entry_rank' },
                    { data: 'present_rank_short', name: 'present_rank_short'},
                    { data: 'conversion_rank_short', name: 'conversion_rank_short'},
                    { data: 'effective_date', name: 'effective_date'},
                    { data: 'type', name: 'type'},
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