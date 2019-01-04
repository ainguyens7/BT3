<div id="modalDeleteCommentDefault" class="modal fade ali-modal ali-modal--noline" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-body text-center">
				<div class="modal-default-review-wrap">
					<form action="" id="formDeleteCommentDefault">
						<div class="delete-reviews-wrap">
							<p class="fw600 mar-b-35">Are you sure you want to delete this review?</p>
							<div class="delete-reviews-btn-wrap">
								<button type="submit" class="button button--primary mar-r-5 w-130px ars-btn">Delete</button>
								<button type="button" class="button button--default mar-l-5 w-130px ars-btn-o" data-dismiss="modal">Cancel</button>
							</div>
						</div>
						<input type="hidden" value="0" name="id">
						<input type="hidden" value="{{ csrf_token() }}" name="_token">
					</form>
				</div>
			</div>
		</div>
	</div>
</div>