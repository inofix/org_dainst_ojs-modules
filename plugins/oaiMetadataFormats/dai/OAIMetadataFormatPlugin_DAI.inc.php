<?php

/**
 * @file plugins/oaiMetadataFormats/dai/OAIMetadataFormatPlugin_DAI.inc.php
 *
 * Copyright (c) 2016 Michael Lustenberger INOFIX GmbH Luzern
 * Cloned from
 *   plugins/oaiMetadataFormats/rfc1807/OAIMetadataFormatPlugin_RFC1807.inc.php
 * Copyright (c) 2013-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class OAIMetadataFormatPlugin_DAI
 * @ingroup oai_format
 * @see OAI
 *
 * @brief custom metadata format plugin for OAI.
 */

import('lib.pkp.classes.plugins.OAIMetadataFormatPlugin');

class OAIMetadataFormatPlugin_DAI extends OAIMetadataFormatPlugin {

	/**
	 * Get the name of this plugin. The name must be unique within
	 * its category.
	 * @return String name of plugin
	 */
	function getName() {
		return 'OAIFormatPlugin_DAI';
	}

	function getDisplayName() {
		return __('plugins.OAIMetadata.dai.displayName');
	}

	function getDescription() {
		return __('plugins.OAIMetadata.dai.description');
	}

	function getFormatClass() {
		return 'OAIMetadataFormat_DAI';
	}

	function getMetadataPrefix() {
		return 'dai';
	}

	function getSchema() {
		return '';
	}

	function getNamespace() {
        return '';
	}
}

?>
