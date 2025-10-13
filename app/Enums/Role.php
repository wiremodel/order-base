<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Office = 'office';
    case Kitchen = 'kitchen';
    case DiningRoom = 'dining_room';
}
