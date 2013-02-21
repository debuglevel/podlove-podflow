<form action="edit.php?post_type=podcast&page=<?php echo \Podlove\Modules\Podflow\Podflow::menu_slug; ?>" method="POST">
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
        <input type="submit" name="preset" value="And now, publish this episode!" class="button-primary" />
    </p>

</form>
