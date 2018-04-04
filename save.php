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
    $video_list = NULL;

    if (isset($array['track']['video'][1]['file'])) {
        $video_list = $array['track']['video'][1]['file'];
    }

    if (($video_list == NULL) && isset($array['track']['video']['file'])) {
        $video_list = $array['track']['video']['file'];
    }

    if (($video_list == NULL) && isset($array['track']['video'])) {

        foreach($array['track']['video'] as $item) {

            if (strpos($item, 'rtmp') !== false) {

                if ($video_list == NULL) {
                    $video_list = array();
                }

                array_push($video_list, $item);
            }
        }
    }

    if (!check($video_id)) {
        if (is_array($video_list)) {
            foreach($video_list as $video) {
                create($video_id, $image_url, $title, $video);
            }
        } else if (is_string($video_list)) {
            create($video_id, $image_url, $title, $video_list);
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

        $url_type   = 0;
        $preview    = file_get_contents($screen_url);
        $processed  = 0;
        $filesize   = 0;
        $status     = 1;
        $filename   = str_replace('.', '', uniqid('', true));

        if (strpos($url, 'rtmp') !== false) {
            $url_type = 1;
        }

        $postman = Postman::init();
        $postman->execute("
                INSERT INTO
                    `playlist`
                    (`video_id`, `title`, `preview`, `url`, `url_type`, `processed`, `filename`, `filesize`, `created_date_time`, `status`)
                VALUES
                    ( ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)
            ",
            array('isssisii', &$video_id, &$title, &$preview, &$url, &$url_type, &$processed, &$filename, &$filesize, &$status)
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
