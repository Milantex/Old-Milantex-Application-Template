<?php
namespace Application\DataSets;

class PageDataSet extends \Old\Milantex\Core\DataSet {
    public function __construct(\Milantex\DAW\DataBase &$database) {
        parent::__construct($database);
    }

    public function getAll(): array {
        return $this->getDatabase()->selectMany('SELECT * FROM `page`');
    }

    public function getById($id) {
        return $this->getDatabase()->selectOne('SELECT * FROM `page` WHERE page_id = ?', [$id]);
    }

    public function getByLink($link) {
        return $this->getDatabase()->selectOne('SELECT * FROM `page` WHERE link = ?', [$link]);
    }

    public function add($title, $content) {
        $res = $this->getDatabase()->execute('INSERT `page` SET title = ?, `content` = ?', [$title, $content]);

        if (!$res) {
            return false;
        }

        return $this->getDatabase()->getLastInsertId();
    }
}
