<?php

/*
Plugin Name: Capitalizr
Description: Utility plugin for Tuts+ editors that makes the title have correct capitalization.
Author: Jeffrey Way
Version: 1
Author URI: http://jeffrey-way.com
*/

// before saving to database...
add_filter('wp_insert_post_data', function( $content ) {
	$title = ucwords( $content['post_title'] );

	$title = capitalizr_lower( $title );
	$title = capitalizr_edge_cases( $title );

	$content['post_title'] = $title;

	return $content;
});

function capitalizr_lower( $title ) {
	// array containing all words that should be lowercase
	$to_lower = explode( " ", "a an and as at but by en for if in of on or the to via vs it is so" );

	// if a word in the title matches an item in that array, update it.
	$reg = array_map( function( $val ) {
		return "/(?<=\s)\b$val\b/i";
	}, $to_lower );

	return preg_replace( $reg, $to_lower, $title );
}

function capitalizr_edge_cases( $title ) {
	// edge cases
	$wrong = array("javascript", "actionscript", "css(3)?", "html", "php");
	$correct = array("JavaScript", "ActionScript", "CSS$1", "HTML", "PHP");
	
	array_walk( $wrong, function( &$val ) {
		$val = "/\b$val\b/i"; 
	});

	return preg_replace($wrong, $correct, $title);
}


// Tests
/*
function equals( $a, $b ) {
	if ( $a !== $b ) {
		die("FAIL. <strong>$a</strong> does not equal <strong>$b</strong>");
	}
}

equals( 
	capitalizr_lower("The Best Way To Learn How To Code"),
	'The Best Way to Learn How to Code'
);

equals( 
	capitalizr_lower( ucwords("10 ways to think about becoming a better developer") ),
	'10 Ways to Think About Becoming a Better Developer'
);

equals(
	capitalizr_lower("It Isn't Hard To Learn Development If You Study"),
	"It Isn't Hard to Learn Development if You Study"
);

equals(
	capitalizr_lower("It is Easy To Think About It"),
	"It is Easy to Think About it"
);
*/