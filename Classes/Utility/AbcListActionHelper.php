<?php

namespace FriendsOfTYPO3\TtAddress\Utility;

class AbcListActionHelper {
	
	/**
	 * Group a Person Model into a group Array
	 *
	 * @param string $firstChar
	 * @param array $range
     * @param integer $personCount
	 * @param array $groupedPersons
	 * @param $person
	 * @return void
	 */
    public static function groupPerson(&$firstChar, &$range, &$personCount, &$groupedPersons, &$person) {
		// Put it in the result array
		if ( array_key_exists($firstChar, $range) ) {
			$groupedPersons[$firstChar][] = $person;
			$range[$firstChar]++;
		} else {
			$groupedPersons['#'][] = $person;
			$range['#']++;
		}

        $personCount++;
	}
	
	/**
	 * Creates the grouping arrays
	 *
	 * @param array $range
	 * @param array $groupedPersons
	 * @return void
	 */
	public static function createGroupArrays(&$range, &$groupedPersons) {
		// Create ABC List Array
		foreach ( range('A','Z') as $char ) {
			$range[$char] = 0;
		}
		$range['#'] = 0;
		
		foreach ( $range as $char ) {
			$groupedPersons[$char] = array();
		}
	}

	/**
	 * Pull up the group range counter
	 *
	 * @param string $firstChar
	 * @param array $range
	 * @return void
	 */
	public static function pullUpRange(&$firstChar, &$range) {
		if ( array_key_exists($firstChar, $range) ) {
			$range[$firstChar]++;
		} else $range['#']++;
	}

}

