<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('phpass-0.3/PasswordHash.php');

define('PHPASS_HASH_STRENGTH', 8);
define('PHPASS_HASH_PORTABLE', false);

/**
 * SimpleLoginSecure Class
 *
 * Makes authentication simple and secure.
 *
 * Simplelogin expects the following database setup. If you are not using 
 * this setup you may need to do some tweaking.
 *   
 * 
 *   CREATE TABLE `users` (
 *     `user_id` int(10) unsigned NOT NULL auto_increment,
 *     `user_email` varchar(255) NOT NULL default '',
 *     `user_pass` varchar(60) NOT NULL default '',
 *     `user_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Creation date',
 *     `user_modified` datetime NOT NULL default '0000-00-00 00:00:00',
 *     `user_last_login` datetime NULL default NULL,
 *     PRIMARY KEY  (`user_id`),
 *     UNIQUE KEY `user_email` (`user_email`),
 *   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 * 
 * @package   SimpleLoginSecure
 * @version   2.1.1
 * @author    Stéphane Bourzeix, Pixelmio <stephane[at]bourzeix.com>
 * @copyright Copyright (c) 2012-2013, Stéphane Bourzeix
 * @license   http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      https://github.com/DaBourz/SimpleLoginSecure
 */
class SimpleLoginSecure
{
	var $CI;
	var $user_table = 'user';
	var $user_table_ex = 'user_ex';
	var $user_group = 'user_group';

	/**
	 * Create a user account
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function create($email = '', $user_pass = '') 
	{
		$this->CI =& get_instance();
		
		//Make sure account info was sent
		if($email == '' OR $user_pass == '') {
			return false;
		}
		
		//Check against user table
		$this->CI->db->where('email', $email); 
		$query = $this->CI->db->get_where($this->user_table);
		
		if ($query->num_rows() > 0) //user_email already exists
			return false;

		//Hash user_pass using phpass
		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
		$user_pass_hashed = $hasher->HashPassword($user_pass);
		
		//Insert account into the database
		$data = array(
					'email' => $email,
					'user_pass' => $user_pass_hashed,
					'CreateDate' => date('c'),
					'user_last_login' => date('c'),
				);

		$this->CI->db->set($data); 

		if(!$this->CI->db->insert($this->user_table)) //There was a problem! 
		return false;					
	}

	/**
	 * Update a user account
	 *
	 * Only updates the email, just here for you can 
	 * extend / use it in your own class.
	 *
	 * @access	public
	 * @param integer
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function update($user_id = null, $user_email = '', $auto_login = true) 
	{
		$this->CI =& get_instance();

		//Make sure account info was sent
		if($user_id == null OR $user_email == '') {
			return false;
		}
		
		//Check against user table
		$this->CI->db->where('user_id', $user_id);
		$query = $this->CI->db->get_where($this->user_table);
		
		if ($query->num_rows() == 0){ // user don't exists
			return false;
		}
		
		//Update account into the database
		$data = array(
					'user_email' => $user_email,
					'user_modified' => date('c'),
				);
 
		$this->CI->db->where('user_id', $user_id);

		if(!$this->CI->db->update($this->user_table, $data)) //There was a problem! 
			return false;						
				
		if($auto_login){
			$user_data['user_email'] = $user_email;
			$user_data['user'] = $user_data['user_email']; // for compatibility with Simplelogin
			
			$this->CI->session->set_userdata($user_data);
			}
		return true;
	}

	/**
	 * Login and sets session variables
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	bool
	 **/
	function login($email = '', $user_pass = '') 
	{
		//create($email,$userpass);
		$this->CI = & get_instance();

		if($email == '' OR $user_pass == '')
			return false;
		
		//Check if already logged in
		if($this->CI->session->userdata('user_email') == $email)
			return true;
				
		//Check against user table
		$this->CI->db->where('email', $email);
		$this->CI->db->where('email !=', '');
		$this->CI->db->or_where('userid', $email);
		
		$query = $this->CI->db->get_where($this->user_table);
		
		if ($query->num_rows() > 0) 
		{
			$user_data = $query->row_array();
			
			$this->CI->db->where('userid', $user_data['define_2']);
		
			$query2 = $this->CI->db->get_where($this->user_table);
			
			$user_data2 = $query2->row_array();
			
			$user_data['supervisor'] = $user_data['define_2'];
			
			$user_data['supervisor_name'] = $user_data2['Name']. " " . $user_data2['lastname'];
			
			$user_data['ex'] = 0;
			//$user_data['define_3'] = $user_data['define_3'];
			
			 
			$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
			//$user_pass_hashed = $hasher->HashPassword($user_pass);
			//$user_pass_hashed = $hasher->HashPassword($user_pass);
			//echo '<script type="text/javascript">alert("' . $user_pass_hashed . '")</script>';
			if(!$hasher->CheckPassword($user_pass, $user_data['user_pass']))
				return false;

			//Destroy old session
			$this->CI->session->sess_destroy();
			
			//Create a fresh, brand new session
			$this->CI->session->sess_create();

			$this->CI->db->simple_query('UPDATE ' . $this->user_table  . ' SET user_last_login = "' . date('Y-m-d H:i:s') . '" WHERE userid = ' . $user_data['userid']);

			//Set session data
			unset($user_data['user_pass']);
			$user_data['logged_in'] = true;
			$this->CI->session->sess_expiration = '14400';// expires in 4 hours
			$this->CI->session->set_userdata($user_data);
			

			
			return true;
		} 
		else 
		{
			
			//return false;
			$this->CI->db->where('email', $email);
			$this->CI->db->where('email !=', '');
			$this->CI->db->or_where('userid', $email);
			
			$query = $this->CI->db->get_where($this->user_table_ex);
			
			if ($query->num_rows() > 0) 
			{
				$user_data = $query->row_array();
				
				$this->CI->db->where('userid', $user_data['define_2']);
			
				$query2 = $this->CI->db->get_where($this->user_table);
				
				$user_data2 = $query2->row_array();
				
				$user_data['supervisor'] = $user_data['define_2'];
				
				$user_data['supervisor_name'] = $user_data2['Name']. " " . $user_data2['lastname'];
				
				$user_data['ex'] = 1;
				
				 
				$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);

				if(!$hasher->CheckPassword($user_pass, $user_data['user_pass']))
					return false;
	
				//Destroy old session
				$this->CI->session->sess_destroy();
				
				//Create a fresh, brand new session
				$this->CI->session->sess_create();
	
				$this->CI->db->simple_query('UPDATE ' . $this->user_table_ex  . ' SET user_last_login = "' . date('Y-m-d H:i:s') . '" WHERE userid = ' . $user_data['userid']);
	
				//Set session data
				unset($user_data['user_pass']);
				$user_data['logged_in'] = true;
				$this->CI->session->sess_expiration = '14400';// expires in 4 hours
				$this->CI->session->set_userdata($user_data);
				return true;
			} 
			else 
			{			
				return false;
			}
		}	

	}

	/**
	 * Logout user
	 *
	 * @access	public
	 * @return	void
	 */
	function logout() {
		$this->CI =& get_instance();		
		$this->CI->session->sess_destroy();
	}

	/**
	 * Delete user
	 *
	 * @access	public
	 * @param integer
	 * @return	bool
	 */
	function delete($user_id) 
	{
		$this->CI =& get_instance();
		
		if(!is_numeric($user_id))
			return false;			

		return $this->CI->db->delete($this->user_table, array('user_id' => $user_id));
	}
	
	
	/**
	* Edit a user password
	* @author    Stéphane Bourzeix, Pixelmio <stephane[at]bourzeix.com>
	* @author    Diego Castro <castroc.diego[at]gmail.com>
	*
	* @access  public
	* @param  string
	* @param  string
	* @param  string
	* @return  bool
	*/
	function edit_password($user_email = '', $old_pass = '', $new_pass = '')
	{
		$this->CI =& get_instance();
		// Check if the password is the same as the old one
		$this->CI->db->select('user_pass');
		$query = $this->CI->db->get_where($this->user_table, array('email' => $user_email));
		if($this->CI->session->userdata('ex') == 1){
			$query = $this->CI->db->get_where($this->user_table_ex, array('email' => $user_email));
		}
		$user_data = $query->row_array();

		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);	
		if (!$hasher->CheckPassword($old_pass, $user_data['user_pass'])){ //old_pass is the same
			return FALSE;
		}
		
		// Hash new_pass using phpass
		$user_pass_hashed = $hasher->HashPassword($new_pass);
		// Insert new password into the database
		$data = array(
			'user_pass' => $user_pass_hashed,
			'user_modified' => date('Y-m-d H:i:s')
		);
		
		$this->CI->db->set($data);
		$this->CI->db->where('email', $user_email);
		if($this->CI->session->userdata('ex') != 1){
			if(!$this->CI->db->update($this->user_table, $data)){ // There was a problem!
				return FALSE;
			} else {
				return TRUE;
			}
		}
		elseif($this->CI->session->userdata('ex') == 1){
			if(!$this->CI->db->update($this->user_table_ex, $data)){ // There was a problem!
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}
	
}
?>
