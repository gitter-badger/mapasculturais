<?php
namespace MapasCulturais\Storage;

use MapasCulturais\App;

/**
 * Store the files in the filesystem.
 *
 * By default this component stores the files at BASE_PATH . '/files'.
 */
class FileSystem extends \MapasCulturais\Storage{

    /**
     * The FileSystem Sotarage configuration.
     * @var array
     */
    private $_config = array();

    /**
     * Creates the FileSystem Storage component.
     *
     * <code>
     * /**
     *  * Sample Configuration (optional)
     *  * In below example the files will be accessible at url http://mapasculturais.domain/relative/url/
     *  {@*}
     *  new \MapasCulturais\Storage\FileSystem(array(
     *      'dir' => '/full/path/',
     *      'baseUrl' => '/relative/url/'
     *  ))
     * </code>
     *
     * @param array $config
     */
    protected function __construct(array $config = array()) {
        $this->config = $config + array(
            'dir' => BASE_PATH . 'files/',
            'baseUrl' => '/files/'
        );
    }

    /**
     * Adds the file to the filesystem.
     *
     * @param \MapasCulturais\Entities\File $file
     *
     * @return bool true if the file was added, false otherwise.
     */
    protected function _add(\MapasCulturais\Entities\File $file) {
        if($file->tmpFile['error'] === UPLOAD_ERR_OK){
            $filename = $this->getPath($file);

            if(!is_dir(dirname($filename)))
                mkdir (dirname($filename), 0755, true);

            // if filename exists, add a number before the last dot
            if(file_exists($filename)){
                $original_file_name = $file->name;
                $fcount = 2;
                while(file_exists($filename)){
                    $file->name = preg_replace("#(\.[[:alnum:]]+)$#i", '-' . $fcount . '$1', $original_file_name);
                    $filename = $this->getPath($file);
                    $fcount++;
                }
            }

            rename($file->tmpFile['tmp_name'], $filename);
        }else{
            return false;
        }
    }

    /**
     * Removes the file from filesystem.
     *
     * @param \MapasCulturais\Entities\File $file
     *
     * @return bool true if the file was removed, false otherwise
     */
    protected function _remove(\MapasCulturais\Entities\File $file) {
        $filename = $this->getPath($file);
        $removed = file_exists($filename) ? unlink($filename) : false;

        // if the folder is empty remove it
        $dir = dirname($filename);
        if($removed && is_readable($dir) && count(scandir($dir)) == 2)
            rmdir($dir);

        return $removed;
    }

    /**
     * Returns the URL to the file.
     *
     * @param \MapasCulturais\Entities\File $file
     *
     * @return string The URL to the file.
     */
    protected function _getUrl(\MapasCulturais\Entities\File $file) {
        return App::i()->baseUrl . $this->config['baseUrl'] . $this->getPath($file, true);
    }

    /**
     * Returns the path to the file.
     *
     * If the owner of the file is another file, the path will be nested.
     *
     * @param \MapasCulturais\Entities\File $file
     * @param type $relative
     *
     * @return string The path to the file.
     */
    protected function _getPath(\MapasCulturais\Entities\File $file, $relative = false){
        $relative_path = strtolower(str_replace("MapasCulturais\Entities\\", "" , $file->objectType)) . '/' . $file->objectId . '/' . $file->name;

        if($file->owner && is_object($file->owner) && $file->owner->getClassName() == 'MapasCulturais\Entities\File')
            $relative_path = dirname($this->getPath($file->owner, true)) . '/' . $relative_path;

        $result = $relative ? $relative_path : $this->config['dir'] . $relative_path;

        return str_replace('\\', '-', $result);;
    }
}
