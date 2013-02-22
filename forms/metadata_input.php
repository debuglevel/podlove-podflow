<p>
    Thank you for the episode you uploaded!
</p>

<form action="edit.php?post_type=podcast&page=<?php echo \Podlove\Modules\Podflow\Podflow::menu_slug; ?>" method="POST">
    <input name="execution_id" type="hidden" value="<?php echo $form_vars['execution_id']; ?>" />

    <p>
        Now, please tell me what's the <strong>title</strong> of this episode:
        <textarea rows="1" class="large-text" name="title"><?php echo $form_vars['title_guess']; ?></textarea>
    </p>

    <p>
        If you want to, you can also add a short <strong>subtitle</strong>:
        <textarea rows="1" class="large-text" name="subtitle"></textarea>
    </p>

    <p>
        And if you want to write a <strong>summary</strong> what this episode is about, I've got some space for you:
        <textarea rows="5" class="large-text" name="summary"></textarea>
    </p>

    <p>
        That's it!
    </p>

    <p>
        <input type="submit" name="metadata" value="And now, publish this episode!" class="button-primary" />
    </p>

</form>
