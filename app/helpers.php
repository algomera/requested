<?php
	if (! function_exists('getTitle')) {
		function getTitle($titleToShow, $item) {
			$result = $item;
			foreach ($titleToShow as $key) {
				if (isset($result[$key])) {
					$result = $result[$key];
				} else {
					$result = null;
					break;
				}
			}
			return $result;
		}
	}
	if (! function_exists('getSubtitle')) {
		function getSubtitle($subtitleToShow, $item) {
			$result = $item;
			foreach ($subtitleToShow as $key) {
				if (isset($result[$key])) {
					$result = $result[$key];
				} else {
					$result = null;
					break;
				}
			}
			return $result;
		}
	}
