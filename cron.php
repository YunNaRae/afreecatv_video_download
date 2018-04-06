<?php

    require_once('/var/www/philgookang/afreecatv/Postman.php');

    // 데이터 베이스 연결!
    $postman = Postman::init();

    $processed  = 0;
    $status     = 1;

    // 다운로드 받을 파일 목록
    $list = $postman->returnDataList('
            SELECT
                `idx`,`url`,`url_type`,`filename`
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

        // 상태 바꾸기
        $postman->execute('
                UPDATE
                    `playlist`
                SET
                    `processed`=?
                WHERE
                    `idx`=?
            ', array('ii', &$processed, &$idx));

        // 다운로드 시간이 길어서
        // 우선 연결 끊고
        // 다시 연결 하자!
        $postman->close();

        // ---------------------

        // check if its normal file streaming type
        if ($item->url_type == 0) {

            // ffmpeg 에서 아프리카 영상 다운로드하기
            exec("/usr/bin/ffmpeg -y -i $url -c copy -bsf:a aac_adtstoasc /mnt/wwwroot/afreecatv/$filename.mp4 ");

        // check if its rtmp
        } else if ($item->url_type == 1) {

            // ffmpeg 에서 영상 다운로드하기
            exec("/usr/bin/ffmpeg -i  $url -vcodec copy -acodec copy out.mp4 /mnt/wwwroot/afreecatv/$filename.mp4 ");
        }

        // ---------------------

        // 데이터베이스 다시 연결
        $postman = Postman::init();

        // 다운로드 완전 값
        $processed  = 2;

        // 다운로드 완료 처리
        $postman->execute('
                UPDATE
                    `playlist`
                SET
                    `processed`=?
                WHERE
                    `idx`=?
            ', array('ii', &$processed, &$idx));

        // 바로 사이즈 찾으려면 이상하게 오류 나네요???
        // 안전하게 1초 쉬자 그럼~
        // 컴퓨터에게는 만녀!
        sleep(1);

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
            ', array('si', &$size, &$idx));
    }
