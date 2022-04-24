<?php

namespace App\Models;

use Base\Db;

abstract class AbstractModel
{
    protected array $fields = [];
    protected array $hidden = [];
    protected string $table;

    public function __construct(array $data = [])
    {
        if (empty($data)) {
            return;
        }

        foreach ($this->fields as $field_name => $field_value) {
            if (!isset($data[$field_name])) {
                continue;
            }
            $this->fields[$field_name] = $data[$field_name];
        }
    }

    public function toJson(): string
    {
        $newArray = [];
        foreach ($this->fields as $key => $value) {
            if (!in_array($key, $this->hidden)) {
                $newArray[$key] = $value;
            }
        }
        return json_encode($newArray);
    }

    public function getCreatedAt(): ?string
    {
        return $this->fields['created_at'];
    }

    public function getUpdatedAt(): ?string
    {
        return $this->fields['updated_at'];
    }

    public function create()
    {
        if ($this->getId()) {
            return;
        }

        $db = Db::getInstance();
        $this->setCreatedAt();
        $this->setUpdatedAt();

        $fields = $this->fields;
        unset($fields['id']);
        $fields_keys = array_keys($fields);

        $query = "INSERT INTO `" . $this->table . "` (" . join(', ', $fields_keys) . ") VALUES (:" . join(', :', $fields_keys) . ") ";

        $db->exec($query, __METHOD__, $fields);

        $this->setId($db->lastInsertId());

        return $db->lastInsertId();
    }

    public function getId(): ?int
    {
        return $this->fields['id'];
    }

    protected function setCreatedAt(): self
    {
        $this->fields['created_at'] = date('Y-m-d H:i:s');
        return $this;
    }

    protected function setUpdatedAt(): self
    {
        $this->fields['updated_at'] = date('Y-m-d H:i:s');
        return $this;
    }

    public function setId(int $id): self
    {
        $this->fields['id'] = $id;
        return $this->setUpdatedAt();
    }

    public function update()
    {
        $db = Db::getInstance();
        $this->setUpdatedAt();

        $fields = $this->fields;
        unset($fields['id']);

        $sets = [];
        foreach ($fields as $field_name => $field_value) {
            $sets[] = "`" . $field_name . "` = :" . $field_name . "";
        }
        $query = "UPDATE `" . $this->table . "` SET " . join(', ', $sets) . " WHERE `id` = :id";

        $db->exec($query, __METHOD__, $this->fields);
    }

    public function delete()
    {
        if (!$this->getId()) {
            return;
        }

        $db = Db::getInstance();

        $query = "DELETE FROM `" . $this->table . "` WHERE `id` = :id";

        $db->exec($query, __METHOD__, [
            'id' => (int)$this->getId()
        ]);
    }
}
