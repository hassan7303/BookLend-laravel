<?php

namespace App\Enums;

enum BorrowsEnums: string
{
    case BORROWED = 'borrowed';
    case RETURNED = 'returned';
    case LATE     = 'late';
}
