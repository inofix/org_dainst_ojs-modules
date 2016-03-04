<?php

/**
 * @file plugins/oaiMetadataFormats/dai/OAIMetadataFormat_DAI.inc.php
 *
 * Copyright (c) 2016 Michael Lustenberger INOFIX GmbH Luzern
 * Cloned from
 *   plugins/oaiMetadataFormats/rfc1807/OAIMetadataFormat_RFC1807.inc.php
 *
 * Copyright (c) 2013-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class OAIMetadataFormat_DAI
 * @ingroup oai_format
 * @see OAI
 *
 * @brief OAI metadata format class -- Custom Format.
 */

class OAIMetadataFormat_DAI extends OAIMetadataFormat {
	/**
	 * @see OAIMetadataFormat#toXml
	 */
	function toXml(&$record, $format = null) {
		$article =& $record->getData('article');
		$journal =& $record->getData('journal');
		$section =& $record->getData('section');
		$issue =& $record->getData('issue');
		$galleys =& $record->getData('galleys');

		// Publisher
		$publisher = $journal->getLocalizedTitle(); // Default
		$publisherInstitution = $journal->getLocalizedSetting('publisherInstitution');
		if (!empty($publisherInstitution)) {
			$publisher = $publisherInstitution;
		}

		// Sources contains journal title, issue ID, and pages
//		$source = $issue->getIssueIdentification();
//		$pages = $article->getPages();
//		if (!empty($pages)) $source .= '; ' . $pages;

		// Format creators
		$creators = array();
		$authors = $article->getAuthors();
		for ($i = 0, $num = count($authors); $i < $num; $i++) {
			$authorName = $authors[$i]->getFullName(true);
			$affiliation = $authors[$i]->getLocalizedAffiliation();
			if (!empty($affiliation)) {
				$authorName .= '; ' . $affiliation;
			}
			$creators[] = $authorName;
		}

		// Subject
		$subjects = array_merge_recursive(
			$this->stripAssocArray((array) $article->getDiscipline(null)),
			$this->stripAssocArray((array) $article->getSubject(null)),
			$this->stripAssocArray((array) $article->getSubjectClass(null))
		);
		$subject = isset($subjects[$journal->getPrimaryLocale()])?$subjects[$journal->getPrimaryLocale()]:'';

        // Relation / File Entry
        $articleFiles = array();
        foreach ( $galleys as $galley) {
            $articleFiles[] = Request::url($journal->getPath(), 'article', 'download', array($article->getId(), $galley->getId()));
        }

		$url = Request::url($journal->getPath(), 'article', 'view', array($article->getBestArticleId()));
        $issueTitle = $issue->getLocalizedTitle();
        $issueYear = $issue->getYear();
        $issueDescription = $issue->getLocalizedDescription();
        $issueCover = $_SERVER['HTTP_HOST'] .
                        DIRECTORY_SEPARATOR .
                        Config::getVar('files', 'public_files_dir') .
                        DIRECTORY_SEPARATOR .
                        "journals" .
                        DIRECTORY_SEPARATOR .
                        $journal->getId() .
                        DIRECTORY_SEPARATOR .
                        $issue->getLocalizedFileName();
        if ( empty($_SERVER['HTTPS']) ) {
            $issueCover = "http://" . $issueCover;
        } else {
            $issueCover = "https://" . $issueCover;
        }

		$response = "<dai>\n" .
//			"\t<bib-version>v2</bib-version>\n" .
			$this->formatElement('id', $url) .
			$this->formatElement('article_id', $article->getId()) .
			$this->formatElement('article_best_id', $article->getBestArticleId()) .
			$this->formatElement('entry', $record->datestamp) .
			$this->formatElement('organization', $publisher) .
			$this->formatElement('issue_year', $issueYear) .
			$this->formatElement('issue_title', $issueTitle) .
			$this->formatElement('issue_cover_image', $issueCover) .
			$this->formatElement('issue_description', $issueDescription) .
			$this->formatElement('article_pages', $article->getPages()) .
			$this->formatElement('title', $article->getLocalizedTitle()) .
			$this->formatElement('type', $section->getLocalizedIdentifyType()) .

			$this->formatElement('author', $creators) .
			($article->getDatePublished()?$this->formatElement('date', $article->getDatePublished()):'') .
			$this->formatElement('copyright', strip_tags($journal->getLocalizedSetting('copyrightNotice'))) .
			$this->formatElement('download', $articleFiles) .
			$this->formatElement('keyword', $subject) .
			$this->formatElement('monitoring', $article->getLocalizedSponsor()) .
			$this->formatElement('language', $article->getLanguage()) .
			$this->formatElement('abstract', strip_tags($article->getLocalizedAbstract())) .
			"</dai>\n";

		return $response;
	}

	/**
	 * Format XML for single RFC 1807 element.
	 * @param $name string
	 * @param $value mixed
	 */
	function formatElement($name, $value) {
		if (!is_array($value)) {
			$value = array($value);
		}

		$response = '';
		foreach ($value as $v) {
			$response .= "\t<$name>" . OAIUtils::prepOutput($v) . "</$name>\n";
		}
		return $response;
	}
}

?>
