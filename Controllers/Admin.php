<?php


class Admin
{
    public $object;
    public function __construct($login, $password) {
        $this->authentication($login, $password);
        return $this->object;
    }

    public function authentication($login, $password) {
        $connect = new DB();
        $result = pg_query($connect->connect, "SELECT username, login, rights FROM public.users WHERE login = '$login' and password='$password'");
        if (!$result) {
            echo "Произошла ошибка.\n";
            exit;
        }
        $this->object = pg_fetch_object($result);
        pg_free_result($result);
        pg_close($connect->connect);
    }
}