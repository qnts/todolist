<?php if (!isset($test)): ?>
    $test is not defined
<?php endif; ?>

<?php foreach ($todos as $todo): ?>
    <p><?php echo htmlentities($todo->name) ?></p>
<?php endforeach; ?>
