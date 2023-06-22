<?php
namespace Src\Utilities;

abstract class Helper {
    public static function validateHashedPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
}
}