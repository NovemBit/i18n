<?php


namespace i18n\system;

use \Exception;
use PDO;
use PDOException;

abstract class ActiveData {

	public static $table_name;

	private static $fieldsNames;

	private static $pkField;

	private static $_fetched;

	private $fields;

	/**
	 * @return PDO|null
	 */
	public static function pdo() {
		return null;
	}

	/**
	 * ActiveData constructor.
	 * @throws \Exception
	 */
	public function __construct() {
		self::fetchFields();
	}

	/**
	 * @throws \Exception
	 */
	public static function fetchFields() {

		if ( self::$_fetched ) {
			return true;
		}

		if ( static::pdo() == null ) {
			return false;
		}

		$driverName = static::pdo()->getAttribute( PDO::ATTR_DRIVER_NAME );

		if ( $driverName == 'mysql' ) {

			$q = static::pdo()->prepare( "DESCRIBE " . static::$table_name );
			$q->execute();

			foreach ( $q->fetchAll( PDO::FETCH_COLUMN ) as $value ) {
				self::$fieldsNames[] = $value;
			}


		} elseif ( $driverName == 'sqlite' ) {

			$q = static::pdo()->prepare( "PRAGMA table_info(" . static::$table_name . ")" );


			$q->execute();

			foreach ( $q->fetchAll( PDO::FETCH_ASSOC ) as $value ) {

				if ( ! isset( self::$pkField ) && $value['pk'] == 1 ) {
					self::$pkField = $value['name'];
				}

				self::$fieldsNames[] = $value['name'];
			}

		} else {
			throw new \Exception( 'Unsupported RDMS driver type.' );
		}

		self::$_fetched = true;

		return true;
	}

	/**
	 * @return string[]|null
	 * @throws \Exception
	 */
	public static function getFieldsNames() {

		if ( self::fetchFields() ) {
			return self::$fieldsNames;
		} else {
			return null;
		}
	}

	/**
	 * @return string|null
	 * @throws \Exception
	 */
	public static function getPKField() {
		if ( self::fetchFields() ) {
			return self::$pkField;
		} else {
			return null;
		}
	}


	/**
	 * @param $pk
	 *
	 * @return bool
	 */
	public function exists( $pk ) {

		$query     = sprintf( "SELECT 1 FROM %s WHERE %s.%s = ? LIMIT 1",
			static::$table_name,
			static::$table_name,
			self::$pkField
		);
		$statement = static::pdo()->prepare( $query );
		$statement->execute( [ $pk ] );
		if ( $statement->fetchColumn() ) {
			return true;
		}

		return false;
	}

	/**
	 * @param null $where
	 * @param null $order
	 * @param null $limit
	 *
	 * @return array
	 * @throws Exception
	 */
	public static function find( $where = null, $order = null, $limit = null ) {

		$list = [];

		$rows = self::findFields( null, $where, $order, $limit );

		if ( $rows == null ) {
			throw new Exception( "Record not found on table." );
		}

		foreach ( $rows as $key => $fields ) {
			$list[ $key ] = new static();
			$list[ $key ]->setFields( $fields );
		}

		return $list;
	}

	/**
	 * @param $pk
	 *
	 * @return ActiveData
	 * @throws \Exception
	 */
	public static function findByPk( $pk ) {

		$model = new static();

		$fields = self::findFieldsByPk( $pk );

		if ( $fields == null ) {
			throw new Exception( "Record not found on table." );
		}

		$model->setFields( $fields );

		return $model;
	}

	/**
	 * @param $pk
	 *
	 * @return array
	 * @throws \Exception
	 */
	private static function findFieldsByPk( $pk ) {

		self::fetchFields();

		$fields = self::findFields( null, [ self::getPKField() . "=?", [ $pk ] ] );

		return $fields;
	}

	/**
	 * @param null $fields
	 * @param string $where
	 * @param string $order
	 * @param string $limit
	 *
	 * @return array|null
	 * @throws Exception
	 */
	public static function findFields( $fields = null, $where = null, $order = null, $limit = null ) {

		self::fetchFields();

		if ( $fields == null || $fields == trim( '*', ' ' ) ) {
			$fields = implode( ', ', self::getFieldsNames() );
		} elseif ( is_array( $fields ) ) {
			$fields = implode( ', ', $fields );
		}

		$where_params = [];

		if ( is_array( $where ) ) {

			if ( empty( $where ) ) {
				$where = 1;
			} else {
				if ( isset( $where[1] ) ) {
					$where_params = $where[1];
				}

				$where = $where[0];
			}
		} elseif ( $where == null ) {
			$where = 1;
		}


		if ( $order == null ) {
			$order = self::getPKField() . ' DESC';
		}

		if ( $limit == null ) {
			$limit = 1;
		}

		$query = sprintf( "SELECT %s FROM %s WHERE %s ORDER BY %s LIMIT %s",
			$fields,
			static::$table_name,
			$where,
			$order,
			$limit
		);

		$statement = static::pdo()->prepare( $query );

		if ( $statement->execute( $where_params ) ) {
			$fields = $limit == 1 ? $statement->fetch( PDO::FETCH_ASSOC ) : $statement->fetchAll( PDO::FETCH_ASSOC );
		} else {
			return null;
		}

		return $fields;
	}

	/**
	 * @throws \Exception
	 */
	public function isNewRecord() {
		if ( $this->getPk() == null || ! $this->exists( $this->getPk() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @throws \Exception
	 */
	public function delete() {
		if ( $this->isNewRecord() ) {
			throw new Exception( "Unknown delete. Record not exists" );
		}

		$query = sprintf( "DELETE FROM %s WHERE %s = ?",
			static::$table_name,
			self::$pkField
		);

		$statement = static::pdo()->prepare( $query );
		if ( $statement->execute( [ $this->getPk() ] ) ) {
			$this->fields = [];

			return true;
		} else {
			return false;
		}
	}

	/**
	 * @throws \Exception
	 */
	public function save() {

		$field_names = self::getFieldsNames();

		/*
		 * Insert new record
		 * */
		if ( $this->isNewRecord() ) {

			$fields_str = implode( self::getFieldsNames(), ', ' );
			$values_str = ":" . implode( self::getFieldsNames(), ', :' );

			$query = "INSERT INTO " . static::$table_name . " ($fields_str) VALUES ($values_str)";

		} /*
		 * Update existed record
		 * */
		else {
			$fields_str = '';
			$length     = count( $field_names );
			for ( $i = 0; $i < $length; $i ++ ) {
				$fields_str .= sprintf( "%s = :%s",

					$field_names[ $i ],
					$field_names[ $i ]
				);
				if ( $i < $length - 1 ) {
					$fields_str .= ", ";
				}
			}

			$query = sprintf( "UPDATE %s SET %s WHERE %s=:%s",
				static::$table_name,
				$fields_str,
				self::getPKField(),
				self::getPKField()
			);
		}

		try {

			$statement = static::pdo()->prepare( $query );
			$statement->execute( $this->getFields() );

			if ( $this->isNewRecord() ) {
				$this->fields = self::findFieldsByPk( static::pdo()->lastInsertId() );
			}

		} catch ( PDOException $e ) {
			var_dump( $e->getMessage() );
		}

	}

	/**
	 * @param mixed $fields
	 */
	public function setFields( $fields ) {
		$this->fields = $fields;
	}

	/**
	 * @return mixed
	 */
	public function getFields() {
		return $this->fields;
	}

	/*
	 * Magic methods get
	 * */
	public function __get( $key ) {
		return $this->fields[ $key ];
	}

	/*
	 * Magic methods set
	 * */
	public function __set( $key, $value ) {
		$this->fields[ $key ] = $value;
	}

	/**
	 * @throws \Exception
	 */
	public function getPk() {
		return isset( $this->fields[ self::getPKField() ] ) ? $this->fields[ self::getPKField() ] : null;
	}
}