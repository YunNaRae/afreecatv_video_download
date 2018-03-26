<?php

    require_once('/var/www/philgookang/afreecatv/Postman.php');

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

        exec("/usr/bin/ffmpeg -y -i $url -c copy -bsf:a aac_adtstoasc /mnt/wwwroot/afreecatv/$filename.mp4 ");

        $processed  = 2;

        $size = filesize("/mnt/wwwroot/afreecatv/$filename.mp4");

        $postman->execute('
                UPDATE
                    `playlist`
                SET
                    `processed`=?,
                    `filesize`=?
                WHERE
                    `idx`=?
            ', array('iii', &$processed, &$size, &$idx));
    }
