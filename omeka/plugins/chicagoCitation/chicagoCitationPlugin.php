<?php
/// This simple plugin is based on some very helpful code and instructions provided on
/// the Omeka forum by sheepeeh (http://omeka.org/forums/topic/change-citation-style)
///
/// The plugin will check the "Item Type" metadata element within Omeka's Dublin Core array,
/// and (according to the Chicago Manual of Style rules pertaining to that item type)
/// generate Chicago citations by pulling from the Dublin Core metadata associated with the
/// Omeka item.
///
/// Currently, this document will detect the following item types:
/// Book, Book Section, Journal Article, Newspaper Article, Webpage, Thesis, Document
///
/// This plugin was originally created to serve the needs of New York University
/// Libraries' "Jewish Peddler Project" (http://jewishpeddler.org)
///
/// Last updated: Feb. 23, 2015
///
/// Stephen Balogh, 2015 | stephen.balogh@nyu.edu

class chicagoCitationPlugin extends Omeka_Plugin_AbstractPlugin
{
	protected $_filters = array('item_citation');

	public function filterItemCitation($citation, $args)
	{

/// BEGINNING OF DOCUMENT TYPE CHECKER
		$document = strip_formatting(metadata('item', array('Dublin Core', 'Item Type')));
		if ($document) {
			switch ($document) {

// BEGIN BOOK
				case 'Book':


		$citation = '';

		$authors = metadata('item', array('Dublin Core', 'Author'), array('all' => true));
	/// Strip formatting and remove empty creator elements.
		$authors = array_filter(array_map('strip_formatting', $authors));
		if ($authors) {
			switch (count($authors)) {
				case 1:
				$author = $authors[0];
				break;
				case 2:
	/// Chicago-style item citation: two authors
				$author = __('%1$s and %2$s', $authors[0], $authors[1]);
				break;
				case 3:
	/// Chicago-style item citation: three authors
				$author = __('%1$s, %2$s, and %3$s', $authors[0], $authors[1], $authors[2]);
				break;
				default:
	/// Chicago-style item citation: more than three authors
				$author = __('%s et al.', $authors[0]);
			}
			$citation .= "$author. ";
		} else {
			$citation .= "Author Unknown. ";
		}

	/// Note that the following (title) portion is drawing from the DC array, not Dublin Core!

		$title = strip_formatting(metadata('item', array('Dublin Core', 'Title')));
		if ($title) {
			$citation .= "<i>$title</i> (";
		}

		$place = metadata('item', array('Dublin Core', 'Place'), array('all' => true));
		$place = array_filter(array_map('strip_formatting', $place));
		if ($place) {
			switch (count($place)) {
				case 1:
				$place = $place[0];
				break;
				case 2:
	/// two fields in publishing metadata –– publisher then location
				$place = __('%2$s and %1$s', $place[0], $place[1]);
				break;

			}
			$citation .= "$place: ";
		} else {
			$citation .= "";
	}

		$publisher = metadata('item', array('Dublin Core', 'Publisher'), array('all' => true));
		$publisher = array_filter(array_map('strip_formatting', $publisher));
		if ($publisher) {
			switch (count($publisher)) {
				case 1:
				$publisher = $publisher[0];
				break;
				case 2:
	/// two fields in publishing metadata –– publisher then location
				$publisher = __('%1$s and %2$s', $publisher[0], $publisher[1]);
				break;

			}
			$citation .= "$publisher, ";
		} else {
			$citation .= "Unknown, ";
	}


		$date = metadata('item', array('Dublin Core', 'Date'), array('all' => true));
		$date = array_filter(array_map('strip_formatting', $date));
		if ($date) {
			switch (count($date)) {
				case 1:
				$date = $date[0];
				break;
			}
			$citation .= "$date).";
		} else {
			$citation .= "Unknown Date).";
	}

	///	$accessed = format_date(time(), Zend_Date::DATE_LONG);
	///	$url = html_escape(record_url('item', null, true));
	/// Chicago-style item citation: access date and URL
	///	$citation .= __('accessed %1$s, %2$s.', $accessed, $url);


break;

// END BOOK
//
// BEGIN JOURNAL ARTICLE

case 'Journal Article':

$citation = '';

		$authors = metadata('item', array('Dublin Core', 'Author'), array('all' => true));
	/// Strip formatting and remove empty author elements.
		$authors = array_filter(array_map('strip_formatting', $authors));
		if ($authors) {
			switch (count($authors)) {
				case 1:
				$author = $authors[0];
				break;
				case 2:
	/// Chicago-style item citation: two authors
				$author = __('%1$s and %2$s', $authors[0], $authors[1]);
				break;
				case 3:
	/// Chicago-style item citation: three authors
				$author = __('%1$s, %2$s, and %3$s', $authors[0], $authors[1], $authors[2]);
				break;
				default:
	/// Chicago-style item citation: more than three authors
				$creator = __('%s et al.', $authors[0]);
			}
			$citation .= "$author. ";
		} else {
			$citation .= "Author Unknown. ";
		}

				$title = strip_formatting(metadata('item', array('Dublin Core', 'Title')));
		if ($title) {
			$citation .= "“".$title.".” ";
		}

		$publication = metadata('item', array('Dublin Core', 'Publication Title'), array('all' => true));
		$publication = array_filter(array_map('strip_formatting', $publication));
		if ($publication) {
			switch (count($publication)) {
				case 1:
				$publication = __('<i>%1$s</i>', $publication[0]);
				break;
			}
			$citation .= "$publication ";
		} else {
			$citation .= "<i>Publication Title Missing</i> ";
	}

		$volume = metadata('item', array('Dublin Core', 'Volume'), array('all' => true));
		$volume = array_filter(array_map('strip_formatting', $volume));
		if ($volume) {
		switch (count($volume)) {
				case 1:
				$volume = __('%1$s', $volume[0]);
				break;
			}
			$citation .= " $volume ";
		} else {
			$citation .= "";
	}

		$issue = metadata('item', array('Dublin Core', 'Issue'), array('all' => true));
		$issue = array_filter(array_map('strip_formatting', $issue));
		if ($issue) {
			switch (count($issue)) {
				case 1:
				$issue = __('%1$s', $issue[0]);
				break;
			}
			$citation .= " $issue ";
		} else {
			$citation .= "";
	}


		$date = metadata('item', array('Dublin Core', 'Date'), array('all' => true));
		$date = array_filter(array_map('strip_formatting', $date));
		if ($date) {
			switch (count($date)) {
				case 1:
				$date = $date[0];
				break;
			}
			$citation .= "($date)";
		} else {
			$citation .= "(Unknown Date)";
	}

			$pagerange = strip_formatting(metadata('item', array('Dublin Core', 'Pages')));
		if ($pagerange) {
			$citation .= ": $pagerange.";
		}
		else {
			$citation .= ".";
}

		$DOI = strip_formatting(metadata('item', array('Dublin Core', 'DOI')));
		if ($DOI) {
			$citation .= " doi: $DOI.";
		}



break;

// END JOURNAL ARTICLE
//
// BEGIN DOCUMENT

case 'Still Image':

$citation = '';

		$authors = metadata('item', array('Dublin Core', 'Author'), array('all' => true));
	/// Strip formatting and remove empty author elements.
		$authors = array_filter(array_map('strip_formatting', $authors));
		if ($authors) {
			switch (count($authors)) {
				case 1:
				$author = $authors[0];
				break;
				case 2:
	/// Chicago-style item citation: two authors
				$author = __('%1$s and %2$s', $authors[0], $authors[1]);
				break;
				case 3:
	/// Chicago-style item citation: three authors
				$author = __('%1$s, %2$s, and %3$s', $authors[0], $authors[1], $authors[2]);
				break;
				default:
	/// Chicago-style item citation: more than three authors
				$creator = __('%s et al.', $authors[0]);
			}
			$citation .= "$author: ";
		} else {
			$citation .= "";
		}

				$title = strip_formatting(metadata('item', array('Dublin Core', 'Title')));
		if ($title) {
			$citation .= "$title";
		}

		$date = metadata('item', array('Dublin Core', 'Date'), array('all' => true));
		$date = array_filter(array_map('strip_formatting', $date));
		if ($date) {
			switch (count($date)) {
				case 1:
				$date = $date[0];
				break;
			}
			$citation .= ", $date.";
		} else {
			$citation .= ".";
	}

		$arcloc = metadata('item', array('Dublin Core', 'Archive Location'), array('all' => true));
		$arcloc = array_filter(array_map('strip_formatting', $arcloc));
		if ($arcloc) {
			switch (count($arcloc)) {
				case 1:
				$arcloc = $arcloc[0];
				break;
				case 2:
	/// two fields in publishing metadata –– publisher then location
				$arcloc = __('%2$s and %1$s', $arcloc[0], $arcloc[1]);
				break;

			}
			$citation .= " $arcloc.";
		} else {
			$citation .= "";
	}


break;

// END DOCUMENT
//
// BEGIN NEWSPAPER ARTICLE

case 'Newspaper Article':

$citation = '';

		$authors = metadata('item', array('Dublin Core', 'Author'), array('all' => true));
	/// Strip formatting and remove empty author elements.
		$authors = array_filter(array_map('strip_formatting', $authors));
		if ($authors) {
			switch (count($authors)) {
				case 1:
				$author = $authors[0];
				break;
				case 2:
	/// Chicago-style item citation: two authors
				$author = __('%1$s and %2$s', $authors[0], $authors[1]);
				break;
				case 3:
	/// Chicago-style item citation: three authors
				$author = __('%1$s, %2$s, and %3$s', $authors[0], $authors[1], $authors[2]);
				break;
				default:
	/// Chicago-style item citation: more than three authors
				$creator = __('%s et al.', $authors[0]);
			}
			$citation .= "$author. ";
		} else {
			$citation .= "Author Unknown. ";
		}

				$title = strip_formatting(metadata('item', array('Dublin Core', 'Title')));
		if ($title) {
			$citation .= "“".$title.".” ";
		}

		$publication = metadata('item', array('Dublin Core', 'Publication Title'), array('all' => true));
		$publication = array_filter(array_map('strip_formatting', $publication));
		if ($publication) {
			switch (count($publication)) {
				case 1:
				$publication = __('<i>%1$s</i>', $publication[0]);
				break;
			}
			$citation .= "$publication";
		} else {
			$citation .= "<i>Publication Title Missing</i>";
	}


		$date = metadata('item', array('Dublin Core', 'Date'), array('all' => true));
		$date = array_filter(array_map('strip_formatting', $date));
		if ($date) {
			switch (count($date)) {
				case 1:
				$date = $date[0];
				break;
			}
			$citation .= ", ($date)";
		} else {
			$citation .= ", (Unknown Date)";
	}

			$pagerange = strip_formatting(metadata('item', array('Dublin Core', 'Pages')));
		if ($pagerange) {
			$citation .= ": $pagerange";
		}
		else {
			$citation .= "";
}

			$url = strip_formatting(metadata('item', array('Dublin Core', 'URL')));
		if ($url) {
			$citation .= ", <a href=\"$url\">$url</a>";
		}
		else {
			$citation .= ".";
}

break;

// END NEWSPAPER ARTICLE
//
// BEGIN THESIS

case 'Thesis':

$citation = '';

		$authors = metadata('item', array('Dublin Core', 'Author'), array('all' => true));
	/// Strip formatting and remove empty author elements.
		$authors = array_filter(array_map('strip_formatting', $authors));
		if ($authors) {
			switch (count($authors)) {
				case 1:
				$author = $authors[0];
				break;
				case 2:
	/// Chicago-style item citation: two authors
				$author = __('%1$s and %2$s', $authors[0], $authors[1]);
				break;
				case 3:
	/// Chicago-style item citation: three authors
				$author = __('%1$s, %2$s, and %3$s', $authors[0], $authors[1], $authors[2]);
				break;
				default:
	/// Chicago-style item citation: more than three authors
				$creator = __('%s et al.', $authors[0]);
			}
			$citation .= "$author. ";
		} else {
			$citation .= "Author Unknown. ";
		}

				$title = strip_formatting(metadata('item', array('Dublin Core', 'Title')));
		if ($title) {
			$citation .= "“".$title.".” ";
		}


		$publisher = metadata('item', array('Dublin Core', 'Publisher'), array('all' => true));
		$publisher = array_filter(array_map('strip_formatting', $publisher));
		$place = strip_formatting(metadata('item', array('Dublin Core', 'Place')));
		if ($publisher) {
			switch (count($publisher)) {
				case 1:
				$publisher = $publisher[0];
				break;
				case 2:
	/// two fields in publishing metadata –– publisher then location
				$publisher = __('%2$s, %1$s', $publisher[0], $publisher[1]);
				break;

			}
			$citation .= "Thesis, $publisher, ";
		} elseif ($place) {
			$citation .= "Thesis, $place, ";
		} else {
			$citation .= "Thesis, Unknown Institution, ";
	}


		$date = metadata('item', array('Dublin Core', 'Date'), array('all' => true));
		$date = array_filter(array_map('strip_formatting', $date));
		if ($date) {
			switch (count($date)) {
				case 1:
				$date = $date[0];
				break;
			}
			$citation .= "$date.";
		} else {
			$citation .= "Unknown Date.";
	}


break;

// END THESIS
//
// BEGIN WEBPAGE

case 'Webpage':

$citation = '';

		$publication = metadata('item', array('Dublin Core', 'Publication Title'), array('all' => true));
		$publication = array_filter(array_map('strip_formatting', $publication));
		if ($publication) {
			switch (count($publication)) {
				case 1:
				$publication = __('<i>%1$s</i>', $publication[0]);
				break;
			}
			$citation .= "$publication.";
		} else {
			$citation .= "<i>Unnamed Web Collection.</i> ";
	}

			$title = strip_formatting(metadata('item', array('Dublin Core', 'Title')));
		if ($title) {
			$citation .= " “".$title.".” ";
		}


		$dateacc = metadata('item', array('Dublin Core', 'Access Date'), array('all' => true));
		$date = array_filter(array_map('strip_formatting', $dateacc));
		if ($date) {
			switch (count($dateacc)) {
				case 1:
				$dateacc = $dateacc[0];
				break;
			}
			$citation .= "Accessed $dateacc. ";
		} else {
			$citation .= "";
	}

			$url = strip_formatting(metadata('item', array('Dublin Core', 'URL')));
		if ($url) {
			$citation .= "<a href=\"$url\">$url</a>";
		}
		else {
			$citation .= "";
}

break;

// END WEBPAGE

}
}
else {
$citation = "Document type unrecognized.";
}
return $citation;

	}
}
