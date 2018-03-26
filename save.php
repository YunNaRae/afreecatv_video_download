<?php

    if (!isset($_POST['content'])) {
        header('Location: index.php');
        return;
    }

    require_once('Postman.php');

    $xml = simplexml_load_string($_POST['content'], "SimpleXMLElement", LIBXML_NOCDATA);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $link = explode('/', $array['share']['link']);
    $video_id = $link[(count($link) - 1)];
    $title = $array['track']['title'];
    $image_url = $array['track']['titleImage'];
    $video_list = $array['track']['video'][1]['file'];


    if (!check($video_id)) {
        foreach($video_list as $video) {
            create($video_id, $image_url, $title, $video);
        }
    }

    function check($video_id) {

        $status = 1;

        $postman = Postman::init();
        $list = $postman->returnDataList("
                SELECT
                    `idx`
                FROM
                    `playlist`
                WHERE
                    `video_id`=?
                AND
                    `status`=?
            ",
            array('ii', &$video_id, &$status)
        );

        if (count($list) > 0)  {
            return true;
        }

        return false;
    }

    function create($video_id, $screen_url, $title, $url) {

        $preview    = file_get_contents($screen_url);
        $processed  = 0;
        $filesize   = 0;
        $status     = 1;
        $filename   = str_replace('.', '', uniqid('', true));
        $postman = Postman::init();
        $postman->execute("
                INSERT INTO
                    `playlist`
                    (`video_id`, `title`, `preview`, `url`, `processed`, `filename`, `filesize`, `created_date_time`, `status`)
                VALUES
                    ( ?, ?, ?, ?, ?, ?, NOW(), ?)
            ",
            array('isssisi', &$video_id, &$title, &$preview, &$url, &$processed, &$filename, &$filesize, &$status)
        );
    }

?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
        <meta http-equiv="refresh" content="1;url=/index.php">

		<title></title>

		<script type="text/javascript">
			// alert("등록 되었습니다.");
            window.location.href = "/index.php";
        </script>
	</head>
	<body></body>
</html>
