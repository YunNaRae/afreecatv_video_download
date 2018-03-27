<?php

    require_once('/var/www/philgookang/afreecatv/Postman.php');

    // 데이터 베이스 연결!
    $postman = Postman::init();

    $processed  = 2;
    $filesize   = 0;
    $status     = 1;

    // 다운로드 받을 파일 목록
    $list = $postman->returnDataList('
            SELECT
                `idx`,`filename`
            FROM
                `playlist`
            WHERE
                `processed`=?
            AND
                `filesize`=?
            AND
                `status`=?
            ORDER BY
                `idx` ASC
            LIMIT 1
        ', array('iii', &$processed, &$filesize, &$status));

    foreach($list as $item) {

        $idx        = $item->idx;
        $filename   = $item->filename;

        // 이제 파일 사이즈 찾자
        $size = filesize("/mnt/wwwroot/afreecatv/$filename.mp4");

        // 이제 사이즈 업데이터 하자
        // 왜 두개 퀴리로 처리하냐?
        // 그냥~ 내 마음~
        $postman->execute('
                UPDATE
                    `playlist`
                SET
                    `filesize`=?
                WHERE
                    `idx`=?
            ', array('ii', &$size, &$idx));
    }
