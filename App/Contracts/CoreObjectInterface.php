<?php

namespace Contracts;

interface CoreObjectInterface
{
    public function setContainer( \Core\DIContainer $container );
    public function load( $service );
}
