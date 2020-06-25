<?php

namespace Qazd;

require_once dirname(__FILE__) . '/wp/wp-diff.php';

use Text_Diff;
use WP_Text_Diff_Renderer_Table;

class TextDiff
{
	/**
	 * Generates formatted HTML table with differences
	 * between two sequences of strings.
	 *
	 * @param string $left_string  text
	 * @param string $right_string text to compare
	 * @param bool   $split_view   split into two columns (side by side comparison)
	 *
	 * @return string HTML on success with differences, blank string when they are identical.
	 */
	public static function render($left_string, $right_string, $split_view = true)
	{
		$text_diff = self::diff($left_string, $right_string);
		$renderer  = new WP_Text_Diff_Renderer_Table(['show_split_view' => $split_view]);
		$diff = $renderer->render($text_diff);

		if (empty($diff))
			return '';

		$r  = '<div class="diff">';
		$r .= PHP_EOL . $diff . PHP_EOL;
		$r .= '</div>';

		return $r;
	}

	/**
	 * Differences between two strings
	 *
	 * @param string $left_string  text
	 * @param string $right_string text to compare
	 *
	 * @return Text_Diff
	 */
	protected static function diff($left_string, $right_string)
	{
		$left_string  = self::normalize_whitespace($left_string);
		$right_string = self::normalize_whitespace($right_string);

		$left_lines  = explode(PHP_EOL, $left_string);
		$right_lines = explode(PHP_EOL, $right_string);

		return new Text_Diff($left_lines, $right_lines);
	}

	/**
	 * Normalize EOL characters and strip duplicate whitespace.
	 *
	 * @param string $str The string to normalize.
	 * @return string The normalized string.
	 */
	protected static function normalize_whitespace($str)
	{
		$str  = trim($str);
		$str  = str_replace("\r", PHP_EOL, $str);
		$str  = preg_replace(['/\n+/', '/[ \t]+/'], [PHP_EOL, ' '], $str);

		return $str;
	}
}
