<?php namespace krisawzm\OctoberLanguageComparison;

use Exception;
use Symfony\Component\Finder\Finder;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * Executes a comparison.
 *
 * @author Kristoffer Alfheim <https://github.com/krisawzm>
 */
class Compare
{
    /**
     * The 'master' lang to compare with.
     *
     * @var string
     */
    const MASTER_LANG = 'en';

    /* Some vars */
    protected $langDir;    // Lang base directory
    protected $devLang;    // Dev lang code

    protected $dirMaster;  // Dir master
    protected $dirDev;     // Dir dev

    /**
     * Notes.
     *
     * @var array
     */
    protected $notes = [];

    /**
     * Constructor.
     *
     * @param string $langDir    lang base directory.
     * @param string $devLang    Language code to compare.
     * @return void
     */
    public function __construct($langDir, $devLang)
    {
        $this->langDir = getcwd().'/'.rtrim($langDir, '/');

        if (!is_dir($this->langDir)) {
            throw new Exception('Lang directory does not exist.');
        }

        $this->dirMaster = $this->langDir.'/'.self::MASTER_LANG;

        if (!is_dir($this->dirMaster)) {
            throw new Exception('Lang '.self::MASTER_LANG.' is missing. Aborting.');
        }

        $this->devLang = $devLang;
        $this->dirDev = $this->langDir.'/'.$this->devLang;

        if (!is_dir($this->dirDev)) {
            throw new Exception('Lang '.$this->devLang.' is missing.');
        }

        $this->checkMissingKeys();
    }

    /**
     * Checks for missing keys.
     *
     * @return void
     */
    protected function checkMissingKeys()
    {
        $keysMaster = $this->loadKeys(
            $this->files($this->dirMaster)
        );

        $keysDev = $this->loadKeys(
            $this->files($this->dirDev)
        );

        foreach ($keysMaster as $key) {
            if (!in_array($key, $keysDev)) {
                $this->logMissing('Missing key: '.$this->devLang.'/'.$key);
            }
        }

        foreach ($keysDev as $key) {
            if (!in_array($key, $keysMaster)) {
                $this->logDeleted('Deleted key: '.$this->devLang.'/'.$key);
            }
        }

        return $this;
    }

    /**
     * Load keys in groups.
     *
     * @param array $groupFiles
     * @return array
     */
    protected function loadKeys($groupFiles)
    {
        $keys = [];
        foreach ($groupFiles as $groupKey => $groupFile) {
            $dataArray = require_once $groupFile;
            $prefix = basename($groupFile).' -> ';

            $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($dataArray));

            foreach ($iterator as $key => $value) {
                for ($i = $iterator->getDepth() - 1; $i >= 0; $i--) {
                    $key = $iterator->getSubIterator($i)->key().'.'.$key;
                }

                $keys[] = $prefix.$key;
            }
        }

        return $keys;
    }

    /**
     * Log missing.
     *
     * @param string $info
     * @return void
     */
    protected function logMissing($info)
    {
        $this->notes[] = [$info, 'red'];
    }

    /**
     * Log deleted.
     *
     * @param string $info
     * @return void
     */
    protected function logDeleted($info)
    {
        $this->notes[] = [$info, 'yellow'];
    }

    /**
     * Get missing data.
     *
     * @return array
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Get an array of all files in a directory.
     *
     * @param string $directory
     * @return array
     */
    public function files($directory)
    {
        $glob = glob($directory.'/*');
        if ($glob === false) {
            return [];
        }

        return array_filter($glob, function($file) {
            return filetype($file) == 'file';
        });
    }
}
