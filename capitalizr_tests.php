<?php
// Tests
function equals( $a, $b ) {
	if ( $a !== $b ) {
		die("<strong style='color:red;'>FAIL: </strong> <strong>" . htmlspecialchars($a) . "</strong> does not equal <strong>" . htmlspecialchars($b) . "</strong>");
	}
}


$C = new Capitalizr(
	array(
		'post_title' => 'this is the best way to learn how to code'
	)
);

equals( 
	$C->update_title(),
	'This is the Best Way to Learn How to Code'
);

$C->content['post_title'] = '10 ways to think about becoming a better developer';
equals(
	$C->update_title(),
	'10 Ways to Think About Becoming a Better Developer'
);



$C->content['post_content'] = '<h2><span>step 1: </span>cut a hole in a box</h2>';
equals(
	$C->update_headings(),
	'<h2><span>Step 1: </span>Cut a Hole in a Box</h2>'
);

$C->content['post_content'] = '<h3>here is how you learn about javascript</h3>';
equals(
	$C->update_headings(),
	'<h3>Here is How You Learn About JavaScript</h3>'
);