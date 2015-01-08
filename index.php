<?php

$repetitions = 10000;

function getmicrotime() {

	$t = gettimeofday();

	return $t[ 'sec' ] * 1000 + $t[ 'usec' ] / 1000;
}

function bench() {

	static $start;
	if ( ! $start ) {
		$start = getmicrotime();

		return NULL;
	}
	$duration = getmicrotime() - $start;
	$start    = 0;

	return $duration;
}

function ms( $ms ) {

	echo '<td>';
	if ( $ms <= 0 ) {
		echo 0;
	} else if ( $ms < 0.5 ) {
		echo '&gt;0';
	} else {
		echo (int) round( $ms );
	}
	echo '&nbsp;ms</td>';
}

function display_bench_results() {

	$html = ob_get_clean();
	preg_match_all( '/>\{([^}]+)\}/is', $html, $matches );
	$min = 0;
	$sum = 0;

	foreach ( $matches[ 1 ] as $i => $s ) {

		if ( $min <= 0 ) {
			$min = floatval( $s );
		} else {
			$min = min( $min, floatval( $s ) );
		}

		$sum += floatval( $s );
	}

	foreach ( $matches[ 1 ] as $i => $s ) {

		if ( 0 === $min ) {
			$min = 1;
		}

		$index = (int) round( floatval( $s ) * 100 / $min );

		if ( $index > 5000 ) {
			$class = 'no';
		} elseif ( $index > 500 ) {
			$class = 'buggy';
		} elseif ( $index > 200 ) {
			$class = 'incomplete';
		} elseif ( $index > 100 ) {
			$class = 'almost';
		} else {
			$class = 'yes';
		}

		$html = str_replace(
			$matches[ 0 ][ $i ],
			' class="' . $class . '">' . $index, $html
		);
	}

	echo $html;
	//echo "Total: " . round( $sum) . " ms";
}

//------------------------------------------------------------------------------

function strcmp_bench( $method ) {

	$r = (int) round( $GLOBALS[ 'repetitions' ] );

	bench();
	$caption = $method( "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz", $r );
	$d       = bench();
	$sum     = $d;
	echo '<tr><td><code>' . $caption . '</code></td>';
	ms( $d );

	bench();
	$method( "0bcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz", $r );
	$d = bench();
	$sum += $d;
	ms( $d );

	bench();
	$method( "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxy0", $r );
	$d = bench();
	$sum += $d;
	ms( $d );

	ms( $sum );
	echo '<td>{' . $sum . '}</td></tr>';
}

function strcmp_method1( $var, $r ) {

	$equal = FALSE;
	$b     = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz";
	while ( $r -- ) {
		$equal = $var == $b;
	}

	return '$a == $b';
}

function strcmp_method2( $var, $r ) {

	$equal = FALSE;
	$b     = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz";
	while ( $r -- ) {
		$equal = ! strcmp( $var, $b );
	}

	return '!strcmp( $a, $b )';
}

function strcmp_method3( $var, $r ) {

	$equal = FALSE;
	$b     = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz";
	while ( $r -- ) {
		$equal = strcmp( $var, $b ) == 0;
	}

	return 'strcmp( $a, $b ) == 0';
}

function strcmp_method4( $var, $r ) {

	$equal = FALSE;
	$b     = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz";
	while ( $r -- ) {
		$equal = strcmp( $var, $b ) === 0;
	}

	return 'strcmp( $a, $b ) === 0';
}

function strcmp_method5( $var, $r ) {

	$equal = FALSE;
	$b     = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz";
	while ( $r -- ) {
		$equal = strcasecmp( $var, $b ) === 0;
	}

	return 'strcasecmp( $a, $b ) === 0';
}

//------------------------------------------------------------------------------

function array_get_bench( $method ) {

	$array = array();
	for ( $i = 0; $i < 100; $i ++ ) {
		$array[ $i ]         = "i" . $i;
		$array[ "key" . $i ] = "s" . $i;
	}
	$r = (int) round( $GLOBALS[ 'repetitions' ] / 4 );

	bench();
	$caption = $method( $array, $r );
	$d       = bench();
	echo '<tr><td><code>' . $caption . '</code></td>';
	ms( $d );
	echo '<td>{' . $d . '}</td></tr>';
}

function array_get_method1( $array, $r ) {

	while ( $r -- ) {
		for ( $i = 0; $i < 100; $i ++ ) {
			$result = $array[ $i ];
		}
	}

	return '$array[0]';
}

function array_get_method2( $array, $r ) {

	while ( $r -- ) {
		for ( $i = 0; $i < 100; $i ++ ) {
			$result = $array[ $i ];
		}
	}

	return '$array[\'key\']';
}

//------------------------------------------------------------------------------

function empty_bench( $method ) {

	$r = (int) round( $GLOBALS[ 'repetitions' ] / 3 );

	bench();
	$caption = $method(
		- 1,
		$r
	);
	$d       = bench();
	$sum     = $d;
	echo '<tr><td><code>' . $caption . '</code></td>';
	ms( $d );

	bench();
	$method(
		NULL,
		$r
	);
	$d = bench();
	$sum += $d;
	ms( $d );

	bench();
	$method(
		FALSE,
		$r
	);
	$d = bench();
	$sum += $d;
	ms( $d );

	bench();
	$method(
		"",
		$r
	);
	$d = bench();
	$sum += $d;
	ms( $d );

	bench();
	$method(
		"0",
		$r
	);
	$d = bench();
	$sum += $d;
	ms( $d );

	bench();
	$method(
		"1",
		$r
	);
	$d = bench();
	$sum += $d;
	ms( $d );

	bench();
	$method(
		"12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890",
		$r
	);
	$d = bench();
	$sum += $d;
	ms( $d );

	ms( $sum );
	echo '<td>{' . $sum . '}</td></tr>';
}

function empty_method1( $var, $r ) {

	if ( $var < 0 ) {
		unset( $var );
	}

	if ( ! isset( $var ) ) {
		$var = FALSE;
	}

	$isEmpty = FALSE;
	while ( $r -- ) {
		if ( ! $var ) {
			$isEmpty = TRUE;
		}
	}

	//if (empty( $var) != $isEmpty) var_dump( $var, 'if (!$var)');
	return 'if ( ! $var )';
}

function empty_method2( $var, $r ) {

	if ( $var < 0 ) {
		unset( $var );
	}
	$isEmpty = FALSE;
	while ( $r -- ) {
		if ( empty( $var ) ) {
			$isEmpty = TRUE;
		}
	}

	//if (empty( $var) != $isEmpty) var_dump( $var, 'if (empty( $var) )');
	return 'if (empty( $var) )';
}

function empty_method3( $var, $r ) {

	if ( $var < 0 ) {
		unset( $var );
	}

	if ( ! isset( $var ) ) {
		$var = FALSE;
	}

	$isEmpty = FALSE;
	while ( $r -- ) {
		if ( $var == "" ) {
			$isEmpty = TRUE;
		}
	}

	//if (empty( $var) != $isEmpty) var_dump( $var, 'if ( $var == "" )');
	return 'if ( $var == "" )';
}

function empty_method4( $var, $r ) {

	if ( $var < 0 ) {
		unset( $var );
	}

	if ( ! isset( $var ) ) {
		$var = FALSE;
	}

	$isEmpty = FALSE;
	while ( $r -- ) {
		if ( "" == $var ) {
			$isEmpty = TRUE;
		}
	}

	//if (empty( $var) != $isEmpty) var_dump( $var, 'if ( "" == $var)');
	return 'if ( "" == $var )';
}

function empty_method5( $var, $r ) {

	if ( $var < 0 ) {
		unset( $var );
	}

	if ( ! isset( $var ) ) {
		$var = FALSE;
	}

	$isEmpty = FALSE;
	while ( $r -- ) {
		if ( $var === "" ) {
			$isEmpty = TRUE;
		}
	}

	//if (empty( $var) != $isEmpty) var_dump( $var, 'if ( $var === "" )');
	return 'if ( $var === "" )';
}

function empty_method6( $var, $r ) {

	if ( $var < 0 ) {
		unset( $var );
	}

	if ( ! isset( $var ) ) {
		$var = FALSE;
	}

	$isEmpty = FALSE;
	while ( $r -- ) {
		if ( "" === $var ) {
			$isEmpty = TRUE;
		}
	}

	//if (empty( $var) != $isEmpty) var_dump( $var, 'if ( "" === $var)');
	return 'if ( "" === $var )';
}

function empty_method7( $var, $r ) {

	if ( $var < 0 ) {
		unset( $var );
	}

	if ( ! isset( $var ) ) {
		$var = FALSE;
	}

	$isEmpty = FALSE;
	while ( $r -- ) {
		if ( strcmp( $var, "" ) == 0 ) {
			$isEmpty = TRUE;
		}
	}

	//if (empty( $var) != $isEmpty) var_dump( $var, 'if (strcmp( $var, "" ) == 0)');
	return 'if ( strcmp( $var, "" ) == 0 )';
}

function empty_method8( $var, $r ) {

	if ( $var < 0 ) {
		unset( $var );
	}

	if ( ! isset( $var ) ) {
		$var = FALSE;
	}

	$isEmpty = FALSE;
	while ( $r -- ) {
		if ( strcmp( "", $var ) == 0 ) {
			$isEmpty = TRUE;
		}
	}

	//if (empty( $var) != $isEmpty) var_dump( $var, 'if (strcmp( "", $var) == 0)');
	return 'if ( strcmp( "", $var ) == 0 )';
}

function empty_method9( $var, $r ) {

	if ( $var < 0 ) {
		unset( $var );
	}

	if ( ! isset( $var ) ) {
		$var = FALSE;
	}

	$isEmpty = FALSE;
	while ( $r -- ) {
		if ( strlen( $var ) == 0 ) {
			$isEmpty = TRUE;
		}
	}

	//if (empty( $var) != $isEmpty) var_dump( $var, 'if (strlen( $var) == 0)');
	return 'if ( strlen( $var ) == 0 )';
}

function empty_method10( $var, $r ) {

	if ( $var < 0 ) {
		unset( $var );
	}

	if ( ! isset( $var ) ) {
		$var = FALSE;
	}

	$isEmpty = FALSE;
	while ( $r -- ) {
		if ( ! strlen( $var ) ) {
			$isEmpty = TRUE;
		}
	}

	//if (empty( $var) != $isEmpty) var_dump( $var, 'if (!strlen( $var) )');
	return 'if ( ! strlen( $var ) )';
}

//------------------------------------------------------------------------------

function strstr_bench( $method ) {

	$r = (int) round( $GLOBALS[ 'repetitions' ] / 4 );

	bench();
	$caption = $method(
		"12345678901234567890123456789012345678901234567890123456789012341234567890123456789012345678901234567890123456789012345678901234",
		"abcd", $r
	);
	$d       = bench();
	$sum     = $d;
	echo '<tr><td><code>' . $caption . '</code></td>';
	ms( $d );

	bench();
	$method(
		"abcd5678901234567890123456789012345678901234567890123456789012341234567890123456789012345678901234567890123456789012345678901234",
		"abcd", $r
	);
	$d = bench();
	$sum += $d;
	ms( $d );

	bench();
	$method(
		"12345678901234567890123456789012345678901234567890123456789012abcd34567890123456789012345678901234567890123456789012345678901234",
		"abcd", $r
	);
	$d = bench();
	$sum += $d;
	ms( $d );

	bench();
	$method(
		"1234567890123456789012345678901234567890123456789012345678901234123456789012345678901234567890123456789012345678901234567890abcd",
		"abcd", $r
	);
	$d = bench();
	$sum += $d;
	ms( $d );

	ms( $sum );
	echo '<td>{' . $sum . '}</td></tr>';
}

function strstr_method1( $haystack, $needle, $r ) {

	$found = FALSE;
	while ( $r -- ) {
		if ( strstr( $haystack, $needle ) ) {
			$found = TRUE;
		}
	}

	return 'strstr( $haystack, $needle )';
}

function strstr_method2( $haystack, $needle, $r ) {

	$found = FALSE;
	while ( $r -- ) {
		if ( strpos( $haystack, $needle ) !== FALSE ) {
			$found = TRUE;
		}
	}

	return 'strpos( $haystack, $needle ) !== FALSE';
}

function strstr_method3( $haystack, $needle, $r ) {

	$found = FALSE;
	while ( $r -- ) {
		if ( strstr( $haystack, $needle ) !== FALSE ) {
			$found = TRUE;
		}
	}

	return 'strstr( $haystack, $needle ) !== FALSE';
}

function strstr_method4( $haystack, $needle, $r ) {

	$found = FALSE;
	while ( $r -- ) {
		if ( stristr( $haystack, $needle ) ) {
			$found = TRUE;
		}
	}

	return 'stristr( $haystack, $needle )';
}

function strstr_method5( $haystack, $needle, $r ) {

	$found  = FALSE;
	$regexp = '/' . preg_quote( $needle, '/' ) . '/';
	while ( $r -- ) {
		if ( preg_match( $regexp, $haystack ) ) {
			$found = TRUE;
		}
	}

	return 'preg_match( "/$needle/", $haystack )';
}

function strstr_method6( $haystack, $needle, $r ) {

	$found  = FALSE;
	$regexp = '/' . preg_quote( $needle, '/' ) . '/i';
	while ( $r -- ) {
		if ( preg_match( $regexp, $haystack ) ) {
			$found = TRUE;
		}
	}

	return 'preg_match( "/$needle/i", $haystack )';
}

function strstr_method7( $haystack, $needle, $r ) {

	$found  = FALSE;
	$regexp = '/' . preg_quote( $needle, '/' ) . '/S';
	while ( $r -- ) {
		if ( preg_match( $regexp, $haystack ) ) {
			$found = TRUE;
		}
	}

	return 'preg_match( "/$needle/S", $haystack )';
}

function strstr_method8( $haystack, $needle, $r ) {

	$found = FALSE;
	while ( $r -- ) {
		// Function ereg is deprecated since 5.3
		if ( ereg( $needle, $haystack ) ) {
			$found = TRUE;
		}
	}

	return 'ereg( $needle, $haystack )<br>&middot; Function ereg is deprecated since 5.3';
}

//------------------------------------------------------------------------------

function startsWith_method1( $haystack, $needle, $r ) {

	while ( $r -- ) {
		$result = strncmp( $haystack, $needle, strlen( $needle ) ) === 0;
	}

	return 'strncmp( $haystack, $needle, strlen( $needle ) ) === 0';
}

function startsWith_method2( $haystack, $needle, $r ) {

	if ( empty( $needle ) ) {
		$needle = 'abcd';
	}

	while ( $r -- ) {
		$result = strncmp( $haystack, $needle, 4 ) === 0;
	}

	return 'strncmp( $haystack, $needle, 4 ) === 0';
}

function startsWith_method3( $haystack, $needle, $r ) {

	while ( $r -- ) {
		$result = strncasecmp( $haystack, $needle, strlen( $needle ) ) === 0;
	}

	return 'strncasecmp( $haystack, $needle, strlen( $needle ) ) === 0';
}

function startsWith_method4( $haystack, $needle, $r ) {

	while ( $r -- ) {
		$result = strpos( $haystack, $needle ) === 0;
	}

	return 'strpos( $haystack, $needle ) === 0';
}

function startsWith_method5( $haystack, $needle, $r ) {

	while ( $r -- ) {
		$result = substr( $haystack, 0, strlen( $needle ) ) === $needle;
	}

	return 'substr( $haystack, 0, strlen( $needle ) ) === $needle';
}

function startsWith_method6( $haystack, $needle, $r ) {

	while ( $r -- ) {
		$result = strcmp( substr( $haystack, 0, strlen( $needle ) ), $needle ) === 0;
	}

	return 'strcmp( substr( $haystack, 0, strlen( $needle ) ), $needle ) === 0';
}

function startsWith_method7( $haystack, $needle, $r ) {

	while ( $r -- ) {
		$result = preg_match( "/^" . preg_quote( $needle, "/" ) . "/", $haystack );
	}

	return 'preg_match( "/^" . preg_quote( $needle, "/" ) . "/", $haystack )';
}

//------------------------------------------------------------------------------

function endsWith_method1( $haystack, $needle, $r ) {

	while ( $r -- ) {
		$result = substr( $haystack, strlen( $haystack ) - strlen( $needle ) ) === $needle;
	}

	return 'substr( $haystack, strlen( $haystack ) - strlen( $needle) ) === $needle';
}

function endsWith_method2( $haystack, $needle, $r ) {

	while ( $r -- ) {
		$result = substr( $haystack, - strlen( $needle ) ) === $needle;
	}

	return 'substr( $haystack, -strlen( $needle) ) === $needle';
}

function endsWith_method3( $haystack, $needle, $r ) {

	while ( $r -- ) {
		$result = strcmp( substr( $haystack, - strlen( $needle ) ), $needle ) === 0;
	}

	return 'strcmp( substr( $haystack, - strlen( $needle) ), $needle) === 0';
}

function endsWith_method4( $haystack, $needle, $r ) {

	while ( $r -- ) {
		$result = preg_match( "/" . preg_quote( $needle, "/" ) . "$/", $haystack );
	}

	return 'preg_match( "/" . preg_quote( $needle, "/" ) . "$/", $haystack )';
}

//------------------------------------------------------------------------------

function strreplace_method1( $subject, $search, $r ) {

	$replace = $search;
	while ( $r -- ) {
		$result = str_replace( $search, $replace, $subject );
	}

	return 'str_replace( $search, $replace, $subject )';
}

function strreplace_method2( $subject, $search, $r ) {

	$replace = $search;
	$regexp  = '/' . preg_quote( $search, '/' ) . '/';
	while ( $r -- ) {
		$result = preg_replace( $regexp, $replace, $subject );
	}

	return 'preg_replace( "/$search/", $replace, $subject )';
}

function strreplace_method3( $subject, $search, $r ) {

	$replace = $search;
	$regexp  = '/' . preg_quote( $search, '/' ) . '/S';
	while ( $r -- ) {
		$result = preg_replace( $regexp, $replace, $subject );
	}

	return 'preg_replace( "/$search/S", $replace, $subject )';
}

function strreplace_method4( $subject, $search, $r ) {

	$replace = $search;
	while ( $r -- ) {
		// Function ereg_replace is deprecated since 5.3
		$result = ereg_replace( $search, $replace, $subject );
	}

	return "ereg_replace( $search, $replace, $subject )<br>&middot; Function ereg_replace is deprecated since 5.3";
}

//------------------------------------------------------------------------------

function trim_bench( $method ) {

	$r = (int) round( $GLOBALS[ 'repetitions' ] / 30 );

	bench();
	$caption = $method(
		"1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890",
		$r
	);
	$d       = bench();
	$sum     = $d;
	echo '<tr><td><code>' . $caption . '</code></td>';
	ms( $d );

	bench();
	$method(
		",,,,,,,,,,,,,,,,,,,,1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890",
		$r
	);
	$d = bench();
	$sum += $d;
	ms( $d );

	bench();
	$method(
		"1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890,,,,,,,,,,,,,,,,,,,,",
		$r
	);
	$d = bench();
	$sum += $d;
	ms( $d );

	bench();
	$method(
		",,,,,,,,,,,,,,,,,,,,1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890\n1234567890,,,,,,,,,,,,,,,,,,,,",
		$r
	);
	$d = bench();
	$sum += $d;
	ms( $d );

	ms( $sum );
	echo '<td>{' . $sum . '}</td></tr>';
}

function trim_method1( $string, $r ) {

	while ( $r -- ) {
		$string = trim( $string, "," );
	}

	return 'trim( $string, "," )';
}

function trim_method2( $string, $r ) {

	while ( $r -- ) {
		$string = preg_replace( '/^,*|,*$/', "", $string );
	}

	return 'preg_replace( \'/^,*|,*$/\', "", $string )';
}

function trim_method3( $string, $r ) {

	while ( $r -- ) {
		$string = preg_replace( '/^,*|,*$/m', "", $string );
	}

	return 'preg_replace( \'/^,*|,*$/m\', "", $string )';
}

function trim_method4( $string, $r ) {

	while ( $r -- ) {
		$string = preg_replace( '/^,+|,+$/', "", $string );
	}

	return 'preg_replace( \'/^,+|,+$/\', "", $string )';
}

function trim_method5( $string, $r ) {

	while ( $r -- ) {
		$string = preg_replace( '/^,+|,+$/m', "", $string );
	}

	return 'preg_replace( \'/^,+|,+$/m\', "", $string )';
}

function trim_method6( $string, $r ) {

	while ( $r -- ) {
		$string = preg_replace( '/^,+/', "", preg_replace( '/,+$/', "", $string ) );
	}

	return 'preg_replace( \'/^,+/\', "", preg_replace( \'/,+$/\', "", &hellip; ) )';
}

//------------------------------------------------------------------------------

function split_bench( $method ) {

	$r = (int) round( $GLOBALS[ 'repetitions' ] / 5 );

	bench();
	$caption = $method(
		"",
		$r
	);
	$d       = bench();
	$sum     = $d;
	echo '<tr><td><code>' . $caption . '</code></td>';
	ms( $d );

	bench();
	$method(
		"1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890",
		$r
	);
	$d = bench();
	$sum += $d;
	ms( $d );

	bench();
	$method(
		"12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890,12345678901234567890",
		$r
	);
	$d = bench();
	$sum += $d;
	ms( $d );

	ms( $sum );
	echo '<td>{' . $sum . '}</td></tr>';
}

function split_method1( $string, $r ) {

	while ( $r -- ) {
		$array = explode( ",", $string );
	}

	return 'explode( ",", $string )';
}

function split_method2( $string, $r ) {

	while ( $r -- ) {
		// Function split is deprecated since 5.3
		$array = split( ",", $string );
	}

	return 'split( ",", $string )<br>&middot; Function split is deprecated since 5.3';
}

function split_method3( $string, $r ) {

	while ( $r -- ) {
		$array = preg_split( "/,/", $string );
	}

	return 'preg_split( "/,/", $string )';
}

function split_method4( $string, $r ) {

	while ( $r -- ) {
		preg_match_all( '/[^,]+/', $string, $matches );
		$array = $matches[ 0 ];
	}

	return 'preg_match_all( \'/[^,]+/\', $string, $matches )';
}

//------------------------------------------------------------------------------

function loop_bench( $method ) {

	$array = array();
	$i     = 128;
	while ( $i -- ) {
		$array[ ] = "abcd";
	}
	reset( $array );

	$r = (int) round( $GLOBALS[ 'repetitions' ] / 200 );

	bench();
	$caption = $method( $array, $r );
	$sum     = bench();
	echo '<tr><td><code>' . $caption . '</code></td>';
	ms( $sum );
	echo '<td>{' . $sum . '}</td></tr>';
}

function loop_method1( &$array, $r ) {

	$found = FALSE;
	while ( $r -- ) {
		for ( $i = 0; $i < count( $array ); $i ++ ) {
			$found = TRUE;
		}
	}

	return 'for ( $i = 0; $i < count( $array ; $i++ )';
}

function loop_method2( &$array, $r ) {

	$found = FALSE;
	while ( $r -- ) {
		for ( $i = 0, $count = count( $array ); $i < $count; $i ++ ) {
			$found = TRUE;
		}
	}

	return 'for ( $i = 0, $count = count( $array ); $i < $count; $i++ )';
}

function loop_method3( &$array, $r ) {

	$found = FALSE;
	while ( $r -- ) {
		for ( $i = count( $array ) - 1; $i >= 0; $i -- ) {
			$found = TRUE;
		}
	}

	return 'for ( $i = count( $array ) - 1; $i >= 0; $i-- )';
}

function loop_method4( &$array, $r ) {

	$found = FALSE;
	while ( $r -- ) {
		for ( $i = count( $array ) - 1; $i >= 0; -- $i ) {
			$found = TRUE;
		}
	}

	return 'for ( $i = count( $array ) - 1; $i >= 0; --$i )';
}

function loop_method5( &$array, $r ) {

	$found = FALSE;
	while ( $r -- ) {
		$i = count( $array );
		while ( $i -- ) {
			$found = TRUE;
		}
	}

	return '$i = count( $array ); while ( $i-- )';
}

//------------------------------------------------------------------------------

function concat_bench( $method ) {

	$r = (int) round( $GLOBALS[ 'repetitions' ] );
	bench();
	$caption = $method( $r );
	$sum     = bench();
	echo '<tr><td><code>' . $caption . '</code></td>';
	ms( $sum );
	echo '<td>{' . $sum . '}</td></tr>';
}

function concat_method1( $r ) {

	$array = array( "mediumLengthExampleString", "mediumLengthExampleString", "mediumLengthExampleString" );
	while ( $r -- ) {
		$string = implode( " ", $array );
	}

	return 'implode( " ", $array )';
}

function concat_method2( $r ) {

	$array = array( "mediumLengthExampleString", "mediumLengthExampleString", "mediumLengthExampleString" );
	while ( $r -- ) {
		$string = "$array[0] $array[1] $array[2]";
	}

	return '"$array[0] $array[1] $array[2]"';
}

function concat_method3( $r ) {

	$array = array( "mediumLengthExampleString", "mediumLengthExampleString", "mediumLengthExampleString" );
	while ( $r -- ) {
		$string = $array[ 0 ] . " " . $array[ 1 ] . " " . $array[ 2 ];
	}

	return '$array[0] . " " . $array[1] . " " . $array[2]';
}

function concat_method4( $r ) {

	$array = array( "mediumLengthExampleString", "mediumLengthExampleString", "mediumLengthExampleString" );
	while ( $r -- ) {
		$string = sprintf( "%s %s %s", $array[ 0 ], $array[ 1 ], $array[ 2 ] );
	}

	return 'sprintf( "%s %s %s", $array[0], $array[1], $array[2] )';
}

function concat_method5( $r ) {

	$array = array( "mediumLengthExampleString", "mediumLengthExampleString", "mediumLengthExampleString" );
	while ( $r -- ) {
		$string = vsprintf( "%s %s %s", $array );
	}

	return 'vsprintf( "%s %s %s", $array )';
}

//------------------------------------------------------------------------------

function quotes_bench( $method ) {

	$r = (int) round( $GLOBALS[ 'repetitions' ] / 2 );
	bench();
	$caption = $method( $r );
	$sum     = bench();
	echo '<tr><td><code>' . $caption . '</code></td>';
	ms( $sum );
	echo '<td>{' . $sum . '}</td></tr>';
}

function quotes_method1( $r ) {

	while ( $r -- ) {
		$string = '12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678';
	}

	return '\'contains no dollar signs\'';
}

function quotes_method2( $r ) {

	while ( $r -- ) {
		$string = "12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678";
	}

	return '"contains no dollar signs"';
}

function quotes_method3( $r ) {

	while ( $r -- ) {
		$string = '1234567890 $a 1234567890 $a 1234567890 $a 1234567890 $a 1234567890 $a 1234567890 $a 1234567890 $a 1234567890 $a 1234567890 $a 12';
	}

	return '\'$variables $are $not $replaced\'';
}

function quotes_method4( $r ) {

	while ( $r -- ) {
		$string = "1234567890 \$a 1234567890 \$a 1234567890 \$a 1234567890 \$a 1234567890 \$a 1234567890 \$a 1234567890 \$a 1234567890 \$a 1234567890 \$a 12";
	}

	return '"\\$variables \\$are \\$not \\$replaced"';
}

function quotes_method5( $r ) {

	$a = '$a';
	while ( $r -- ) {
		$string = "1234567890 $a 1234567890 $a 1234567890 $a 1234567890 $a 1234567890 $a 1234567890 $a 1234567890 $a 1234567890 $a 1234567890 $a 12";
	}

	return '"$variables $are $replaced"';
}

function quotes_method6( $r ) {

	$a = '$a';
	while ( $r -- ) {
		$string = '1234567890 ' . $a . ' 1234567890 ' . $a . ' 1234567890 ' . $a . ' 1234567890 ' . $a . ' 1234567890 ' . $a . ' 1234567890 ' . $a . ' 1234567890 ' . $a . ' 1234567890 ' . $a . ' 1234567890 ' . $a . ' 12';
	}

	return '$variables . \' \' . $are . \' \' . $replaced';
}

function quotes_method7( $r ) {

	$a = '$a';
	while ( $r -- ) {
		$string = "1234567890 " . $a . " 1234567890 " . $a . " 1234567890 " . $a . " 1234567890 " . $a . " 1234567890 " . $a . " 1234567890 " . $a . " 1234567890 " . $a . " 1234567890 " . $a . " 1234567890 " . $a . " 12";
	}

	return '$variables . " " . $are . " " . $replaced';
}

//------------------------------------------------------------------------------

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>My Collection of PHP Performance Benchmarks</title>
	<style type="text/css">
		body {
			background: #FFF;
			color: #333;
			font: 12px Verdana, sans-serif;
		}

		h1, h2, h3 {
			color: #000;
			font-family: "Lucida Sans", "Lucida Sans Unicode", Verdana, sans-serif;
			margin: 1.5em 0 0.5em;
			padding: 0;
			text-shadow: #CCC 2px 2px 4px;
		}

		h1 {
			margin-top: 0
		}

		address {
			font-style: normal;
			margin: 1.5em 0;
			text-align: right;
		}

		a {
			border-bottom: 1px dashed #BBB;
			color: #000;
			font-weight: bold;
			text-decoration: none;
		}

		a:hover {
			border: 0;
			color: #00F;
			text-decoration: underline;
		}

		table {
			border-collapse: separate;
			border-spacing: 1px;
			empty-cells: show;
		}

		tr:hover {
			background: #EEF
		}

		th, td {
			padding: 0.2em 1em;
			text-align: left;
			vertical-align: top;
		}

		th {
			background: #777;
			color: #FFF;
			padding: 0.4em 1em;
		}

		td {
			border-bottom: 1px solid #DDD
		}

		td + td {
			text-align: right
		}

		td a {
			border: 0
		}

		.yes {
			background: #00882D;
			border: 0;
			color: #FFF;
		}

		.almost {
			background: #40A662;
			border: 0;
			color: #FFF;
		}

		.incomplete {
			border: 1px solid #00882D;
			color: #00882D;
		}

		.buggy {
			background: #DA4C57;
			border: 0;
			color: #FFF;
		}

		.no {
			background: #CB000F;
			border: 0;
			color: #FFF;
		}
	</style>
</head>
<body>

<h1>My PHP Performance Benchmarks</h1>

<p>PHP version <?php echo phpversion(); ?> is running on this server.

<p>Please note that these are micro benchmarks. Micro benchmarks are stupid.
	I created this comparison to learn something about PHP and how the PHP compiler works.
	This can not be used to compare PHP versions or servers.</p>

<h2>Check if a String is empty</h2>
<table>
	<tr>
		<th>Method</th>
		<th>Undefined</th>
		<th>Null</th>
		<th>False</th>
		<th>Empty string</th>
		<th>String "0"</th>
		<th>String "1"</th>
		<th>Long string</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<?php
	ob_start();
	for ( $i = 1; ; $i ++ ) {
		$method = 'empty_method' . $i;
		if ( ! function_exists( $method ) ) {
			break;
		}
		empty_bench( $method );
	}
	display_bench_results();
	?>
</table>
<p>My conclusion:
	In most cases, use <code>empty()</code> because it does not trigger a warning when used with undefined variables.
	Note that <code>empty( "0" )</code> returns true.
	Use <code>strlen()</code> if you want to detect <code>"0"</code>.
	Try to avoid <code>==</code> at all because it may cause strange behaviour
	(e.g. <code>"9a" == 9</code> returns true).
	Prefer <code>===</code> over <code>==</code> and <code>!==</code> over <code>!=</code> if possible
	because it does compare the variable types in addition to the contents.
</p>

<h2>Compare two Strings</h2>
<table>
	<tr>
		<th>Method</th>
		<th>Equal</th>
		<th>First character not equal</th>
		<th>Last character not equal</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<?php
	ob_start();
	for ( $i = 1; ; $i ++ ) {
		$method = 'strcmp_method' . $i;
		if ( ! function_exists( $method ) ) {
			break;
		}
		strcmp_bench( $method );
	}
	display_bench_results();
	?>
</table>
<p>My conclusion: Use what fits your needs.</p>

<h2>Check if a String contains another String</h2>
<table>
	<tr>
		<th>Method</th>
		<th>Not found</th>
		<th>Found at the start</th>
		<th>Found in the middle</th>
		<th>Found at the end</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<?php
	ob_start();
	strstr_bench( 'strstr_method1' );
	strstr_bench( 'strstr_method2' );
	strstr_bench( 'strstr_method3' );
	strstr_bench( 'strstr_method4' );
	strstr_bench( 'strstr_method5' );
	strstr_bench( 'strstr_method6' );
	strstr_bench( 'strstr_method7' );
	strstr_bench( 'strstr_method8' );
	display_bench_results();
	?>
</table>
<p>My conclusion:
	It does not matter if you use <code>strstr()</code> or <code>strpos()</code>.
	Use the <code>preg&hellip;()</code> functions only if you need the power of regular expressions.
	Never use the <code>ereg&hellip;()</code> functions.</p>

<h2>Check if a String starts with another String</h2>
<table>
	<tr>
		<th>Method</th>
		<th>Not found</th>
		<th>Found at the start</th>
		<th>Found in the middle</th>
		<th>Found at the end</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<?php
	ob_start();
	strstr_bench( 'startsWith_method1' );
	strstr_bench( 'startsWith_method2' );
	strstr_bench( 'startsWith_method3' );
	strstr_bench( 'startsWith_method4' );
	strstr_bench( 'startsWith_method5' );
	strstr_bench( 'startsWith_method6' );
	strstr_bench( 'startsWith_method7' );
	display_bench_results();
	?>
</table>
<p>My conclusion:
	<code>strpos()</code> is very fast and can be used in almost all cases.
	<code>strncmp()</code> is good if you are looking for a constant length needle.</p>

<h2>Check if a String ends with another String</h2>
<table>
	<tr>
		<th>Method</th>
		<th>Not found</th>
		<th>Found at the start</th>
		<th>Found in the middle</th>
		<th>Found at the end</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<?php
	ob_start();
	strstr_bench( 'endsWith_method1' );
	strstr_bench( 'endsWith_method2' );
	strstr_bench( 'endsWith_method3' );
	strstr_bench( 'endsWith_method4' );
	display_bench_results();
	?>
</table>
<p>My conclusion:
	Using <code>substr()</code> with a negative position is a good trick.</p>

<h2>Replace a String inside another String</h2>
<table>
	<tr>
		<th>Method</th>
		<th>Not found</th>
		<th>Found at the start</th>
		<th>Found in the middle</th>
		<th>Found at the end</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<?php
	ob_start();
	strstr_bench( 'strreplace_method1' );
	strstr_bench( 'strreplace_method2' );
	strstr_bench( 'strreplace_method3' );
	strstr_bench( 'strreplace_method4' );
	display_bench_results();
	?>
</table>
<p>My conclusion:
	Never use the <code>ereg&hellip;()</code> functions.</p>

<h2>Trim Characters from the Beginning and End of a String</h2>
<table>
	<tr>
		<th>Method</th>
		<th>Not found</th>
		<th>Found at start</th>
		<th>Found at end</th>
		<th>Found at both sides</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<?php
	ob_start();
	trim_bench( 'trim_method1' );
	trim_bench( 'trim_method2' );
	trim_bench( 'trim_method3' );
	trim_bench( 'trim_method4' );
	trim_bench( 'trim_method5' );
	trim_bench( 'trim_method6' );
	display_bench_results();
	?>
</table>
<p>My conclusion:
	Always benchmark your regular expressions!
	In this case, with <code>.*</code> you also replace nothing with nothing which takes time
	because there is a lot of &ldquo;nothing&rdquo; in every string.</p>

<h2>Split a String into an Array</h2>
<table>
	<tr>
		<th>Method</th>
		<th>Empty string</th>
		<th>Single occurrence</th>
		<th>Multiple occurrences</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<?php
	ob_start();
	split_bench( 'split_method1' );
	split_bench( 'split_method2' );
	split_bench( 'split_method3' );
	split_bench( 'split_method4' );
	display_bench_results();
	?>
</table>
<p>My conclusion:
	Don't use <code>split()</code>. It's deprecated in PHP 5.3 and will be removed in PHP 6.</p>

<h2>Loop a numerical indexed Array of Strings</h2>
<table>
	<tr>
		<th>Method</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<?php
	ob_start();
	loop_bench( 'loop_method1' );
	loop_bench( 'loop_method2' );
	loop_bench( 'loop_method3' );
	loop_bench( 'loop_method4' );
	loop_bench( 'loop_method5' );
	display_bench_results();
	?>
</table>
<p>My conclusion:
	<code>count()</code> is horribly slow. Always precalculate it, if possible.</p>

<h2>Get Elements from an Array</h2>
<table>
	<tr>
		<th>Method</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<?php
	ob_start();
	for ( $i = 1; ; $i ++ ) {
		$method = 'array_get_method' . $i;
		if ( ! function_exists( $method ) ) {
			break;
		}
		array_get_bench( $method );
	}
	display_bench_results();
	?>
</table>
<p>My conclusion: I like associative arrays.</p>

<h2>Implode an Array</h2>
<table>
	<tr>
		<th>Method</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<?php
	ob_start();
	concat_bench( 'concat_method1' );
	concat_bench( 'concat_method2' );
	concat_bench( 'concat_method3' );
	concat_bench( 'concat_method4' );
	concat_bench( 'concat_method5' );
	display_bench_results();
	?>
</table>
<p>My conclusion: String concatenation is a cheap operation in PHP. Don't waste your time benchmarking this.</p>

<h2>The single vs. double Quotes Myth</h2>
<table>
	<tr>
		<th>Method</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<?php
	ob_start();
	quotes_bench( 'quotes_method1' );
	quotes_bench( 'quotes_method2' );
	quotes_bench( 'quotes_method3' );
	quotes_bench( 'quotes_method4' );
	quotes_bench( 'quotes_method5' );
	quotes_bench( 'quotes_method6' );
	quotes_bench( 'quotes_method7' );
	display_bench_results();
	?>
</table>
<p>My conclusion:
	It does not matter if you use single or double quotes at all.
	The inclusion of variables has a measurable effect, but that's independent from the quotes.</p>

</body>
</html>