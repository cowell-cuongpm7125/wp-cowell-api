<html>
<header>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</header>
<body>
<div style="padding-top: 100px">
    <div class="container">
        <div class="alert <?php echo $success ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <strong>
                <?php
                    echo $message;
                ?>
            </strong>
        </div>
    </div>
</div>

</body>
</html>
