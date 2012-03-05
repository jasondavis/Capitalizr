<?php

/*
Plugin Name: Capitalizr
Description: Utility plugin for Tuts+ editors that makes the title have correct capitalization. Requires PHP 5.3
Author: Jeffrey Way
Version: 1
Author URI: http://jeffrey-way.com
*/

class Capitalizr {
	
	function __construct( $content )
	{
		$this->content = $content;
	}

	public function update_title() 
	{
		$title = $this->lower( $this->content['post_title'] );

		return $this->content['post_title'] = $this->update_edge_cases( $title );
	}

	public function update_headings()
	{
		return $this->content['post_content'] = 
			preg_replace_callback('
				/(?P<open_tag><h(?:2|3)>)
				(?P<content>.+)
				(?P<close_tag><\/h(?:2|3)>)/ixs', 

				function($matches) {
					extract($matches);

					$heading = Capitalizr::lower( trim($content) );
					$heading = Capitalizr::update_edge_cases( $heading );

					return $open_tag . $heading . $close_tag;
		}, $this->content['post_content'] );
	}

	public function lower( $content )
	{
		// Fixes potential issue with a word next to a closing angle bracket.
		// <span>step 1 will become <span>Step 1
		$content = preg_replace_callback('/(?<=>| ")[a-z]/', function( $matches ) {
			return strtoupper( $matches[0] );
		}, ucwords($content) );

		// array containing all words that should be lowercase
		$to_lower = explode( " ", "a an and as at but by en for if in of on or the to via vs it is so" );

		// if a word in the title matches an item in that array, update it.
		$reg = array_map( function( $val ) {
			return "/(?<=\s|>)$val\b/i";
		}, $to_lower );

		return preg_replace( $reg, $to_lower, $content );
	}

	public function update_edge_cases( $content )
	{
		// edge cases
		$wrong = array("javascript", "actionscript", "css(3)?", "html", "php", "api");
		$correct = array("JavaScript", "ActionScript", "CSS$1", "HTML", "PHP", "API");
		
		array_walk( $wrong, function( &$val ) {
			$val = "/\b$val\b/i"; 
		});

		return preg_replace( $wrong, $correct, $content );
	}
}

// when post is saved to the database
add_filter('wp_insert_post_data', function( $content ) {
	$cap = new Capitalizr( $content );

	$cap->update_title();
	$cap->update_headings();
	
	return $cap->content;
});


// include('capitalizr_tests.php');