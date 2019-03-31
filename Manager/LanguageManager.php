<?php
namespace ReaccionEstudio\ReaccionCMSBundle\Manager;

use ReaccionEstudio\ReaccionCMSBundle\Entity\Language;
use ReaccionEstudio\ReaccionCMSBundle\Manager\AbstractManager;

class LanguageManager extends AbstractManager
{
    protected $class = Language::class;
}