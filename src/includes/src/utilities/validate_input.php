<?php # Uses regular expressions to validate user inputs

	/* 	
	 *	FIRST NAMES, LAST NAMES, LOCATION:
	 *	Reg expression makes sure first names contains only letters,
	 *	spaces, apostrophes, periods and dashes. i modifier = case insensitive
	 *
	 *	EMAIL:
	 *	Reg Expression makes sure email addresses start with 1 or more 
	 *	alphanumeric characters, periods or dashes (username) followed by an @ symboml
	 *	with another string of 1 or more alphanumeric characters, periods or dashes (domain/subdomain)
	 *	followed by a period then by 2 or 8 letters (top level domain)
	 *
	 *	PASSWORD:
	 *	Reg Expression makes sure passwords only contains a-z, A-Z, 0-9 and _ (underscore)
	 *	and limits passwords to between 4 and 20 characters
	 */
	
function validate_input($input, $input_type=NULL){
	
	switch($input_type){
		case 'first_name':
			return preg_match('/^[A-Z\'.-]{2,20}$/i', $input);
			break;
		case 'last_name':
			return preg_match('/^[A-Z\'.-]{2,40}$/i', $input);
			break;
		case 'email':
			return preg_match('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,8}$/', $input);
			break;
		case 'password':
			return preg_match('/^\w{4,20}$/', $input);
			break;
		case 'profile_link':
			return preg_match('/^[a-z0-9_.-]{4,100}$/', $input);
			break;
		case 'location':
			return preg_match('|^[A-Z /\',.-]{2,40}$|i', $input);
			break;
		case 'group_name':
			return preg_match('/^[A-Z0-9 \'.)(&-]{2,100}$/i', $input);
			break;
		default:
			return preg_match('|^[\w /\',.)(:+?&-=]+$|', $input);
			break;
	}
	return;
}

?>