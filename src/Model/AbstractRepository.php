<?php
/**
 * NOTICE OF LICENSE
 *
 * @author Scwaall
 * @copyright Copyright (c) Scwaall - 2020
 * @package YziPrestaShopModule
 * @support scwaall@gmail.com
 */

namespace Scwaall\YziPrestaShopModule\Model;

require_once(dirname(__FILE__) . '/../../autoload.php');

use DbCore as Db; // TODO: Replace DbCore by Db.
use DbQuery;
use Exception;
use ObjectModelCore as ObjectModel; // TODO: Replace ObjectModelCore by ObjectModel.
use PrestaShopDatabaseException;
use PrestaShopException;
use Scwaall\YziPrestaShopModule\Tools;

/**
 * Class AbstractRepository
 *
 * @package Scwaall\YziPrestaShopModule\Model
 */
abstract class AbstractRepository extends ObjectModel
{
    /**
     * Installs the repository to the database.
     *
     * @return bool
     * @throws Exception
     */
    public static function install()
    {
        return Db::getInstance()->execute(static::getSqlQueryCreateTable());
    }

    /**
     * Uninstalls the repository from the database.
     *
     * @return bool
     * @throws Exception
     */
    public static function uninstall()
    {
        return Db::getInstance()->execute(static::getSqlQueryDropTable());
    }

    /**
     * Uninstalls and installs again the repository to the database.
     *
     * @return bool
     * @throws Exception
     */
    public static function reset()
    {
        return static::uninstall() && static::install();
    }

    /**
     * Purges all data of the repository from the database.
     *
     * @return bool
     * @throws Exception
     */
    public static function purge()
    {
        return Db::getInstance()->delete(pSQL(static::getTable()));
    }

    /**
     * Gets an instance of a repository.
     *
     * @param int|null $id [optional] The entity's ID.
     * @param int|null $shopId [optional] The shop's ID.
     * @param int|null $languageId [optional] The language's ID.
     * @return static
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public static function getInstance($id = null, $shopId = null, $languageId = null)
    {
        return new static($id, $languageId, $shopId);
    }

    /**
     * Reads all data of the repository from the database.
     *
     * @param null|string $orderBy [optional] Example: 'my_column ASC' or 'my_column DESC.
     * @return array
     * @throws Exception
     * @throws PrestaShopDatabaseException
     */
    public static function read($orderBy = null)
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from(pSQL(static::getTable()));
        if (!empty($orderBy)) {
            $query->orderBy($orderBy);
        }

        $result = Db::getInstance()->executeS($query);
        if (!is_array($result)) {
            $result = array();
        }

        return $result;
    }

    /**
     * Gets the last creation's date of the repository.
     *
     * @return bool|false|null|string
     * @throws Exception
     */
    public static function getLastDateAdd()
    {
        if (empty(static::getDefinition('date_add'))) {
            throw new Exception(sprintf(
                'The field \'date_add\' is not defined for \'%s\'!',
                static::getTable(true)
            ));
        }

        $query = new DbQuery();
        $query->select('MAX date_add');
        $query->from(pSQL(static::getTable()));
        return Db::getInstance()->getValue($query);
    }

    /**
     * Gets the last update's date of the repository.
     *
     * @return bool|false|null|string
     * @throws Exception
     */
    public static function getLastDateUpd()
    {
        if (empty(static::getDefinition('date_upd'))) {
            throw new Exception(sprintf(
                'The field \'date_upd\' is not defined for \'%s\'!',
                static::getTable(true)
            ));
        }

        $query = new DbQuery();
        $query->select('MAX date_upd');
        $query->from(pSQL(static::getTable()));
        return Db::getInstance()->getValue($query);
    }

    /**
     * Gets the repository's table name.
     *
     * @param bool $withPrefix [optional] Defines whether to get the repository's table name with the database prefix.
     * @return null|string
     * @throws Exception
     */
    public static function getTable($withPrefix = false)
    {
        if (!isset(static::$definition['table']) || empty(static::$definition['table'])) {
            throw new Exception(sprintf(
                'The table is not defined for the repository \'%s\'!',
                static::class
            ));
        }

        return ($withPrefix ? _DB_PREFIX_ : '') . static::$definition['table'];
    }

    /**
     * Gets the repository's primary key.
     *
     * @return null|string
     * @throws Exception
     */
    public static function getPrimaryKey()
    {
        if (!isset(static::$definition['primary']) || empty(static::$definition['primary'])) {
            throw new Exception(sprintf(
                'No primary key for the table \'%s\'!',
                static::getTable(true)
            ));
        }

        return static::$definition['primary'];
    }

    /**
     * Gets the repository's unique key.
     *
     * @return array|null|string|string[]
     */
    public static function getUniqueKey()
    {
        return (isset(static::$definition['unique']) && !empty(static::$definition['unique'])
            ? static::$definition['unique']
            : ''
        );
    }

    /**
     * Gets the repository's definition.
     *
     * @param null|string $field [optional] The field to get the definition.
     * @param null|string $class [optional] The class' name to get the repository's definition. Gets the current repository's definition by default.
     * @return array|bool|null|string
     * @throws Exception
     */
    public static function getDefinition($field = null, $class = null)
    {
        if (empty($class)) {
            $class = static::class;
        }

        $definition = parent::getDefinition($class);
        if (empty($field)) {
            return $definition;
        } elseif (!isset($definition['fields']) || empty($definition['fields'])) {
            throw new Exception(sprintf(
                'The fields are not defined for \'%s\'!',
                static::getTable(true)
            ));
        } elseif (!isset($definition['fields'][$field]) || empty($definition['fields'][$field])) {
            throw new Exception(sprintf(
                'The field \'%s\' is not defined for \'%s\'!',
                $field,
                static::getTable(true)
            ));
        } elseif (!is_array($definition['fields'][$field])) {
            throw new Exception(sprintf(
                'The field\'s definition must be an array for \'%s.%s\'!',
                static::getTable(true),
                $field
            ));
        }

        return $definition['fields'][$field];
    }

    /**
     * Gets a field's data value.
     *
     * @param string $field The field's name.
     * @param string $data The field's data.
     * @return mixed|string
     * @throws Exception
     */
    public static function getFieldData($field, $data)
    {
        $fieldData = static::getDefinition($field);
        return isset($fieldData[$data]) ? $fieldData[$data] : '';
    }

    /**
     * Gets the field's size.
     *
     * @param string $field The field's name.
     * @return int|null|string NULL if the data is not defined.
     * @throws Exception
     */
    public static function getFieldSize($field)
    {
        return static::getFieldData($field, 'size');
    }

    /**
     * Defines if the field is required.
     *
     * @param string $field The field's name.
     * @return bool
     * @throws Exception
     */
    public static function isRequiredField($field)
    {
        return (bool)static::getFieldData($field, 'required');
    }

    /**
     * Gets the field's default value.
     *
     * @param string $field The field's name.
     * @return mixed|null NULL if the data is not defined.
     * @throws Exception
     */
    public static function getFieldDefaultValue($field)
    {
        return static::getFieldData($field, 'default');
    }

    /**
     * Gets the field's default value on update.
     *
     * @param string $field The field's name.
     * @return mixed|null NULL if the data is not defined.
     * @throws Exception
     */
    public static function getFieldOnUpdateValue($field)
    {
        return static::getFieldData($field, 'onUpdate');
    }

    /**
     * Gets a field's type with its size if it is defined, else default size.
     *
     * @param string $fieldName The field's name.
     * @return string
     * @throws Exception
     */
    public static function getFieldType($fieldName)
    {
        $size = static::getFieldSize($fieldName);
        $fieldTypeId = static::getFieldData($fieldName, 'type');
        switch ($fieldTypeId) {
            case self::TYPE_INT:
                return 'INT(' . (empty($size) ? '11' : (int)$size) . ')';

            case self::TYPE_BOOL:
                return 'TINYINT(1)';

            case self::TYPE_STRING:
                return empty($size) ? 'TEXT' : 'VARCHAR(' . (int)$size . ')';

            case self::TYPE_FLOAT:
                return 'DOUBLE(' . (empty($size) ? '20,6' : pSQL(trim(trim($size, '(')), ')')) . ')';

            case self::TYPE_DATE:
                return 'DATETIME';

            case self::TYPE_HTML:
            case self::TYPE_NOTHING:
                return 'LONGTEXT';

            case self::TYPE_SQL:
                return 'TEXT';

            default:
                throw new Exception(sprintf(
                    'The type\'s ID \'%d\' is not available on the PrestaShop ObjectModel for the field \'%s\'!',
                    (int)$fieldTypeId,
                    $fieldName
                ));
        }
    }

    /**
     * Gets the SQL's query for the repository creation.
     *
     * @return string
     * @throws Exception
     */
    public static function getSqlQueryCreateTable()
    {
        $query = 'CREATE TABLE IF NOT EXISTS ' . static::getTable(true) . ' (';
        $query .= "\n\t" . pSQL(static::getPrimaryKey() . ' INT(11) NOT NULL AUTO_INCREMENT');

        foreach (static::$definition['fields'] as $fieldName => $fieldData) {
            $query .= ',' . "\n\t" . pSQL(static::getSqlQueryField($fieldName));
        }

        $query .= ',' . "\n\t" . pSQL(static::getSqlQueryIndexPrimaryKey());

        if (!empty(static::getUniqueKey())) {
            $query .= ',' . "\n\t" . pSQL(static::getSqlQueryIndexUniqueKey());
        }

        $query .= "\n" . ')';
        $query .= ' ENGINE=' . pSQL(_MYSQL_ENGINE_);
        $query .= ' DEFAULT CHARSET=utf8';
        $query .= ' AUTO_INCREMENT=1;';
        return $query;
    }

    /**
     * Gets the SQL's query for the repository deletion.
     *
     * @return string
     * @throws Exception
     */
    public static function getSqlQueryDropTable()
    {
        return 'DROP TABLE ' . static::getTable(true);
    }

    /**
     * Gets the SQL's query for a repository's field.
     *
     * @param $field
     * @return string
     * @throws Exception
     */
    public static function getSqlQueryField($field)
    {
        if (!isset(static::getDefinition($field)['type'])) {
            throw new Exception(sprintf(
                'The field\'s type is not defined for \'%s.%s\'!',
                static::getTable(true),
                $field
            ));
        }

        $query = $field . ' ' . static::getFieldType($field);
        $query .= static::isRequiredField($field) ? ' NOT NULL' : ' NULL';
        if (!empty(static::getFieldDefaultValue($field))) {
            $query .= ' DEFAULT ' . static::getFieldDefaultValue($field);
        }
        if (!empty(static::getFieldOnUpdateValue($field))) {
            $query .= ' ON UPDATE ' . static::getFieldOnUpdateValue($field);
        }

        return $query;
    }

    /**
     * Gets the SQL's query for the repository's primary key index.
     *
     * @return string
     * @throws Exception
     */
    public static function getSqlQueryIndexPrimaryKey()
    {
        return 'PRIMARY KEY (' . static::getPrimaryKey() . ')';
    }

    /**
     * Gets the SQL's query for the repository's unique key index.
     *
     * @return string
     * @throws Exception
     */
    public static function getSqlQueryIndexUniqueKey()
    {
        $query = '';
        if (empty(static::getUniqueKey())) {
            return $query;
        }

        $uniqueKeyName = '';
        $uniqueKeyFieldList = static::getUniqueKey();
        if (Tools::isAssociativeArray($uniqueKeyFieldList)) {
            $uniqueKeyName = array_keys($uniqueKeyFieldList)[0];
            $uniqueKeyFieldList = $uniqueKeyFieldList[$uniqueKeyName];
        }
        if (!is_array($uniqueKeyFieldList)) {
            $uniqueKeyFieldList = explode(',', $uniqueKeyFieldList);
        }

        $query .= 'UNIQUE KEY ' . trim($uniqueKeyName) . '(';

        foreach ($uniqueKeyFieldList as $fieldName) {
            if (!isset(static::$definition['fields'][$fieldName])) {
                throw new Exception(sprintf(
                    'The field \'%s\' is not defined in the fields list for \'%s\'!',
                    $fieldName,
                    static::getTable(true)
                ));
            }

            $query .= trim($fieldName) . ', ';
        }

        $query = rtrim($query, ', ') . ')';
        return $query;
    }
}
