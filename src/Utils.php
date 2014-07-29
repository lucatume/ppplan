<?php

namespace PPPlan;


class Utils {
	public static function getPluralSuffixFor( $hours ) {
		return $hours > 1 ? 's' : '';
	}
}