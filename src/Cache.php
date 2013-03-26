<?php
class Cache {
	
	const APPEND = 1;
	const PREPEND = 2;
	
	private $dir = "";
		
	function __construct($directory="cache/") {
		$this->dir = $directory;
		if(!is_dir($this->dir))
			mkdir($this->dir);
	}
	
	private function _set($name, $data) {
		file_put_contents($this->getName($name, true), $data);
	}
	
	public function set($name, $data, $append=0, $clean=false) {
		if(strlen($data) > 0 || $clean) {
			if(is_array($name)) {
				foreach($name as $nam) {
					$setData = "";
					switch($append) {
						case 0:
							$setData = $data;
							break;
							
						case 1:
							$setData = $this->get($nam) . $data;
							break;
							
						case 2:
							$setData = $data . $this->get($nam);
							break;
							
						default:
							$setData = $data;
							break;
					}
					
					$this->_set($nam, $setData);
				}
				
				return true;
			}
			else {
				$setData = "";
				switch($append) {
					case 0:
						$setData = $data;
						break;
						
					case 1:
						$setData = $this->get($name) . $data;
						break;
						
					case 2:
						$setData = $data . $this->get($name);
						break;
						
					default:
						$setData = $data;
						break;
				}

				$this->_set($name, $setData);
				
				return true;
			}
		}
		
		return false;
	}
	
	public function get($name) {
		if(file_exists($this->getName($name, true))) {
			return file_get_contents($this->getName($name, true));
		}
		
		return false;
	}
	
	public function getName($name, $full=false) {
		return $full ? $this->dir . md5($name) . ".cache" : md5($name) . ".cache";
	}
	
	public function getLastEdit($name) {
		return filemtime($this->getName($name, true));
	}
}
?>