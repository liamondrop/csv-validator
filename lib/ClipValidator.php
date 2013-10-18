<?php

// if $returnValid == true, return a collection of all clips
// that satisfy the validation criteria. Otherwise, return all the
// clips that DO NOT satisfy the validation criteria.
class ClipValidator extends FilterIterator {
    private $returnValid;
    private $privacy = 'anybody';
    private $minLikes = 10;
    private $minPlays = 200;
    private $maxTitleLen = 30;
    
    public function __construct(Iterator $iterator, $returnValid = true) {
        parent::__construct($iterator);
        $this->returnValid = $returnValid;
    }

    private function validate($clip) {
        return (($clip['privacy'] == $this->privacy) &&
                ($clip['total_likes'] > $this->minLikes) &&
                ($clip['total_plays'] > $this->minPlays) &&
                (strlen($clip['title']) < $this->maxTitleLen));
    }
    
    public function accept() {
        $clip = $this->getInnerIterator()->current();
        $isClipValid = $this->validate($clip);
        return $this->returnValid ? $isClipValid : !$isClipValid;
    }
}
