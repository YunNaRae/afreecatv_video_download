<?php

    require_once('Postman.php');

    $postman = Postman::init();

    $processed  = 0;
    $status     = 1;

    $list = $postman->returnDataList('
            SELECT
                `idx`,`url`,`filename`
            FROM
                `playlist`
            WHERE
                `processed`=?
            AND
                `status`=?
            ORDER BY
                `idx` ASC
            LIMIT 1
        ', array('ii', &$processed, &$status));

    foreach($list as $item) {

        $processed  = 1;
        $idx        = $item->idx;
        $url        = $item->url;
        $filename   = $item->filename;

        $postman->execute('
                UPDATE
                    `playlist`
                SET
                    `processed`=?
                WHERE
                    `idx`=?
            ', array('ii', &$processed, &$idx));

        exec("/usr/bin/ffmpeg -i $url -c copy -bsf:a aac_adtstoasc /var/www/$filename.mp4 ");

        $processed  = 2;

        $postman->execute('
                UPDATE
                    `playlist`
                SET
                    `processed`=?
                WHERE
                    `idx`=?
            ', array('ii', &$processed, &$idx));
    }
