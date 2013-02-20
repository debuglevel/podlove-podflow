<p>
    Thank you! Your file was uploaded.
</p>

<form action="edit.php?post_type=podcast&page=podlove_podflow_settings_handle" method="POST">
    <input name="execution_id" type="hidden" value="<?php echo $execution_id; ?>" />

    <p>
        Now, please tell me what's the title of this episode:
        <textarea rows="1" class="large-text" name="title"></textarea>
    </p>

    <p>
        If you want to, you can also add a short subtitle:
        <textarea rows="1" class="large-text" name="subtitle"></textarea>
    </p>

    <p>
        And if you want to describe what this episode is about, I've got some space for you:
        <textarea rows="1" class="large-text" name="summary"></textarea>
    </p>

    <p>
        That's it!
        <input type="submit" name="metadata" value="And now, publish this episode!" />
    </p>

</form>
