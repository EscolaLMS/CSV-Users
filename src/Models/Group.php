<?php

namespace EscolaLms\CsvUsers\Models;

use EscolaLms\Auth\Models\Group as AuthGroup;

/**
 * @property string $name
 * @property bool $registerable
 */
class Group extends AuthGroup
{
}
