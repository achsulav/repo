<?php

namespace App\Foundation;

use Flasher\Prime\Container\ContainerInterface;

final class FlasherContainerWrapper implements ContainerInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if ('flasher' === $id) {
            return FlasherManager::getFlasher();
        }

        return FlasherManager::getFlasher()->create($id);
    }
}
