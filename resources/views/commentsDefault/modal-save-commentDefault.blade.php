<div id="modalSaveCommentDefault" class="modal fade ali-modal ali-modal--noline" role="dialog">
	<div class="modal-dialog modal-md">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="demo-icon icon-cancel-4"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="modal-default-review-wrap">
					<form action="" id="formSaveCommentDefault">
						<h2>Create Default Reviews</h2>
						<div class="ars-field">
							<label for="author" class="ars-field-title">Name</label>
							<input type="text" name="author" id="author" >
						</div>
						<div class="ars-field">
							<label for="" class="ars-field-title">Rating</label>
							<div class="rate-it">
								<a href="javascript:void(0)" data-star="1" class="btn-choice-start-1 btn-choice-start">
									<i class="demo-icon icon-star-2"></i>
								</a>
								<a href="javascript:void(0)" data-star="2" class="btn-choice-start-2 btn-choice-start">
									<i class="demo-icon icon-star-2"></i>
								</a>
								<a href="javascript:void(0)" data-star="3" class="btn-choice-start-3 btn-choice-start">
									<i class="demo-icon icon-star-2"></i>
								</a>
								<a href="javascript:void(0)" data-star="4" class="btn-choice-start-4 btn-choice-start">
									<i class="demo-icon icon-star-2"></i>
								</a>
								<a href="javascript:void(0)" data-star="5" class="btn-choice-start-5 btn-choice-start">
									<i class="demo-icon icon-star-2"></i>
								</a>
							</div>
							<input type="hidden" name="star" value="5">
						</div>
						<div class="ars-field">
							<label for="content" class="ars-field-title">Feedback</label>
							<textarea name="content" id="content"></textarea>
							<span></span>
						</div>

						<div class="ars-field">
							<label for="country" class="ars-field-title">Country</label>
							<div class="box-select-country">
								<select name="country" class="select-multi" >
									<option value="">--- Select country ---</option>
									@if(!empty($allCountryCode))
										@foreach($allCountryCode as $k => $v)
											<option value="{{  $k }}">{{ $v }}</option>
										@endForeach
									@endIf
								</select>
							</div>
							<label id="country-error" class="error" for="country"></label>
						</div>

						<input type="hidden" name="id" value="0">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="modal-default-review-btn-wrap">
							<button class="ars-btn">SAVE</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>