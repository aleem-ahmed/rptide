<form id="data-form">
	<div class="form-wrapper">
		<!-- Ticker & Title (Stock Notes) -->
		<div class="form-row">
			<div class="control-wrapper">
				<label for="stock-notes">Ticker & Title</label>
				<input type="text" id="note-ticker" name="note-ticker" maxlength="8" style="width: 75px; margin-right: 10px; text-transform:uppercase" >
				<input type="text" id="note-title" name="note-title" placeholder="Title" maxlength="80" style="width: 355px;">
			</div>
		</div>

		<!-- Text Area (Stock Notes) -->
		<div class="form-row">
			<div class="control-wrapper">
				<label for="stock-notes">Stock Notes</label>
				<textarea id="note-content" rows="10" cols="60" name="note-content" style="resize: none; width: 100%;" ></textarea>
			</div>
		</div>

		<!-- Submit Controls -->
		<div class="form-row">
			<div class="control-wrapper">
				<div><button id="data-save-button" class="btn data-save-button" type="submit" role="button">Save</button></div>
				
				<div>
					<button id="data-cancel-button" class="btn btn-plain data-cancel-button" role="button">Cancel</button>
					<span id="result" class="result-text"></span>
				</div>
			</div>
			<div class="control-wrapper">
				<div>
					<button id="data-delete-button" class="btn data-delete-button" type="button" role="button">Delete this Note</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Error and Messages -->
	<span id="form-error" class="error error-message form-error-message"></span>
	<span id="form-message" class="form-message"></span>
	
	<!-- Hidden Inputs sent to the Javascript Handlers -->
	<input type="hidden" id="log-id" name="log-id" value="<?php echo $_SESSION['log_id']; ?>">
	<input type="hidden" id="total-notes" name="total-notes" value="<?php echo $_SESSION['total_notes']; ?>">
	<input type="hidden" id="activity-id" name="activity-id">
	<input type="hidden" id="data-verb" name="data-verb">
	<input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">
</form>