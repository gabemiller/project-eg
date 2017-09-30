<?php

class FileUploader {

    public $allowedExtensions = array();
    public $sizeLimit = null;
    public $inputName = 'file';
    public $chunksFolder = 'chunks';

    public $chunksCleanupProbability = 0.001; // Once in 1000 requests on avg
    public $chunksExpireIn = 604800; // One week

    protected $uploadName;

    function __construct(){
        $this->sizeLimit = $this->toBytes(ini_get('upload_max_filesize'));
    }

    /**
     * Get the original filename
     */
    public function getName(){
        if (isset($_REQUEST['qqfilename']))
            return $_REQUEST['qqfilename'];

        if (isset($_FILES[$this->inputName]))
            return $_FILES[$this->inputName]['name'];
    }

    /**
     * Get the name of the uploaded file
     */
    public function getUploadName(){
        return $this->uploadName;
    }

    /**
     * Process the upload.
     * @param string $uploadDirectory Target directory.
     * @param string $name Overwrites the name of the file.
     */
    public function handleUpload($uploadDirectory, $name){

        if (is_writable($this->chunksFolder) &&
            1 == mt_rand(1, 1/$this->chunksCleanupProbability)){

            // Run garbage collection
            $this->cleanupChunks();
        }

        // Check that the max upload size specified in class configuration does not
        // exceed size allowed by server config
        if ($this->toBytes(ini_get('post_max_size')) < $this->sizeLimit ||
            $this->toBytes(ini_get('upload_max_filesize')) < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            return array('error'=>'Szerver hiba. Növeld a "post_max_size" és a "upload_max_filesize" értékét '.$size.'-ra');
        }

		// is_writable() is not reliable on Windows (http://www.php.net/manual/en/function.is-executable.php#111146)
		// The following tests if the current OS is Windows and if so, merely checks if the folder is writable;
		// otherwise, it checks additionally for executable status (like before).
		
		$isWin = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
		$folderInaccessible = ($isWin) ? !is_writable($uploadDirectory) : ( !is_writable($uploadDirectory) || !is_executable($uploadDirectory) );

        if ($folderInaccessible){
            return array('error' => "Szerver hiba. Uploads nem írható" . (!$isWin) ? " vagy futtatható." : ".");
        }

        if(!isset($_SERVER['CONTENT_TYPE'])) {
            return array('error' => "Nincsenek feltöltött fájlok.");
        } else if (strpos(strtolower($_SERVER['CONTENT_TYPE']), 'multipart/') !== 0){
            return array('error' => 'Szerver hiba. Nem "multipart request". Kérlek állítsd át "forceMultipart" alapértelmezett értékét (true)-ra.');
        }

        // Get size and name

		$file = $_FILES[$this->inputName];
        $size = $file['size'];

        if ($name === null){
            $name = $this->getName();
        }

        // Validate name

        if ($name === null || $name === ''){
            return array('error' => 'A fájl neve üres.');
        }

        // Validate file size

        if ($size == 0){
            return array('error' => 'A fájl üres.');
        }

        if ($size > $this->sizeLimit){
            return array('error' => 'A fájl túl nagy.');
        }

        // Validate file extension

        $pathinfo = pathinfo($name);
        $ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';

        if($this->allowedExtensions && !in_array(strtolower($ext), array_map("strtolower", $this->allowedExtensions))){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'Nem megfelelő a fájl kiterjesztése, megfelelő kiterjesztések: '. $these . '.');
        }

        // Save a chunk

        $totalParts = isset($_REQUEST['qqtotalparts']) ? (int)$_REQUEST['qqtotalparts'] : 1;

        if ($totalParts > 1){

            $chunksFolder = $this->chunksFolder;
            $partIndex = (int)$_REQUEST['qqpartindex'];
            $uuid = $_REQUEST['qquuid'];

            if (!is_writable($chunksFolder) && !is_executable($uploadDirectory)){
                return array('error' => 'Szerver error. Chunks mappa nem írható vagy futtatható.');
            }

            $targetFolder = $this->chunksFolder.DIRECTORY_SEPARATOR.$uuid;

            if (!file_exists($targetFolder)){
                mkdir($targetFolder);
            }

            $target = $targetFolder.'/'.$partIndex;
            $success = move_uploaded_file($_FILES[$this->inputName]['tmp_name'], $target);

            // Last chunk saved successfully
            if ($success AND ($totalParts-1 == $partIndex)){

                $target = $this->getUniqueTargetPath($uploadDirectory, $name);
                $this->uploadName = basename($target);

                $target = fopen($target, 'wb');

                for ($i=0; $i<$totalParts; $i++){
                    $chunk = fopen($targetFolder.DIRECTORY_SEPARATOR.$i, "rb");
                    stream_copy_to_stream($chunk, $target);
                    fclose($chunk);
                }

                // Success
                fclose($target);

                for ($i=0; $i<$totalParts; $i++){
                    unlink($targetFolder.DIRECTORY_SEPARATOR.$i);
                }

                rmdir($targetFolder);

                return array("success" => true);

            }

            return array("success" => true);

        } else {

            $target = $this->getUniqueTargetPath($uploadDirectory, $name);

            if ($target){
                $this->uploadName = basename($target);

                if (move_uploaded_file($file['tmp_name'], $target)){
                    return array('success'=> true);
                }
            }

            return array('error'=> 'Nem menthető a feltöltött fájl.' .
                'A feltöltés félbeszakadt, vagy a szerver nem válaszol.');
        }
    }

    /**
     * Returns a path to use with this upload. Check that the name does not exist,
     * and appends a suffix otherwise.
     * @param string $uploadDirectory Target directory
     * @param string $filename The name of the file to use.
     */
    protected function getUniqueTargetPath($uploadDirectory, $filename)
    {
        // Allow only one process at the time to get a unique file name, otherwise
        // if multiple people would upload a file with the same name at the same time
        // only the latest would be saved.

        if (function_exists('sem_acquire')){
            $lock = sem_get(ftok(__FILE__, 'u'));
            sem_acquire($lock);
        }

        $pathinfo = pathinfo($filename);
        $base = $pathinfo['filename'];
        $ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
        $ext = $ext == '' ? $ext : '.' . $ext;

        $unique = $base;
        $suffix = 0;

        // Get unique file name for the file, by appending random suffix.

        while (file_exists($uploadDirectory . DIRECTORY_SEPARATOR . $unique . $ext)){
            $suffix += rand(1, 999);
            $unique = $base.'-'.$suffix;
        }

        $result =  $uploadDirectory . DIRECTORY_SEPARATOR . $unique . $ext;

        // Create an empty target file
        if (!touch($result)){
            // Failed
            $result = false;
        }

        if (function_exists('sem_acquire')){
            sem_release($lock);
        }

        return $result;
    }

    /**
     * Deletes all file parts in the chunks folder for files uploaded
     * more than chunksExpireIn seconds ago
     */
    protected function cleanupChunks(){
        foreach (scandir($this->chunksFolder) as $item){
            if ($item == "." || $item == "..")
                continue;

            $path = $this->chunksFolder.DIRECTORY_SEPARATOR.$item;

            if (!is_dir($path))
                continue;

            if (time() - filemtime($path) > $this->chunksExpireIn){
                $this->removeDir($path);
            }
        }
    }

    /**
     * Removes a directory and all files contained inside
     * @param string $dir
     */
    protected function removeDir($dir){
        foreach (scandir($dir) as $item){
            if ($item == "." || $item == "..")
                continue;

            unlink($dir.DIRECTORY_SEPARATOR.$item);
        }
        rmdir($dir);
    }

    /**
     * Converts a given size with units to bytes.
     * @param string $str
     */
    protected function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }
}