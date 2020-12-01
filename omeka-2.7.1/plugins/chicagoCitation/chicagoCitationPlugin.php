<?php

class chicagoCitationPlugin extends Omeka_Plugin_AbstractPlugin
{
	protected $_filters = array('item_citation');

	public function filterItemCitation($citation, $args)
	{
		$citation = '';

		$creators = metadata('item', array('Dublin Core', 'Creator'), array('all' => true));
	/// Strip formatting and remove empty creator elements.
		$creators = array_filter(array_map('strip_formatting', $creators));
		if ($creators) {
			switch (count($creators)) {
				case 1:
				$creator = $creators[0];
				break;
				case 2:
	/// Chicago-style item citation: two authors
				$creator = __('%1$s and %2$s', $creators[0], $creators[1]);
				break;
				case 3:
	/// Chicago-style item citation: three authors
				$creator = __('%1$s, %2$s, and %3$s', $creators[0], $creators[1], $creators[2]);
				break;
				default:
	/// Chicago-style item citation: more than three authors
				$creator = __('%s et al.', $creators[0]);
			}
			$citation .= "$creator. ";
		} else {
			$citation .= "Unknown. ";
		}

		$title = strip_formatting(metadata('item', array('Dublin Core', 'Title')));
		if ($title) {
			$citation .= "<i>$title</i>, " ;
		}
	/// Get year
		$date = strip_formatting(metadata('item', array('Dublin Core', 'Date')));
		if ($date) {
			switch ($date) {
				case (preg_match('/([0-9]{1,}\-)([0-9]{1,}\-)([0-9]{4})/', $date) ? true : false):
					$date = preg_replace('/([0-9]{1,}\-)([0-9]{1,}\-)([0-9]{4})/', "$3", $date);
					break;
				case (preg_match('/([0-9]{1,}\-)([0-9]{4})/', $date) ? true : false):
					$date = preg_replace('/([0-9]{1,}\-)([0-9]{4})/', "$2", $date);
					break;
				case (preg_match('/([A-z]{1,}\,)([0-9]{4})/', $date) ? true : false):
					$date = preg_replace('/([A-z]{1,}\,)([0-9]{4})/', "$2", $date);
					break;
				case (preg_match('/([A-z]{1,}\s)([0-9]{4})/', $date) ? true : false):
					$date = preg_replace('/([A-z]{1,}\s)([0-9]{4})/', "$2", $date);
					break;
				case (preg_match('/([A-z]{1,}\s[0-9]{1,}\,)([0-9]{4})/', $date) ? true : false):
					$date = preg_replace('/([A-z]{1,}\s[0-9]{1,}\,)([0-9]{4})/', "$2", $date);
					break;
				case (preg_match('/([A-z]{1,}\s[0-9]{1,}\s)([0-9]{4})/', $date) ? true : false):
					$date = preg_replace('/([A-z]{1,}\s[0-9]{1,}\s)([0-9]{4})/', "$2", $date);
					break;
				case (preg_match('/([0-9]{4})(\-)([0-9]{1,})(\-)([0-9]{1,})/', $date) ? true : false):
					$date = preg_replace('/([0-9]{4})(\-)([0-9]{1,})(\-)([0-9]{1,})/', "$1", $date);
					break;
				case (preg_match('/([0-9]{4})(\-)([0-9]{1,})/', $date) ? true : false):
					$date = preg_replace('/([0-9]{4})(\-)([0-9]{1,})/', "$1", $date);
					break;
				case (preg_match('/([0-9]{1,}\/[0-9]{1,}\/)([0-9]{4})/', $date) ? true : false):
					$date = preg_replace('/([0-9]{1,}\/[0-9]{1,}\/)([0-9]{4})/', "$2", $date);
					break;
				case (preg_match('/([0-9]{1,}\/)([0-9]{2,})/', $date) ? true : false):
					$date = preg_replace('/([0-9]{1,}\/[0-9]{1,}\/)([0-9]{4})/', "$2", $date);
					break;
				case (preg_match('/([0-9]{1,}\/[0-9]{1,}\/)([0-9]{2})/', $date) ? true : false):
					$date = preg_replace('/([0-9]{1,}\/[0-9]{1,}\/)([0-9]{2})/', "19$2", $date);
					break;
				case (preg_match('/([0-9]{1,}\/)([0-9]{2,})/', $date) ? true : false):
					$date = preg_replace('/([0-9]{1,}\/[0-9]{1,}\/)([0-9]{2})/', "19$2", $date);
					break;

				default:
					$date = $date;
			}
				$citation .= "$date, ";
		}

	/// Chicago-style item Publisher and Archive
		$citation .= "Mappalachia, ";

		$source = strip_formatting(metadata('item', array('Dublin Core', 'Source')));
		if ($source) {
			$citation .= "$source, ";
		}

		$accessed = format_date(time(), Zend_Date::DATE_LONG);
		$url = html_escape(record_url('item', null, true));
	/// Chicago-style item citation: access date and URL
		$citation .= __('accessed %1$s, %2$s.', $accessed, $url);
		return $citation;
	}
}
