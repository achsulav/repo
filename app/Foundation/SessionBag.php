<?php

namespace App\Foundation;

use Flasher\Prime\Storage\Bag\BagInterface;

final class SessionBag implements BagInterface
{
    const ENVELOPES_NAMESPACE = 'flasher::envelopes';

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        if (!isset($_SESSION[self::ENVELOPES_NAMESPACE])) {
            return array();
        }

        return $_SESSION[self::ENVELOPES_NAMESPACE];
    }

    /**
     * {@inheritdoc}
     */
    public function set(array $envelopes)
    {
        $_SESSION[self::ENVELOPES_NAMESPACE] = $envelopes;
    }
}
