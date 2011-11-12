<?php
class fwktecSessions {
	var $db; //db class instance
	
	function fwktecSessions() {
		$this->db=new aphpsDb();	
	}
	
	function open()
	{
	}

	function close()
	{
	}

	function read($id)
	{
		global $dbsession;

		$id = mysql_real_escape_string($id);

		$sql = "SELECT data
            FROM   ##sessions
            WHERE  id = '$id'";

		if ($this->db->select($sql)) {
			$this->db->next();
			return base64_decode($this->db->get('data'));
		}

		return '';
	}

	function write($id, $data)
	{
		global $dbsession;

		$access = date('Y-m-d H:i:s',time());

		$id = mysql_real_escape_string($id);
		$access = mysql_real_escape_string($access);
		$data = mysql_real_escape_string(base64_encode($data));

		$sql = "REPLACE
            INTO    ##sessions
            VALUES  ('$id', '$access', '$data')";

		return $this->db->update($sql);
	}

	function destroy($id)
	{
		global $dbsession;

		$id = mysql_real_escape_string($id);

		$sql = "DELETE
            FROM   ##sessions
            WHERE  id = '$id'";

		return $this->db->update($sql);
	}

	function clean($max)
	{
		global $dbsession;

		$old = date('Y-m-d H:i:s',time() - $max);
		$old = mysql_real_escape_string($old);

		$sql = "DELETE
            FROM   ##sessions
            WHERE  access < '$old'";

		return $this->db->update($sql);
	}
}