<?php


class Users
{
    public $id;
    public $login;
    public $password;
    public $username;

    public function __construct() {}

    public function getUserById($login) {
        $connect = new DB();
        $getUser = pg_query($connect->connect, "SELECT id FROM public.users WHERE login = $login");
        if (!$getUser) {
            echo "Произошла ошибка.\n";
            exit;
        }
        $id = pg_fetch_object($getUser);
        pg_close($connect->connect);
        return $id->id;
    }

    public function createNewUser($data) {
        $connect = new DB();
        $createUser = pg_query($connect->connect, "INSERT INTO public.users (login, password, username) VALUES ('$data->login', '$data->password', '$data->username');");
        if (!$createUser) {
            echo "Ошибка регистрации пользователя";
            exit;
        }
    }
}