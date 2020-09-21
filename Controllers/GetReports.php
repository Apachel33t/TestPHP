<?php


class GetReports
{
    public function __construct() {}
    public function getReports($login, $order) {
        $connect = new DB();

        if (!empty($order) && $order === true) {
            $order = 'DESC';
        } else {
            $order = 'ASC';
        }

        $reports = pg_query($connect->connect, "
                   SELECT m.id, m.state, purpose, file_id, comment, date, time, file_name
                   FROM
                       users u
                           inner join main m
                                      on m.users_id = u.id
                           inner join reports r
                                      on m.reports_id = r.id
                           inner join file f
                                      on r.file_id = f.id
                   WHERE u.login = '$login' and m.flag = 1 ORDER BY m.id $order;");
        if(!$reports) {
            echo "Произошла ошибка.\n";
            exit;
        }
        $reports = pg_fetch_all($reports);
        return $reports;
    }

    public function getReportsByAdmin($login, $order) {
        $connect = new DB();

        if (!empty($order) && $order === true) {
            $order = 'DESC';
        } else {
            $order = 'ASC';
        }

        $reports = pg_query($connect->connect, "
                   SELECT m.id, m.state, purpose, file_id, comment, date, time, file_name
                   FROM
                       users u
                           inner join main m
                                      on m.users_id = u.id
                           inner join reports r
                                      on m.reports_id = r.id
                           inner join file f
                                      on r.file_id = f.id ORDER BY m.id $order;");
        if(!$reports) {
            echo "Произошла ошибка.\n";
            exit;
        }
        $reports = pg_fetch_all($reports);
        return $reports;
    }

    public function downloadReports($id) {
        $connect = new DB();

        $path = pg_query($connect->connect, "
                SELECT fi.file_name
                FROM
                    main m
                        inner join reports r
                                   on r.id = m.reports_id
                        inner join file fi
                                   on fi.id = r.file_id
                WHERE m.id = $id and m.flag = 1;
        ");
        if (!$path) {
            echo "Произошла ошибка.\n";
            exit;
        }
        $path = pg_fetch_object($path);
        $path = $path->file_name;

        $path = explode("\\", $path);
        $path = "./" . $path[4] . "/" . $path[5];

        if (file_exists($path)) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($path));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            // читаем файл и отправляем его пользователю
            if ($fd = fopen($path, 'rb')) {
                while (!feof($fd)) {
                    print fread($fd, 1024);
                }
                fclose($fd);
            }
            exit;
        }
    }
}