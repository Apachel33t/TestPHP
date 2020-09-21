<?php


class Main
{
    public $id;
    public $users_id;
    public $rights_id;
    public $reports_id;

    public function dumpReportInBase($path, $object) {
        $connect = new DB();

        $select = pg_query($connect->connect, "SELECT id FROM public.users WHERE login = '$object->login'");
        if (!$select) {
            echo "Произошла ошибка.\n";
            exit;
        }
        $user_id = pg_fetch_object($select);
        $user_id = intval($user_id->id);
        date_default_timezone_set('Europe/Moscow');
        $date = date('Y-m-d', time());
        $time = date('H:i', time());
        $insertFile = pg_query($connect->connect, "INSERT INTO public.file (file_name) VALUES ('$path'); 
                                                         SELECT currval(pg_get_serial_sequence('file','id'));");
        if(!$insertFile) {
            echo "Произошла ошибка.\n";
            exit;
        }
        $file_id = pg_fetch_object($insertFile);
        $file_id = intval($file_id->currval);
        $insertReport = pg_query($connect->connect, "INSERT INTO public.reports (purpose, file_id, comment, date, time) VALUES ('$object->purpose', $file_id, '$object->comment', '$date', '$time'); 
                                                           SELECT currval(pg_get_serial_sequence('reports','id'));");
        if(!$insertReport) {
            echo "Произошла ошибка.\n";
            exit;
        }
        $report_id = pg_fetch_object($insertReport);
        $report_id = intval($report_id->currval);
        $insertMain = pg_query($connect->connect, "INSERT INTO public.main (users_id, rights_id, reports_id) VALUES ($user_id, 1, $report_id)");
        if(!$insertMain) {
            echo "Произошла ошибка.\n";
            exit;
        }
        pg_close($connect->connect);
    }

    public function accept($id) {
        $connect = new DB();

        $delete = pg_query($connect->connect, "UPDATE public.main SET state = 'Accept' WHERE id = $id");
        if(!$delete) {
            echo "Произошла ошибка.\n";
            exit;
        }
        pg_close($connect->connect);
    }
    
    public function decline($id) {
        $connect = new DB();

        $delete = pg_query($connect->connect, "UPDATE public.main SET state = 'Decline' WHERE id = $id");
        if(!$delete) {
            echo "Произошла ошибка.\n";
            exit;
        }
        pg_close($connect->connect);
    }

    public function deleteReport($id, $rights, $file) {
        // That func change flag in table main (Я знаю что нельзя пользоваться подобными флагами и что флаг должен быть информативным, но в рамках тестового задания пойдет я думаю)
        // 1 is readable             (user can read his own reports and download file)
        // 2 was deleted by user     (after the flag became is 2, no one user can't read this report,
        //                           but users with rights admin can read and download file)
        // 3 was deleted by admin    (no one user and admin can't read this report and download file)
        $connect = new DB();
        if($rights === 1) {
            $delete = pg_query($connect->connect, "DELETE FROM public.file WHERE id = $file");
        } else {
            $delete = pg_query($connect->connect, "UPDATE public.main SET flag = 2 WHERE id = $id");
        }
        if(!$delete) {
            echo "Произошла ошибка.\n";
            exit;
        }
        pg_close($connect->connect);
    }
}