<?php

namespace App\Enums;

enum BookCopiesEnums: string
{
    case AVAILABLE    = 'available';
    case BORROWED     = 'borrowed';
    case RESERVED     = 'reserved';
    case LIBRARY_ONLY = 'library_only';
    case DAMAGED      = 'damaged';
    case LOST         = 'lost';
}
