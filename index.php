<?php

    require_once('Postman.php');

    $postman = Postman::init();

    $status = 1;

    $list = $postman->returnDataList('
            SELECT
                `title`,`preview`,`url`,`processed`
            FROM
                `playlist`
            WHERE
                `status`=?
            ORDER BY
                `idx` DESC
        ', array('i', &$status));
?><!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Download</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    </head>
    <body class="bg-light">
        <div class="container">
            <div class="py-5 text-center">
                <h2>AfreecaTV Download</h2>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="mb-3">Download</h4>
                    <form class="needs-validation" method="POST" action="save.php" novalidate>
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="content">Video XML</label>
                                <textarea class="form-control" id="content" name="content" placeholder="" value="" required rows="6"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-10"></div>
                            <div class="col-lg-2">
                                <br />
                                <button class="btn btn-primary btn-lg btn-block" type="submit">Request Video</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <hr class="mb-4"></hr>
                    <h4 class="mb-3">Video List</h4>
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text-center">Title</th>
                                        <th style="width: 100px;" class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($list as $item) { ?>
                                        <tr>
                                            <td class="text-center" style="background-image: url(data:image/jpeg;base64,<?php echo base64_encode($item->preview); ?>); background-position: center center; background-repeat: no-repeat; background-size: cover; width: 160px; height: 160px;"></td>
                                            <td class="align-middle"><?php echo $item->title; ?></td>
                                            <td class="align-middle text-center">
                                                <?php if ($item->processed == 0) { ?>
                                                    대기중
                                                <?php } else if ($item->processed == 1) { ?>
                                                    다운로드중
                                                <?php } else { ?>
                                                    <a href="#">
                                                        <div class="btn btn-success">
                                                            Download
                                                        </div>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="my-5 pt-5 text-muted text-center text-small">
                <p class="mb-1">© 2017-2018 Company Name</p>
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="#">Privacy</a></li>
                    <li class="list-inline-item"><a href="#">Terms</a></li>
                    <li class="list-inline-item"><a href="#">Support</a></li>
                </ul>
            </footer>
        </div>
    </body>
</html>
