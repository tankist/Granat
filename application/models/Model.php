<?php

namespace Model;

interface Model {
	
	public function save();
	
	public function delete();
	
	public function populate($data = array());
	
	public function toArray();
}
?>
