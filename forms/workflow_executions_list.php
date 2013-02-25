<p>Hello!</p>
<p>Do you want to have a look on the work I've done for you so far? Okay, but be warned... my notes are kind of verbose...</p>
<p>Here you are:</p>

<table>
    <thead>
        <tr>
            <th>
                ID
            </th>
            <th>
                Workflow
            </th>
            <th>
                State
            </th>
            <th>
                Start date
            </th>
            <th>
                Suspend date
            </th>
            <th>
                End date
            </th>
            <th>
                Waiting for...
            </th>
            <th>
                Variables set
            </th>
            <th>
                var_dump
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($form_vars['workflow_executions'] as $workflow_execution) { ?>
        <tr>
            <td>
                <?php echo $workflow_execution['id']; ?>
            </td>
            <td>
                <?php echo $workflow_execution['name']; ?>
            </td>
            <td>
                <?php echo $workflow_execution['state']; ?>
            </td>
            <td>
                Start date
            </td>
            <td>
                Suspend date
            </td>
            <td>
                End date
            </td>
            <td>
                <?php echo '<pre>'.($workflow_execution['waitingfor']).'</pre>'; ?>
            </td>
            <td>
                <?php echo '<pre>'.($workflow_execution['variables']).'</pre>'; ?>
            </td>
            <td>
                <?php  ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
