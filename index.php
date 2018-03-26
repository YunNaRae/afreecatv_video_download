<?php

    require_once('Postman.php');

    $postman = Postman::init();

    $status = 1;

    $list = $postman->returnDataList('
            SELECT
                `title`,`preview`,`filename`,`processed`,`video_id`,`filesize`
            FROM
                `playlist`
            WHERE
                `status`=?
            ORDER BY
                `idx` DESC
        ', array('i', &$status));

    $complete_list = array();
    $video_id = $list[0]->video_id;
    $tmp = array();
    foreach($list as $item) {
        if ($item->video_id != $video_id) {
            $video_id = $item->video_id;
            array_push($complete_list, $tmp);
            $tmp = array();
        }
        array_push($tmp, $item);
    }
    if (count($tmp) > 0) {
        array_push($complete_list, $tmp);
    }

    function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
?><!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Download</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

        <style>
            .prev {
                background-position: center center; background-repeat: no-repeat; background-size: cover; width: 160px; height: 160px;
            }
        </style>

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
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text-center">Title</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($complete_list as $list) { $item = $list[0]; ?>
                                        <tr>
                                            <td rowspan="2" class="text-center" style="width: 160px; padding: 0px; margin: 0px; font-size: 0px;">
                                                <div class="prev" style="background-image: url(data:image/jpeg;base64,<?php echo base64_encode($item->preview); ?>);"></div>
                                            </td>
                                            <td class="align-middle"><?php echo $item->title; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle">
                                                <ol>
                                                    <?php foreach($list as $item) { ?>
                                                        <li>
                                                            <?php if ($item->processed == 0) { ?>
                                                                대기중
                                                            <?php } else if ($item->processed == 1) { ?>
                                                                다운로드중
                                                            <?php } else { ?>
                                                                <a href="http://afreecatv.cdn.philgookang.com/afreecatv/<?php echo $item->filename; ?>.mp4">
                                                                    다운로드 (<?php echo formatBytes($item->filesize); ?>)
                                                                </a>
                                                            <?php } ?>
                                                        </li>
                                                    <?php } ?>
                                                </ol>
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
