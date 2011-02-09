<?php

namespace Model\Mapper;

interface Mapper {
	
	public function save($data);
	
	public function delete($data);
	
	public function search($conditions, $order = null, $count = null, $offset = null);
	
}
?>
