<h1>My PHP Performance Benchmarks</h1>

<p>PHP version 5.6.4 is running on this server.

</p><p>Please note that these are micro benchmarks. Micro benchmarks are stupid.
    I created this comparison to learn something about PHP and how the PHP compiler works.
	This can not be used to compare PHP versions or servers.</p>

<h2>Check if a String is empty</h2>
<table>
	<tbody><tr>
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
	<tr><td><code>if ( ! $var )</code></td><td>2&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>1&nbsp;ms</td><td>1&nbsp;ms</td><td>1&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>4&nbsp;ms</td><td class="almost">193</td></tr><tr><td><code>if (empty( $var) )</code></td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>1&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>3&nbsp;ms</td><td class="almost">130</td></tr><tr><td><code>if ( $var == "" )</code></td><td>&gt;0&nbsp;ms</td><td>1&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>1&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>19&nbsp;ms</td><td>22&nbsp;ms</td><td class="buggy">969</td></tr><tr><td><code>if ( "" == $var )</code></td><td>&gt;0&nbsp;ms</td><td>1&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>1&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>3&nbsp;ms</td><td class="almost">124</td></tr><tr><td><code>if ( $var === "" )</code></td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>1&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>2&nbsp;ms</td><td class="almost">101</td></tr><tr><td><code>if ( "" === $var )</code></td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>1&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>&gt;0&nbsp;ms</td><td>2&nbsp;ms</td><td class="yes">100</td></tr><tr><td><code>if ( strcmp( $var, "" ) == 0 )</code></td><td>6&nbsp;ms</td><td>6&nbsp;ms</td><td>6&nbsp;ms</td><td>6&nbsp;ms</td><td>5&nbsp;ms</td><td>5&nbsp;ms</td><td>5&nbsp;ms</td><td>39&nbsp;ms</td><td class="buggy">1722</td></tr><tr><td><code>if ( strcmp( "", $var ) == 0 )</code></td><td>6&nbsp;ms</td><td>6&nbsp;ms</td><td>6&nbsp;ms</td><td>6&nbsp;ms</td><td>6&nbsp;ms</td><td>5&nbsp;ms</td><td>5&nbsp;ms</td><td>39&nbsp;ms</td><td class="buggy">1725</td></tr><tr><td><code>if ( strlen( $var ) == 0 )</code></td><td>6&nbsp;ms</td><td>5&nbsp;ms</td><td>5&nbsp;ms</td><td>6&nbsp;ms</td><td>5&nbsp;ms</td><td>5&nbsp;ms</td><td>5&nbsp;ms</td><td>37&nbsp;ms</td><td class="buggy">1611</td></tr><tr><td><code>if ( ! strlen( $var ) )</code></td><td>5&nbsp;ms</td><td>6&nbsp;ms</td><td>5&nbsp;ms</td><td>5&nbsp;ms</td><td>5&nbsp;ms</td><td>5&nbsp;ms</td><td>5&nbsp;ms</td><td>36&nbsp;ms</td><td class="buggy">1599</td></tr></tbody></table>
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
	<tbody><tr>
		<th>Method</th>
		<th>Equal</th>
		<th>First character not equal</th>
		<th>Last character not equal</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<tr><td><code>$a == $b</code></td><td>1&nbsp;ms</td><td>1&nbsp;ms</td><td>1&nbsp;ms</td><td>3&nbsp;ms</td><td class="yes">100</td></tr><tr><td><code>!strcmp( $a, $b )</code></td><td>17&nbsp;ms</td><td>16&nbsp;ms</td><td>16&nbsp;ms</td><td>48&nbsp;ms</td><td class="buggy">1606</td></tr><tr><td><code>strcmp( $a, $b ) == 0</code></td><td>16&nbsp;ms</td><td>16&nbsp;ms</td><td>16&nbsp;ms</td><td>48&nbsp;ms</td><td class="buggy">1589</td></tr><tr><td><code>strcmp( $a, $b ) === 0</code></td><td>16&nbsp;ms</td><td>16&nbsp;ms</td><td>16&nbsp;ms</td><td>48&nbsp;ms</td><td class="buggy">1612</td></tr><tr><td><code>strcasecmp( $a, $b ) === 0</code></td><td>16&nbsp;ms</td><td>15&nbsp;ms</td><td>16&nbsp;ms</td><td>48&nbsp;ms</td><td class="buggy">1601</td></tr></tbody></table>
<p>My conclusion: Use what fits your needs.</p>

<h2>Check if a String contains another String</h2>
<table>
	<tbody><tr>
		<th>Method</th>
		<th>Not found</th>
		<th>Found at the start</th>
		<th>Found in the middle</th>
		<th>Found at the end</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<tr><td><code>strstr( $haystack, $needle )</code></td><td>4&nbsp;ms</td><td>4&nbsp;ms</td><td>4&nbsp;ms</td><td>4&nbsp;ms</td><td>17&nbsp;ms</td><td class="yes">100</td></tr><tr><td><code>strpos( $haystack, $needle ) !== FALSE</code></td><td>4&nbsp;ms</td><td>4&nbsp;ms</td><td>5&nbsp;ms</td><td>4&nbsp;ms</td><td>17&nbsp;ms</td><td class="almost">101</td></tr><tr><td><code>strstr( $haystack, $needle ) !== FALSE</code></td><td>4&nbsp;ms</td><td>4&nbsp;ms</td><td>5&nbsp;ms</td><td>4&nbsp;ms</td><td>17&nbsp;ms</td><td class="almost">103</td></tr><tr><td><code>stristr( $haystack, $needle )</code></td><td>12&nbsp;ms</td><td>13&nbsp;ms</td><td>11&nbsp;ms</td><td>12&nbsp;ms</td><td>49&nbsp;ms</td><td class="incomplete">289</td></tr><tr><td><code>preg_match( "/$needle/", $haystack )</code></td><td>7&nbsp;ms</td><td>6&nbsp;ms</td><td>7&nbsp;ms</td><td>7&nbsp;ms</td><td>27&nbsp;ms</td><td class="almost">160</td></tr><tr><td><code>preg_match( "/$needle/i", $haystack )</code></td><td>6&nbsp;ms</td><td>6&nbsp;ms</td><td>7&nbsp;ms</td><td>7&nbsp;ms</td><td>26&nbsp;ms</td><td class="almost">154</td></tr><tr><td><code>preg_match( "/$needle/S", $haystack )</code></td><td>7&nbsp;ms</td><td>6&nbsp;ms</td><td>7&nbsp;ms</td><td>7&nbsp;ms</td><td>27&nbsp;ms</td><td class="almost">160</td></tr><tr><td><code>ereg( $needle, $haystack )<br>· Function ereg is deprecated since 5.3</code></td><td>10&nbsp;ms</td><td>7&nbsp;ms</td><td>19&nbsp;ms</td><td>31&nbsp;ms</td><td>67&nbsp;ms</td><td class="incomplete">401</td></tr></tbody></table>
<p>My conclusion:
	It does not matter if you use <code>strstr()</code> or <code>strpos()</code>.
	Use the <code>preg…()</code> functions only if you need the power of regular expressions.
	Never use the <code>ereg…()</code> functions.</p>

<h2>Check if a String starts with another String</h2>
<table>
	<tbody><tr>
		<th>Method</th>
		<th>Not found</th>
		<th>Found at the start</th>
		<th>Found in the middle</th>
		<th>Found at the end</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<tr><td><code>strncmp( $haystack, $needle, strlen( $needle ) ) === 0</code></td><td>8&nbsp;ms</td><td>8&nbsp;ms</td><td>8&nbsp;ms</td><td>7&nbsp;ms</td><td>31&nbsp;ms</td><td class="almost">193</td></tr><tr><td><code>strncmp( $haystack, $needle, 4 ) === 0</code></td><td>4&nbsp;ms</td><td>4&nbsp;ms</td><td>4&nbsp;ms</td><td>4&nbsp;ms</td><td>16&nbsp;ms</td><td class="yes">100</td></tr><tr><td><code>strncasecmp( $haystack, $needle, strlen( $needle ) ) === 0</code></td><td>8&nbsp;ms</td><td>8&nbsp;ms</td><td>8&nbsp;ms</td><td>8&nbsp;ms</td><td>31&nbsp;ms</td><td class="almost">192</td></tr><tr><td><code>strpos( $haystack, $needle ) === 0</code></td><td>4&nbsp;ms</td><td>4&nbsp;ms</td><td>4&nbsp;ms</td><td>4&nbsp;ms</td><td>17&nbsp;ms</td><td class="almost">103</td></tr><tr><td><code>substr( $haystack, 0, strlen( $needle ) ) === $needle</code></td><td>8&nbsp;ms</td><td>9&nbsp;ms</td><td>8&nbsp;ms</td><td>8&nbsp;ms</td><td>33&nbsp;ms</td><td class="incomplete">205</td></tr><tr><td><code>strcmp( substr( $haystack, 0, strlen( $needle ) ), $needle ) === 0</code></td><td>11&nbsp;ms</td><td>12&nbsp;ms</td><td>12&nbsp;ms</td><td>12&nbsp;ms</td><td>46&nbsp;ms</td><td class="incomplete">285</td></tr><tr><td><code>preg_match( "/^" . preg_quote( $needle, "/" ) . "/", $haystack )</code></td><td>10&nbsp;ms</td><td>11&nbsp;ms</td><td>10&nbsp;ms</td><td>10&nbsp;ms</td><td>42&nbsp;ms</td><td class="incomplete">257</td></tr></tbody></table>
<p>My conclusion:
	<code>strpos()</code> is very fast and can be used in almost all cases.
	<code>strncmp()</code> is good if you are looking for a constant length needle.</p>

<h2>Check if a String ends with another String</h2>
<table>
	<tbody><tr>
		<th>Method</th>
		<th>Not found</th>
		<th>Found at the start</th>
		<th>Found in the middle</th>
		<th>Found at the end</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<tr><td><code>substr( $haystack, strlen( $haystack ) - strlen( $needle) ) === $needle</code></td><td>14&nbsp;ms</td><td>13&nbsp;ms</td><td>12&nbsp;ms</td><td>12&nbsp;ms</td><td>51&nbsp;ms</td><td class="almost">155</td></tr><tr><td><code>substr( $haystack, -strlen( $needle) ) === $needle</code></td><td>8&nbsp;ms</td><td>8&nbsp;ms</td><td>8&nbsp;ms</td><td>8&nbsp;ms</td><td>33&nbsp;ms</td><td class="yes">100</td></tr><tr><td><code>strcmp( substr( $haystack, - strlen( $needle) ), $needle) === 0</code></td><td>12&nbsp;ms</td><td>12&nbsp;ms</td><td>12&nbsp;ms</td><td>12&nbsp;ms</td><td>47&nbsp;ms</td><td class="almost">141</td></tr><tr><td><code>preg_match( "/" . preg_quote( $needle, "/" ) . "$/", $haystack )</code></td><td>14&nbsp;ms</td><td>24&nbsp;ms</td><td>11&nbsp;ms</td><td>11&nbsp;ms</td><td>60&nbsp;ms</td><td class="almost">181</td></tr></tbody></table>
<p>My conclusion:
	Using <code>substr()</code> with a negative position is a good trick.</p>

<h2>Replace a String inside another String</h2>
<table>
	<tbody><tr>
		<th>Method</th>
		<th>Not found</th>
		<th>Found at the start</th>
		<th>Found in the middle</th>
		<th>Found at the end</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<tr><td><code>str_replace( $search, $replace, $subject )</code></td><td>5&nbsp;ms</td><td>5&nbsp;ms</td><td>5&nbsp;ms</td><td>5&nbsp;ms</td><td>19&nbsp;ms</td><td class="yes">100</td></tr><tr><td><code>preg_replace( "/$search/", $replace, $subject )</code></td><td>7&nbsp;ms</td><td>8&nbsp;ms</td><td>7&nbsp;ms</td><td>8&nbsp;ms</td><td>29&nbsp;ms</td><td class="almost">154</td></tr><tr><td><code>preg_replace( "/$search/S", $replace, $subject )</code></td><td>7&nbsp;ms</td><td>8&nbsp;ms</td><td>7&nbsp;ms</td><td>7&nbsp;ms</td><td>30&nbsp;ms</td><td class="almost">155</td></tr><tr><td><code>ereg_replace( abcd, abcd, 12345678901234567890123456789012345678901234567890123456789012341234567890123456789012345678901234567890123456789012345678901234 )<br>· Function ereg_replace is deprecated since 5.3</code></td><td>7&nbsp;ms</td><td>10&nbsp;ms</td><td>22&nbsp;ms</td><td>34&nbsp;ms</td><td>73&nbsp;ms</td><td class="incomplete">382</td></tr></tbody></table>
<p>My conclusion:
	Never use the <code>ereg…()</code> functions.</p>

<h2>Trim Characters from the Beginning and End of a String</h2>
<table>
	<tbody><tr>
		<th>Method</th>
		<th>Not found</th>
		<th>Found at start</th>
		<th>Found at end</th>
		<th>Found at both sides</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<tr><td><code>trim( $string, "," )</code></td><td>1&nbsp;ms</td><td>1&nbsp;ms</td><td>1&nbsp;ms</td><td>1&nbsp;ms</td><td>3&nbsp;ms</td><td class="yes">100</td></tr><tr><td><code>preg_replace( '/^,*|,*$/', "", $string )</code></td><td>4&nbsp;ms</td><td>4&nbsp;ms</td><td>4&nbsp;ms</td><td>4&nbsp;ms</td><td>18&nbsp;ms</td><td class="buggy">707</td></tr><tr><td><code>preg_replace( '/^,*|,*$/m', "", $string )</code></td><td>7&nbsp;ms</td><td>7&nbsp;ms</td><td>7&nbsp;ms</td><td>7&nbsp;ms</td><td>27&nbsp;ms</td><td class="buggy">1068</td></tr><tr><td><code>preg_replace( '/^,+|,+$/', "", $string )</code></td><td>1&nbsp;ms</td><td>1&nbsp;ms</td><td>1&nbsp;ms</td><td>1&nbsp;ms</td><td>4&nbsp;ms</td><td class="almost">162</td></tr><tr><td><code>preg_replace( '/^,+|,+$/m', "", $string )</code></td><td>1&nbsp;ms</td><td>1&nbsp;ms</td><td>1&nbsp;ms</td><td>1&nbsp;ms</td><td>3&nbsp;ms</td><td class="almost">138</td></tr><tr><td><code>preg_replace( '/^,+/', "", preg_replace( '/,+$/', "", … ) )</code></td><td>2&nbsp;ms</td><td>2&nbsp;ms</td><td>2&nbsp;ms</td><td>3&nbsp;ms</td><td>8&nbsp;ms</td><td class="incomplete">334</td></tr></tbody></table>
<p>My conclusion:
	Always benchmark your regular expressions!
	In this case, with <code>.*</code> you also replace nothing with nothing which takes time
	because there is a lot of “nothing” in every string.</p>

<h2>Split a String into an Array</h2>
<table>
	<tbody><tr>
		<th>Method</th>
		<th>Empty string</th>
		<th>Single occurrence</th>
		<th>Multiple occurrences</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<tr><td><code>explode( ",", $string )</code></td><td>4&nbsp;ms</td><td>4&nbsp;ms</td><td>8&nbsp;ms</td><td>16&nbsp;ms</td><td class="yes">100</td></tr><tr><td><code>split( ",", $string )<br>· Function split is deprecated since 5.3</code></td><td>5&nbsp;ms</td><td>5&nbsp;ms</td><td>81&nbsp;ms</td><td>91&nbsp;ms</td><td class="buggy">578</td></tr><tr><td><code>preg_split( "/,/", $string )</code></td><td>5&nbsp;ms</td><td>5&nbsp;ms</td><td>13&nbsp;ms</td><td>24&nbsp;ms</td><td class="almost">152</td></tr><tr><td><code>preg_match_all( '/[^,]+/', $string, $matches )</code></td><td>6&nbsp;ms</td><td>7&nbsp;ms</td><td>20&nbsp;ms</td><td>33&nbsp;ms</td><td class="incomplete">209</td></tr></tbody></table>
<p>My conclusion:
	Don't use <code>split()</code>. It's deprecated in PHP 5.3 and will be removed in PHP 6.</p>

<h2>Loop a numerical indexed Array of Strings</h2>
<table>
	<tbody><tr>
		<th>Method</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<tr><td><code>for ( $i = 0; $i &lt; count( $array ; $i++ )</code></td><td>40&nbsp;ms</td><td class="no">5451</td></tr><tr><td><code>for ( $i = 0, $count = count( $array ); $i &lt; $count; $i++ )</code></td><td>1&nbsp;ms</td><td class="almost">134</td></tr><tr><td><code>for ( $i = count( $array ) - 1; $i &gt;= 0; $i-- )</code></td><td>1&nbsp;ms</td><td class="almost">134</td></tr><tr><td><code>for ( $i = count( $array ) - 1; $i &gt;= 0; --$i )</code></td><td>1&nbsp;ms</td><td class="almost">131</td></tr><tr><td><code>$i = count( $array ); while ( $i-- )</code></td><td>1&nbsp;ms</td><td class="yes">100</td></tr></tbody></table>
<p>My conclusion:
	<code>count()</code> is horribly slow. Always precalculate it, if possible.</p>

<h2>Get Elements from an Array</h2>
<table>
	<tbody><tr>
		<th>Method</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<tr><td><code>$array[0]</code></td><td>34&nbsp;ms</td><td class="yes">100</td></tr><tr><td><code>$array['key']</code></td><td>34&nbsp;ms</td><td class="almost">101</td></tr></tbody></table>
<p>My conclusion: I like associative arrays.</p>

<h2>Implode an Array</h2>
<table>
	<tbody><tr>
		<th>Method</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<tr><td><code>implode( " ", $array )</code></td><td>18&nbsp;ms</td><td class="buggy">556</td></tr><tr><td><code>"$array[0] $array[1] $array[2]"</code></td><td>3&nbsp;ms</td><td class="yes">100</td></tr><tr><td><code>$array[0] . " " . $array[1] . " " . $array[2]</code></td><td>3&nbsp;ms</td><td class="almost">105</td></tr><tr><td><code>sprintf( "%s %s %s", $array[0], $array[1], $array[2] )</code></td><td>24&nbsp;ms</td><td class="buggy">764</td></tr><tr><td><code>vsprintf( "%s %s %s", $array )</code></td><td>23&nbsp;ms</td><td class="buggy">729</td></tr></tbody></table>
<p>My conclusion: String concatenation is a cheap operation in PHP. Don't waste your time benchmarking this.</p>

<h2>The single vs. double Quotes Myth</h2>
<table>
	<tbody><tr>
		<th>Method</th>
		<th>Summary</th>
		<th>Index</th>
	</tr>
	<tr><td><code>'contains no dollar signs'</code></td><td>1&nbsp;ms</td><td class="yes">100</td></tr><tr><td><code>"contains no dollar signs"</code></td><td>1&nbsp;ms</td><td class="almost">109</td></tr><tr><td><code>'$variables $are $not $replaced'</code></td><td>1&nbsp;ms</td><td class="almost">103</td></tr><tr><td><code>"\$variables \$are \$not \$replaced"</code></td><td>1&nbsp;ms</td><td class="almost">103</td></tr><tr><td><code>"$variables $are $replaced"</code></td><td>3&nbsp;ms</td><td class="buggy">647</td></tr><tr><td><code>$variables . ' ' . $are . ' ' . $replaced</code></td><td>5&nbsp;ms</td><td class="buggy">989</td></tr><tr><td><code>$variables . " " . $are . " " . $replaced</code></td><td>5&nbsp;ms</td><td class="buggy">959</td></tr></tbody></table>
<p>My conclusion:
	It does not matter if you use single or double quotes at all.
	The inclusion of variables has a measurable effect, but that's independent from the quotes.</p>
