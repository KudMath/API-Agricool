<?php

namespace App\Services;

class ContainersService extends BaseService
{

    public function getOne($id)
    {
        return $this->db->fetchAssoc("SELECT * FROM containers WHERE id=?", [(int) $id]);
    }

    public function getAll()
    {
        return $this->db->fetchAll("SELECT * FROM containers");
    }

    function save($container)
    {
        $this->db->insert("containers", $container);
        return $this->db->lastInsertId();
    }

    function update($id, $container)
    {
        return $this->db->update('containers', $container, ['id' => $id]);
    }

    function delete($id)
    {
        return $this->db->delete("containers", array("id" => $id));
    }

}
