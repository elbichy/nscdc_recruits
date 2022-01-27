@extends('administration.layouts.app', ['title' => 'Add New Records'])

@section('content')
    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- CORRESPONDENCE HEADING --}}
                <h6 class="center sectionHeading">NEW CORRESPONDENCE</h6>

                {{-- CORRESPONDENCE FORM --}}
                <div class="sectionFormWrap z-depth-1" style="padding:24px;">
                    <p class="formMsg blue lighten-5 left-align">
                        Fill the form below with the file information and submit.
                    </p>
					<form action="{{ route('correspondence_store') }}" method="POST" name="create_form" id="create_form">
						@csrf
						<div class="formWrap">
							<fieldset id="form" class="row card">
								<div class="row" style="margin-bottom: 0px;">
									{{-- Register Type --}}
									<div class="col s12 l2">
										<label for="register_type"> Register Type</label>
										<select id="register_type" name="register_type" class=" browser-default" required>
											<option disabled selected>Select Type</option>
											<option value="incoming">Incoming</option>
											<option value="outgoing">Outgoing</option>
										</select>
										@if ($errors->has('register_type'))
											<span class="helper-text red-text">
												<sub>{{ $errors->first('register_type') }}</sub>
											</span>
										@endif
									</div>
									{{-- Correspondence Type --}}
									<div class="col s12 l2">
										<label for="correspondence_type">Correspondence Type</label>
										<select id="correspondence_type" name="correspondence_type" class=" browser-default" required>
											<option disabled selected>Select Type</option>
											<option value="file">File</option>
											<option value="mail">Mail</option>
										</select>
										@if ($errors->has('correspondence_type'))
											<span class="helper-text red-text">
												<sub>{{ $errors->first('correspondence_type') }}</sub>
											</span>
										@endif
									</div>
									{{-- Filename --}}
									<div class="input-field col s12 l8">
										<input id="filename" name="filename" type="text" value="{{old('filename')}}" required>
										@if ($errors->has('filename'))
											<span class="helper-text red-text">
												<sub>{{ $errors->first('filename') }}</sub>
											</span>
										@endif
										<label for="filename">Filename</label>
									</div>
								</div>
								<div class="row" style="margin-bottom: 0px;">
									{{-- File No --}}
									<div class="input-field col s12 l2">
										<input id="file_number" name="file_number" type="number" value="{{old('file_number')}}" required>
										@if ($errors->has('file_number'))
											<span class="helper-text red-text">
												<sub>{{ $errors->first('file_number') }}</sub>
											</span>
										@endif
										<label for="file_number">File No.</label>
									</div>
									{{-- From Office --}}
									<div class="input-field col s12 l3">
										<input name="from" type="text" id="from" class="autocomplete" value="{{old('from')}}" required>
										@if ($errors->has('from'))
											<span class="helper-text red-text">
												<sub>{{ $errors->first('from') }}</sub>
											</span>
										@endif
										<label for="from">From (office)</label>
									</div>
									{{-- Action Page --}}
									<div class="input-field col s12 l2">
										<input id="action_page_no" name="action_page_no" type="number" value="{{old('action_page')}}" required>
										@if ($errors->has('action_page_no'))
											<span class="helper-text red-text">
												<sub>{{ $errors->first('action_page_no') }}</sub>
											</span>
										@endif
										<label for="action_page_no">Action page no.</label>
									</div>
									{{-- Date --}}
									<div class="input-field col s12 l2">
										<input id="date" name="date" type="text" class="datepicker" value="{{old('date')}}" required>
										@if ($errors->has('date'))
											<span class="helper-text red-text">
												<sub>{{ $errors->first('date') }}</sub>
											</span>
										@endif
										<label for="date">Date</label>
									</div>
									<div class="input-field col s12 l3" style="margin-bottom: 0px; margin-top: 0px; display: flex;
									height: 60px; align-items: flex-end; justify-content: flex-end;">
										<button class="submit btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>ADD RECORD</button>
									</div>
								</div>
							</fieldset>
						</div>
						
					</form>
                </div>
            </div>
        </div>
        <div class="footer z-depth-1">
            <p>&copy; NSCDC ICT & Cybersecurity Department</p>
        </div>
    </div>
@endsection

@push('scripts')
	<script>
		$(document).ready(function(){

			$('.datepicker').datepicker({
				defaultDate: new Date(),
				format: 'yyyy-mm-dd',
            	setDefaultDate: true
			});
			$('.timepicker').timepicker({
				defaultTime: 'now'
			});

			$('#create_form').submit(function (e) { 
				$('.submit').prop('disabled', true).html('ADDING RECORD...');
			});

			var offices_arr = {!! $office_arr !!};
            var offices = {};

            for(var i=0; i < offices_arr.length; i++){
                offices[offices_arr[i]] = null;
            }

            $('input.autocomplete').autocomplete({
				data: offices
			});
		});
	</script>
@endpush