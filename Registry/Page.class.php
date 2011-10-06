<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * KRIKKA Business Social Network
 */

/**
 * Description of Page
 *
 * @author pmasala
 */
class Page {
    //page title
    private $_title = '';
    //template tags
    private $_tags = array();
    // tags which should be processed after the page has been parsed
    // reason: what if there ara template tags within the database content,
    // we must parse the page, then parse it again for post parse tags
    private $_postParseTags = array();
    // template bits
    private $_bits = array();
    // the page content
    private $_content = "";
    private $_apd = array();
    
    /**
     * Create our page object
     */
    function __construct(Registry $registry) {
        $this->registry = $registry;
    }
    
    /**
     * Get the page title from the page
     * @return string
     */
    public function getTitle() {
        return $this->_title;
    }
    
    /**
     * Set the page title
     * @param string $title the page title
     * @return void
     */
    public function setTitle($title) {
        $this->_title = $title;
    }
    
    /**
     * Set the page content
     * @param String $content the page content
     * @return void
     */
    public function setContent($content) {
        $this->_content = $content;
    }
    
    /**
     * Add a template tag, and its replacement value/data to the page
     * @param String $key the key to store within the tags array
     * @param String $data the replacement data (may also be an array)
     * @return void
     */
    public function addTag($key, $data) {
        $this->_tags[$key] = $data;
    }
    
    public function removeTag($key) {
        unset ($this->_tags[$key]);
    }
    
    /**
     * Get tags associated with th epage
     * @return void
     */
    public function getTags() {
        return $this->_tags;
    }
    
    /**
     * Add post parse tags: as per adding tags
     * @paaram String $key the key to store within the array
     * @param String $data the replacement data
     * @return void
     */
    public function addPPTag($key, $data) {
        $this->_postParseTags[$key] = $data;
    }
    
    /**
     * Get tags to be parsed after the firs batch have been parsed
     * @return array
     */
    public function getPPTags() {
        return $this->_postParseTags;
    }
    
    /**
     * Add a templatw bit to the page, does not actually add the content jast yet
     * @param String the tag where the template is added
     * @param String the template file name
     * @return void
     */
    public function addTemplateBit($tag, $bit) {
        $this->_bits[$tag] = $bit;
    }
    
    /**
     * Adds additional parsing data
     * A.P.D is used in parsing loops. We may want to have an extra bit of data
     * depending on interations value for example on a form list, we may want a specific
     * item to be selected
     * @param String block the condition applies to
     * @param String tag within the block the condition applies to
     * @param string condition : what the tag must equal
     * @param String extratag : if the tag value = condition the we have an extra
     * tag called extratag
     * @param String data : if the tag value = condition the extra tag is replaced
     * with this value
     */
    public function addAdditionalParsingData($block, $tag, $condition, $extratag, $data) {
        $this->_apd[$block] = array($tag => array('condition' => $condition, 'tag' => $extratag, 'data' => $data));
    }
    
    /**
     * get the template bits to be entered into the page
     * @return array the array of the template tags and template file names
     */
    public function getBits() {
        return $this->_bits;
    }
    
    public function getAdditionalParsingData() {
        return $this->_apd;
    }
    
    /**
     * Gets a chunk of page content
     * @param String the tag wrapping the block (<!-- START tag --> block <!-- END tag -->)
     * @return String the block of content
     */
    public function getBlock ($tag) {
        // echo $tag
        preg_match ('#<!-- START ' . $tag . ' -->(.+?)<!-- END , . $tag . , -->#si', 
                $this->_content, $tor);
        $tor = str_replace ('<!-- START ' . $tag . ' -->', "", $tor[0]);
        $tor = str_replace('<!-- END ' . $tag . ' -->', "", $tor);
        
        return $tor;
    }
    
    public function getContent() {
        return $this->_content;
    }
    
    public function getContentToPrint() {
        $this->_content = preg_replace('#{form_(.+?)}#si', '', $this->_content);
        $this->_content = preg_replace ('#{nbd_(.+?)}#si', '', $this->_content);
        $this->_content = str_replace ('</body>', '<!-- Generated by Krikka Business Social Network --></body>', $this->_content);
        return $this->_content;
    }
}

?>