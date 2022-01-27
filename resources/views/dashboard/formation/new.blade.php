@extends('administration.layouts.app', ['title' => 'Add New Records'])

@section('content')
    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- CORRESPONDENCE HEADING --}}
                <h6 class="center sectionHeading">NEW FORMATION</h6>

                {{-- CORRESPONDENCE FORM --}}
                <div class="sectionFormWrap z-depth-1" style="padding:24px;">
                    <p class="formMsg blue lighten-5 left-align">
                        Fill the form below with the file information and submit.
                    </p>
					<form action="{{ route('formation_store') }}" method="POST" name="create_form" id="create_form">
						@csrf
						<div class="formWrap">
							<fieldset id="form" class="row card">
								{{-- Type --}}
								<div class="col s12 l3">
									<label for="type">Select Formation Type</label>
									<select id="type" name="type" class=" browser-default" required>
										<option disabled>Select Type</option>
										<option value="state" selected>State Command</option>
										<option value="zone">Zonal Command</option>
										<option value="nhq">National Headquarters</option>
										<option value="fct">FCT Command</option>
										<option value="institution">Academy/Colleges</option>
									</select>
								</div>
								{{-- Formation name --}}
								<div class="input-field col s12 l6">
									<input id="formation" name="formation" type="text" value="{{old('formation')}}" required>
									@if ($errors->has('formation'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('formation') }}</strong>
										</span>
									@endif
									<label for="formation">Formation name</label>
								</div>
								{{-- Level --}}
								<div class="col s12 l3">
									<label for="level">Select Formation Level</label>
									<select id="level" name="level" class=" browser-default" required>
										<option disabled>Select Type</option>
										<option value="1">Priority 1</option>
										<option value="2">Priority 2</option>
										<option value="3">Priority 3</option>
									</select>
								</div>
							</fieldset>
						</div>
						<div class="input-field col s12 l4">
							<button class="submit btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>ADD RECORD</button>
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
			let commands = {
						'National Headquarters' : null,
						'Abia State Command' : null,
						'Adamawa State Command' : null,
						'Akwa-ibom State Command' : null,
						'Anambra State Command' : null,
						'Bauchi State Command' : null,
						'Bayelsa State Command' : null,
						'Benue State Command' : null,
						'Borno State Command' : null,
						'Cross-river State Command' : null,
						'Delta State Command' : null,
						'Ebonyi State Command' : null,
						'Edo State Command' : null,
						'Ekiti State Command' : null,
						'Enugu State Command' : null,
						'FCT Command' : null,
						'Gombe State Command' : null,
						'Imo State Command' : null,
						'Jigawa State Command' : null,
						'Kaduna State Command' : null,
						'Kano State Command' : null,
						'Katsina State Command' : null,
						'Kebbi State Command' : null,
						'Kogi State Command' : null,
						'Kwara State Command' : null,
						'Lagos State Command' : null,
						'Nasarawa State Command' : null,
						'Niger State Command' : null,
						'Ogun State Command' : null,
						'Ondo State Command' : null,
						'Osun State Command' : null,
						'Oyo State Command' : null,
						'Plateau State Command' : null,
						'Rivers State Command' : null,
						'Sokoto State Command' : null,
						'Taraba State Command' : null,
						'Yobe State Command' : null,
						'Zamfara State Command' : null,
						'Zone A HQ, Lagos' : null,
						'Zone B HQ, Kaduna' : null,
						'Zone C HQ, Bauchi' : null,
						'Zone D HQ, Minna' : null,
						'Zone E HQ, Oweri' : null,
						'Zone F HQ, Abeokuta' : null,
						'Zone G HQ, Benin' : null,
						'Zone H HQ, Makurdi' : null,
						'College of Security Management, Abeokuta' : null,
						'College of Peace, Conflic Resolution &Desaster Management, Katsina' : null,
						'Civil Defence Academy, Sauka' : null
					}
			$('#addMultiple').on('click', function (e) {
				e.preventDefault();
				let count = e.currentTarget.dataset.count;
				$(this).attr('data-count', Number(count)+1);
				let content = `
					<fieldset id="form" class="row card">
						<a id="closePane" class="closePane btn-floating btn-small waves-effect waves-light red left"><i class="material-icons">close</i></a>
						{{-- Type --}}
						<div class="col s12 l2">
							<label for="type.${count}">Select Type</label>
							<select id="type.${count}" name="type[]" class=" browser-default" required>
								<option disabled>Select Type</option>
								<option value="incoming" selected>Incoming</option>
								<option value="outgoing">Outgoing</option>
							</select>
						</div>
						{{-- Filename --}}
						<div class="input-field col s12 l7">
							<input id="filename.${count}" name="filename[]" type="text" value="{{old('filename.${count}')}}" required>
							@if ($errors->has('filename.${count}'))
								<span class="helper-text red-text">
									<strong>{{ $errors->first('filename.${count}') }}</strong>
								</span>
							@endif
							<label for="filename.${count}">Filename</label>
						</div>
						{{-- File No --}}
						<div class="input-field col s12 l3">
							<input id="file_number.${count}" name="file_number[]" type="number" value="{{old('file_number.${count}')}}" required>
							@if ($errors->has('file_number.${count}'))
								<span class="helper-text red-text">
									<strong>{{ $errors->first('file_number.${count}') }}</strong>
								</span>
							@endif
							<label for="file_number.${count}">File No.</label>
						</div>
						{{-- From Office --}}
						<div class="input-field col s12 l4">
							<input name="from[]" type="text" id="autocomplete-input from.${count}" class="autocomplete" value="{{old('from.${count}')}}" required>
							@if ($errors->has('from.${count}'))
								<span class="helper-text red-text">
									<strong>{{ $errors->first('from.${count}') }}</strong>
								</span>
							@endif
							<label for="from.${count}">From (Office)</label>
						</div>
						{{-- To Office --}}
						<div class="input-field col s12 l4">
							<input name="to[]" type="text" id="autocomplete-input to.${count}" class="autocomplete" value="{{old('to.${count}')}}" required>
							@if ($errors->has('to.${count}'))
								<span class="helper-text red-text">
									<strong>{{ $errors->first('to.${count}') }}</strong>
								</span>
							@endif
							<label for="to.${count}">To (Office)</label>
						</div>
						{{-- Date --}}
						<div class="input-field col s12 l4">
							<input id="date.${count}" name="date[]" type="text" class="datepicker" value="{{old('date.${count}')}}" required>
							@if ($errors->has('date.${count}'))
								<span class="helper-text red-text">
									<strong>{{ $errors->first('date.${count}') }}</strong>
								</span>
							@endif
							<label for="date.${count}">Date</label>
						</div>
					</fieldset>`;
				$(content).hide().appendTo('.formWrap').slideDown();

				// Initialization
				$('.datepicker').datepicker({
					defaultDate: new Date(),
					format: 'yyyy-mm-dd',
					setDefaultDate: true
				});
				$('.timepicker').timepicker({
					defaultTime: 'now'
				});
				$('input.autocomplete').autocomplete({
					data: commands
				});

				$('.closePane').on('click', function(e){
					e.preventDefault();
					e.currentTarget.parentElement.remove();
				});
			});

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

			$('input.autocomplete').autocomplete({
				data: commands
			});
		});
	</script>
@endpush