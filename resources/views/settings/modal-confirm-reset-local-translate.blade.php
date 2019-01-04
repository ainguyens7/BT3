<div class="modal ali-modal ali-modal--noline fade" id="modalResetLocalTranslate" tabindex="-1" role="dialog" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form action="" id="formResetLocalTranslate">
				<div class="modal-body text-center pad-0">
					<div class="modal-body__content modal-pad-30">
						<p class="note fw600 mar-b-30">Are you sure you want to reset to default settings? </p>
					</div>
				</div>
				<div class="modal-footer text-center">
					<button type="submit" class="button button--primary w-130px mar-r-5">Reset</button>
					<button type="button" class="button button--default w-130px mar-l-5" data-dismiss="modal">Cancel</button>
				</div>
				{!! csrf_field() !!}
			</form>
		</div>
	</div>
</div>