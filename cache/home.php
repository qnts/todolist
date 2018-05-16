<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo htmlentities(config('name')) ?></title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo htmlentities(url('/css/app.css')) ?>">
    </head>
    <body>
        <div class="container">
            <h1><?php echo htmlentities(config('name')) ?></h1>
            <div class="panel">
                <a class="btn" href="<?php echo htmlentities(url('/todo/create')) ?>">Add new</a>
            </div>
            <div id="calendar" data-edit="<?php echo htmlentities(url('/todo/__id__/edit')) ?>" data-delete="<?php echo htmlentities(url('/todo/__id__/')) ?>"></div>
        </div>
        <script type="text/template" id="data">
            <?php echo json_encode($todos) ?>
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" charset="utf-8"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js" charset="utf-8"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js" charset="utf-8"></script>
        <script src="<?php echo htmlentities(url('/js/app.js')) ?>" charset="utf-8"></script>
    </body>
</html>
