<div class="modal fade" id="check-{{ $action }}" tabindex="-1" role="dialog" aria-labelledby="check-admin-lbl" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="check-admin-lbl">
					@if($action === 'adminer')
						{{'Must input password to enter Adminer'}}
					@elseif($action === 'register')
						{{'Must input password to register user'}}
					@elseif($action === 'programmer')
						{{'Must input password to programmer points'}}
					@endif
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="/check-admin" method="POST" id="form-{{ $action }}-pass" autocomplete="off">
					<label for="{{ $action }}-password" class="col-form-label">Enter the password</label>
					<input id="{{ $action }}-password" class="form-control" type="password" name="password" autocomplete="off" />
					<input type="hidden" name="useof" value="{{ $action }}" />
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary btn-sm" form="form-{{ $action }}-pass">&nbsp;&nbsp;&nbsp;Submit&nbsp;&nbsp;&nbsp;</button>
			</div>
		</div>
	</div>
</div>
