<?php
/*///////////////////////////////////////////////////////////////
 /** ID: | /-- ID: Indonesia
 /** EN: | /-- EN: English
 ///////////////////////////////////////////////////////////////*/

/**
 * ID: DIC - Library untuk Kecik Framework, library ini khusus untuk membantu masalah Dependency Injection 
 * EN: DIC - Library for Kecik Framework, this library specially for help Dependency Injection Problem
 * 
 * @author 		Dony Wahyu Isp
 * @copyright 	2015 Dony Wahyu Isp
 * @link 		http://github.com/kecik-framework/dic
 * @license		MIT
 * @version 	1.0.1-alpha
 * @package		Kecik\DIC
 **/
namespace Kecik;

/**
 * DIC
 * @package 	Kecik\DIC
 * @author 		Dony Wahyu Isp
 * @since 		1.0.0-alpha
 **/
class DIC implements \ArrayAccess{
    /**
     * @var stdclass $storage
     **/
	private $storage = null;

    /**
     * Constructor
     **/
	public function __construct() {
		$this->storage = new \stdClass;
	}

    /**
     * factory
     * ID: Untuk selalu membuat instan baru dari sebuah objek
     * EN: For always create new instance from a object
     * @return array callback
     **/
	public function factory(\Closure $func) {
		return array('factory'=>$func);
	}

    /**
     * offsetSet
     * ID: Untuk menampung atau menyetting fungsi baru ke dalam storage
     * EN: For a collect or setting new function in storage
     **/
	public function offsetSet($offset, $value) {
        if (!empty($offset)) {
            $this->storage->$offset = $value;
        }

    }

    /**
     * offsetExists
     * ID: Jika sebuah funngsi telah didefinisi
     * EN: If a function are exists
     * @return function/closure ID: jika belum pernah digunakan | EN: if never use
     **/
    public function offsetExists($offset) {
        return isset($this->storage->$offset);
    }

    /**
     * offsetUnset
     * ID: Ketika fungsi dihapus
     * EN: when function deleting
     **/
    public function offsetUnset($offset) {
        unset($this->storage->$offset);
    }

    /**
     * offsetGet
     * ID: Ketika fungsi digunakan
     * EN: When using function
     **/
    public function offsetGet($offset) {
    	$callable = $this->storage->$offset;
    	
    	if ( is_callable($callable) )
    		$this->storage->$offset = $callable($this);

    	$return = $this->storage->$offset;
    	if ( is_array($callable) && isset($callable['factory']) ) {
    		$callable = $callable['factory'];
    		$return = $callable($this);
    	}

        return isset($this->storage->$offset) ? $return : null;
    }
}