<?php

namespace YAWPS\inc;

class clAutoloader {

    /**
     * An key-value array. 
     * The key is the namespace-prefix and the value is an array of base-directories for classes in that namespace.
     * 
     * @var array
     */
    protected $aPrefixes = array();

    /**
     * Register loader with SPL autoloader stack.
     * 
     * @return void
     */
    public function register() {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Adds a base directory for a namespace prefix.
     * 
     * @param string $sPrefix The namespace prefix.
     * @param string $sBaseDir A base directory for class files in the namespace.
     * @param string $bPrepend If true, prepend the base directory to the stack instead of appending it; this causes it to be searched first rather than last.
     * 
     * @return void
     */
    public function addNamespace($sPrefix, $sBaseDir, $bPrepend = false) {
        $sPrefix = trim($sPrefix, '\\') . '\\';

        $sBaseDir = rtrim($sBaseDir, DIRECTORY_SEPARATOR) . '/';

        if (isset($this->aPrefixes[$sPrefix]) === false) {
            $this->aPrefixes[$sPrefix] = array();
        }

        if ($bPrepend) {
            array_unshift($this->aPrefixes[$sPrefix], $sBaseDir);
        } else {
            array_push($this->aPrefixes[$sPrefix], $sBaseDir);
        }
    }

    /**
     * Loads the class file for a given class name.
     * 
     * @param string $sClass The fully-qualified class name.
     * @return mixed The mapped file name on success, or boolean false on failure.
     */
    public function loadClass($sClass) {
        $sPrefix = $sClass;

        // Walk backward through namespace of the fully-qualified
        // class name to find a mapped file name.
        while (false !== $iPos = strrpos($sPrefix, '\\')) {

            $sPrefix = substr($sClass, 0, $iPos + 1);

            $sRelativeClass = substr($sClass, $iPos + 1);

            $oMappedFile = $this->loadMappedFile($sPrefix, $sRelativeClass);

            if ($oMappedFile) {
                return $oMappedFile;
            }
            $sPrefix = rtrim($sPrefix, '\\');
        }

        return false;
    }

    /**
     * Load the mapped file for a namespace prefix and relative class.
     * 
     * @param string $sPrefix The namespace prefix.
     * @param string $sRelativeClass The relative class name.
     * @return mixed Boolean false if no mapped file can be loaded, or the name of the mapped file that was loaded.
     */
    protected function loadMappedFile($sPrefix, $sRelativeClass) {
        if (isset($this->aPrefixes[$sPrefix]) === false) {
            return false;
        }

        foreach ($this->aPrefixes[$sPrefix] as $sBaseDir) {
            $sFile = $sBaseDir
                    . str_replace('\\', '/', $sRelativeClass)
                    . '.php';
            if ($this->requireFile($sFile)) {
                return $sFile;
            }
        }
        return false;
    }

    /**
     * If a file exists, require it from the file system.
     * 
     * @param type $sFile The file to require.
     * @return boolean True if the file exists, false if not.
     */
    protected function requireFile($sFile) {
        if (file_exists($sFile)) {
            require $sFile;
            return true;
        }
        return false;
    }

}
