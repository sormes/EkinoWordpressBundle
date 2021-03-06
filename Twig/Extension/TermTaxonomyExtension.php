<?php

namespace Ekino\WordpressBundle\Twig\Extension;

use Ekino\WordpressBundle\Model\TermTaxonomy;
use Ekino\WordpressBundle\Manager\OptionManager;

/**
 * Class TermTaxonomyExtension
 *
 * Provides twig functions related to term_taxonomy entities
 *
 * @author Xavier Coureau <xav@takeatea.com>
 */
class TermTaxonomyExtension extends \Twig_Extension
{
    /**
     * @var OptionManager
     */
    protected $optionManager;

    /**
     * @param OptionManager $optionManager
     */
    public function __construct(OptionManager $optionManager)
    {
        $this->optionManager = $optionManager;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('wp_get_term_link', array($this, 'getTermLink')),
        );
    }

    /**
     * @param TermTaxonomy $termTaxonomy
     * @param string $type The link type. Can be "category" or "tag"
     *
     * @return string
     */
    public function getTermLink(TermTaxonomy $termTaxonomy, $type = 'category')
    {
        $prefix = ($prefix = $this->optionManager->findOneByOptionName($type . '_base')) ? $prefix->getValue() : null;
        $output = array($termTaxonomy->getTerm()->getSlug());

        while ($parent = $termTaxonomy->getParent()) {
            $output[] = $parent->getSlug();
            $termTaxonomy = $parent;
        }

        return ($prefix ? $prefix : '') .'/'. implode('/', array_reverse($output)) . (count($output) ? '/' : '');
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'ekino_wordpress_term_taxonomy';
    }
}