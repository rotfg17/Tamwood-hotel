<?php
class Paging {
    private $currentPage;
    private $totalItems;
    private $itemsPerPage;
    private $totalPages;

    public function __construct(int $currentPage, int $totalItems, int $itemsPerPage=20) {
        $this->currentPage = max(1, (int)$currentPage);
        $this->totalItems = (int)$totalItems;
        $this->itemsPerPage = (int)$itemsPerPage;
        $this->totalPages = (int)ceil($this->totalItems / $this->itemsPerPage);
    }
    function display_info():array{
        $outputArray = [];
        foreach($this as $key=>$val){
            $outputArray += [$key=>$val];
        }
        return $outputArray;
    }
    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function getTotalPages() {
        return $this->totalPages;
    }

    public function getItemsPerPage() {
        return $this->itemsPerPage;
    }

    public function getOffset() {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }

    public function getPaginationLinks($baseUrl) {
        $links = [];
        for ($i = 1; $i <= $this->totalPages; $i++) {
            $links[] = sprintf('<a href="%s?currentPage=%d">%d</a>', $baseUrl, $i, $i);
        }
        return implode(' ', $links);
    }
}
?>