<?php

use Civi\Core\Lock\NullLock;
use CRM_Revertcontactinfo_ExtensionUtil as E;
// use CRM_CORE_DAO;

/**
 * ContactInfo.Revertdata API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_contact_info_Revertdata_spec(&$spec) {
  // Id of the Contact that is being changed
  $spec['contact_id']['api.required'] = 1;
  // The Entity that is changing, i.e. Phone, Email, Address
  $spec['entity']['api.required'] = 1;
}

/**
 * ContactInfo.Revertdata API
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 *
 * @throws API_Exception
 *
 * We want to look up the current value of the entity that is given for the contact id being passed in 
 * to compare that value to the previous value of that same entity for that contact.
 */

/**
 * Look up the entity specified for the given contact
 * and retrieve the most recent and second most recent values for that entity
 * so that the data can be reverted to the second most recent value.
 * Returns an Object
 */
function revertOneEntity($entity, $contactID) {
  // Look up the entity's log table.
  $entityLogTable = getLoggingDatabase($entity, ['civicrm\_%'], NULL);
  // Find the most recent entity[email] found for that contact. | Input: contact_id, entity to revert | Output: entity id
  $mostRecentQuery = "SELECT id FROM $entityLogTable WHERE contact_id = $contactID ORDER BY log_date DESC LIMIT 1;";
  $mostRecentEntityID = CRM_Core_DAO::singleValueQuery($mostRecentQuery);
  // Take that entity's id and look up the second most recent address with that id. | Input: entity to revert, entity's id | Output: row with that entity_id
  // By doing it by the id we can revert the whole row, so it does not have to be entity specific.
  $secondMostRecentQuery = "SELECT * FROM $entityLogTable WHERE id = $mostRecentEntityID ORDER BY log_date DESC LIMIT 1 OFFSET 1;";
  $params = [];
  $secondMostRecentDAO = CRM_Core_DAO::executeQuery($secondMostRecentQuery, $params);
  return $secondMostRecentDAO;
}

/**
 * Helper function to get the logging database and table name for the relevant entity.
 * Returns a String.
 */
function getLoggingDatabase($entity, $pattern = ['civicrm\_%'], $databaseList = NULL) {
  $dao = new CRM_Core_DAO();
  $databases = $databaseList ?? [$dao->_database];

  $tableNameLikePatterns = [];
  $logTableNameLikePatterns = [];

  $pattern = CRM_Utils_Type::escape($pattern, 'String');
  $entity = CRM_Utils_Type::escape($entity, 'String');

  $tableNameLikePatterns[] = "Name LIKE '{$pattern}\_{$entity}'";
  $logTableNameLikePatterns[] = "Name LIKE 'log\_{$pattern}\_{$entity}'";

  foreach ($databases as $database) {
    $dao = CRM_Core_DAO::executeQuery("SHOW TABLE STATUS FROM `{$database}` WHERE Engine = 'InnoDB' AND (" . implode(' OR ', $tableNameLikePatterns) . ")");
    while ($dao->fetch()) {
      $tables["`{$database}`.`{$dao->Name}`"] = [
        'Engine' => $dao->Engine,
      ];
    }
  }
  // If we specified a list of databases assume the user knows what they are doing.
  // If they specify the database they should also specify the pattern.
  if (!$databaseList) {
    $dsn = defined('CIVICRM_LOGGING_DSN') ? CRM_Utils_SQL::autoSwitchDSN(CIVICRM_LOGGING_DSN) : CRM_Utils_SQL::autoSwitchDSN(CIVICRM_DSN);
    $dsn = DB::parseDSN($dsn);
    $logging_database = $dsn['database'];
    $dao = CRM_Core_DAO::executeQuery("SHOW TABLE STATUS FROM `{$logging_database}` WHERE Engine <> 'MyISAM' AND (" . implode(' OR ', $logTableNameLikePatterns) . ")");
    while ($dao->fetch()) {
      $tables["`{$logging_database}`.`{$dao->Name}`"] = [
        'Engine' => $dao->Engine,
      ];
    }
  }
  return $dao[''];
}

/**
 * APIv3 call to update the value of the entity.
 */
function civicrm_api3_contact_info_Revertdata($params) {
  // Always be passing in an array, regardless of how many elements
  // For each entity in that array, call revertOneEntity().
  $params['entity'] = (array) $params['entity'];
  foreach ($params['entity'] as $entity) {
    revertOneEntity($entity, $params['contact_id']);
  }
  if (array_key_exists('contact_id', $params) && array_key_exists('entity', $params)) {
    $returnValues = [];
    // Spec: civicrm_api3_create_success($values = 1, $params = [], $entity = NULL, $action = NULL)
    return civicrm_api3_create_success($returnValues, $params, 'ContactInfo', 'Revertdata');
  }
  else {
    throw new API_Exception(/*error_message*/ '', /*error_code*/ '');
  }
}
