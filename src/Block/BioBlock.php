<?php
/**
 * Created by PhpStorm.
 * User: al
 * Date: 31/08/2018
 * Time: 12:35 PM
 */

namespace Twohill\ElementalBioBlock\Block;


use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Convert;
use SilverStripe\ElementalFileBlock\Block\FileBlock;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Requirements;

class BioBlock extends FileBlock
{
    private static $db = [
        'Role' => 'Varchar(255)',
        'Content' => 'HTMLText',
        'CallToActionLink' => 'Link',
    ];

    private static $singular_name = 'bio';
    private static $plural_name = 'bios';
    private static $table_name = 'T_EB_BioBlock';

    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Bio');
    }

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            // Remove default scaffolded relationship fields
            $fields->removeByName('CallToActionLinkID');
            // Move the file upload field to be before the content
            $upload = $fields->fieldByName('Root.Main.File');
            $fields->insertBefore('Content', $upload);
            // Set the height of the content fields
            $fields->fieldByName('Root.Main.Content')->setRows(5);
        });
        // Ensure TinyMCE's javascript is loaded before the blocks overrides
        Requirements::javascript(TinyMCEConfig::get()->getScriptURL());
        Requirements::javascript('silverstripe/elemental-bannerblock:client/dist/js/bundle.js');
        Requirements::css('silverstripe/elemental-bannerblock:client/dist/styles/bundle.css');
        return parent::getCMSFields();
    }
    /**
     * For the frontend, return a parsed set of data for use in templates
     *
     * @return ArrayData|null
     */
    public function CallToActionLink()
    {
        return $this->decodeLinkData($this->getField('CallToActionLink'));
    }
    /**
     * Add the banner content instead of the image title
     *
     * {@inheritDoc}
     */
    public function getSummary()
    {
        if ($this->File() && $this->File()->exists()) {
            return $this->getSummaryThumbnail() . $this->dbObject('Content')->Summary(20);
        }
        return '';
    }
    /**
     * Return content summary for summary section of ElementEditor
     *
     * @return array
     */
    protected function provideBlockSchema()
    {
        $blockSchema = parent::provideBlockSchema();
        $blockSchema['content'] = $this->dbObject('Content')->Summary(20);
        return $blockSchema;
    }
    /**
     * Given a set of JSON data, decode it, attach the relevant Page object and return as ArrayData
     *
     * @param string $linkJson
     * @return ArrayData|null
     */
    protected function decodeLinkData($linkJson)
    {
        if (!$linkJson || $linkJson === 'null') {
            return;
        }
        $data = ArrayData::create(Convert::json2obj($linkJson));
        // Link page, if selected
        if ($data->PageID) {
            $data->setField('Page', self::get_by_id(SiteTree::class, $data->PageID));
        }
        return $data;
    }
}
