<?php

/**
 * @file plugins/oaiMetadataFormats/dai/index.php
 *
 * Copyright (c) 2016 Michael Lustenberger INOFIX GmbH Luzern
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_oaiMetadata
 * @brief Wrapper for the OAI DAI format plugin.
 */

require_once('OAIMetadataFormatPlugin_DAI.inc.php');
require_once('OAIMetadataFormat_DAI.inc.php');

return new OAIMetadataFormatPlugin_DAI();

?>
