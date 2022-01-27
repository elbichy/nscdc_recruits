@extends('layouts.app', ['title' => 'App Settings' ])

@section('content')
    <div class="my-content-wrapper">
        <div class="content-container white">
            <div class="sectionWrap z-depth-0">

				<!-- Modal Structure Editting Privilages -->
				<div id="modal" class="modal privilageModal">
					<form action="" method="POST" name="create_form" id="edit_privilage_form">
						<div class="modal-content" style="padding: 24px 9px 0px;">
							@csrf
							@method('PUT')
							<div class="formWrap">
								<fieldset id="form" class="row" style="margin-bottom: 0px; padding: 0px; border: none;">
									{{-- SERVICE NUMBER --}}
									<div class="input-field col s12 l4">
										<input id="svc_no" name="svc_no" type="number" value="{{ old('svc_no') }}" class="fillable" placeholder="Service number" required>
										@if ($errors->has('svc_no'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('svc_no') }}</strong>
											</span>
										@endif
										<label for="svc_no">Service number</label>
									</div>
									{{-- NAME --}}
									<div class="input-field col s12 l4">
										<input id="name" name="name" type="text" value="{{ old('name') }}" class="fillable" placeholder="Name" required>
										@if ($errors->has('name'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
										<label for="name">Name</label>
									</div>
									{{-- ROLE --}}
									<div class="col s12 l3">
										<label for="role">Select Privilage</label>
										<select id="role" name="role" class="browser-default" required>
											<option disabled selected>Select privilage</option>
											<option value="global_admin">Global Admin</option>
											<option value="state_admin">State Admin</option>
											<option value="basic">Basic User</option>
										</select>
										@if ($errors->has('role'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('role') }}</strong>
											</span>
										@endif
									</div>
								</fieldset>
							</div>
						</div>
						<div class="modal-footer" style="padding: 0px 10px 0 0; width: 99%;">
							<a href="#!" id="modal-close" class="modal-close waves-effect waves-green btn-flat">Close</a>
							<button class="submit btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>UPDATE RECORD</button>
						</div>
					</form>
				</div>

                <div class="sectionProfileWrap z-depth-0" style="margin-top:18px; padding:0;">
					<h5 style="padding:8px; width:100%; margin:0 0 20px 0; border-bottom: 2px dotted #ccc; text-align:center; font-weight:bold;">
						APPLICATION SETTINGS
					</h5> 

					{{-- ROLES MANAGEMENT --}}
					<div class="fieldset" style="margin-top:40px; border: 2px solid #ccc;">
						<legend><p style="    border: 2px solid #ccc;">ROLES MANAGEMENT</p></legend>
						<div class="table_form_wrapper">

							<table class="striped responsive-table centered highlight">
								<thead>
									<tr>
										<th>ID</th>
										<th>Name</th>
										<th>Permissions</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@foreach($roles as $role)
										<tr>
											<td>{{ $role->id }}</td>
											<td>{{ str_replace('-', ' ', strtoupper($role->name)) }}</td>
											<td>
												@foreach($role->permissions as $key => $permission)
													@if ($key >= 2)
														<div class="chip blue text-white">
															<a href="#">view more</a>
															@break
														</div>
													@else
														<div class="chip">
															{{ $permission->name }}
														</div>
													@endif
												@endforeach
											</td>
											<td>
												<button data-role_id="{{ $role->id }}"  data-role_name="{{ ucwords($role->name) }}" class="edit_privilage" title="Edit role" style="background: transparent; border: none; cursor: pointer; margin-right: 10px;">
													<i class="blue-text fas fa-edit"></i>
												</button>
											</td>
										</tr>
									@endforeach
								</tbody>
								
							</table>

							<form action="{{ route('app_settings_add_role') }}" method="POST" class="card add_record_form" id="nok_form">
								@csrf
								<div class="row">
									{{-- NAME --}}
									<div class="input-field col s12 l3">
										<input id="name" name="name" type="text" value="{{ old('name') }}" class="fillable" required>
										@if ($errors->has('name'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
										<label for="name">Name</label>
									</div>
									{{-- Permissions --}}
									<div class="col s12 l6">
										<!-- Customizable input  -->
										<div id="chip1" class="chips chips-placeholder" style="border-bottom: 1px solid #ffffff;">
											<input id="myInputField" class="custom-class" style="color: #fff;">
										</div>
									</div>
									<input type="hidden" name="permissions", id="permissions">
									
									{{-- BUTTON --}}
									<div class="input-field col s12 l3 right">
										<button class="submit_privilage btn waves-effect waves-light right" type="submit">
											<i class="material-icons right">send</i>ADD ROLE
										</button>
									</div>
								</div>
							</form>
							
						</div>
					</div>

					{{-- USERS PRIVILAGE SETTINGS --}}
					<div class="fieldset" style="margin-top:40px; border: 2px solid #ccc;">
						<legend><p style="    border: 2px solid #ccc;">USERS ROLES & PERMISSIONS</p></legend>
						<div class="table_form_wrapper">

							<table class="striped responsive-table centered highlight">
								<thead>
									<tr>
										<th>Name</th>
										<th>Svc No.</th>
										<th>Formation</th>
										<th>Privilage</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@foreach($users as $user)
										<tr>
											<td>{{ ucwords($user->name) }}</td>
											<td>{{ $user->service_number }}</td>
											<td>{{ ucwords($user->current_formation) }}</td>
											<td>{{ $user->role }}</td>
											<td>
												<button data-user_id="{{ $user->id }}" data-user_svc_no="{{ $user->service_number }}" data-user_name="{{ ucwords($user->name) }}" data-user_role="{{ $user->role }}" class="edit_privilage" title="Edit privilage" style="background: transparent; border: none; cursor: pointer; margin-right: 10px;">
													<i class="blue-text fas fa-edit"></i>
												</button>
											</td>
										</tr>
									@endforeach
								</tbody>
								
							</table>

							<form action="{{ route('app_settings_assign_privilage') }}" method="POST" class="card add_record_form" id="role_assignment">
								@csrf
								<div class="row">
									{{-- SERVICE NUMBER --}}
									<div class="input-field col s12 l2">
										<input id="svc_no" name="svc_no" type="number" value="{{ old('svc_no') }}" class="fillable" required>
										@if ($errors->has('svc_no'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('svc_no') }}</strong>
											</span>
										@endif
										<label for="svc_no">Service number</label>
									</div>
									{{-- NAME --}}
									<div class="input-field col s12 l4">
										<input id="name" name="name" type="text" value="{{ old('name') }}" class="fillable" readonly>
										@if ($errors->has('name'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
										<label for="name">Fullname</label>
									</div>
									{{-- FORMATION --}}
									<div class="input-field col s12 l3">
										<input id="formation" name="formation" type="text" value="{{ old('formation') }}" class="fillable" readonly>
										@if ($errors->has('formation'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('formation') }}</strong>
											</span>
										@endif
										<label for="formation">Formation</label>
									</div>
									{{-- ROLE --}}
									<div class="col s12 l3">
										<label for="role">Select Role</label>
										<select id="role" name="role" class="browser-default" required>
											<option disabled selected>Select Role</option>
											@foreach($roles as $role)
											<option value="{{ $role->name }}">{{ str_replace('-', ' ', strtoupper($role->name)) }}</option>
											@endforeach
										</select>
										@if ($errors->has('role'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('role') }}</strong>
											</span>
										@endif
									</div>
									{{-- PERMISSIONS --}}
									<div id="permissionWrapper" class="col s12" style="display: flex; justify-content:space-evenly; align-items: center;">
									</div>
									{{-- BUTTON --}}
									<div class="input-field col s12 l3 right">
										<button class="submit_privilage btn waves-effect waves-light right" type="submit">
											<i class="material-icons right">send</i>ASSIGN PRIVILAGE
										</button>
									</div>
								</div>
							</form>
							
						</div>
					</div>

                </div>
            </div>
        </div>
        <div class="footer z-depth-1">
            <p>&copy; Nigeria Security & Civil Defence Corps</p>
        </div>
    </div>
@endsection

@push('scripts')
	@if ($errors->any())
    <script>
        $(function() {
            $('.changePassModal').modal('open');
        });
    </script>
	@endif
	
    <script>
        $(function() {

			$('.dofa_datepicker').datepicker({
				container: 'body',
				format: 'yyyy-mm-dd',
				yearRange: [2004, (new Date).getFullYear()]
			});

			$('.chips-placeholder').chips({
				placeholder: 'Type & hit \'Enter\'',
				secondaryPlaceholder: 'add another',
				onChipAdd: chips2Input,
				onChipDelete: chips2Input,
				Limit: 10,
				minLength: 1
			});

			// DO THE CHIPS THING
			function chips2Input(){
				var instance = M.Chips.getInstance(document.getElementById('chip1')), inpt = document.getElementById('myInputField');
				inpt.value =  null;
				for(var i=0; i<instance.chipsData.length; i++){
					if(inpt.value == null)
						inpt.value = `${instance.chipsData[i].tag},`;
					else{
						inpt.value += `${instance.chipsData[i].tag},`; //csv
					}
				}
				var hidden = document.getElementById('permissions');
				hidden.value = inpt.value;
				
			}

			// LOAD PERMISSIONS ON ROLE SELECTION
            $(document).on('change', '#role', function(event) {
                let selected_option = event.currentTarget.value;
                if (selected_option != '') {
                    axios.post(`{!! route('permissions_get_from_role') !!}`, { role: selected_option })
                        .then(function(response) {
                            if(response.status == 200){
                                let result = response.data;
                                let checkboxes = '';
								// console.log(result);
                                result.forEach(function(option){
                                    checkboxes+=`
										<p>
											<label>
											  <input type="checkbox" name="permissions[]" value="${option.name}" class="filled-in" checked="checked" />
											  <span>${option.name}</span>
											</label>
										</p>
									`;
                                });
                                $('#permissionWrapper').html(checkboxes);
                                // $('#new_gl').html(options);
                            }
                        });
                } else {
                    alert('You must select at least one personnel!');
                }
            });

			//ROLE UPDATE 
			$(document).on('click', '.edit_privilage', function(event) {
				event.preventDefault();
				
				let role = this.dataset.user_role == '' ? 'basic' : this.dataset.user_role;

				$('#edit_privilage_form').prop('action', `/administration/dashboard/settings/privilage/${this.dataset.user_id}/update`);
				$('#svc_no').prop('value', `${this.dataset.user_svc_no}`);
				$('#name').prop('value', `${this.dataset.user_name}`);
				$('#role option[value='+role+']').prop('selected', true);
				$('.privilageModal').modal('open');
            });

			$('#role_assignment').submit(function(){
				$('.submit_privilage').html(`Assigning role...`);
			});

			
        });
    </script>
@endpush