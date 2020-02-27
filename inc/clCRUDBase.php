<?php

namespace YAWPS\inc;

class clCRUDBase {
    
    private $sTable = '';
    private $mType = "OBJECT";
    private $sCriteriaDefaultJoin = "AND";
    /**
     * The constructor of this class.
     * 
     * @global object $wpdb The global wp-db
     * @param string $sTable The table too use
     * @param string $mType What object should be returned when selecting rows ( OBJECT | ARRAY_A | ARRAY_N )
     * @param string $sCriteriaDefaultJoin What should be used when formatting the criterias.
     */
    public function __construct( $sTable = "", $mType = "OBJECT", $sCriteriaDefaultJoin = "AND" ) {
        if( empty($sTable) ) return;
        global $wpdb;
        $this->sTable = $wpdb->prefix . $sTable;
        $this->mType = $mType;
        $this->sCriteriaDefaultJoin = $sCriteriaDefaultJoin;
    }
    
    /**
     * Set a new table too use.
     * 
     * @global object $wpdb The global wp-db
     * @param string $sTable The table too use.
     * @return boolean True on new table set, false if $sTable is empty.
     */
    public function setTable( $sTable = "" ) {
        if( empty($sTable) ) return false;
        global $wpdb;
        $this->sTable = $wpdb->prefix . $sTable;
        return true;
    }
    
    /**
     * Get the current table.
     * 
     * @return string The name of the current table.
     */
    public function getTable() {
        return $this->sTable;
    }
    
    /**
     * Basic method for inserting data
     * 
     * @global object $wpdb The global wp-db
     * @param array $aData The data to insert.
     * @param array $aFormat Optional, formatting for the data.
     * @return int|boolean Returns id of the inserted row, false on failure.
     */
    public function create( $aData, $aFormat = array() ) {
        global $wpdb;
        
        $sTableName = $this->getTable();
        
        $mResult = $wpdb->insert(
            $sTableName,
            $aData,
            ( !empty($aFormat) ? $aFormat : null )
        );
        return $mResult;
    }
    
    /**
     * 
     * @global object $wpdb The global wp-db
     * @param array $aFields The fields to select, if null all fields are selected.
     * @param array $aCriterias An array of criterias, please see code for example.
     * @return mixed Object, associative array, numerically indexed array.
     */
    public function read( $aFields = array(), $aCriterias = array() ) {
        global $wpdb;
        
        $sTableName = $this->getTable();
        
        $sQuery = "SELECT ";
        if( !empty($aFields) ) {
            $sQuery .= implode( ", ", $aFields );
        } else {
            $sQuery .= "* ";
        }
        
        $sQuery .= "FROM $sTableName";
        if( !empty($aCriterias) ) {
            // Format the criterias
            /**
             * ['userId'] => array(
             *  'field' => 'user',
             *  'value' => 'developer',
             *  'type' => 'LIKE'
             * )
             */
            $sCriterias = ' WHERE ';
            $aCriterias = array();
            foreach( $aCriterias as $sKey => $aValue ) {
                $aCriterias[] = $aValue['field'] . ' ' . $aValue['type'] . ' ' . $aValue['value'];
            }
            $sCriterias .= implode( " AND ", $aCriterias );
            $sQuery .= $sCriterias;
        }
        
        $mRows = $wpdb->get_results( $sQuery, $this->mType );
        return $mRows;
    }
    
    /**
     * Update a row.
     * 
     * @global object $wpdb The wpdb-object
     * @param array $aData The data to update
     * @param array $aCriterias The criterias for updating the row
     * @return mixed The result
     */
    public function update( $aData = array(), $aCriterias = array() ) {
        global $wpdb;
        if( empty($aData) || empty($aCriterias) ) return false;
        $sTableName = $this->getTable();
        
        $mResult = $wpdb->update(
            $sTableName,
            $aData,
            $aCriterias    
        );
        
        return $mResult;
    }
    
    /**
     * Delete a row.
     * 
     * @global object $wpdb The wpdb-object
     * @param array $aCriterias The criterias to delete by
     * @return mixed The result
     */
    public function delete( $aCriterias = array() ) {
        global $wpdb;
        if( empty($aCriterias) ) return false;
        
        $sTableName = $this->getTable();
        
        $sQuery = "DELETE FROM $sTableName WHERE ";
        $sCriterias = '';
        $aCriterias = array();
        foreach( $aCriterias as $sKey => $aValue ) {
            $aCriterias[] = $aValue['field'] . ' ' . $aValue['type'] . ' ' . $aValue['value'];
        }
        $sCriterias .= implode( " AND ", $aCriterias );
        $sQuery .= $sCriterias;
        
        $mResult = $wpdb->query( $sQuery );
        return $mResult;
    }
    
    /**
     * Begin a data-transaction
     * 
     * @global object $wpdb
     * @return boolean true
     */
    public function beginTransaction() {
        global $wpdb;
        $wpdb->query( "START TRANSACTION;" );
        return true;
    }
    
    /**
     * End a data-transaction
     * 
     * @global object $wpdb
     * @return boolean true
     */
    public function endTransaction() {
        global $wpdb;
        $wpdb->query( "COMMIT;" );
        return true;
    }
    
    /**
     * Rollback a recent data-transaction
     * 
     * @global object $wpdb
     * @return boolean true
     */
    public function rollbackTransaction() {
        global $wpdb;
        $wpdb->query( "ROLLBACK;" );
        return true;
    }
}
