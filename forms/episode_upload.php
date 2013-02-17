<p>
	Hello!
</p>
<p>
	I am Poddy, your personal assistant for publishing new podcast episodes.
</p>
<p>
	Okay, let's start. First of all, I need the episode you recorded.
</p>

<form enctype="multipart/form-data" action="edit.php?post_type=podcast&page=podlove_podflow_settings_handle" method="POST">
	<input name="execution_id" type="hidden" value="<?php echo $execution_id; ?>" />
	<input name="episodefile" type="file" />
	<input type="submit" value="Okay, let's go!" />
</form>
