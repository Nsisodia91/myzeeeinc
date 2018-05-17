<?php

namespace Zinc;

/**
* Filelogs is core class to write up logs to specific location
*/
class Filelogs
{
    /**
     * Defining constant value of directory separator
     */
    const DS = '/';

    /**
     * $site_path is global site_path thats been set for your current file
     * @var string
     */
    public $site_path;

    /**
     * $file_path is path to file
     * @var string
     */
    private $file_path;

    /**
     * $file_name is name of file
     * @var string
     */
    private $file_name;

    /**
     * $content may be HTML/JSON String
     * @var string
     */
    private $content;

    /**
     * __construct
     */
    public function __construct($site_path)
    {
        $this->site_path = $site_path;
    }

    /**
     * setFilePath to set file path if not exists then create directories recursively
     * @param string  $file_path
     * @param integer $permission
     */
    public function setFilePath($file_path, $permission = 0777)
    {
        $this->file_path = $this->site_path . self::DS . $file_path;

        if(!is_dir($this->file_path))
        {
            if(!$this->createFolder($this->file_path)) throw new \Exception("Error while creating folder", 1);
            ;
        }

        return $this;
    }

    /**
     * setFile is to set file name within file_name variable
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file_name = $file; return $this;
    }

    /**
     * setContent is to set content string within variable content
     * @param string $content
     */
    public function setContent($content = '')
    {
        $this->content = $content; return $this;
    }

    /**
     * createFolder is to create folder within specific given path
     * @param  string  $filepath
     * @param  integer $permission
     * @return string
     */
    private function createFolder($filepath, $permission = 0777)
    {
        $root_path = $this->site_path . self::DS;
        $dir_sep = '/';
        $pathfolder = @explode($dir_sep, str_replace($root_path, "", $filepath));
        $realpath = $makefolder = "";
        for ($p = 0; $p < count($pathfolder); $p++) {
            if ($pathfolder[$p] != '') {
                $realpath = $realpath . $pathfolder[$p] . $dir_sep;
                $makefolder = $root_path . $dir_sep . $realpath;
                if (!is_dir($makefolder)) {
                    $old_umask = @umask(0);
                    @mkdir($makefolder, $permission);
                    @chmod($makefolder, $permission);
                    @chown($makefolder, get_current_user());
                    @umask($old_umask);
                }
            }
        }
        return $makefolder;
    }

    /**
     * writeLog is to write log file within system
     * @return array
     */
    public function writeLog()
    {
        $return = [];

        try
        {
            if (empty($this->file_name))
            {
                throw new Exception("Please set file first using setFile method.", 0);
            }

            if(!file_exists($this->file_path))
            {
                if(!$this->createFolder($this->file_path)) throw new Exception("Error while creating files and folder", 1);
                ;
            }

            $file = $this->file_path . '/' . $this->file_name;

            $fp = @fopen($file, 'a+');
            @fwrite($fp, $this->content);
            @fclose($fp);

            $return = [
                'success' => 1,
                'message' => 'Successfully logged file named @path -> '. $file,
            ];
        }
        catch(Exception $e)
        {
            $return = [
                'success' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }

        return $return;
    }

}