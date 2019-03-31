<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Model;

interface ModelInterface
{
    public function create();

    public function get($id);

    public function findAll();

    public function update($entity);

    public function remove($entity);
}