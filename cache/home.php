<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo htmlentities(config('name')) ?></title>
    </head>
    <body>
        <h1><?php echo htmlentities(config('name')) ?></h1>
        <ul>
            <?php foreach ($todos as $todo): ?>
            <li>
                <a href="<?php echo htmlentities(url('/todo/{:id}', ['id' => $todo->id])) ?>"><?php echo $todo->name ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </body>
</html>
