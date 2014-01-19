<?php

class FilteredFilesystemIterator extends FilterIterator
{
    private $filteredFiles;
    private $excludeFiles;

    /**
     * @param Iterator $path
     * @param          $filteredFiles
     * @param bool     $excludeFiles
     */
    public function __construct($path, $filteredFiles, $excludeFiles = true)
    {
        parent::__construct(new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS));
        $this->setInfoClass('ExtendedSPLFileInfo');

        $this->filteredFiles = $filteredFiles;
        $this->excludeFiles = $excludeFiles;
    }

    /**
     * @return bool
     */
    public function accept()
    {
        $is_match = in_array($this->getFilename(), $this->filteredFiles);
        return $this->excludeFiles ? !$is_match : $is_match;
    }
}
