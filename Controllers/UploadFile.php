<?php


class UploadFile
{
    public $pathOnServer;
    public function __construct($incomingFile) {
        $this->upload($incomingFile);
    }

    public function upload($incomingFile) {
        $filename = $incomingFile['name'];
        $baseFilename = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $filenameOnServer = $baseFilename .".". $extension;
        $pathOnServer = __UPLOAD_PATH__ . $filenameOnServer;
        if (copy($incomingFile['tmp_name'], $pathOnServer)) {
            $this->pathOnServer = $pathOnServer;
        }
    }

    public function returnPath() {
        return strval($this->pathOnServer);
    }
}