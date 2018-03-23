<?php

/**
 * Redirect model
 *
 * @package     Nails
 * @subpackage  module-redirect
 * @category    Model
 * @author      Nails Dev Team
 * @link
 */

namespace Nails\Redirect\Model;

use Nails\Common\Model\Base;

class Redirect extends Base
{
    /**
     * Redirect constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->table             = NAILS_DB_PREFIX . 'redirect';
        $this->defaultSortColumn = null;
        $this->tableLabelColumn  = null;
        $this->searchableFields  = ['old_url', 'neW_url'];
    }

    // --------------------------------------------------------------------------

    /**
     * Describes the fields for this model automatically and with some guesswork;
     * for more fine grained control models should overload this method.
     *
     * @return array
     */
    public function describeFields()
    {
        $aFields = parent::describeFields();

        $aFields['old_url']->validation[] = 'required';
        $aFields['new_url']->validation[] = 'required';
        $aFields['type']->validation[]    = 'required';

        return $aFields;
    }

    // --------------------------------------------------------------------------

    /**
     * Creates a new object; overriding to normalise URLs
     *
     * @param  array   $aData         The data to create the object with
     * @param  boolean $bReturnObject Whether to return just the new ID or the full object
     *
     * @return mixed
     * @throws ModelException
     */
    public function create($aData = [], $bReturnObject = false)
    {
        $this->normaliseUrls($aData);
        return parent::create($aData, $bReturnObject);
    }

    // --------------------------------------------------------------------------

    /**
     * Updates an existing object; overriding to normalise URLs
     *
     * @param  integer|array $mIds  The ID (or array of IDs) of the object(s) to update
     * @param  array         $aData The data to update the object(s) with
     *
     * @return boolean
     * @throws ModelException
     */
    public function update($mIds, $aData = [])
    {
        $this->normaliseUrls($aData);
        return parent::update($mIds, $aData);
    }

    // --------------------------------------------------------------------------

    /**
     * Normalises the old_url and new_url keys in the $aData array
     *
     * @param array $aData The $aData array
     *
     * @throws \Exception
     */
    protected function normaliseUrls(array &$aData)
    {
        if (array_key_exists('old_url', $aData)) {
            $aData['old_url'] = static::normaliseUrl(trim($aData['old_url']));
        }
        if (array_key_exists('new_url', $aData)) {
            $aData['new_url'] = static::normaliseUrl(trim($aData['new_url']));
        }
    }

    // --------------------------------------------------------------------------

    /**
     * Normalises a URL to just its path and query components
     *
     * @param stirng $sUrl The URL to normalise
     *
     * @throws \Exception
     * @return string
     */
    public static function normaliseUrl($sUrl)
    {
        $aUrl = parse_url($sUrl);
        if (!is_array($aUrl)) {
            throw new \Exception('Failed to parse URL (' . $sUrl . ')');
        }

        $sScheme = getFromArray('scheme', $aUrl, 'http');
        $sHost   = getFromArray('host', $aUrl, BASE_URL);
        $sPath   = getFromArray('path', $aUrl, '/');
        $sQuery  = getFromArray('query', $aUrl);

        $aBaseUrl    = parse_url(BASE_URL);
        $sBaseScheme = getFromArray('scheme', $aBaseUrl, 'http');
        $sBaseHost   = getFromArray('host', $aBaseUrl, BASE_URL);

        if ($sBaseScheme === $sScheme && $sBaseHost === $sHost) {
            $sDomain = '';
        } else {
            $sDomain = $sScheme . '://' . $sHost;
        }

        $sUrl = $sDomain . implode(
                '?',
                array_filter([
                    getFromArray('path', $aUrl),
                    getFromArray('query', $aUrl),
                ])
            );

        return rtrim($sUrl, '/');
    }
}
