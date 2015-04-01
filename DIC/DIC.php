<?php
/**
 * DIC - Library untuk framework kecik, library ini khusus untuk membantu masalah Dependency Injection 
 *
 * @author 		Dony Wahyu Isp
 * @copyright 	2015 Dony Wahyu Isp
 * @link 		http://github.io/kecik_dic
 * @license		MIT
 * @version 	1.0-alpha
 * @package		Kecik\DIC
 **/
namespace Kecik;

/**
 * Controller
 * @package 	Kecik\DIC
 * @author 		Dony Wahyu Isp
 * @since 		1.0-alpha
 **/

class DIC implements \ArrayAccess{
	private $storage = null;

	public function __construct() {
		$this->storage = new \stdClass;
	}

	public function factory(\Closure $func) {
		return array('factory'=>$func);
	}

	public function offsetSet($offset, $value) {
        if (!empty($offset)) {
            $this->storage->$offset = $value;
        }

    }

    public function offsetExists($offset) {
        return isset($this->storage->$offset);
    }

    public function offsetUnset($offset) {
        unset($this->storage->$offset);
    }

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