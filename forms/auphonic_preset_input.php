<form action="edit.php?post_type=podcast&page=<?php echo \Podlove\Modules\Podflow\Podflow::menu_slug_new; ?>" method="POST">
    <input name="execution_id" type="hidden" value="<?php echo $form_vars['execution_id']; ?>" />

    <p>
        Looking in your face, I think you know about the Auphonic presets, don't you? 
    </p>
    <p>
        So please tell me which <strong>preset</strong> I should use:
    </p>
    <p>
        <select name="preset_uuid">
            <?php
            foreach ($form_vars['presets'] as $preset)
            {
                printf('<option value="%s">%s</option>', $preset['uuid'],
                        $preset['name']);
            }
            ?>
        </select>
    </p>

    <p>

        <input type="hidden" name="preset" value="preset" />
        <!-- <input type="submit" name="preset" value=""  />
        <input type="submit" name="preset" value="" /> -->
        <button name="publish_state" value="publish" type="submit" class="button-primary">And now, publish this episode!</button>
        <button name="publish_state" value="draft" type="submit">Just save it as a draft.</button>
    </p>

</form>
