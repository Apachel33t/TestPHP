<?php


class App
{
    public function __construct()
    {
        if(isset($_GET['path'])) {
            $tokens = explode('/', rtrim($_GET['path'], '/'));
            $controllerName = ucfirst(array_shift($tokens));
            switch ($controllerName) {
                case "Signin":
                    $tokens = json_decode($tokens[0]);
                    $result = new SignIn($tokens->login, $tokens->password);
                    $result = json_encode($result->object, JSON_UNESCAPED_UNICODE);
                    echo $result;
                    break;
                case "Signup":
                    $tokens = json_decode($tokens[0]);
                    $createUser = new Users();
                    $createUser->createNewUser($tokens);
                    $result = new SignIn($tokens->login, $tokens->password);
                    $result = json_encode($result->object, JSON_UNESCAPED_UNICODE);
                    echo $result;
                    break;
                case "Admin":
                    require_once('view/admin/index.php');
                    break;
                case "AdminAuth":
                    $tokens = json_decode($tokens[0]);
                    $result = new Admin($tokens->login, $tokens->password);
                    $result = json_encode($result->object, JSON_UNESCAPED_UNICODE);
                    echo $result;
                    break;
                case "Accept":
                    $tokens = json_decode($tokens[0]);
                    $accept = new Main();
                    $accept->accept($tokens->id);
                    break;
                case "Decline":
                    $tokens = json_decode($tokens[0]);
                    $accept = new Main();
                    $accept->decline($tokens->id);
                    break;
                case "Getreports":
                    $tokens = json_decode($tokens[0]);
                    $getReports = new GetReports();
                    if(empty($tokens->rights)) {
                        $reports = $getReports->getReports($tokens->login, $tokens->order);
                    } else {
                        $reports = $getReports->getReportsByAdmin($tokens->login, $tokens->order);
                    }
                    $result = json_encode($reports, JSON_UNESCAPED_UNICODE);
                    echo $result;
                    break;
                case "Uploadfile":
                    if(!empty($_FILES['file']['tmp_name'])) {
                        $tokens = json_decode($tokens[0]);
                        $upload = new UploadFile($_FILES['file']);
                        $path = $upload->returnPath();

                        $dumpInBase = new Main();
                        $dumpInBase->dumpReportInBase($path, $tokens);
                    }
                    break;
                case "Download":
                    $tokens = json_decode($tokens[0]);
                    $tokens = intval($tokens);
                    $download = new GetReports();
                    $download->downloadReports($tokens);
                    break;
                case "Deletereport":
                    $tokens = stripslashes($tokens[0]);
                    $tokens = str_replace(";;", "\\\\", $tokens);
                    $tokens = json_decode($tokens);
                    $delete = new Main();
                    $object = $tokens->object;
                    $delete->deleteReport($tokens->id, $tokens->rights, $object->file_id);
                    $response = json_encode("file is delete");
                    echo $response;
                    break;
                default:
                    echo 'Default';
                    break;
            }
        } else {
            require_once('view/client/index.php');
        }
    }
}