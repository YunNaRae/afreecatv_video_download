<?php

    if (!isset($_POST['content'])) {
        header('Location: index.php');
        return;
    }

    require_once('Postman.php');

    $xml = simplexml_load_string($_POST['content'], "SimpleXMLElement", LIBXML_NOCDATA);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $title = $array['track']['title'];
    $image_url = $array['track']['titleImage'];
    $video_list = $array['track']['video'][1]['file'];

    foreach($video_list as $video) {
        create($image_url, $title, $video);
    }

    function create($screen_url, $title, $url) {
        $preview    = file_get_contents($screen_url);
        $processed  = 0;
        $status     = 1;
        $filename   = str_replace('.', '', uniqid('', true));
        $postman = Postman::init();
        $postman->execute("
                INSERT INTO
                    `playlist`
                    (`title`, `preview`, `url`, `processed`, `filename`, `created_date_time`, `status`)
                VALUES
                    ( ?, ?, ?, ?, ?, NOW(), ?)
            ",
            array('sssisi', &$title, &$preview, &$url, &$processed, &$filename, &$status)
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
