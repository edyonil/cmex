{{ $openForm }}
@if (isset($status['success']))
<div class="alert alert-success span6">{{ $status['success'] }}</div>
@endif

@if (isset($status['error']))
<div class="alert alert-error span6">{{ $status['error'] }}</div>
@endif

<div class="control-group">
	<label for="sendername" class="control-label">Ihr Name:</label>
	<div class="controls">
		<input type="text" class="span6" name="sendername" />
	</div>
</div>

<div class="control-group">
	<label for="sender" class="control-label">Ihre E-Mail-Adresse:</label>
	<div class="controls">
		<input type="text" class="span6" name="sender" />
	</div>
</div>

<div class="control-group">
	<label for="subject" class="control-label">Betreff:</label>
	<div class="controls">
		<input type="text" class="span6" name="subject" />
	</div>
</div>

<div class="control-group">
	<label for="mailtext" class="control-label">Ihr Name:</label>
	<div class="controls">
		<textarea rows="10" class="span6" name="mailtext"></textarea>
	</div>
</div>

<div class="span6 form-actions">
	<button type="submit" class="btn btn-primary">Absenden</button>
	<button type="reset" class="btn">Zurücksetzen</button>
</div>

</form>