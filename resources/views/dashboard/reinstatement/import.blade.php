@extends('administration.layouts.app', ['title' => 'Add New Records'])

@section('content')
    <div class="my-content-wrapper">
        <div class="content-container">
			<div class="sectionWrap">
				{{-- SECTION HEADING --}}
				<h6 class="center sectionHeading">IMPORT RECORDS</h6>
				<div class="sectionFormWrap z-depth-1" style="padding:24px;">
					<div class="importRecords">
						<div class="progress importProgress" style="margin:0; display: none;">
							<div class="indeterminate"></div>
						</div>
						<div class="card green darken-1"  style="margin-top:0;">
							<div class="card-content">
								<h5 class="card-title white-text">Import reinstatement records from excel</h5>
								<form style="margin-top: 15px;padding: 10px;" action="{{ route('store_imported_reinstatement') }}" method="post" enctype="multipart/form-data" id="importData" class="row">
									@csrf
									<input type="file" name="import_file" class="left"/>
									<button class="importBtn btn waves-effect waves-light green darken-2 right">Import File</button>
								</form>
							</div>
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
	<script>
		$(document).ready(function(){

			$('#importData').submit(function () {
				$('.importProgress').css('display', 'block');
				$('.importBtn').html('Importing...');
			});

		});
	</script>
@endpush