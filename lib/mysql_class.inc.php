<?php

	/* MySQL abstraction class
	** -----------------------
	** Author  : Dan Barber
	** Date    : 05 Nov 2009
	** Purpose : To abstract database functionality away from
	**           the main code to make it easier to port to
	**           different database systems
	** Version : 0.2
	**
	*/

	class db_connect {

		var $db_host;
		var $db_port;
		var $db_user;
		var $db_pass;
		var $db_name;
		
		var $messages;

		var $connection;

		/* ---------------------------------------------------------------------------------------- */

		function db_connect($db_host, $db_port, $db_user, $db_pass, $db_name) {

			// Initialise the object.  This will create the connection to the database.

			$this->db_host = $db_host;
			$this->db_port = $db_port;
			$this->db_user = $db_user;
			$this->db_pass = $db_pass;
			$this->db_name = $db_name;

			if (!$this->connection = mysql_connect($this->db_host . ($this->db_port ? ':' . $this->db_port : ''), $this->db_user, $this->db_pass)) {
				$this->messages .= "ER: Could not connect to database server.\n";
				return false;
			};
			
			if (!mysql_select_db($this->db_name, $this->connection)) {
				$this->messages .= "ER: Could not select database.\n";
				return false;
			};
			
			$this->messages .= "OK: Database connection successful.\n";
			return true;

		}

		/* ---------------------------------------------------------------------------------------- */
		
		function db_close() {
			
			// Close the database connection.
			
			if ($result = mysql_close($this->connection)) {
				$this->messages .= "OK: Database connection closed.\n";
				return $result;
			}
			else {
				return $result;
			}
			
		}

		/* ---------------------------------------------------------------------------------------- */

		function run_query($query) {

			// Run a query against the database and return an array of the results.

			$query_results = array();

			$result = mysql_query($query, $this->connection);

			while ($row = mysql_fetch_assoc($result)) {
				$query_results[] = $row;
			}

			return $query_results;

		}

		/* ---------------------------------------------------------------------------------------- */

		function new_query($query) {

			return mysql_query($query, $this->connection);

		}

		/* ---------------------------------------------------------------------------------------- */

		function fetch_row_assoc($result) {

			return mysql_fetch_assoc($result);

		}

		/* ---------------------------------------------------------------------------------------- */
		
		function num_rows($result) {
			
			// return the number of rows returned by the query.
			
			return mysql_num_rows($result);
			
		}

		/* ---------------------------------------------------------------------------------------- */

		function html_table($query) {

			// run the query and output the results as a HTML table.

			$results = $this->run_query($query);

			/* start with the headers */
			$table = '<table><tr>';

			foreach ($results[0] as $key => $column) {
				$table .= "<th>$key</th>";
			}

			$table .= '</tr>';

			/* now create the actual rows */
			foreach ($results as $row) {
				$table .= '<tr>';
				foreach ($row as $key => $value) {
					$table .= "<td>$value</td>";
				}
				$table .= '</tr>';
			}

			/* close the table */
			$table .= '</table>';

			return $table;
		}

	}

	/* ---------------------------------------------------------------------------------------- */

	function escape_array(&$item, $key = '') {
		if (get_magic_quotes_gpc()) {
			$item = stripslashes($item);
		}
		$item = mysql_escape_string($item);
	}

?>
