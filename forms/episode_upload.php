<div style="width: 229px; height: 310px; float: left; background-image: url(<?php echo \Podlove\PLUGIN_URL ?>/lib/modules/podflow/images/mascot.png)"></div>
<p>
    Hello!
</p>
<p>
    I am Poddy, your personal assistant for publishing new podcast episodes.
</p>
<p>
    Okay, let's start: First of all, I need the episode you recorded.
</p>

<form enctype="multipart/form-data" action="edit.php?post_type=podcast&page=<?php echo \Podlove\Modules\Podflow\Podflow::menu_slug_new; ?>" method="POST">
    <p>
        <input name="execution_id" type="hidden" value="<?php echo $form_vars['execution_id']; ?>" />
        <input name="episodefile" type="file" />
    </p>
    <p>
        <input type="submit" value="Okay, let's go!" class="button-primary" />
    </p>
</form>
