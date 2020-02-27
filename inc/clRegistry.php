<?php

namespace YAWPS\inc;

class clRegistry {
    public static $aEntries = array();
    
    /**
     * Add a already loaded object into the static registry-array.
     * 
     * @param string $sKey The key for the entry.
     * @param string $oObject The loaded class-object.
     * @return boolean True if class was set in the registry.
     */
    public static function set( $sKey, $oObject ) {
        self::$aEntries[$sKey] = $oObject;
        return true;
    }
    
    /**
     * Get the object for the given key.
     * 
     * @param string $sKey The key for the entry to get the object for.
     * @return mixed False if the class doesn't exists, and the object if the class is present and can be reused.
     */
    public static function get( $sKey ) {
        if( !array_key_exists($sKey, self::$aEntries) ) {
            return false;
        }
        return self::$aEntries[$sKey];
    }
}
